<?php

namespace Modules\Formtools\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Formtools\Models\FormModel;
use Modules\ModulesController;

class HomeController extends ModulesController {

    public function getModel() {
        $all = $this->request->all();
        $dataBase = env("DB_DATABASE");
        $insters = [];
        if(!$all['table']){
            return back()->with("pageDataMsg", "参数有误!");
        }


        $tables = DB::connection()->getDoctrineSchemaManager()->listTables();

        foreach ($all['table'] as $key => $table) {
            $identification = str_replace('module_formtools_', '', $table);
            if ($identification=="models"){
                continue;
            }
            $check = FormModel::query()
                ->where("identification",$identification)
                ->first();
            if ($check){
                continue;
            }
            $table = env('DB_PREFIX').$table;

            foreach ($tables as $tableName) {
                if($tableName->getName()==$table){
                    $temp['name'] = $tableName->getComment();
                    break;
                }
            }

            $results = DB::select("SELECT
                                    COLUMN_NAME,
                                    IS_NULLABLE,
                                    COLUMN_DEFAULT,
                                    DATA_TYPE,
                                    CHARACTER_MAXIMUM_LENGTH,
                                    COLUMN_COMMENT
                                FROM
                                    INFORMATION_SCHEMA.COLUMNS
                                WHERE
                                    TABLE_NAME = '{$table}' AND
                                    TABLE_SCHEMA = '{$dataBase}'");

            $field = [];
            foreach ($results as $key => $result) {
                if(in_array($result->COLUMN_NAME,['id','created_at','updated_at'])){
                    continue;
                }
                $field['name'] = $result->COLUMN_COMMENT;
                $field['rule'] = "unlimited";
                $field['regex'] = null;
                $field['foreign'] = null;
                $field['remark'] = $field['name'];
                $field['isindex'] = "NOINDEX";
                $field['formtype'] = "";
                $field['required'] = "";
                $field['maxlength'] = "";
                $field['foreign_key'] = "";
                $field['identification'] = $result->COLUMN_NAME;
                $fields[] = $field;
            }

            $temp['identification'] = $temp['access_identification'] = str_replace(env('DB_PREFIX').'module_formtools_', '', $table);
            $temp['menuname'] = "待同步模型";
            $temp['icon'] = "icon-list-numbered";
            $temp['remark'] = $temp['name'];
            $temp['created_at'] = $temp['updated_at'] = date('Y-m-d H:i:s');

            //[
            //{
            //"name": "内容",
            // "rule": "unlimited",
            // "regex": null,
            // "remark": "内容",
            // "foreign": null,
            // "isindex": "NOINDEX",
            // "formtype": "editor",
            // "required": "required",
            // "fieldtype": "longText",
            // "maxlength": "0",
            // "foreign_key": null,
            // "is_show_list": "2",
            // "identification": "content"
            //}
            //]
            $temp['fields'] = json_encode($fields);

            $insters[] = $temp;
        }
        $res = FormModel::query()->insert($insters);

        return returnArr($res?200:0,'获取成功！','','');

    }

    public function synmodel() {

        try {
            Artisan::call('db:seed', [
                '--class' => "Modules\Formtools\Database\Seeders\DatabaseSeeder",
                '--force' => 1,
            ]);

            return redirect("/admin/formtools/index")->with(["pageDataMsg" => "同步模型成功", "pageDataStatus" => 200]);
        } catch (\Exception $exception) {
            return back()->with("pageDataMsg", "操作失败!");
        }

    }

    public function resetModelData() {
        try {
            Artisan::call('migrate', [
                '--path' => "Modules/Formtools/Database/Migrations/install",
                '--force' => 1,
            ]);

            Artisan::call('db:seed', [
                '--class' => "Modules\Formtools\Database\Seeders\DatabaseSeeder",
                '--force' => 1,
            ]);

            return redirect("/admin/formtools/index")->with(["pageDataMsg" => "重置模型数据成功", "pageDataStatus" => 200]);
        } catch (\Exception $exception) {
            return back()->with("pageDataMsg", "操作失败!");
        }
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index() {
        $pageData = getURIByRoute($this->request);
        $pageData['title'] = "模型列表";

        $tables = DB::connection()->getDoctrineSchemaManager()->listTables();
        $tablesList = [];

        foreach ($tables as $table) {
            if (strpos($table->getName(), 'module_formtools') !== false) {
                $tablename = str_replace(env("DB_PREFIX"),'', $table->getName());
                $identification = str_replace('module_formtools_', '', $tablename);
                if ($identification=="models"){
                    continue;
                }
                $check = FormModel::query()
                    ->where("identification",$identification)
                    ->first();
                if($check){
                    continue;
                }
                $tablesList[] = [
                    "name" => $tablename,
                    "comment" => $table->getComment(),
                ];
            }
        }

        $pageData['datas'] = FormModel::query()
            ->orderBy("show_home_page", "desc")
            ->orderBy("home_page_sort")
            ->orderBy("id", "desc")
            ->paginate(10);

        return view("formtools::admin.index.index", [
            "pageData" => $pageData,
            "tablesList" => $tablesList,
        ]);

    }

    public function modelAdd() {
        if ($this->request->isMethod("post")) {
            $data = $this->request->all();

            if (!$data['name'] || !$data['identification'] || !$data['access_identification'] || !$data['remark']) {
                return back()->with("pageDataMsg", "请填写完整");
            }
            $check = FormModel::query()->where("identification", $data['identification'])->first();
            if ($check) {
                return back()->with("pageDataMsg", "标识已存在");
            }

            $data['list_template'] = $data['list_template'] ?: $data['custom_list_template'];
            $data['created_at'] = date("Y-m-d H:i:s", time());
            $data['updated_at'] = date("Y-m-d H:i:s", time());

            if ($_FILES['home_page_bg_img']['size'] > 0) {
                try {
                    $data['home_page_bg_img'] = UploadFile($this->request, "home_page_bg_img", "model/" . date("Y/m/d/") . uniqid(), ALLOWEXT, __E("upload_driver"));
                } catch (\Exception $exception) {
                    return redirect("/admin/formtools/index")->with("pageDataMsg", $exception->getMessage());
                }
            }

            unset($data['_token'], $data['custom_list_template']);
            $tableName = 'module_formtools_' . $data['identification'];
            //创建模型表
            if (Schema::hasTable($tableName)) {
                return back()->with("pageDataMsg", "表已存在");
            }

            // 表不存在
            call_user_func([new TableStructure(), 'createTable'], $tableName, $data);

            $res = FormModel::query()->insert($data);
            if ($res) {
                return redirect("/admin/formtools/index")->with(["pageDataMsg" => "添加成功", "pageDataStatus" => 200]);
            }
            return redirect("/admin/formtools/index")->with("pageDataMsg", "添加失败");
        }


        $pageData = getURIByRoute($this->request);
        $pageData['title'] = "模型管理";
        $pageData['subtitle'] = "万能模型添加";
        $pageData['modules'] = event(new \Modules\Formtools\Events\GetFormToolsModules())[0];

        return view("formtools::admin.index.modelAdd", [
            "pageData" => $pageData
        ]);
    }

    public function modelEdit() {
        if ($this->request->isMethod("post")) {
            $data = $this->request->all();

            if (!$data['name'] || !$data['identification'] || !$data['access_identification'] || !$data['remark']) {
                return back()->with("pageDataMsg", "请填写完整");
            }
            $check = FormModel::query()->where("identification", $data['identification'])->first();
            if (!$check) {
                return back()->with("pageDataMsg", "标识不存在");
            }

            $data['updated_at'] = date("Y-m-d H:i:s", time());
            $data['list_template'] = $data['list_template'] ?: $data['custom_list_template'];

            unset($data['_token'], $data['custom_list_template']);
            $updateData = [
                "name" => $data['name'],
                "identification" => $data['identification'],
                "access_identification" => $data['access_identification'],
                "menuname" => $data['menuname'],
                "module" => $data['module'],
                "remark" => $data['remark'],
                "icon" => $data['icon'],
                "form_template" => $data['form_template'],
                "list_template" => $data['list_template'],
                "detail_template" => $data['detail_template'],
                "data_source" => $data['data_source'],
                "data_source_api_url" => $data['data_source_api_url'] ?: '',
                "data_source_api_url_detail" => $data['data_source_api_url_detail'] ?: "",
                "data_source_field_mapping" => $data['data_source_field_mapping'] ?: null,
                "page_num" => $data['page_num'] ?: 20,
                "list_page_template" => $data['list_page_template'],
                "home_page_title" => $data['home_page_title'],
                "home_page_describe" => $data['home_page_describe'],
                "show_home_page" => $data['show_home_page'],
                "home_page_num" => $data['home_page_num'],
                "home_page_sort" => $data['home_page_sort'],

                "updated_at" => $data['updated_at']
            ];

            if ($_FILES['home_page_bg_img']['size'] > 0) {
                try {
                    $updateData['home_page_bg_img'] = UploadFile($this->request, "home_page_bg_img", "model/" . date("Y/m/d/") . uniqid(), ALLOWEXT, __E("upload_driver"));
                } catch (\Exception $exception) {
                    return redirect("/admin/formtools/index")->with("pageDataMsg", $exception->getMessage());
                }
            }

            $res = FormModel::query()->where('id', $data['id'])->update($updateData);
            if ($res) {
                return redirect("/admin/formtools/index")->with(["pageDataMsg" => "编辑成功", "pageDataStatus" => 200]);
            }
            return redirect("/admin/formtools/index")->with("pageDataMsg", "编辑失败");
        }


        $all = $this->request->all();
        $pageData = getURIByRoute($this->request);
        $pageData['title'] = "模型管理";
        $pageData['subtitle'] = "万能模型编辑";
        $pageData['modules'] = event(new \Modules\Formtools\Events\GetFormToolsModules())[0];
        $pageData['data'] = DB::table("module_formtools_models")->where("id", $all['id'])->first();
        return view("formtools::admin.index.modelEdit", [
            "pageData" => $pageData
        ]);
    }

    public function modelDelete() {
        $all = $this->request->all();

        $check = FormModel::query()->where("id", $all['id'])->first();
        if (!$check) {
            return back()->with("pageDataMsg", "模型不存在");
        }

        $tableName = 'module_formtools_' . $check->identification;
        $res = FormModel::query()->where("id", $all['id'])->delete();

        if ($res) {
            call_user_func([new TableStructure(), 'deleteTable'], $tableName);
            return redirect("/admin/formtools/index")->with(["pageDataMsg" => "删除成功", "pageDataStatus" => 200]);
        }
        return redirect("/admin/formtools/index")->with("pageDataMsg", "删除失败");

    }


    public function fieldList() {

        $pageData = getURIByRoute($this->request);
        $pageData['id'] = $this->request->input("id");
        $pageData['title'] = "万能表单";
        $pageData['subtitle'] = "模型字段管理";


        $pageData['data'] = FormModel::query()->where("id", $pageData['id'])->first();
        $colunmListDetaill = [];
        $fields = [];
        $tableName = 'module_formtools_' . $pageData['data']->identification;
        $colunmList = call_user_func([new TableStructure(), 'getColumns'], $tableName);

        if ($pageData['data']->fields) {
            $fields = json_decode($pageData['data']->fields, true);
        }
        foreach ($fields as $k2 => $v2) {
            foreach ($colunmList as $k => $v) {
                if ($v2['identification'] == $v) {
                    $colunmListDetaill[] = $v2;
                }
            }
        }
        return view("formtools::admin.index.fieldList", [
            "pageData" => $pageData,
            "colunmListDetaill" => $colunmListDetaill
        ]);
    }

    public function fieldAdd() {
        if ($this->request->isMethod("post")) {
            $data = $this->request->all();

            if (!$data['identification'] || !$data['remark']) {
                return back()->with("pageDataMsg", "请填写完整");
            }
            $check = DB::table("module_formtools_models")->where("id", $data['id'])->first();

            $updateData2['identification'] = $data['identification'];
            $updateData2['name'] = $data['name'];
            $updateData2['required'] = $data['required'];
            $updateData2['is_show_list'] = $data['is_show_list'];
            $updateData2['isindex'] = $data['isindex'];
            $updateData2['formtype'] = $data['formtype'];
            $updateData2['fieldtype'] = $data['fieldtype'];
            $updateData2['datas'] = json_encode($data['datas'] ? array_values($data['datas']) : [], JSON_UNESCAPED_UNICODE);
            $updateData2['rule'] = $data['rule'];
            $updateData2['regex'] = $data['regex'];
            $updateData2['maxlength'] = $data['maxlength'];
            $updateData2['foreign'] = $data['foreign'];//{{$v->identification}}-{{$v->id}}
            $updateData2['foreign_key'] = $data['foreign_key'];
            $updateData2['remark'] = $data['remark'];
            $updateData2['is_show_admin_list_search'] = $data['is_show_admin_list_search'];
            $updateData2['is_show_home_form'] = $data['is_show_home_form'];
            $updateData2['is_show_home_list_search'] = $data['is_show_home_list_search'];
            $temp = explode("-", $updateData2['foreign']);

            $updateData = json_decode($check->fields, true) ?: [];
            $fields = [];
            foreach ($updateData as $k => $v) {
                if ($v['identification'] == $data['identification']) {
                    return back()->with("pageDataMsg", "标识已存在");
                }
                $fields[] = $v;
                if ($v['identification'] == $data['after_field']) {
                    $fields[] = $updateData2;
                }
            }
            if ($data['after_field'] == 'id') {
                array_unshift($fields, $updateData2);
            }

            $updateModelData = [
                'fields' => json_encode($fields, JSON_UNESCAPED_UNICODE),
                'updated_at' => getDay(),
            ];

            if ($temp && $temp[1]) $updateModelData['supermodel'] = $temp[1];

            $res = FormModel::query()
                ->where("id", $data['id'])
                ->update($updateModelData);

            if ($res) {
                //添加表字段
                $tableName = 'module_formtools_' . $check->identification;
                call_user_func([new TableStructure(), 'createColumn'], $tableName, $data);

                //创建索引
                if ($updateData2['isindex'] != 'NOINDEX') {
                    call_user_func([new TableStructure(), 'createIndex'], $tableName, $updateData2);
                }

                return redirect("/admin/formtools/fieldList?id=" . $data['id'])->with(["pageDataMsg" => "添加成功", "pageDataStatus" => 200]);
            }
            return redirect("/admin/formtools/fieldList?id=" . $data['id'])->with("pageDataMsg", "添加失败");
        }
        $pageData = getURIByRoute($this->request);
        $pageData['id'] = $this->request->input("id");
        $pageData['title'] = "万能表单";
        $pageData['subtitle'] = "表单字段添加";

        $pageData['models'] = FormModel::query()->where("id", '!=', $pageData['id'])->get();
        foreach ($pageData['models'] as $k => $v) {
            $fields = json_decode($v->fields, true);
            foreach ($fields as &$f) {
                $f['datas'] = $f['datas'] ? json_decode($f['datas'], true) : null;
            }
            $pageData['modelsFields'][$v->identification . '-' . $v->id] = $fields;
        }
        //防止双引号转义
        $pageData['modelsFields'] = json_encode($pageData['modelsFields'], JSON_UNESCAPED_UNICODE);

        $pageData['currentModelFields'] = json_decode(FormModel::query()->find($pageData['id'])['fields'], true) ?: [];
        krsort($pageData['currentModelFields']);
        return view("formtools::admin.index.fieldAdd", [
            "pageData" => $pageData
        ]);
    }

    public function fieldEdit() {
        if ($this->request->isMethod("post")) {
            $data = $this->request->all();


            if (!$data['identification'] || !$data['remark']) {
                return back()->with("pageDataMsg", "请填写完整");
            }
            $check = FormModel::query()->where("id", $data['id'])->first();

            $updateData = json_decode($check->fields, true) ?: [];
            $oldData = [];
            $currentKey = false;
            foreach ($updateData as $k => $v) {
                if ($v['identification'] == $data['identification']) {
                    $oldData = $v;
                    $currentKey = $k;
                    continue;
                }
            }
            if ($currentKey === false) return back()->with("pageDataMsg", "数据错误");

            $updateData2['identification'] = $data['identification'];
            $updateData2['required'] = $data['required'];
            $updateData2['is_show_list'] = $data['is_show_list'];
            $updateData2['isindex'] = $data['isindex'];
            $updateData2['name'] = $data['name'];
            $updateData2['formtype'] = $data['formtype'];
            $updateData2['fieldtype'] = $data['fieldtype'];
            $updateData2['datas'] = json_encode($data['datas'] ? array_values($data['datas']) : [], JSON_UNESCAPED_UNICODE);
            $updateData2['rule'] = $data['rule'];
            $updateData2['regex'] = $data['regex'];
            if ($data['fieldtype'] == "text") {
                $updateData2['maxlength'] = $data['maxlength'] = 0;
            }
            $updateData2['maxlength'] = $data['maxlength'];
            $updateData2['foreign'] = $data['foreign'];//{{$v->identification}}-{{$v->id}}
            $updateData2['foreign_key'] = $data['foreign_key'];
            $updateData2['remark'] = $data['remark'];
            $updateData2['is_show_admin_list_search'] = $data['is_show_admin_list_search'];
            $updateData2['is_show_home_form'] = $data['is_show_home_form'];
            $updateData2['is_show_home_list_search'] = $data['is_show_home_list_search'];
            $temp = explode("-", $updateData2['foreign']);

            $updateData[$currentKey] = $updateData2;

            $updateModelData = [
                'fields' => json_encode($updateData, JSON_UNESCAPED_UNICODE),
                'updated_at' => getDay(),
            ];

            if ($temp && $temp[1]) $updateModelData['supermodel'] = $temp[1];

//            DB::beginTransaction();

            $res = DB::table("module_formtools_models")
                ->where("id", $data['id'])
                ->update($updateModelData);

            if ($res) {
                //添加表字段
                $tableName = 'module_formtools_' . $check->identification;


                try {
                    call_user_func([new TableStructure(), 'editIndex'], $tableName, $data, $oldData);
                    call_user_func([new TableStructure(), 'editColumn'], $tableName, $data, $oldData);

                } catch (\Exception $e) {
//                    DB::rollback();
                    return redirect("/admin/formtools/fieldList?id=" . $data['id'])->with(["pageDataMsg" => "编辑失败", "pageDataStatus" => 400]);
                }

//                DB::commit();
                return redirect("/admin/formtools/fieldList?id=" . $data['id'])->with(["pageDataMsg" => "编辑成功", "pageDataStatus" => 200]);
            } else {
//                DB::rollback();
                return redirect("/admin/formtools/fieldList?id=" . $data['id'])->with(["pageDataMsg" => "编辑失败", "pageDataStatus" => 400]);
            }
        }

        $pageData = getURIByRoute($this->request);
        $pageData['id'] = $this->request->input("id");
        $pageData['identification'] = $this->request->input("identification");
        $pageData['title'] = "万能模型";
        $pageData['subtitle'] = "表单字段编辑";

        $check = FormModel::query()->where("id", $pageData['id'])->first();
        $fieldsData = json_decode($check->fields, true);
        $pageData['fieldData'] = [];
        foreach ($fieldsData as $k => $v) {
            if ($pageData['identification'] == $v['identification']) {
                $pageData['fieldData'] = $v;
            }
        }
        $pageData['fieldData']['datas'] = $pageData['fieldData']['datas'] ? json_decode($pageData['fieldData']['datas'], true) : [];

        $pageData['models'] = FormModel::query()->where("id", '!=', $pageData['id'])->get();
        foreach ($pageData['models'] as $k => $v) {
            $fields = json_decode($v->fields, true);
            foreach ($fields as &$f) {
                $f['datas'] = $f['datas'] ? json_decode($f['datas'], true) : null;
            }
            $pageData['modelsFields'][$v->identification . '-' . $v->id] = $fields;
        }

        //防止双引号转义
        $pageData['modelsFields'] = json_encode($pageData['modelsFields'], JSON_UNESCAPED_UNICODE);
        return view("formtools::admin.index.fieldEdit", [
            "pageData" => $pageData
        ]);
    }

    public function fieldDel() {
        $id = $this->request->input("id");
        $identification = $this->request->input("identification");
        $check = FormModel::query()->where("id", $id)->first();
        $fieldsData = json_decode($check->fields, true);
        $pageData = [];
        foreach ($fieldsData as $k => $v) {
            if ($identification != $v['identification']) {
                $pageData[] = $v;
            }
        }
        $res = FormModel::query()->where("id", $id)->update([
            "fields" => json_encode($pageData)
        ]);
        if ($res) {
            //删除表字段
            $tableName = 'module_formtools_' . $check->identification;
            call_user_func([new TableStructure(), 'deleteColumn'], $tableName, $identification);
            return redirect("/admin/formtools/fieldList?id=" . $id)->with(["pageDataMsg" => "删除成功", "pageDataStatus" => 200]);
        }
        return redirect("/admin/formtools/fieldList?id=" . $id)->with(["pageDataMsg" => "删除失败"]);
    }

    public function fieldMove() {
        $all = $this->request->all();
        $check = DB::table("module_formtools_models")->where("id", $all['id'])->first();
        if (!$check) return redirect("/admin/formtools/fieldList?id=" . $all['id'])->with("pageDataMsg", "数据不存在");
        $fields = json_decode($check->fields, true);
        $currentKey = 0;
        foreach ($fields as $key => $val) {
            if ($val['identification'] == $all['identification']) {
                $currentKey = $key;
                break;
            }
        }
        $fieldData = $fields[$currentKey];
        if ($all['move_type'] == 1) {
            if ($currentKey == 0) return redirect("/admin/formtools/fieldList?id=" . $all['id'])->with("pageDataMsg", "已是最上面");
            $tmpData = $fields[$currentKey - 1];
            $fields[$currentKey - 1] = $fields[$currentKey];
            $fields[$currentKey] = $tmpData;
            $after_field = $fields[$currentKey - 2]['identification'] ?: 'id';
        } else {
            if ($currentKey == count($fields) - 1) return redirect("/admin/formtools/fieldList?id=" . $all['id'])->with("pageDataMsg", "已是最下面");
            $tmpData = $fields[$currentKey + 1];
            $fields[$currentKey + 1] = $fields[$currentKey];
            $fields[$currentKey] = $tmpData;
            $after_field = $tmpData['identification'];
        }

        $res = DB::table("module_formtools_models")->where("id", $all['id'])->update([
            'fields' => json_encode($fields, JSON_UNESCAPED_UNICODE),
            'updated_at' => getDay(),
        ]);
        if ($res) {
            //添加表字段
            $tableName = 'module_formtools_' . $check->identification;
            $data = [
                'identification' => $fieldData['identification'],
                'remark' => $fieldData['remark'],
                'after_field' => $after_field,
            ];
            call_user_func([new TableStructure(), 'moveColumn'], $tableName, $data);
            return redirect("/admin/formtools/fieldList?id=" . $all['id'])->with(["pageDataMsg" => "操作成功", "pageDataStatus" => 200]);
        } else {
            return redirect("/admin/formtools/fieldList?id=" . $all['id'])->with("pageDataMsg", "操作失败");
        }
    }
}
