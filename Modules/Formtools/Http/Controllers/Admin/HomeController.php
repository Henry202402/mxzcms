<?php

namespace Modules\Formtools\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Formtools\Models\FormModel;
use Modules\Formtools\Models\FormPage;
use Modules\Formtools\Support\FormTemplateResolver;
use Modules\Main\Services\ServiceModel;
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
            if (FormModel::isReservedIdentification($identification)){
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
            $fields = [];
            foreach ($results as $key => $result) {
                if(in_array($result->COLUMN_NAME, [
                    'id', 'created_at', 'updated_at', 'uid', 'access_count', 'good_count',
                    'comment_count', 'download_count', 'seo_title', 'seo_keywords',
                    'seo_description', 'status', 'remark'
                ], true)){
                    continue;
                }
                $field['name'] = $result->COLUMN_COMMENT;
                $field['rule'] = "unlimited";
                $field['regex'] = null;
                $field['foreign'] = null;
                $field['remark'] = $field['name'];
                $field['isindex'] = "NOINDEX";
                $field['formtype'] = $this->guessImportedFormType($result->COLUMN_NAME, $result->DATA_TYPE);
                $field['required'] = "";
                $field['fieldtype'] = $this->guessImportedFieldType($result->DATA_TYPE);
                $field['maxlength'] = $result->CHARACTER_MAXIMUM_LENGTH ?: "";
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
        if (!$insters) {
            return returnArr(0, '没有可同步的新模型', '', '');
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

            return redirect("/admin/formtools/index")->with(["pageDataMsg" => "恢复默认模型配置成功", "pageDataStatus" => 200]);
        } catch (\Exception $exception) {
            return back()->with("pageDataMsg", "操作失败!");
        }

    }

    public function seedDemoContent() {
        try {
            Artisan::call('db:seed', [
                '--class' => "Modules\Formtools\Database\Seeders\ModuleFormtoolsDemoContentSeeder",
                '--force' => 1,
            ]);

            return redirect("/admin/formtools/index")->with(["pageDataMsg" => "灌入演示内容成功", "pageDataStatus" => 200]);
        } catch (\Exception $exception) {
            return back()->with("pageDataMsg", "操作失败!");
        }
    }

    public function resetModelData() {
        try {
            Artisan::call('migrate', [
                '--path' => modules_relative_path('Formtools/Database/Migrations/install'),
                '--force' => 1,
            ]);

            Artisan::call('db:seed', [
                '--class' => "Modules\Formtools\Database\Seeders\DatabaseSeeder",
                '--force' => 1,
            ]);

            Artisan::call('db:seed', [
                '--class' => "Modules\Formtools\Database\Seeders\ModuleFormtoolsDemoContentSeeder",
                '--force' => 1,
            ]);

            return redirect("/admin/formtools/index")->with(["pageDataMsg" => "重建模型结构与演示数据成功", "pageDataStatus" => 200]);
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
            $tablename = str_replace(env("DB_PREFIX"),'', $table->getName());
            if (!str_starts_with($tablename, 'module_formtools_')) {
                continue;
            }
            if (FormModel::isReservedTableName($tablename)) {
                continue;
            }
            $identification = str_replace('module_formtools_', '', $tablename);
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

        $pageData['datas'] = FormModel::query()
            ->withoutReserved()
            ->orderBy("show_home_page", "desc")
            ->orderBy("home_page_sort")
            ->orderBy("id", "desc")
            ->paginate(10);

        //dd($pageData['datas'] );

        return view("formtools::admin.index.index", [
            "pageData" => $pageData,
            "tablesList" => $tablesList,
            "homepageStatus" => $this->resolveHomepageStatus(),
        ]);

    }

    public function modelStatistics() {
        $pageData = getURIByRoute($this->request);
        $pageData['title'] = "模型统计";
        $pageData['subtitle'] = "数据统计";
        $pageData['moduleName'] = $pageData['moduleName'] ?? 'Formtools';

        $id = (int) $this->request->query('id', 0);
        $currentModel = FormModel::query()->find($id);
        if (!$currentModel) {
            return redirect("/admin/formtools/index")->with("pageDataMsg", "模型不存在");
        }

        $pageData['currentModel'] = $currentModel;
        $pageData['tableName'] = 'module_formtools_' . $currentModel->identification;
        $pageData['statistics'] = $this->buildModelStatistics($currentModel);

        return view("formtools::admin.index.statistics", [
            "pageData" => $pageData,
        ]);
    }

    private function resolveHomepageStatus(): array
    {
        $page = FormPage::resolveHomepage();
        if ($page) {
            $fallbackModule = ServiceModel::getModuleIndex();
            $fallbackSummary = $fallbackModule
                ? '取消后会自动回退到模块首页：' . (trim((string) ($fallbackModule->name ?? '')) ?: trim((string) ($fallbackModule->title ?? '')) ?: $fallbackModule->identification)
                : '取消后会继续按“模块第二、默认第三”规则回退。';
            return [
                'source' => 'page',
                'title' => '页面优先',
                'name' => $page->name,
                'summary' => '当前站点首页由页面管理接管，访问 `/` 时会直接渲染这张页面。',
                'detail' => '页面标识：' . $page->identification . '，正式路径：' . ($page->getPublicPath() ?: '/') . '。' . $fallbackSummary,
                'manage_url' => url('admin/formtools/pageEdit?id=' . $page->id),
                'manage_text' => '编辑首页页面',
                'cancel_url' => url('admin/formtools/pageSetHome?id=' . $page->id . '&is_home=0'),
                'cancel_text' => '取消页面首页',
                'module_url' => url('admin/module'),
                'module_text' => '模块首页设置',
            ];
        }

        $module = ServiceModel::getModuleIndex();
        if ($module) {
            $moduleName = trim((string) ($module->name ?? '')) ?: trim((string) ($module->title ?? '')) ?: trim((string) ($module->identification ?? '未命名模块'));
            return [
                'source' => 'module',
                'title' => '模块第二',
                'name' => $moduleName,
                'summary' => '当前没有页面接管首页，所以根路径 `/` 会继续交给已设为首页的模块处理。',
                'detail' => '模块标识：' . ($module->identification ?? '-') . '。如果后面设置了首页页面，页面会自动覆盖模块首页。',
                'manage_url' => '',
                'manage_text' => '',
                'cancel_url' => url('admin/module/changeIndex?m=' . $module->identification . '&is_index=0'),
                'cancel_text' => '取消模块首页',
                'module_url' => url('admin/module'),
                'module_text' => '模块首页设置',
            ];
        }

        return [
            'source' => 'default',
            'title' => '默认第三',
            'name' => (string) (cacheGlobalSettingsByKey('website_name') ?: '默认首页模板'),
            'summary' => '当前既没有首页页面，也没有模块首页，所以系统会回退到默认首页模板。',
            'detail' => '默认首页会读取开启“首页展示”的模型区块和基础站点信息，适合作为最终兜底。',
            'manage_url' => '',
            'manage_text' => '',
            'cancel_url' => '',
            'cancel_text' => '',
            'module_url' => url('admin/module'),
            'module_text' => '模块首页设置',
        ];
    }

    public function modelAdd() {
        if ($this->request->isMethod("post")) {
            $data = $this->request->all();

            if (!$data['name'] || !$data['identification'] || !$data['access_identification'] || !$data['remark']) {
                return back()->with("pageDataMsg", "请填写完整");
            }
            $data['identification'] = strtolower($data['identification']);
            if (FormModel::isReservedIdentification($data['identification'])) {
                return back()->with("pageDataMsg", "该标识为页面系统保留标识，不能用于模型");
            }
            $check = FormModel::query()->where("identification", $data['identification'])->first();
            if ($check) {
                return back()->with("pageDataMsg", "标识已存在");
            }
            if (FormModel::query()->where('access_identification', $data['access_identification'])->exists()) {
                return back()->with("pageDataMsg", "访问标识已存在");
            }

            $data['admin_config'] = FormTemplateResolver::normalizeAdminConfig((array) ($data['admin_config'] ?? []));
            $data['home_config'] = FormTemplateResolver::normalizeHomeConfig((array) ($data['home_config'] ?? []));
            $data['created_at'] = date("Y-m-d H:i:s", time());
            $data['updated_at'] = date("Y-m-d H:i:s", time());

            if ($_FILES['detail_page_bg_img']['size'] > 0) {
                try {
                    $data['home_config']['detail_page_bg_img'] = UploadFile($this->request, "detail_page_bg_img", "model/" . date("Y/m/d/") . uniqid(), ALLOWEXT, __E("upload_driver"));
                } catch (\Exception $exception) {
                    return redirect("/admin/formtools/index")->with("pageDataMsg", $exception->getMessage());
                }
            }

            if ($_FILES['home_page_bg_img']['size'] > 0) {
                try {
                    $data['home_config']['home_page_bg_img'] = UploadFile($this->request, "home_page_bg_img", "model/" . date("Y/m/d/") . uniqid(), ALLOWEXT, __E("upload_driver"));
                } catch (\Exception $exception) {
                    return redirect("/admin/formtools/index")->with("pageDataMsg", $exception->getMessage());
                }
            }

            unset($data['_token'], $data['custom_list_template'], $data['requestid']);
            $tableName = 'module_formtools_' . $data['identification'];
            //创建模型表
            if (Schema::hasTable($tableName)) {
                return back()->with("pageDataMsg", "表已存在");
            }

            // 表不存在
            call_user_func([new TableStructure(), 'createTable'], $tableName, $data);

            $data['admin_config'] = json_encode($data['admin_config'], true);
            $data['home_config'] = json_encode($data['home_config'], true);
            $data['home_seo_config'] = json_encode($data['home_seo_config'], true);
            $data['home_seo_detail_config'] = json_encode($data['home_seo_detail_config'], true);
            $data['other_config'] = json_encode($data['other_config'], true);

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

        return $this->renderModelForm($pageData);
    }

    public function modelEdit() {
        if ($this->request->isMethod("post")) {
            $data = $this->request->all();

            if (!$data['name'] || !$data['identification'] || !$data['access_identification'] || !$data['remark']) {
                return back()->with("pageDataMsg", "请填写完整");
            }
            $data['identification'] = strtolower($data['identification']);
            if (FormModel::isReservedIdentification($data['identification'])) {
                return back()->with("pageDataMsg", "该标识为页面系统保留标识，不能用于模型");
            }
            $check = FormModel::query()->where("identification", $data['identification'])->first();
            if (!$check) {
                return back()->with("pageDataMsg", "标识不存在");
            }
            $find = FormModel::query()->where('id', $data['id'])->first();
            if (FormModel::query()
                ->where('access_identification', $data['access_identification'])
                ->where('id', '<>', $data['id'])
                ->exists()) {
                return back()->with("pageDataMsg", "访问标识已存在");
            }
            $home_config = json_decode($find['home_config'], true);

            $data['updated_at'] = date("Y-m-d H:i:s", time());
            $data['admin_config'] = FormTemplateResolver::normalizeAdminConfig((array) ($data['admin_config'] ?? []));
            $data['home_config'] = FormTemplateResolver::normalizeHomeConfig((array) ($data['home_config'] ?? []));

            if ($_FILES['detail_page_bg_img']['size'] > 0) {
                try {
                    $data['home_config']['detail_page_bg_img'] = UploadFile($this->request, "detail_page_bg_img", "model/" . date("Y/m/d/") . uniqid(), ALLOWEXT, __E("upload_driver"));
                } catch (\Exception $exception) {
                    return redirect("/admin/formtools/index")->with("pageDataMsg", $exception->getMessage());
                }
            } else {
                $data['home_config']['detail_page_bg_img'] = $home_config['detail_page_bg_img'];
            }
            if ($_FILES['home_page_bg_img']['size'] > 0) {
                try {
                    $data['home_config']['home_page_bg_img'] = UploadFile($this->request, "home_page_bg_img", "model/" . date("Y/m/d/") . uniqid(), ALLOWEXT, __E("upload_driver"));
                } catch (\Exception $exception) {
                    return redirect("/admin/formtools/index")->with("pageDataMsg", $exception->getMessage());
                }
            } else {
                $data['home_config']['home_page_bg_img'] = $home_config['home_page_bg_img'];
            }

            unset($data['_token'], $data['custom_list_template'], $data['requestid']);
            $updateData = [
                "name" => $data['name'],
                "identification" => $data['identification'],
                "access_identification" => $data['access_identification'],
                "menuname" => $data['menuname'],
                "module" => $data['module'],
                "remark" => $data['remark'],
                "icon" => $data['icon'],
                "type" => $data['type'],
                "show_home_page" => $data['show_home_page'],
                "home_page_num" => $data['home_page_num'],
                "home_page_sort" => $data['home_page_sort'],
                "updated_at" => $data['updated_at']
            ];

            $updateData['admin_config'] = json_encode($data['admin_config'], true);
            $updateData['home_config'] = json_encode($data['home_config'], true);
            $updateData['home_seo_config'] = json_encode($data['home_seo_config'], true);
            $updateData['home_seo_detail_config'] = json_encode($data['home_seo_detail_config'], true);
            $updateData['other_config'] = json_encode($data['other_config'], true);


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
        $pageData['data']->admin_config = json_decode($pageData['data']->admin_config, true);
        $pageData['data']->home_config = json_decode($pageData['data']->home_config, true);
        $pageData['data']->home_seo_config = json_decode($pageData['data']->home_seo_config, true);
        $pageData['data']->home_seo_detail_config = json_decode($pageData['data']->home_seo_detail_config, true);
        $pageData['data']->other_config = json_decode($pageData['data']->other_config, true);
        $modelData = (array) $pageData['data'];
        $normalized = FormTemplateResolver::normalizeModelData($modelData);
        $modelData['admin_config'] = $normalized['admin_config'];
        $modelData['home_config'] = $normalized['home_config'];

        return $this->renderModelForm($pageData, $modelData);
    }

    private function renderModelForm(array $pageData, array $modelData = []) {
        $modelData = array_merge([
            'id' => '',
            'menuname' => '',
            'name' => '',
            'icon' => 'icon-list-numbered',
            'type' => 'multi',
            'identification' => '',
            'access_identification' => '',
            'remark' => '',
            'module' => '',
            'show_home_page' => 'no',
            'home_page_num' => '',
            'home_page_sort' => 0,
        ], $modelData);

        $adminConfig = FormTemplateResolver::normalizeAdminConfig((array) ($modelData['admin_config'] ?? []));
        $homeConfig = FormTemplateResolver::normalizeHomeConfig((array) ($modelData['home_config'] ?? []));
        $homeSeoConfig = array_merge([
            'title' => '',
            'keyword' => '',
            'describe' => '',
        ], (array) ($modelData['home_seo_config'] ?? []));
        $homeSeoDetailConfig = array_merge([
            'title' => '',
            'keyword' => '',
            'describe' => '',
        ], (array) ($modelData['home_seo_detail_config'] ?? []));
        $otherConfig = array_merge([
            'data_source' => 'local',
            'data_source_api_url' => '',
            'data_source_api_url_detail' => '',
            'data_source_field_mapping' => '',
        ], (array) ($modelData['other_config'] ?? []));

        $isEdit = !empty($modelData['id']);
        $formtool = FormTool::create();
        $formtool->formAction(url($isEdit ? "admin/formtools/modelEdit" : "admin/formtools/modelAdd"));
        $formtool->csrf_field();
        $formtool->formid('formtoolsModelForm');
        $formtool->tips('在这里完成模型的基本设置、前台展示和数据来源配置；保存后就可以继续添加字段和内容。');
        $formtool->inlineScript($this->buildModelFormInlineScript(!$isEdit));

        if ($isEdit) {
            $formtool->field('id', ['formtype' => 'hidden', 'value' => $modelData['id']]);
        }

        $formtool->section('section_menu', '菜单');
        $formtool->field('menuname', '一级菜单名称', $modelData['menuname'])
            ->required('menuname', 'required')
            ->placeholder('menuname', '菜单名称，左边导航的名称')
            ->notes('menuname', '左侧菜单的大类名称。');
        $formtool->field('name', '模型名称', $modelData['name'])
            ->required('name', 'required')
            ->placeholder('name', '模型名称')
            ->notes('name', '会显示在左侧菜单和模型列表中。');
        $formtool->field('icon', '菜单图标', $modelData['icon'])
            ->required('icon', 'required')
            ->placeholder('icon', '菜单图标')
            ->notes('icon', '可直接输入图标名称，也可以点击下面的常用图标。');
        $formtool->field('icon_preset_preview', '常用图标预设', $this->buildModelIconPresetGuide($modelData['icon']))
            ->formtype('icon_preset_preview', 'word')
            ->notes('icon_preset_preview', '点一下即可选中，也可以继续手动修改。');

        $formtool->section('section_model', '模型');
        $formtool->field('type', '模型类型', $modelData['type'])
            ->formtype('type', 'radio')
            ->datas('type', $this->buildRadioOptions([
                'multi' => '列表',
                'single' => '单页',
            ]));

        if ($isEdit) {
            $formtool->field('identification', '模型标识', $modelData['identification'])
                ->formtype('identification', 'readonly')
                ->required('identification', 'required')
                ->notes('identification', '保存后会作为这个模型的数据标识使用。');
        } else {
            $formtool->field('identification', '模型标识', $modelData['identification'])
                ->required('identification', 'required')
                ->placeholder('identification', '模型标识/表名')
                ->notes('identification', '建议使用英文或下划线，保存后会作为这个模型的数据标识。');
        }

        $formtool->field('access_identification', '访问标识', $modelData['access_identification'])
            ->required('access_identification', 'required')
            ->placeholder('access_identification', '访问标识')
            ->notes('access_identification', '前台访问会使用这个名称，建议简短好记。');
        $formtool->field('remark', '模型备注', $modelData['remark'])
            ->required('remark', 'required')
            ->placeholder('remark', '模型备注')
            ->notes('remark', '方便区分模型用途的说明。');
        $formtool->field('module', '追加模型到', $modelData['module'])
            ->formtype('module', 'select')
            ->datas('module', $this->buildModuleOptions($pageData['modules'] ?? []))
            ->notes('module', '不选择也可以单独使用这个模型。');

        $formtool->section('section_admin', '后台', '设置后台录入页的显示方式。');
        $formtool->field('admin_config[form_template]', '后台表单模板', $adminConfig['form_template'])
            ->formtype('admin_config[form_template]', 'select')
            ->datas('admin_config[form_template]', $this->buildSelectOptions([
                'row' => '并列模板',
                'solo' => '独行模板',
            ]));

        $formtool->section('section_home', '前台', '设置前台列表、详情页和首页展示方式。');
        $formtool->field('home_frontend_route_guide', '前台访问说明', $this->buildFrontendRouteGuide($modelData['access_identification']))
            ->formtype('home_frontend_route_guide', 'word')
            ->notes('home_frontend_route_guide', '保存后可直接打开这些页面查看效果。');
        $formtool->field('home_frontend_field_guide', '字段与前台交互', $this->buildFrontendFieldGuide())
            ->formtype('home_frontend_field_guide', 'word')
            ->notes('home_frontend_field_guide', '字段是否显示在前台表单和搜索区，可在字段管理里随时调整。');
        $formtool->field('home_template_guide', '模板选型建议', $this->buildTemplateGuide())
            ->formtype('home_template_guide', 'word')
            ->notes('home_template_guide', '先选最接近的模板即可，后面还可以继续微调。');
        $formtool->field('home_config[list_template]', '列表模板', $homeConfig['list_template'])
            ->formtype('home_config[list_template]', 'select')
            ->datas('home_config[list_template]', $this->buildSelectOptions(\Modules\Formtools\Helper\FormFunc::listTemplate()))
            ->notes('home_config[list_template]', '选择适合当前内容的列表样式；找不到自定义模板时会自动使用系统模板。');
        $formtool->field('home_config[custom_list_template]', '自定义列表模板', $homeConfig['custom_list_template'])
            ->placeholder('home_config[custom_list_template]', '自定义列表模板名称')
            ->notes('home_config[custom_list_template]', '如果你有专用页面样式，可以在这里填写模板名称。');
        $formtool->field('home_config[page_num]', '列表分页数量', $homeConfig['page_num'])
            ->required('home_config[page_num]', 'required')
            ->placeholder('home_config[page_num]', '分页数量')
            ->notes('home_config[page_num]', '填写每页显示数量，填 0 表示全部显示。');
        $formtool->field('home_config[list_page_template]', '列表分页样式', $homeConfig['list_page_template'])
            ->formtype('home_config[list_page_template]', 'select')
            ->datas('home_config[list_page_template]', $this->buildSelectOptions([
                'center' => '分页居中',
                'left' => '分页居左',
                'right' => '分页居右',
            ]));
        $formtool->field('home_config[detail_template]', '详情页模板', $homeConfig['detail_template'])
            ->formtype('home_config[detail_template]', 'select')
            ->datas('home_config[detail_template]', $this->buildSelectOptions(\Modules\Formtools\Helper\FormFunc::detailTemplate()));
        $formtool->field('home_config[custom_detail_template]', '自定义详情模板', $homeConfig['custom_detail_template'])
            ->placeholder('home_config[custom_detail_template]', '自定义详情模板名称')
            ->notes('home_config[custom_detail_template]', '如果你有专用详情页样式，可以在这里填写模板名称。');
        $formtool->field('home_config[detail_page_title]', '详情页面标题', $homeConfig['detail_page_title'])
            ->placeholder('home_config[detail_page_title]', '详情页面标题');
        $formtool->field('home_config[detail_page_describe]', '详情页面简介', $homeConfig['detail_page_describe'])
            ->formtype('home_config[detail_page_describe]', 'textarea')
            ->rows('home_config[detail_page_describe]', 5)
            ->placeholder('home_config[detail_page_describe]', '详情页面简介');
        $formtool->field('home_config[detail_page_show_type]', '详情区块背景类型', $homeConfig['detail_page_show_type'])
            ->formtype('home_config[detail_page_show_type]', 'radio')
            ->datas('home_config[detail_page_show_type]', $this->buildRadioOptions([
                'color' => '纯色',
                'img' => '图片',
            ]));
        $formtool->field('home_config[detail_page_bg_color]', '详情区块背景颜色', $homeConfig['detail_page_bg_color'])
            ->formtype('home_config[detail_page_bg_color]', 'color')
            ->placeholder('home_config[detail_page_bg_color]', '#ffffff')
            ->notes('home_config[detail_page_bg_color]', '选择“纯色”背景时会使用这里的颜色。');
        $formtool->field('detail_page_bg_img', '详情块背景图', $homeConfig['detail_page_bg_img'])
            ->formtype('detail_page_bg_img', 'image')
            ->notes('detail_page_bg_img', '选择“图片”背景时可在这里上传。');
        $formtool->field('show_home_page', '是否显示在首页', $modelData['show_home_page'])
            ->formtype('show_home_page', 'switch')
            ->datas('show_home_page', $this->buildRadioOptions([
                'yes' => '显示',
                'no' => '不显示',
            ]));
        $formtool->field('home_page_num', '首页显示数量', $modelData['home_page_num'])
            ->placeholder('home_page_num', '首页显示数量，0 为全部');
        $formtool->field('home_page_sort', '显示在前台的顺序', $modelData['home_page_sort'])
            ->placeholder('home_page_sort', '升序排序，默认为 0')
            ->notes('home_page_sort', '数字越小越靠前。');
        $formtool->field('home_config[home_page_title]', '首页页面标题', $homeConfig['home_page_title'])
            ->placeholder('home_config[home_page_title]', '首页页面标题');
        $formtool->field('home_config[home_page_title_size]', '首页页面标题大小', $homeConfig['home_page_title_size'])
            ->placeholder('home_config[home_page_title_size]', '首页页面标题大小');
        $formtool->field('home_config[home_page_title_color]', '首页页面标题颜色', $homeConfig['home_page_title_color'])
            ->formtype('home_config[home_page_title_color]', 'color')
            ->placeholder('home_config[home_page_title_color]', '#222222');
        $formtool->field('home_config[home_page_describe]', '首页页面简介', $homeConfig['home_page_describe'])
            ->formtype('home_config[home_page_describe]', 'textarea')
            ->rows('home_config[home_page_describe]', 5)
            ->placeholder('home_config[home_page_describe]', '首页页面简介');
        $formtool->field('home_config[home_page_describe_size]', '首页页面简介大小', $homeConfig['home_page_describe_size'])
            ->placeholder('home_config[home_page_describe_size]', '首页页面简介大小');
        $formtool->field('home_config[home_page_describe_color]', '首页页面简介颜色', $homeConfig['home_page_describe_color'])
            ->formtype('home_config[home_page_describe_color]', 'color')
            ->placeholder('home_config[home_page_describe_color]', '#666666');
        $formtool->field('home_config[show_home_type]', '首页区块背景类型', $homeConfig['show_home_type'])
            ->formtype('home_config[show_home_type]', 'radio')
            ->datas('home_config[show_home_type]', $this->buildRadioOptions([
                'color' => '纯色',
                'img' => '图片',
            ]));
        $formtool->field('home_config[home_page_bg_color]', '首页区块背景颜色', $homeConfig['home_page_bg_color'])
            ->formtype('home_config[home_page_bg_color]', 'color')
            ->placeholder('home_config[home_page_bg_color]', '#ffffff')
            ->notes('home_config[home_page_bg_color]', '选择“纯色”背景时会使用这里的颜色。');
        $formtool->field('home_page_bg_img', '首页区块背景图', $homeConfig['home_page_bg_img'])
            ->formtype('home_page_bg_img', 'image')
            ->notes('home_page_bg_img', '选择“图片”背景时可在这里上传。');

        $formtool->section('section_seo', 'SEO', '设置列表页和详情页的搜索展示信息。');
        $formtool->field('seo_strategy_guide', 'SEO 覆盖说明', $this->buildSeoGuide())
            ->formtype('seo_strategy_guide', 'word')
            ->notes('seo_strategy_guide', '这里填写的是默认值，单条内容里还可以单独设置。');
        $formtool->field('home_seo_config[title]', '列表页 SEO 标题', $homeSeoConfig['title'])
            ->placeholder('home_seo_config[title]', 'SEO 标题')
            ->notes('home_seo_config[title]', $this->buildSeoTokenNote(['{{model_name}}', '{{model_title}}', '{{website_name}}', '{{current_lang}}', '{{current_url}}']));
        $formtool->field('home_seo_config[keyword]', '列表页 SEO 关键词', $homeSeoConfig['keyword'])
            ->placeholder('home_seo_config[keyword]', 'SEO 关键词')
            ->notes('home_seo_config[keyword]', $this->buildSeoTokenNote(['{{model_name}}', '{{website_keywords}}', '{{current_lang}}']));
        $formtool->field('home_seo_config[describe]', '列表页 SEO 描述', $homeSeoConfig['describe'])
            ->formtype('home_seo_config[describe]', 'textarea')
            ->rows('home_seo_config[describe]', 4)
            ->placeholder('home_seo_config[describe]', 'SEO 描述')
            ->notes('home_seo_config[describe]', $this->buildSeoTokenNote(['{{model_name}}', '{{model_home_page_describe}}', '{{website_description}}', '{{current_lang}}']) . '；为空时会继续回退到系统 SEO 配置。');
        $formtool->field('home_seo_detail_config[title]', '详情页 SEO 标题', $homeSeoDetailConfig['title'])
            ->placeholder('home_seo_detail_config[title]', 'SEO 标题')
            ->notes('home_seo_detail_config[title]', $this->buildSeoTokenNote(['{{model_name}}', '{{data_title}}', '{{data_name}}', '{{detail_title}}', '{{detail_name}}', '{{model_home_page_title}}', '{{website_name}}', '{{current_lang}}', '{{current_url}}']));
        $formtool->field('home_seo_detail_config[keyword]', '详情页 SEO 关键词', $homeSeoDetailConfig['keyword'])
            ->placeholder('home_seo_detail_config[keyword]', 'SEO 关键词')
            ->notes('home_seo_detail_config[keyword]', $this->buildSeoTokenNote(['{{model_name}}', '{{data_title}}', '{{data_name}}', '{{detail_title}}', '{{detail_name}}', '{{website_keywords}}', '{{current_lang}}']));
        $formtool->field('home_seo_detail_config[describe]', '详情页 SEO 描述', $homeSeoDetailConfig['describe'])
            ->formtype('home_seo_detail_config[describe]', 'textarea')
            ->rows('home_seo_detail_config[describe]', 4)
            ->placeholder('home_seo_detail_config[describe]', 'SEO 描述')
            ->notes('home_seo_detail_config[describe]', $this->buildSeoTokenNote(['{{model_name}}', '{{data_title}}', '{{data_name}}', '{{detail_title}}', '{{detail_name}}', '{{model_home_page_describe}}', '{{website_description}}', '{{current_lang}}']) . '；为空时优先回退系统详情 SEO，再回退到内容摘要。');

        $formtool->section('section_other', '其他', '可选择使用本站数据，或接入第三方接口内容。');
        $formtool->field('api_data_source_guide', 'API 数据源说明', $this->buildApiDataSourceGuide())
            ->formtype('api_data_source_guide', 'word')
            ->notes('api_data_source_guide', '只有在接入第三方内容时才需要填写，不用时保持默认即可。');
        $formtool->field('other_config[data_source]', '数据源', $otherConfig['data_source'])
            ->formtype('other_config[data_source]', 'radio')
            ->datas('other_config[data_source]', $this->buildRadioOptions([
                'local' => '本地',
                'api' => 'API',
            ]));
        $formtool->field('other_config[data_source_api_url]', 'API 请求列表地址', $otherConfig['data_source_api_url'])
            ->placeholder('other_config[data_source_api_url]', '数据源 API 请求列表地址')
            ->notes('other_config[data_source_api_url]', '填写第三方列表接口地址即可。');
        $formtool->field('other_config[data_source_api_url_detail]', 'API 请求详情地址', $otherConfig['data_source_api_url_detail'])
            ->placeholder('other_config[data_source_api_url_detail]', '数据源 API 请求详情地址')
            ->notes('other_config[data_source_api_url_detail]', '填写第三方详情接口地址，系统会按内容 ID 自动获取详情。');
        $formtool->field('other_config[data_source_field_mapping]', 'API 字段映射', $otherConfig['data_source_field_mapping'])
            ->formtype('other_config[data_source_field_mapping]', 'code')
            ->rows('other_config[data_source_field_mapping]', 10)
            ->placeholder('other_config[data_source_field_mapping]', "title=>data.title\ncontent=>data.body\ncover=>data.cover")
            ->notes('other_config[data_source_field_mapping]', '左边写本站字段，右边写第三方接口字段，一行一组即可。');

        return $formtool->formView($pageData);
    }

    private function buildSelectOptions(array $options): array {
        $datas = [];
        foreach ($options as $value => $name) {
            $datas[] = [
                'name' => $name,
                'value' => (string) $value,
                'children' => [],
                'child' => [],
            ];
        }
        return $datas;
    }

    private function buildRadioOptions(array $options): array {
        $datas = [];
        foreach ($options as $value => $name) {
            $datas[] = [
                'name' => $name,
                'value' => (string) $value,
            ];
        }
        return $datas;
    }

    private function buildModuleOptions(array $modules): array {
        $datas = [[
            'name' => '不关联模块',
            'value' => '',
            'children' => [],
            'child' => [],
        ]];
        foreach ($modules as $module) {
            $datas[] = [
                'name' => $module['name'],
                'value' => $module['identification'],
                'children' => [],
                'child' => [],
            ];
        }
        return $datas;
    }

    private function buildFrontendRouteGuide(string $accessIdentification = ''): string {
        $accessIdentification = trim($accessIdentification) !== '' ? trim($accessIdentification) : 'your-model';
        $listUrl = url('list/' . $accessIdentification);
        $detailUrl = url('detail/' . $accessIdentification . '/{id}');
        $handleUrl = url('handle/' . $accessIdentification);

        return <<<HTML
<div style="line-height: 1.9;">
    <div><strong>列表页：</strong><code>{$listUrl}</code></div>
    <div><strong>详情页：</strong><code>{$detailUrl}</code></div>
    <div><strong>提交接口：</strong><code>{$handleUrl}</code></div>
</div>
HTML;
    }

    private function buildFrontendFieldGuide(): string {
        return <<<'HTML'
<div style="line-height: 1.9;">
    <div>字段管理中开启 <code>前台表单</code> 后，字段会参与前台提交表单渲染。</div>
    <div>字段管理中开启 <code>前台列表搜索</code> 后，字段会参与前台列表筛选。</div>
    <div>如果要直接复用系统模板，建议优先规范这些字段：<code>name</code>、<code>title</code>、<code>content</code>、<code>cover</code>、<code>date</code>、<code>pid</code>。</div>
</div>
HTML;
    }

    private function buildTemplateGuide(): string {
        return <<<'HTML'
<div style="line-height: 1.95;">
    <div><strong>纯文字列表：</strong>适合新闻、公告、协议、说明文档，推荐字段 <code>title/name</code> + <code>content</code>。</div>
    <div><strong>卡片网格：</strong>适合案例、产品、专题，推荐字段 <code>cover</code> + <code>title/name</code> + <code>content</code>。</div>
    <div><strong>树形目录：</strong>适合帮助中心、协议目录、知识分类，推荐字段 <code>pid</code> + <code>title/name</code>，分页建议设置为 <code>0</code> 或较大值。</div>
    <div><strong>图文/时间线：</strong>适合品牌故事、发展历程、活动记录，推荐补充 <code>date</code>、<code>cover</code> 等展示字段。</div>
</div>
HTML;
    }

    private function buildApiDataSourceGuide(): string {
        return <<<'HTML'
<div style="line-height: 1.95;">
    <div><strong>列表返回：</strong>默认优先读取 <code>data</code>，也兼容 <code>list</code>、<code>items</code>。</div>
    <div><strong>详情返回：</strong>默认优先读取 <code>data</code>，关联推荐内容兼容 <code>other</code>、<code>related</code>、<code>list</code>。</div>
    <div><strong>详情地址：</strong>支持 <code>https://api.test.com/article/{id}</code>、<code>.../:id</code> 或直接填写详情基础地址自动拼接。</div>
    <div><strong>字段映射：</strong>左边写模板字段，右边写 API 路径，例如 <code>title=>data.title</code>、<code>cover=>cover.url</code>。</div>
    <div><strong>模板建议：</strong>如果走系统模板，建议至少映射出 <code>id</code>、<code>title/name</code>、<code>content</code>、<code>cover</code>。</div>
</div>
HTML;
    }

    private function buildFieldStatusGuide(array $pageData, array $fieldData, string $fieldTypeLabel): string
    {
        $tableName = 'module_formtools_' . ($pageData['currentModel']->identification ?? '');
        $requiredText = ($fieldData['required'] ?? '') === 'required' ? '必填' : '非必填';
        $adminListText = ($fieldData['is_show_list'] ?? '2') === '1' ? '后台列表显示' : '后台列表隐藏';
        $frontFormText = ($fieldData['is_show_home_form'] ?? '2') === '1' ? '前台表单显示' : '前台表单隐藏';
        $frontDetailText = ($fieldData['is_show_home_detail'] ?? '1') === '1' ? '前台详情显示' : '前台详情隐藏';
        $frontSearchText = ($fieldData['is_show_home_list_search'] ?? '2') === '1' ? '前台搜索启用' : '前台搜索关闭';
        $foreignText = !empty($fieldData['foreign']) ? $fieldData['foreign'] . ' / ' . ($fieldData['foreign_key'] ?? '未选字段') : '无';
        $downloadTip = $this->isDownloadCapableFormType((string) ($fieldData['formtype'] ?? ''))
            ? '是附件字段；只有开启前台详情且内容里确实有文件值时，前台才会显示下载入口。'
            : '当前不是附件字段，不会自动生成前台下载入口。';

        return <<<HTML
<div style="line-height: 1.9;">
    <div><strong>真实字段：</strong><code>{$tableName}.{$fieldData['identification']}</code></div>
    <div><strong>字段类型：</strong><code>{$fieldTypeLabel}</code>，当前编辑模式下保持锁定。</div>
    <div><strong>字段备注：</strong><code>{$fieldData['remark']}</code></div>
    <div><strong>当前状态：</strong>{$requiredText} / {$adminListText} / {$frontFormText} / {$frontDetailText} / {$frontSearchText}</div>
    <div><strong>下载入口：</strong>{$downloadTip}</div>
    <div><strong>关联关系：</strong>{$foreignText}</div>
</div>
HTML;
    }

    private function buildSeoGuide(): string {
        return <<<'HTML'
<div style="line-height: 1.9;">
    <div>模型级 SEO 用于列表页与详情页的默认值。</div>
    <div>内容管理页中的 <code>seo_title</code>、<code>seo_keywords</code>、<code>seo_description</code> 会优先覆盖内容详情页输出。</div>
    <div>未填写时，系统会按模型字段与通用 SEO 规则自动回退。</div>
    <div><strong>推荐变量：</strong><code>{{website_name}}</code>、<code>{{model_name}}</code>、<code>{{data_title}}</code>、<code>{{detail_title}}</code>、<code>{{current_lang}}</code>、<code>{{current_url}}</code></div>
</div>
HTML;
    }

    private function buildSeoTokenDefinitions(): array
    {
        return [
            '{{website_name}}' => '站点名称',
            '{{website_title}}' => '站点名称',
            '{{website_keywords}}' => '站点默认关键词',
            '{{website_description}}' => '站点默认描述',
            '{{model_name}}' => '模型名称',
            '{{model_title}}' => '模型名称',
            '{{data_title}}' => '当前内容标题',
            '{{data_name}}' => '当前内容名称',
            '{{detail_title}}' => '当前内容标题',
            '{{detail_name}}' => '当前内容名称',
            '{{detaill_title}}' => '当前内容标题（兼容旧写法）',
            '{{detaill_name}}' => '当前内容名称（兼容旧写法）',
            '{{model_home_page_title}}' => '模型首页标题',
            '{{model_home_page_describe}}' => '模型首页描述',
            '{{current_lang}}' => '当前语言',
            '{{current_url}}' => '当前页面地址',
        ];
    }

    private function buildSeoTokenNote(array $tokens): string
    {
        $definitions = $this->buildSeoTokenDefinitions();
        $notes = [];
        foreach ($tokens as $token) {
            if (!isset($definitions[$token])) {
                continue;
            }
            $notes[] = $token . ' ' . $definitions[$token];
        }

        return $notes ? '可用变量：' . implode('；', $notes) : '当前字段不支持变量。';
    }

    private function buildModelFormInlineScript(bool $syncRemark = false): string {
        $script = <<<'JS'
(function ($) {
    function toggleDataSourceApiFields() {
        var dataSource = $('input[name="other_config[data_source]"]:checked').val();
        var selectors = [
            'input[name="other_config[data_source_api_url]"]',
            'input[name="other_config[data_source_api_url_detail]"]',
            'textarea[name="other_config[data_source_field_mapping]"]'
        ];

        $.each(selectors, function (_, selector) {
            var $group = $(selector).closest('.form-group, .col-md-6');
            if (!$group.length) {
                return;
            }
            if (dataSource === 'api') {
                $group.show();
            } else {
                $group.hide();
            }
        });
    }

    function toggleCustomTemplateField(selectName, inputName) {
        var value = $('select[name="' + selectName + '"]').val();
        var $group = $('input[name="' + inputName + '"]').closest('.form-group, .col-md-6');
        if (!$group.length) {
            return;
        }
        if (value === '') {
            $group.show();
        } else {
            $group.hide();
        }
    }

    function refreshTemplateFields() {
        toggleCustomTemplateField('home_config[list_template]', 'home_config[custom_list_template]');
        toggleCustomTemplateField('home_config[detail_template]', 'home_config[custom_detail_template]');
    }

    function refreshIconPresetState() {
        var currentIcon = $.trim($('input[name="icon"]').val());
        $('.js-model-icon-preset').each(function () {
            var isActive = $(this).attr('data-icon-value') === currentIcon;
            $(this).css({
                borderColor: isActive ? '#2563eb' : '#e5e7eb',
                background: isActive ? '#eff6ff' : '#fff',
                color: isActive ? '#1d4ed8' : '#334155',
                boxShadow: isActive ? '0 8px 18px rgba(37,99,235,.12)' : 'none'
            });
        });
    }

    $(document).on('change', 'input[name="other_config[data_source]"]', toggleDataSourceApiFields);
    $(document).on('change', 'select[name="home_config[list_template]"], select[name="home_config[detail_template]"]', refreshTemplateFields);
    $(document).on('click', '.js-model-icon-preset', function () {
        $('input[name="icon"]').val($(this).attr('data-icon-value')).trigger('input');
    });
    $(document).on('input', 'input[name="icon"]', refreshIconPresetState);
    toggleDataSourceApiFields();
    refreshTemplateFields();
    refreshIconPresetState();
JS;

        if ($syncRemark) {
            $script .= <<<'JS'

    $(document).on('input', 'input[name="name"]', function () {
        $('input[name="remark"]').val($(this).val());
    });
JS;
        }

        $script .= <<<'JS'
})(jQuery);
JS;

        return $script;
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
        $columnMap = array_flip($colunmList);

        if ($pageData['data']->fields) {
            $fields = json_decode($pageData['data']->fields, true) ?: [];
        }
        foreach ($fields as $k2 => $v2) {
            $v2['column_exists'] = array_key_exists($v2['identification'], $columnMap);
            $v2['is_download_field'] = $this->isDownloadCapableFormType((string) ($v2['formtype'] ?? ''));
            $v2['download_ready'] = $v2['is_download_field'] && ($v2['is_show_home_detail'] ?? '1') == 1;
            if ($v2['column_exists']) {
                $colunmListDetaill[] = $v2;
            }
        }
        return view("formtools::admin.index.fieldList", [
            "pageData" => $pageData,
            "colunmListDetaill" => $colunmListDetaill,
            "tableName" => $tableName,
            "tableColumns" => $colunmList,
            "configuredFields" => $fields,
        ]);
    }

    public function fieldAdd() {
        if ($this->request->isMethod("post")) {
            $data = $this->request->all();
            try {
                $data['datas'] = $this->normalizeFieldOptionInput($data);
            } catch (\InvalidArgumentException $exception) {
                return back()->withInput()->with("pageDataMsg", $exception->getMessage());
            }

            if (!$data['identification'] || !$data['remark']) {
                return back()->withInput()->with("pageDataMsg", "请填写完整");
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
            $updateData2['is_show_home_list'] = $data['is_show_home_list'] ?? '1';
            $updateData2['is_show_home_detail'] = $data['is_show_home_detail'] ?? '1';
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
        $pageData = $this->buildFieldFormPageData((int) $this->request->input("id"), "表单字段添加");
        return $this->renderFieldForm($pageData);
    }

    public function fieldEdit() {
        if ($this->request->isMethod("post")) {
            $data = $this->request->all();
            try {
                $data['datas'] = $this->normalizeFieldOptionInput($data);
            } catch (\InvalidArgumentException $exception) {
                return back()->withInput()->with("pageDataMsg", $exception->getMessage());
            }


            if (!$data['identification'] || !$data['remark']) {
                return back()->withInput()->with("pageDataMsg", "请填写完整");
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
            $updateData2['is_show_home_list'] = $data['is_show_home_list'] ?? '1';
            $updateData2['is_show_home_detail'] = $data['is_show_home_detail'] ?? '1';
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

        $pageData = $this->buildFieldFormPageData((int) $this->request->input("id"), "表单字段编辑");
        $pageData['identification'] = $this->request->input("identification");

        $fieldsData = json_decode($pageData['currentModel']->fields, true) ?: [];
        $pageData['fieldData'] = [];
        foreach ($fieldsData as $field) {
            if ($pageData['identification'] == $field['identification']) {
                $pageData['fieldData'] = $field;
                break;
            }
        }
        $pageData['fieldData']['datas'] = !empty($pageData['fieldData']['datas']) ? json_decode($pageData['fieldData']['datas'], true) : [];

        return $this->renderFieldForm($pageData, $pageData['fieldData']);
    }

    private function buildFieldFormPageData(int $id, string $subtitle): array {
        $pageData = getURIByRoute($this->request);
        $pageData['id'] = $id;
        $pageData['title'] = "万能表单";
        $pageData['subtitle'] = $subtitle;
        $pageData['currentModel'] = FormModel::query()->find($id);
        $pageData['models'] = FormModel::query()->where("id", '!=', $id)->get();
        $pageData['modelsFieldsMap'] = [];

        foreach ($pageData['models'] as $model) {
            $fields = json_decode($model->fields, true) ?: [];
            foreach ($fields as &$field) {
                $field['datas'] = !empty($field['datas']) ? json_decode($field['datas'], true) : [];
            }
            $pageData['modelsFieldsMap'][$model->identification . '-' . $model->id] = $fields;
        }

        $pageData['currentModelFields'] = json_decode($pageData['currentModel']->fields, true) ?: [];
        krsort($pageData['currentModelFields']);

        return $pageData;
    }

    private function renderFieldForm(array $pageData, array $fieldData = []) {
        $isEdit = !empty($fieldData['identification']);
        $fieldData = array_merge([
            'name' => '',
            'identification' => '',
            'remark' => '',
            'formtype' => 'text',
            'fieldtype' => 'string',
            'maxlength' => 11,
            'required' => 'required',
            'isindex' => 'NOINDEX',
            'rule' => 'string',
            'regex' => '',
            'foreign' => '',
            'foreign_key' => '',
            'is_show_list' => '1',
            'is_show_admin_list_search' => '2',
            'is_show_home_form' => '1',
            'is_show_home_list' => '1',
            'is_show_home_detail' => '1',
            'is_show_home_list_search' => '2',
            'datas' => [],
            'after_field' => 'id',
        ], $fieldData);

        $datasJson = $this->prettyJson($fieldData['datas'] ?? []);
        $selectedForeignOptions = $this->buildForeignKeyOptions($pageData['modelsFieldsMap'], $fieldData['foreign'] ?? '');
        $fieldTypeOptions = \Modules\Formtools\Helper\FormFunc::fieldtype();
        $fieldTypeLabel = $fieldTypeOptions[$fieldData['fieldtype']] ?? $fieldData['fieldtype'];
        $formtool = FormTool::create();
        $formtool->formAction(url($isEdit ? "admin/formtools/fieldEdit?id=" . $pageData['id'] : "admin/formtools/fieldAdd?id=" . $pageData['id']));
        $formtool->csrf_field();
        $formtool->formid('formtoolsFieldForm');
        $formtool->tips($isEdit
            ? '修改字段后，后台录入和前台展示都会跟着变化；保存前请先确认名称、显示位置和规则。'
            : '新增字段后，后台录入和前台展示都会用到它，建议先想清楚字段名称和用途。');
        $formtool->inlineScript($this->buildFieldFormInlineScript(
            $pageData['modelsFieldsMap'],
            !$isEdit,
            $fieldData['foreign'] ?? '',
            $fieldData['foreign_key'] ?? ''
        ));

        $formtool->field('id', '', $pageData['id'], 'hidden');
        if ($isEdit) {
            $formtool->field('identification_hidden', '', $fieldData['identification'], 'hidden');
        }

        $formtool->section('field_guide', '使用说明', '字段会影响录入、展示和搜索效果。');
        $formtool->field('field_template_guide', '模板字段建议', $this->buildFieldTemplateGuide())
            ->formtype('field_template_guide', 'word')
            ->notes('field_template_guide', '想让页面更快成型，优先使用这些常用字段。');
        $formtool->field('field_route_guide', '当前模型联动', $this->buildFieldWorkbenchGuide($pageData))
            ->formtype('field_route_guide', 'word')
            ->notes('field_route_guide', '设置后可返回字段列表、内容管理和前台页面继续查看效果。');
        if ($isEdit) {
            $formtool->field('field_runtime_status', '当前字段状态', $this->buildFieldStatusGuide($pageData, $fieldData, $fieldTypeLabel))
                ->formtype('field_runtime_status', 'word')
                ->notes('field_runtime_status', '这里会告诉你这个字段当前的使用状态，保存前可以先确认一下。');
        }

        $formtool->section('field_basic', '基础信息');
        $formtool->field('name', '字段名称', $fieldData['name'])
            ->required('name', 'required')
            ->placeholder('name', '字段名称，显示在数据列表和表单上');

        if ($isEdit) {
            $formtool->field('identification', '字段标识', $fieldData['identification'])
                ->formtype('identification', 'readonly')
                ->required('identification', 'required')
                ->notes('identification', '字段标识已落到真实数据表，编辑时保持只读。');
        } else {
            $formtool->field('identification', '字段标识', $fieldData['identification'])
                ->required('identification', 'required')
                ->placeholder('identification', '字段标识')
                ->notes('identification', '建议使用英文、小写和下划线。');
        }

        $formtool->field('remark', '字段备注', $fieldData['remark'])
            ->required('remark', 'required')
            ->placeholder('remark', '字段备注，列在数据表的备注');
        $formtool->field('formtype', '表单类型', $fieldData['formtype'])
            ->formtype('formtype', 'select')
            ->datas('formtype', $this->buildSelectOptions(\Modules\Formtools\Helper\FormFunc::formtype()))
            ->notes('formtype', '选择这个字段在录入页里的展示方式。');
        $formtool->field('field_formtype_hint', '前台联动提示', $this->buildFieldFormTypeHint($fieldData['formtype'] ?? ''))
            ->formtype('field_formtype_hint', 'word')
            ->notes('field_formtype_hint', '切换类型后，这里会告诉你前台会不会出现下载入口。');
        if ($isEdit) {
            $formtool->field('fieldtype', '', $fieldData['fieldtype'], 'hidden');
            $formtool->field('fieldtype_display', '字段类型', $fieldTypeLabel)
                ->formtype('fieldtype_display', 'readonly')
                ->notes('fieldtype_display', '为避免影响已有数据，编辑时不建议再改字段类型。');
        } else {
            $formtool->field('fieldtype', '字段类型', $fieldData['fieldtype'])
                ->formtype('fieldtype', 'select')
                ->datas('fieldtype', $this->buildSelectOptions($fieldTypeOptions))
                ->notes('fieldtype', '决定这个字段的数据保存方式，新增后尽量不要频繁修改。');
        }
        $formtool->field('maxlength', '最大长度', $fieldData['maxlength'])
            ->formtype('maxlength', 'number')
            ->placeholder('maxlength', '最大长度，0 表示没有限制');

        $formtool->section('field_schema', '数据结构', '选项型字段和外键字段会在这里补配置。');
        if (!$isEdit) {
            $formtool->field('after_field', '字段位置', $fieldData['after_field'])
                ->formtype('after_field', 'select')
                ->datas('after_field', $this->buildAfterFieldOptions($pageData['currentModelFields']))
                ->notes('after_field', '新字段会排在这里选择的字段后面。');
        }
        $formtool->field('datas_json', '选项数据', $datasJson)
            ->formtype('datas_json', 'json')
            ->rows('datas_json', 10)
            ->placeholder('datas_json', "[\n  {\"value\": \"1\", \"name\": \"选项一\"},\n  {\"value\": \"2\", \"name\": \"选项二\"}\n]")
            ->notes('datas_json', '单选、下拉、多选这类字段才需要填写这里。');
        $formtool->field('rule', '字段规则', $fieldData['rule'])
            ->formtype('rule', 'select')
            ->datas('rule', $this->buildSelectOptions(\Modules\Formtools\Helper\FormFunc::rule()));
        $formtool->field('regex', '正则表达式', $fieldData['regex'])
            ->placeholder('regex', '正则表达式');
        $formtool->field('required', '是否必填', $fieldData['required'])
            ->formtype('required', 'switch')
            ->datas('required', $this->buildRadioOptions([
                'required' => '必填',
                '' => '非必填',
            ]));
        $formtool->field('isindex', '是否设索引', $fieldData['isindex'])
            ->formtype('isindex', 'radio')
            ->datas('isindex', $this->buildRadioOptions(\Modules\Formtools\Helper\FormFunc::isindex()));

        $formtool->section('field_relation', '关联关系');
        $formtool->field('foreign', '关联模型', $fieldData['foreign'])
            ->formtype('foreign', 'select')
            ->datas('foreign', $this->buildForeignModelOptions($pageData['models']))
            ->notes('foreign', '选择关联模型后，下方会联动可选的关联字段。');
        $formtool->field('foreign_key', '关联字段', $fieldData['foreign_key'])
            ->formtype('foreign_key', 'select')
            ->datas('foreign_key', $selectedForeignOptions)
            ->notes('foreign_key', '通常选择对方模型中的标题、名称等字段。');

        $formtool->section('field_visibility', '显示控制', '决定字段会不会出现在后台列表、前台表单和前台页面里。');
        $formtool->field('is_show_list', '后台数据列表', $fieldData['is_show_list'])
            ->formtype('is_show_list', 'switch')
            ->datas('is_show_list', $this->buildRadioOptions(['1' => '显示', '2' => '不显示']));
        $formtool->field('is_show_admin_list_search', '后台列表搜索', $fieldData['is_show_admin_list_search'])
            ->formtype('is_show_admin_list_search', 'switch')
            ->datas('is_show_admin_list_search', $this->buildRadioOptions(['1' => '显示', '2' => '不显示']));
        $formtool->field('is_show_home_form', '前台表单', $fieldData['is_show_home_form'])
            ->formtype('is_show_home_form', 'switch')
            ->datas('is_show_home_form', $this->buildRadioOptions(['1' => '显示', '2' => '不显示']));
        $formtool->field('is_show_home_list', '前台列表展示', $fieldData['is_show_home_list'])
            ->formtype('is_show_home_list', 'switch')
            ->datas('is_show_home_list', $this->buildRadioOptions(['1' => '显示', '2' => '不显示']));
        $formtool->field('is_show_home_detail', '前台详情展示', $fieldData['is_show_home_detail'])
            ->formtype('is_show_home_detail', 'switch')
            ->datas('is_show_home_detail', $this->buildRadioOptions(['1' => '显示', '2' => '不显示']));
        $formtool->field('is_show_home_list_search', '前台列表搜索', $fieldData['is_show_home_list_search'])
            ->formtype('is_show_home_list_search', 'switch')
            ->datas('is_show_home_list_search', $this->buildRadioOptions(['1' => '显示', '2' => '不显示']));

        return $formtool->formView($pageData);
    }

    private function normalizeFieldOptionInput(array $data): array {
        if (array_key_exists('datas_json', $data)) {
            $raw = trim((string) $data['datas_json']);
            if ($raw === '') {
                return [];
            }

            $decoded = json_decode($raw, true);
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
                throw new \InvalidArgumentException('选项数据必须是有效的 JSON 数组');
            }

            $normalized = [];
            foreach ($decoded as $item) {
                if (!is_array($item)) {
                    continue;
                }
                $value = trim((string) ($item['value'] ?? ''));
                $name = trim((string) ($item['name'] ?? ''));
                if ($value === '' && $name === '') {
                    continue;
                }
                $normalized[] = [
                    'value' => $value,
                    'name' => $name,
                ];
            }
            return $normalized;
        }

        return array_values($data['datas'] ?? []);
    }

    private function prettyJson($value): string
    {
        if (empty($value)) {
            return '[]';
        }

        return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    private function buildFieldWorkbenchGuide(array $pageData): string {
        $fieldListUrl = url('admin/formtools/fieldList?id=' . $pageData['id']);
        $modelConfigUrl = url('admin/formtools/modelEdit?id=' . $pageData['id']);
        $contentManageUrl = url('admin/formtools/model?moduleName=' . ($pageData['moduleName'] ?? 'Formtools') . '&action=List&model=' . $pageData['currentModel']->identification);
        $previewUrl = $pageData['currentModel']->access_identification ? url('list/' . $pageData['currentModel']->access_identification) : '';

        $previewLine = $previewUrl !== ''
            ? '<div><strong>前台预览：</strong><code>' . $previewUrl . '</code></div>'
            : '<div><strong>前台预览：</strong>当前模型还没有可用的访问标识。</div>';

        return <<<HTML
<div style="line-height: 1.9;">
    <div><strong>字段列表：</strong><code>{$fieldListUrl}</code></div>
    <div><strong>模型配置：</strong><code>{$modelConfigUrl}</code></div>
    <div><strong>内容管理：</strong><code>{$contentManageUrl}</code></div>
    {$previewLine}
</div>
HTML;
    }

    private function buildFieldTemplateGuide(): string {
        return <<<'HTML'
<div style="line-height: 1.9;">
    <div>系统模板常用字段建议：<code>name</code>、<code>title</code>、<code>content</code>、<code>pid</code>、<code>cover</code>、<code>date</code>。</div>
    <div>开启 <code>前台表单</code> 后，字段会参与前台提交页渲染。</div>
    <div><code>upload</code>、<code>uploadAjax</code>、<code>file</code> 这类附件字段，只有开启 <code>前台详情</code> 且内容值不为空时，前台详情页才会出现下载按钮。</div>
    <div>开启 <code>前台列表搜索</code> 后，字段会参与前台列表筛选。</div>
</div>
HTML;
    }

    private function buildModelIconPresetGuide(string $currentIcon): string
    {
        $items = [];
        foreach ($this->getModelIconPresets() as $icon => $label) {
            $isActive = $icon === $currentIcon;
            $style = $isActive
                ? 'border-color:#2563eb;background:#eff6ff;color:#1d4ed8;box-shadow:0 8px 18px rgba(37,99,235,.12);'
                : 'border-color:#e5e7eb;background:#fff;color:#334155;';
            $items[] = '<button type="button" class="js-model-icon-preset" data-icon-value="' . e($icon) . '" style="display:inline-flex;align-items:center;gap:8px;padding:10px 14px;border:1px solid;border-radius:999px;margin:0 10px 10px 0;font-weight:600;' . $style . '">'
                . '<i class="' . e($icon) . '"></i>'
                . '<span>' . e($label) . '</span>'
                . '<code style="padding:2px 6px;border-radius:999px;background:rgba(148,163,184,.12);font-size:12px;">' . e($icon) . '</code>'
                . '</button>';
        }

        return '<div id="js-model-icon-preset-wrap" style="line-height:1.9;">'
            . '<div style="margin-bottom:10px;color:#64748b;">推荐按模型用途选择图标，后续左侧菜单会直接复用这里的图标类名。</div>'
            . implode('', $items)
            . '</div>';
    }

    private function getModelIconPresets(): array
    {
        return [
            'icon-list-numbered' => '列表',
            'icon-file-text2' => '文档',
            'icon-book3' => '文章',
            'icon-stack-text' => '内容',
            'icon-grid2' => '分类',
            'icon-tree7' => '树形',
            'icon-clipboard2' => '表单',
            'icon-bubbles4' => '留言',
            'icon-phone2' => '联系',
            'icon-image2' => '图集',
            'icon-video-camera3' => '视频',
            'icon-download' => '下载',
        ];
    }

    private function buildFieldFormTypeHint(string $formtype): string
    {
        if ($this->isDownloadCapableFormType($formtype)) {
            return <<<'HTML'
<div id="js-field-formtype-hint" style="padding: 14px 16px; border-radius: 12px; border: 1px solid #bfdbfe; background: linear-gradient(135deg, #eff6ff 0%, #f8fbff 100%); color: #1e3a8a; line-height: 1.9;">
    <div><strong>当前属于附件字段。</strong> 前台详情页可生成下载入口。</div>
    <div>生效条件：开启 <code>前台详情</code>，并且内容里这个字段确实上传了文件。</div>
    <div>如果前台没出现下载按钮，请优先检查这两个条件。</div>
</div>
HTML;
        }

        return <<<'HTML'
<div id="js-field-formtype-hint" style="padding: 14px 16px; border-radius: 12px; border: 1px solid #e5e7eb; background: #f8fafc; color: #475569; line-height: 1.9;">
    <div><strong>当前不是附件字段。</strong> 系统不会自动生成前台下载入口。</div>
    <div>只有 <code>upload</code>、<code>uploadAjax</code>、<code>file</code> 这类字段，才会参与前台下载链路。</div>
</div>
HTML;
    }

    private function isDownloadCapableFormType(string $formtype): bool
    {
        return in_array($formtype, ['upload', 'uploadAjax', 'file'], true);
    }

    private function buildAfterFieldOptions(array $currentModelFields): array {
        $datas = [[
            'name' => 'id 之后',
            'value' => 'id',
            'children' => [],
            'child' => [],
        ]];

        foreach ($currentModelFields as $field) {
            $datas[] = [
                'name' => ($field['identification'] ?? '') . ' 之后',
                'value' => $field['identification'] ?? '',
                'children' => [],
                'child' => [],
            ];
        }

        return $datas;
    }

    private function buildForeignModelOptions($models): array {
        $datas = [[
            'name' => '非外键',
            'value' => '',
            'children' => [],
            'child' => [],
        ]];

        foreach ($models as $model) {
            $datas[] = [
                'name' => $model->name,
                'value' => $model->identification . '-' . $model->id,
                'children' => [],
                'child' => [],
            ];
        }

        return $datas;
    }

    private function buildForeignKeyOptions(array $modelsFieldsMap, string $foreign = ''): array {
        $datas = [[
            'name' => '请选择',
            'value' => '',
            'children' => [],
            'child' => [],
        ]];

        foreach (($modelsFieldsMap[$foreign] ?? []) as $field) {
            $datas[] = [
                'name' => $field['name'] ?? ($field['remark'] ?? $field['identification']),
                'value' => $field['identification'] ?? '',
                'children' => [],
                'child' => [],
            ];
        }

        return $datas;
    }

    private function buildFieldFormInlineScript(array $modelsFieldsMap, bool $syncRemark = false, string $currentForeign = '', string $currentForeignKey = ''): string {
        $modelsFieldsJson = json_encode($modelsFieldsMap, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $currentForeign = json_encode($currentForeign, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $currentForeignKey = json_encode($currentForeignKey, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $script = <<<JS
(function ($) {
    var modelsFieldsMap = {$modelsFieldsJson};
    var currentForeign = {$currentForeign};
    var currentForeignKey = {$currentForeignKey};
    var optionFormTypes = ['radio', 'checkbox', 'checkboxList', 'select', 'selectMore', 'multipleSelect', 'switch'];
    var downloadFormTypes = ['upload', 'uploadAjax', 'file'];

    function toggleFieldGroup(selector, shouldShow) {
        var \$group = $(selector).closest('.form-group, .col-md-6');
        if (!\$group.length) {
            return;
        }
        if (shouldShow) {
            \$group.show();
        } else {
            \$group.hide();
        }
    }

    function refreshOptionFieldVisibility() {
        var formType = $('select[name="formtype"]').val();
        toggleFieldGroup('textarea[name="datas_json"]', optionFormTypes.indexOf(formType) > -1);
    }

    function refreshFormTypeHint() {
        var formType = $('select[name="formtype"]').val();
        var isDownloadType = downloadFormTypes.indexOf(formType) > -1;
        var html = '';

        if (isDownloadType) {
            html = '<div style="padding: 14px 16px; border-radius: 12px; border: 1px solid #bfdbfe; background: linear-gradient(135deg, #eff6ff 0%, #f8fbff 100%); color: #1e3a8a; line-height: 1.9;">'
                + '<div><strong>当前属于附件字段。</strong> 前台详情页可生成下载入口。</div>'
                + '<div>生效条件：开启 <code>前台详情</code>，并且内容里这个字段确实上传了文件。</div>'
                + '<div>如果前台没出现下载按钮，请优先检查这两个条件。</div>'
                + '</div>';
        } else {
            html = '<div style="padding: 14px 16px; border-radius: 12px; border: 1px solid #e5e7eb; background: #f8fafc; color: #475569; line-height: 1.9;">'
                + '<div><strong>当前不是附件字段。</strong> 系统不会自动生成前台下载入口。</div>'
                + '<div>只有 <code>upload</code>、<code>uploadAjax</code>、<code>file</code> 这类字段，才会参与前台下载链路。</div>'
                + '</div>';
        }

        $('#js-field-formtype-hint').html(html);
    }

    function refreshForeignKeyOptions(selectedValue) {
        var \$select = $('select[name="foreign_key"]');
        var fields = modelsFieldsMap[selectedValue] || [];
        \$select.html('<option value="">请选择</option>');

        $.each(fields, function (_, field) {
            var label = field.name || field.remark || field.identification || '';
            var selected = currentForeignKey && field.identification === currentForeignKey ? ' selected' : '';
            \$select.append('<option value="' + field.identification + '"' + selected + '>' + label + '</option>');
        });

        var shouldShow = selectedValue !== '' && fields.length > 0;
        toggleFieldGroup('select[name="foreign_key"]', shouldShow);
        if (!shouldShow) {
            \$select.val('');
        }
    }

    $(document).on('change', 'select[name="formtype"]', function () {
        refreshOptionFieldVisibility();
        refreshFormTypeHint();
    });
    $(document).on('change', 'select[name="foreign"]', function () {
        currentForeignKey = '';
        refreshForeignKeyOptions($(this).val());
    });

    refreshOptionFieldVisibility();
    refreshFormTypeHint();
    if (currentForeign) {
        $('select[name="foreign"]').val(currentForeign).trigger('change');
        currentForeignKey = {$currentForeignKey};
        refreshForeignKeyOptions(currentForeign);
    } else {
        refreshForeignKeyOptions('');
    }
JS;

        if ($syncRemark) {
            $script .= <<<'JS'

    $(document).on('input', 'input[name="name"]', function () {
        $('input[name="remark"]').val($(this).val());
    });
JS;
        }

        $script .= <<<'JS'
})(jQuery);
JS;

        return $script;
    }

    private function guessImportedFormType(string $columnName, string $dataType): string
    {
        $columnName = strtolower($columnName);
        $dataType = strtolower($dataType);
        if (in_array($dataType, ['longtext', 'mediumtext'], true)) {
            return $columnName === 'content' ? 'editor' : 'textarea';
        }
        if ($dataType === 'text') {
            return $columnName === 'content' ? 'editor' : 'textarea';
        }
        if (str_contains($columnName, 'cover') || str_contains($columnName, 'image') || str_contains($columnName, 'img')) {
            return 'image';
        }
        if (str_contains($columnName, 'file') || str_contains($columnName, 'attachment')) {
            return 'upload';
        }
        if ($dataType === 'date') {
            return 'date';
        }
        if (in_array($dataType, ['datetime', 'timestamp'], true)) {
            return 'datetime';
        }
        if ($dataType === 'time') {
            return 'time';
        }
        if (in_array($dataType, ['tinyint', 'smallint', 'mediumint', 'int', 'bigint', 'decimal', 'double', 'float'], true)) {
            return 'number';
        }

        return 'text';
    }

    private function guessImportedFieldType(string $dataType): string
    {
        $dataType = strtolower($dataType);
        return match ($dataType) {
            'varchar', 'char' => 'string',
            'text' => 'text',
            'mediumtext' => 'mediumText',
            'longtext' => 'longText',
            'tinyint' => 'tinyInteger',
            'smallint' => 'smallInteger',
            'mediumint' => 'mediumInteger',
            'int', 'integer' => 'integer',
            'bigint' => 'bigInteger',
            'date' => 'date',
            'datetime' => 'dateTime',
            'timestamp' => 'timestamp',
            'time' => 'time',
            'decimal' => 'decimal',
            'double' => 'double',
            'float' => 'float',
            'json' => 'json',
            default => 'string',
        };
    }

    public function fieldDel() {
        $id = $this->request->input("id");
        $identification = $this->request->input("identification");
        $check = FormModel::query()->where("id", $id)->first();
        if (!$check) {
            return redirect("/admin/formtools/index")->with("pageDataMsg", "模型不存在");
        }
        $fieldsData = json_decode($check->fields, true) ?: [];
        $pageData = [];
        $currentField = null;
        foreach ($fieldsData as $k => $v) {
            if ($identification != $v['identification']) {
                $pageData[] = $v;
            } else {
                $currentField = $v;
            }
        }
        if (!$currentField) {
            return redirect("/admin/formtools/fieldList?id=" . $id)->with("pageDataMsg", "字段不存在或已删除");
        }
        $res = FormModel::query()->where("id", $id)->update([
            "fields" => json_encode($pageData, JSON_UNESCAPED_UNICODE),
            'updated_at' => getDay(),
        ]);
        if ($res) {
            //删除表字段
            $tableName = 'module_formtools_' . $check->identification;
            try {
                call_user_func([new TableStructure(), 'deleteColumn'], $tableName, $identification);
            } catch (\Exception $exception) {
                return redirect("/admin/formtools/fieldList?id=" . $id)->with(["pageDataMsg" => "删除失败：数据表字段处理异常", "pageDataStatus" => 400]);
            }
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

    private function buildModelStatistics(FormModel $model): array
    {
        $tableName = 'module_formtools_' . $model->identification;
        $tableExists = Schema::hasTable($tableName);
        $tableColumns = $tableExists ? Schema::getColumnListing($tableName) : [];
        $titleColumn = $this->resolveStatisticTitleColumn($model, $tableColumns);
        $otherConfig = json_decode($model->other_config ?: '[]', true) ?: [];

        $statistics = [
            'table_exists' => $tableExists,
            'table_columns' => $tableColumns,
            'title_column' => $titleColumn,
            'data_source' => $otherConfig['data_source'] ?? 'local',
            'cards' => [],
            'status_counts' => [],
            'top_access' => [],
            'top_good' => [],
            'top_download' => [],
            'publisher_ranking' => [],
        ];

        if (!$tableExists) {
            return $statistics;
        }

        $totalCount = DB::table($tableName)->count();
        $approvedCount = in_array('status', $tableColumns, true) ? DB::table($tableName)->where('status', 1)->count() : 0;
        $pendingCount = in_array('status', $tableColumns, true) ? DB::table($tableName)->where('status', 0)->count() : 0;
        $offlineCount = in_array('status', $tableColumns, true) ? DB::table($tableName)->where('status', 2)->count() : 0;
        $accessTotal = in_array('access_count', $tableColumns, true) ? (int) DB::table($tableName)->sum('access_count') : 0;
        $goodTotal = in_array('good_count', $tableColumns, true) ? (int) DB::table($tableName)->sum('good_count') : 0;
        $downloadTotal = in_array('download_count', $tableColumns, true) ? (int) DB::table($tableName)->sum('download_count') : 0;
        $publisherTotal = in_array('uid', $tableColumns, true) ? (int) DB::table($tableName)->where('uid', '>', 0)->distinct()->count('uid') : 0;

        $statistics['cards'] = [
            [
                'label' => '内容总数',
                'value' => $totalCount,
                'desc' => '当前模型数据表中的全部记录数',
            ],
            [
                'label' => '审核通过',
                'value' => $approvedCount,
                'desc' => '当前处于通过状态的内容数量',
            ],
            [
                'label' => '总浏览量',
                'value' => $accessTotal,
                'desc' => '来自 access_count 的累计访问次数',
            ],
            [
                'label' => '总点赞量',
                'value' => $goodTotal,
                'desc' => '来自 good_count 的累计点赞次数',
            ],
            [
                'label' => '总下载量',
                'value' => $downloadTotal,
                'desc' => '来自 download_count 的累计下载次数',
            ],
            [
                'label' => '发布者数',
                'value' => $publisherTotal,
                'desc' => '当前模型中有内容记录的 uid 数量',
            ],
        ];
        $statistics['status_counts'] = [
            '1' => $approvedCount,
            '0' => $pendingCount,
            '2' => $offlineCount,
        ];
        $statistics['top_access'] = $this->buildMetricRanking($tableName, $tableColumns, $titleColumn, 'access_count');
        $statistics['top_good'] = $this->buildMetricRanking($tableName, $tableColumns, $titleColumn, 'good_count');
        $statistics['top_download'] = $this->buildMetricRanking($tableName, $tableColumns, $titleColumn, 'download_count');
        $statistics['publisher_ranking'] = $this->buildPublisherRanking($tableName, $tableColumns);

        return $statistics;
    }

    private function resolveStatisticTitleColumn(FormModel $model, array $tableColumns): ?string
    {
        foreach (['title', 'name', 'cate_name', 'company_name', 'full_name'] as $column) {
            if (in_array($column, $tableColumns, true)) {
                return $column;
            }
        }

        $fields = json_decode($model->fields ?: '[]', true) ?: [];
        foreach ($fields as $field) {
            $identification = (string) ($field['identification'] ?? '');
            if ($identification !== '' && in_array($identification, $tableColumns, true)) {
                return $identification;
            }
        }

        return in_array('id', $tableColumns, true) ? 'id' : null;
    }

    private function buildMetricRanking(string $tableName, array $tableColumns, ?string $titleColumn, string $metricColumn, int $limit = 8): array
    {
        if (!in_array($metricColumn, $tableColumns, true)) {
            return [];
        }

        $selectColumns = ['id', $metricColumn];
        if ($titleColumn && in_array($titleColumn, $tableColumns, true) && $titleColumn !== 'id') {
            $selectColumns[] = $titleColumn;
        }
        if (in_array('status', $tableColumns, true)) {
            $selectColumns[] = 'status';
        }

        $rows = DB::table($tableName)
            ->select($selectColumns)
            ->orderByDesc($metricColumn)
            ->orderByDesc('id')
            ->limit($limit)
            ->get();

        return array_map(function ($row) use ($metricColumn, $titleColumn) {
            return [
                'id' => (int) ($row->id ?? 0),
                'title' => $this->resolveStatisticRowTitle($row, $titleColumn),
                'value' => (int) ($row->{$metricColumn} ?? 0),
                'status' => (string) ($row->status ?? ''),
            ];
        }, $rows->all());
    }

    private function buildPublisherRanking(string $tableName, array $tableColumns, int $limit = 8): array
    {
        if (!in_array('uid', $tableColumns, true)) {
            return [];
        }

        $rows = DB::table($tableName)
            ->where('uid', '>', 0)
            ->select([
                'uid',
                DB::raw('COUNT(*) as content_count'),
                DB::raw((in_array('access_count', $tableColumns, true) ? 'SUM(access_count)' : '0') . ' as access_total'),
                DB::raw((in_array('good_count', $tableColumns, true) ? 'SUM(good_count)' : '0') . ' as good_total'),
                DB::raw((in_array('download_count', $tableColumns, true) ? 'SUM(download_count)' : '0') . ' as download_total'),
            ])
            ->groupBy('uid')
            ->orderByDesc('content_count')
            ->orderBy('uid')
            ->limit($limit)
            ->get();

        return array_map(static function ($row) {
            return [
                'uid' => (int) ($row->uid ?? 0),
                'content_count' => (int) ($row->content_count ?? 0),
                'access_total' => (int) ($row->access_total ?? 0),
                'good_total' => (int) ($row->good_total ?? 0),
                'download_total' => (int) ($row->download_total ?? 0),
            ];
        }, $rows->all());
    }

    private function resolveStatisticRowTitle($row, ?string $titleColumn): string
    {
        $rowData = (array) $row;
        if ($titleColumn && !empty($rowData[$titleColumn])) {
            return (string) $rowData[$titleColumn];
        }

        foreach (['title', 'name', 'cate_name', 'company_name', 'full_name'] as $column) {
            if (!empty($rowData[$column])) {
                return (string) $rowData[$column];
            }
        }

        return 'ID #' . ($rowData['id'] ?? '');
    }
}
