<?php

namespace Modules\Formtools\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\DB;
use Modules\Formtools\Models\FormModel;
use Modules\Main\Models\Common;
use Modules\ModulesController;

class ModelController extends ModulesController {
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function loadModel() {
        set_time_limit(0);
        $all = $this->request->all();
        $modeldetaill = FormModel::query()
            ->where("identification", $all['model'])
            ->first();
        if (!$modeldetaill) {
            oneFlash([400, '模型不存在']);
        }
        $all['modeldetaill'] = $modeldetaill;
        return call_user_func([$this, $all['action']], $all);

    }

    public function List($all) {
        $pageData = getURIByRoute($this->request);
        $pageData['title'] = $all['modeldetaill']->name . "-列表";
//        $pageData[''] = "列表";
        $pageData['model'] = $all['model'];
        $pageData['action'] = "model?action=List&model=" . $all['model'];
        $pageData['modeldetaill'] = json_decode($all['modeldetaill']->fields, true);
        foreach ($pageData['modeldetaill'] as $key => $modeldetaill) {
            if ($modeldetaill['is_show_list'] != 1) unset($pageData['modeldetaill'][$key]);
        }
        $leftJoin = [];
        $select = [];
        foreach ($pageData['modeldetaill'] as $k => $v) {
            if ($v['foreign']) {
                $temp = explode("-", $v['foreign']);
                $leftJoin[] = $temp[0] . '-' . $v['identification'] . '-' . $v['foreign_key'];
            }
            if ($v['datas'] && !is_array($v['datas'])) {
                $datas = json_decode($v['datas'], true);
                $pageData['modeldetaill'][$k]['datas'] = array_column($datas, 'name', 'value');
            }
        }
        $tableName = 'module_formtools_' . $all['model'];
        $pageData['datas'] = DB::table($tableName);
        foreach ($leftJoin as $k => $v) {
            $temp = explode("-", $v);
            $pageData['datas']->leftJoin('module_formtools_' . $temp[0] . ' as ' . $temp[0], $tableName . '.' . $temp[1], '=', $temp[0] . '.id');
            $select[] = $temp[0] . '.' . $temp[2] . ' as ' . $temp[1];
        }
        //排序
        $identificationArray = array_column($pageData['modeldetaill']?:[], 'identification');
        if (in_array('sorts', $identificationArray)) {
            $pageData['datas'] = $pageData['datas']->orderBy($tableName . ".sorts", "desc");
        } elseif (in_array('sort', $identificationArray)) {
            $pageData['datas'] = $pageData['datas']->orderBy($tableName . ".sort", "desc");
        }
        $pageData['datas'] = $pageData['datas']->orderBy($tableName . ".id", "desc")
            ->select(array_merge([$tableName . '.*'], $select))
            ->paginate(getLen());
        if ($all['moduleName']) $pageData['moduleName'] = $all['moduleName'];
        unset($all);
        return view("formtools::admin.model.list", [
            "pageData" => $pageData
        ]);
    }

    public function Add($all) {

        $pageData = getURIByRoute($this->request);
        $pageData['title'] = $all['modeldetaill']->name;
        $pageData['subtitle'] = "添加数据";
        $pageData['model'] = $all['model'];
        $pageData['action'] = "model?action=List&model=" . $all['model'];
        $pageData['fields'] = json_decode($all['modeldetaill']->fields, true);
        //关联数据
        foreach ($pageData['fields'] as $k => $v) {
            if ($v['datas'] && !is_array($v['datas'])) {
                $pageData['fields'][$k]['datas'] = json_decode($v['datas'], true);
            }
            if ($v['foreign']) {
                $temp = explode("-", $v['foreign']);
                $pageData['fields'][$k]['datas'] = Common::query()->from('module_formtools_' . $temp[0])->select(['id as value', $v['foreign_key'] . ' as name'])->get()->toArray();
            }

        }
        if ($all['moduleName']) $pageData['moduleName'] = $all['moduleName'];
        return view("formtools::admin.model.add&edit", [
            "pageData" => $pageData
        ]);
    }

    public function Edit($all) {
        $pageData = getURIByRoute($this->request);
        $pageData['title'] = $all['modeldetaill']->name;
        $pageData['subtitle'] = "编辑数据";
        $pageData['model'] = $all['model'];
        $pageData['action'] = "model?action=List&model=" . $all['model'];
        $pageData['page'] = $all['page'];
        $fields = json_decode($all['modeldetaill']->fields, true);
        $tableName = 'module_formtools_' . $all['model'];
        $data = DB::table($tableName)->where("id", $all['id'])->first();
        $formtool = FormTool::create();
        //关联数据
        foreach ($fields as $k => $v) {
            if ($v['datas'] && !is_array($v['datas'])) {
                $fields[$k]['datas'] = json_decode($v['datas'], true);
            }
            if ($v['foreign']) {
                $temp = explode("-", $v['foreign']);
                $datas = Common::query()->from('module_formtools_' . $temp[0])->select(['id as value', $v['foreign_key'] . ' as name'])->get();
                if ($datas) {
                    $fields[$k]['datas'] = json_decode(json_encode($datas), true);
                }
//                $fields[$k]['formtype'] = 'select';
            }
            $fields[$k]['value'] = get_object_vars($data)[$v['identification']];

            $formtool->field($v['identification'], $v['name'], $fields[$k]['value'], $fields[$k]['formtype'], $fields[$k]['datas']);
        }
        $formData = $formtool->field("id", '', $all['id'], 'hidden')
            ->csrf_field()->getData();

        $pageData = array_merge($formData, $pageData);

        if ($all['moduleName']) $pageData['moduleName'] = $all['moduleName'];
        return view("formtools::admin.model.add&edit", [
            "pageData" => $pageData
        ]);
    }

    public function Submit($all) {

        if (!$this->request->isMethod("post")) {
            return back()->with("pageDataMsg", "非法请求");
        }
        $pageData = getURIByRoute($this->request);
        $tableName = 'module_formtools_' . $all['model'];
        $modeldetaill = json_decode($all['modeldetaill']->fields, true);
        $uploadKey = [];
        foreach ($modeldetaill as $f) {
            //判断字段规则
            $insterdata[$f['identification']] = is_array($all[$f['identification']]) ? implode(',', $all[$f['identification']]) : $all[$f['identification']];
            if (in_array($f['formtype'], ['upload', 'image'])) {
                $uploadKey[] = $f['identification'];
            }
        }
        if ($uploadKey) {
            foreach ($uploadKey as $key) {
                unset($insterdata[$key]);
                if ($_FILES[$key]['size'] > 0) {
                    try {
                        $insterdata[$key] = UploadFile(\Request(), $key, "file/" . date("Y/m/d/") . uniqid(), ALLOWEXT, __E("upload_driver"));
                    } catch (\Exception $exception) {
                        return back()->with("pageDataMsg", $exception->getMessage());
                    }
                }
            }
        }


        $insterdata['updated_at'] = date("Y-m-d H:i:s", time());
        if ($all['id'] > 0) {
            $res = DB::table($tableName)->where("id", $all['id'])->update($insterdata);
            if ($res) {
                return redirect('admin/' . strtolower($pageData['moduleName']) . '/model?moduleName=' . $all['moduleName'] . '&action=List&model=' . $all['model'] . '&page=' . $all['page'])
                    ->with("pageDataMsg", "修改成功")
                    ->with('pageDataStatus', 200);
            }
            return back()->with("pageDataMsg", "修改失败");
        }
        $insterdata['created_at'] = date("Y-m-d H:i:s", time());
        $res = DB::table($tableName)->insertGetId($insterdata);
        if ($res) {
            return redirect('admin/' . strtolower($pageData['moduleName']) . '/model?moduleName=' . $all['moduleName'] . '&action=List&model=' . $all['model'])
                ->with("pageDataMsg", "添加成功")
                ->with('pageDataStatus', 200);
        }
        return back()->with("pageDataMsg", "添加失败");
    }

    public function Del($all) {
        $pageData = getURIByRoute($this->request);
        $tableName = 'module_formtools_' . $all['model'];
        $res = DB::table($tableName)->where("id", $all['id'])->delete();
        if ($res) {
            return redirect('admin/' . strtolower($pageData['moduleName']) . '/model?moduleName=' . $all['moduleName'] . '&action=List&model=' . $all['model'] . '&page=' . $all['page'])
                ->with("pageDataMsg", "删除成功")
                ->with('pageDataStatus', 200);
        }
        return back()->with("pageDataMsg", "删除失败");
    }

    public function uploadImg($all) {
        set_time_limit(0);
        ini_set("memory_limit", "-1");
        $request = \Request();
        if ($_FILES['file']['size'] <= 0) return json_encode(["msg" => '请选择有效文件']);
        //if ($_FILES['file']['size'] > 1 * 1024 * 1024) return json_encode(["msg" => '文件不能大于1M']);
        try {
            $file = UploadFile($request, 'file', "file/" . date("Y/m/d/") . uniqid(), ALLOWEXT, __E("upload_driver"));
            return json_encode(["location" => GetUrlByPath($file)]);
        } catch (\Exception $exception) {
            return json_encode(["msg" => $exception->getMessage()]);
        }

    }


}
