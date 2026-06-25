<?php

namespace Modules\Formtools\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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
        if (empty($all['model']) || empty($all['action']) || !method_exists($this, $all['action'])) {
            return back()->with('pageDataMsg', '参数有误');
        }
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
        $pageData['title'] = $all['modeldetaill']->name ;
//        $pageData[''] = "列表";
        $pageData['model'] = $all['model'];
        $pageData['access_identification'] = $all['modeldetaill']->access_identification;
        $pageData['currentModelId'] = $all['modeldetaill']->id ?? 0;
        $pageData['action'] = "model?action=List&model=" . $all['model'];
        $tableName = 'module_formtools_' . $all['model'];
        $tableColumns = $this->getDynamicTableColumns($tableName);
        $pageData['modeldetaill'] = $this->buildListFields(json_decode($all['modeldetaill']->fields, true) ?: [], $tableColumns);
        $pageData['searchableFields'] = $this->buildSearchableFields(json_decode($all['modeldetaill']->fields, true) ?: [], $tableColumns);
        $pageData['showCreatedAt'] = in_array('created_at', $tableColumns, true);
        $pageData['tableName'] = $tableName;

        $leftJoin = [];
        $select = [];
        foreach ($pageData['modeldetaill'] as $k => $v) {
            if (!empty($v['foreign']) && in_array($v['identification'], $tableColumns, true)) {
                $temp = explode("-", $v['foreign']);
                $foreignTableName = 'module_formtools_' . ($temp[0] ?? '');
                if (
                    !empty($temp[0]) &&
                    $this->hasDynamicTableColumn($foreignTableName, 'id') &&
                    !empty($v['foreign_key']) &&
                    $this->hasDynamicTableColumn($foreignTableName, $v['foreign_key'])
                ) {
                    $leftJoin[] = $temp[0] . '-' . $v['identification'] . '-' . $v['foreign_key'];
                }
            }
        }
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
            ->select(array_merge([$tableName . '.*'], $select));

        $keyword = trim((string) $this->request->query('keyword', ''));
        $searchField = (string) $this->request->query('search_field', '');
        $statusFilter = (string) $this->request->query('status', '');
        $pageData['searchKeyword'] = $keyword;
        $pageData['searchField'] = $searchField;
        $pageData['statusFilter'] = $statusFilter;
        $pageData['hasStatusColumn'] = in_array('status', $tableColumns, true);
        $pageData['statusOptions'] = [
            '1' => '通过',
            '0' => '待审核',
            '2' => '下架',
        ];
        if ($keyword !== '') {
            $allowedSearchFields = array_column($pageData['searchableFields'], 'identification');
            if ($searchField !== '' && in_array($searchField, $allowedSearchFields, true)) {
                $pageData['datas']->where($tableName . '.' . $searchField, 'like', '%' . $keyword . '%');
            } elseif ($allowedSearchFields) {
                $pageData['datas']->where(function ($query) use ($allowedSearchFields, $tableName, $keyword) {
                    foreach ($allowedSearchFields as $index => $field) {
                        $method = $index === 0 ? 'where' : 'orWhere';
                        $query->{$method}($tableName . '.' . $field, 'like', '%' . $keyword . '%');
                    }
                });
            }
        }
        if ($pageData['hasStatusColumn'] && $statusFilter !== '' && array_key_exists($statusFilter, $pageData['statusOptions'])) {
            $pageData['datas']->where($tableName . '.status', $statusFilter);
        }

        $pageData['totalCount'] = DB::table($tableName)->count();
        $pageData['filteredCount'] = (clone $pageData['datas'])->count();
        $pageData['searchEnabledCount'] = count($pageData['searchableFields']);
        $pageData['statusCountMap'] = $pageData['hasStatusColumn']
            ? [
                '1' => DB::table($tableName)->where('status', 1)->count(),
                '0' => DB::table($tableName)->where('status', 0)->count(),
                '2' => DB::table($tableName)->where('status', 2)->count(),
            ]
            : [];
        $pageData['metricAvailableMap'] = [
            'access_count' => in_array('access_count', $tableColumns, true),
            'good_count' => in_array('good_count', $tableColumns, true),
            'download_count' => in_array('download_count', $tableColumns, true),
            'uid' => in_array('uid', $tableColumns, true),
        ];
        $pageData['metricTotalMap'] = [
            'access_count' => $pageData['metricAvailableMap']['access_count'] ? (int) DB::table($tableName)->sum('access_count') : 0,
            'good_count' => $pageData['metricAvailableMap']['good_count'] ? (int) DB::table($tableName)->sum('good_count') : 0,
            'download_count' => $pageData['metricAvailableMap']['download_count'] ? (int) DB::table($tableName)->sum('download_count') : 0,
        ];
        $pageData['publisherCount'] = $pageData['metricAvailableMap']['uid']
            ? (int) DB::table($tableName)->where('uid', '>', 0)->distinct()->count('uid')
            : 0;

        if ($all['moduleName']) $pageData['moduleName'] = $all['moduleName'];

        if($all['modeldetaill']->type=="multi" || !$all['modeldetaill']->type){
            $pageData['datas'] = $pageData['datas'] ->paginate(getLen());
            return view("formtools::admin.model.list", [
                "pageData" => $pageData
            ]);
        }
        $pageData['datas'] = $pageData['datas'] ->first();

        return view("formtools::admin.model.single", [
            "pageData" => $pageData
        ]);
    }

    public function Batch($all) {
        if (!$this->request->isMethod('post')) {
            return back()->with('pageDataMsg', '非法请求');
        }

        $pageData = getURIByRoute($this->request);
        $tableName = 'module_formtools_' . $all['model'];
        $ids = array_values(array_filter(array_map('intval', (array) $this->request->input('ids', []))));
        $batchAction = (string) $this->request->input('batch_action', '');
        $redirectUrl = $this->buildContentListUrl($pageData['moduleName'], $all['model'], [
            'page' => $this->request->input('page', ''),
            'keyword' => $this->request->input('keyword', ''),
            'search_field' => $this->request->input('search_field', ''),
            'status' => $this->request->input('status', ''),
        ]);

        if (!$ids) {
            return redirect($redirectUrl)->with('pageDataMsg', '请先选择要操作的内容');
        }

        $query = DB::table($tableName)->whereIn('id', $ids);
        if ($batchAction === 'delete') {
            $affected = $query->delete();
            return redirect($redirectUrl)
                ->with('pageDataMsg', $affected ? '批量删除成功' : '批量删除失败')
                ->with('pageDataStatus', $affected ? 200 : 400);
        }

        if (!$this->hasDynamicTableColumn($tableName, 'status')) {
            return redirect($redirectUrl)->with('pageDataMsg', '当前模型未启用审核状态字段');
        }

        $statusMap = [
            'approve' => 1,
            'reject' => 0,
            'offline' => 2,
        ];
        if (!array_key_exists($batchAction, $statusMap)) {
            return redirect($redirectUrl)->with('pageDataMsg', '请选择有效的批量操作');
        }

        $updateData = ['status' => $statusMap[$batchAction]];
        if ($this->hasDynamicTableColumn($tableName, 'updated_at')) {
            $updateData['updated_at'] = date("Y-m-d H:i:s", time());
        }
        $affected = $query->update($updateData);

        return redirect($redirectUrl)
            ->with('pageDataMsg', $affected ? '批量操作成功' : '批量操作失败')
            ->with('pageDataStatus', $affected ? 200 : 400);
    }

    public function QuickStatus($all) {
        $pageData = getURIByRoute($this->request);
        $tableName = 'module_formtools_' . $all['model'];
        $redirectUrl = $this->buildContentListUrl($pageData['moduleName'], $all['model'], [
            'page' => $all['page'] ?? '',
            'keyword' => $all['keyword'] ?? '',
            'search_field' => $all['search_field'] ?? '',
            'status' => $all['status'] ?? '',
        ]);

        $id = (int) ($all['id'] ?? 0);
        if ($id <= 0) {
            return redirect($redirectUrl)->with('pageDataMsg', '缺少有效的内容 ID');
        }

        if (!$this->hasDynamicTableColumn($tableName, 'status')) {
            return redirect($redirectUrl)->with('pageDataMsg', '当前模型未启用审核状态字段');
        }

        $statusAction = (string) ($all['status_action'] ?? '');
        $statusMap = [
            'approve' => ['value' => 1, 'label' => '审核通过'],
            'reject' => ['value' => 0, 'label' => '设为待审核'],
            'offline' => ['value' => 2, 'label' => '下架'],
        ];
        if (!array_key_exists($statusAction, $statusMap)) {
            return redirect($redirectUrl)->with('pageDataMsg', '请选择有效的审核操作');
        }

        $current = DB::table($tableName)->where('id', $id)->first();
        if (!$current) {
            return redirect($redirectUrl)->with('pageDataMsg', '数据不存在或已删除');
        }

        $targetStatus = $statusMap[$statusAction]['value'];
        if ((string) ($current->status ?? '') === (string) $targetStatus) {
            return redirect($redirectUrl)
                ->with('pageDataMsg', '当前内容已是“' . $statusMap[$statusAction]['label'] . '”状态')
                ->with('pageDataStatus', 200);
        }

        $updateData = ['status' => $targetStatus];
        if ($this->hasDynamicTableColumn($tableName, 'updated_at')) {
            $updateData['updated_at'] = date("Y-m-d H:i:s", time());
        }
        $affected = DB::table($tableName)->where('id', $id)->update($updateData);

        return redirect($redirectUrl)
            ->with('pageDataMsg', $affected ? ($statusMap[$statusAction]['label'] . '成功') : ($statusMap[$statusAction]['label'] . '失败'))
            ->with('pageDataStatus', $affected ? 200 : 400);
    }

    public function Add($all) {
        return $this->renderContentForm($all);
    }

    public function Edit($all) {
        $tableName = 'module_formtools_' . $all['model'];
        $data = DB::table($tableName)->where("id", $all['id'])->first();
        if (!$data) {
            return back()->with('pageDataMsg', '数据不存在');
        }
        return $this->renderContentForm($all, $data);
    }

    public function Submit($all) {

        if (!$this->request->isMethod("post")) {
            return back()->with("pageDataMsg", "非法请求");
        }
        $pageData = getURIByRoute($this->request);
        $redirectUrl = $this->buildContentListUrl($pageData['moduleName'], $all['model'], [
            'page' => $all['page'] ?? '',
            'keyword' => $all['keyword'] ?? '',
            'search_field' => $all['search_field'] ?? '',
            'status' => $all['status'] ?? '',
        ]);
        $tableName = 'module_formtools_' . $all['model'];
        $modeldetaill = json_decode($all['modeldetaill']->fields, true);
        $tableColumns = $this->getDynamicTableColumns($tableName);
        $uploadKey = [];
        $currentData = null;
        if (!empty($all['id'])) {
            $currentData = DB::table($tableName)->where("id", $all['id'])->first();
        }
        foreach ($modeldetaill as $f) {
            if (!in_array($f['identification'], $tableColumns, true)) {
                continue;
            }
            //判断字段规则
            $fieldValue = $all[$f['identification']] ?? '';
            $insterdata[$f['identification']] = is_array($fieldValue) ? implode(',', $fieldValue) : $fieldValue;
            if (in_array($f['formtype'], ['upload', 'image', 'uploadAjax', 'imageAjax'], true)) {
                $uploadKey[] = $f['identification'];
            }
        }
        if ($uploadKey) {
            foreach ($uploadKey as $key) {
                unset($insterdata[$key]);
                if (!empty($_FILES[$key]['size']) && $_FILES[$key]['size'] > 0) {
                    try {
                        $insterdata[$key] = UploadFile(\Request(), $key, "file/" . date("Y/m/d/") . uniqid(), ALLOWEXT, __E("upload_driver"));
                    } catch (\Exception $exception) {
                        return back()->with("pageDataMsg", $exception->getMessage());
                    }
                } elseif ($currentData && isset($currentData->{$key})) {
                    $insterdata[$key] = $currentData->{$key};
                }
            }
        }


        $this->assignColumnValue($insterdata, $tableName, 'remark', $all['remark'] ?? '');
        $this->assignColumnValue($insterdata, $tableName, 'seo_title', $all['seo_title'] ?? '');
        $this->assignColumnValue($insterdata, $tableName, 'seo_keywords', $all['seo_keywords'] ?? '');
        $this->assignColumnValue($insterdata, $tableName, 'seo_description', $all['seo_description'] ?? '');
        $this->assignColumnValue($insterdata, $tableName, 'updated_at', date("Y-m-d H:i:s", time()));
        if ($all['id'] > 0) {
            $this->assignColumnValue($insterdata, $tableName, 'status', $all['status'] ?? 1);
            $res = DB::table($tableName)->where("id", $all['id'])->update($insterdata);
            if ($res) {
                return redirect($redirectUrl)
                    ->with("pageDataMsg", "修改成功")
                    ->with('pageDataStatus', 200);
            }
            return back()->with("pageDataMsg", "修改失败");
        }
        $this->assignColumnValue($insterdata, $tableName, 'created_at', date("Y-m-d H:i:s", time()));
        $this->assignColumnValue($insterdata, $tableName, 'uid', session()->get('admin_info')->uid ?? 0);
        $this->assignColumnValue($insterdata, $tableName, 'status', 1);
        $res = DB::table($tableName)->insertGetId($insterdata);
        if ($res) {
            return redirect($redirectUrl)
                ->with("pageDataMsg", "添加成功")
                ->with('pageDataStatus', 200);
        }
        return back()->with("pageDataMsg", "添加失败");
    }

    public function Del($all) {
        $pageData = getURIByRoute($this->request);
        $tableName = 'module_formtools_' . $all['model'];
        $redirectUrl = $this->buildContentListUrl($pageData['moduleName'], $all['model'], [
            'page' => $all['page'] ?? '',
            'keyword' => $all['keyword'] ?? '',
            'search_field' => $all['search_field'] ?? '',
            'status' => $all['status'] ?? '',
        ]);
        $current = DB::table($tableName)->where("id", $all['id'])->first();
        if (!$current) {
            return redirect($redirectUrl)
                ->with("pageDataMsg", "数据不存在或已删除");
        }
        try {
            $res = DB::table($tableName)->where("id", $all['id'])->delete();
        } catch (\Exception $exception) {
            return back()->with("pageDataMsg", "删除失败");
        }
        if ($res) {
            return redirect($redirectUrl)
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

    private function renderContentForm(array $all, $data = null) {
        $pageData = getURIByRoute($this->request);
        $isEdit = $data !== null;
        $pageData['title'] = $all['modeldetaill']->name;
        $pageData['subtitle'] = $isEdit ? "编辑数据" : "添加数据";
        $pageData['model'] = $all['model'];
        $pageData['access_identification'] = $all['modeldetaill']->access_identification;
        $pageData['action'] = "model?action=List&model=" . $all['model'];
        $pageData['page'] = $all['page'] ?? '';
        if (!empty($all['moduleName'])) {
            $pageData['moduleName'] = $all['moduleName'];
        }

        $tableName = 'module_formtools_' . $all['model'];
        $tableColumns = $this->getDynamicTableColumns($tableName);
        $fields = $this->buildContentFields(json_decode($all['modeldetaill']->fields, true) ?: [], $tableName, $tableColumns, $data);
        $formtool = FormTool::create();
        $query = [
            'action' => 'Submit',
            'moduleName' => $pageData['moduleName'],
            'model' => $all['model'],
        ];
        if (!empty($pageData['page'])) {
            $query['page'] = $pageData['page'];
        }
        if (!empty($all['keyword'])) {
            $query['keyword'] = $all['keyword'];
        }
        if (!empty($all['search_field'])) {
            $query['search_field'] = $all['search_field'];
        }
        if (isset($all['status']) && $all['status'] !== '') {
            $query['status'] = $all['status'];
        }
        $formtool->formAction(url("admin/" . $pageData['moduleName'] . "/model?" . http_build_query($query)));
        $formtool->csrf_field();
        $formtool->formid('formtoolsContentForm');
        $formtool->tips('在这里填写内容后，就可以继续查看前台展示效果；如果设置了单条内容的 SEO，会优先用于详情页。');

        if ($isEdit) {
            $formtool->field('id', '', $all['id'], 'hidden');
        }

        $formtool->section('content_guide', '内容工作台', '在这里填写或修改内容。');
        $formtool->field('content_frontend_guide', '前台联调', $this->buildContentPreviewGuide($pageData, $data))
            ->formtype('content_frontend_guide', 'word')
            ->notes('content_frontend_guide', '保存后可回到列表继续查看，也可以直接打开前台页面确认效果。');

        $formtool->section('content_fields', '内容字段');
        foreach ($fields as $field) {
            $formtool->field($field['identification'], $field['name'], $field['value'], $field['formtype'], $field['datas']);
            $formtool->required($field['identification'], $field['required'] ?? '');
            $formtool->placeholder($field['identification'], $field['placeholder'] ?? '');
            $formtool->notes($field['identification'], $field['notes'] ?? '');
            $formtool->disabled($field['identification'], $field['disabled'] ?? '');
            $formtool->showtype($field['identification'], $field['showtype'] ?? 'row');
            $formtool->rows($field['identification'], $field['rows'] ?? 5);
            $formtool->template($field['identification'], $field['template'] ?? '');
            $formtool->cssClass($field['identification'], $field['cssClass'] ?? '');
        }

        $metaFields = $this->buildContentMetaFields($tableName, $data);
        $pageData['contentWorkbench'] = $this->buildContentWorkbench($pageData, $fields, $metaFields, $data);
        if ($metaFields) {
            $formtool->section('content_review', '审核与 SEO', '这里可以设置审核状态、备注和详情页 SEO。');
            foreach ($metaFields as $metaField) {
                $formtool->field($metaField['identification'], $metaField['name'], $metaField['value'], $metaField['formtype'], $metaField['datas'] ?? []);
                $formtool->placeholder($metaField['identification'], $metaField['placeholder'] ?? '');
                $formtool->notes($metaField['identification'], $metaField['notes'] ?? '');
                $formtool->rows($metaField['identification'], $metaField['rows'] ?? 5);
            }
        }

        return $formtool->formView($pageData);
    }

    private function buildContentFields(array $fields, string $tableName, array $tableColumns, $data = null): array {
        $rowData = $data ? get_object_vars($data) : [];
        $metaColumns = ['status', 'remark', 'seo_title', 'seo_keywords', 'seo_description'];
        $fields = array_values(array_filter($fields, function ($field) use ($tableColumns) {
            return in_array($field['identification'] ?? '', $tableColumns, true);
        }));
        $fields = array_values(array_filter($fields, function ($field) use ($metaColumns) {
            return !in_array($field['identification'] ?? '', $metaColumns, true);
        }));
        $singleContentField = count($fields) === 1
            && (($fields[0]['identification'] ?? '') === 'content')
            && empty($fields[0]['formtype']);
        foreach ($fields as $key => $field) {
            if (!empty($field['datas']) && !is_array($field['datas'])) {
                $field['datas'] = json_decode($field['datas'], true) ?: [];
            }
            if ($singleContentField && ($field['identification'] ?? '') === 'content' && empty($field['formtype'])) {
                $field['formtype'] = 'editor';
            }
            $field['formtype'] = !empty($field['formtype']) ? (string) $field['formtype'] : 'text';
            if (!empty($field['foreign'])) {
                $temp = explode("-", $field['foreign']);
                $foreignTableName = 'module_formtools_' . ($temp[0] ?? '');
                if (
                    !empty($temp[0]) &&
                    !empty($field['foreign_key']) &&
                    $this->hasDynamicTableColumn($foreignTableName, 'id') &&
                    $this->hasDynamicTableColumn($foreignTableName, $field['foreign_key'])
                ) {
                    $datas = Common::query()
                        ->from($foreignTableName)
                        ->select(['id as value', $field['foreign_key'] . ' as name'])
                        ->get()
                        ->toArray();
                    $field['datas'] = json_decode(json_encode($datas), true) ?: [];
                }
            }
            $field['value'] = $rowData[$field['identification']] ?? ($field['value'] ?? '');
            $fields[$key] = $field;
        }

        return $fields;
    }

    private function buildListFields(array $fields, array $tableColumns): array
    {
        $listFields = [];
        foreach ($fields as $field) {
            if (($field['is_show_list'] ?? '1') != 1) {
                continue;
            }
            if (!in_array($field['identification'] ?? '', $tableColumns, true)) {
                continue;
            }
            if (!empty($field['datas']) && !is_array($field['datas'])) {
                $datas = json_decode($field['datas'], true) ?: [];
                $field['datas'] = array_column($datas, 'name', 'value');
            }
            $listFields[] = $field;
        }

        return $listFields;
    }

    private function buildSearchableFields(array $fields, array $tableColumns): array
    {
        $searchableFields = [];
        foreach ($fields as $field) {
            if (($field['is_show_admin_list_search'] ?? '2') != 1) {
                continue;
            }
            if (!in_array($field['identification'] ?? '', $tableColumns, true)) {
                continue;
            }
            $searchableFields[] = [
                'identification' => $field['identification'],
                'name' => $field['name'] ?: ($field['remark'] ?: $field['identification']),
            ];
        }

        return $searchableFields;
    }

    private function buildContentMetaFields(string $tableName, $data = null): array
    {
        $metaFields = [];
        if ($this->hasDynamicTableColumn($tableName, 'status')) {
            $metaFields[] = [
                'identification' => 'status',
                'name' => '审核状态',
                'value' => (string) ($data->status ?? '1'),
                'formtype' => 'radio',
                'datas' => [
                    ['name' => '通过', 'value' => '1'],
                    ['name' => '不通过', 'value' => '0'],
                    ['name' => '下架', 'value' => '2'],
                ],
                'notes' => '控制当前内容在后台和前台中的可用状态。',
            ];
        }
        if ($this->hasDynamicTableColumn($tableName, 'remark')) {
            $metaFields[] = [
                'identification' => 'remark',
                'name' => '审核备注',
                'value' => (string) ($data->remark ?? ''),
                'formtype' => 'text',
                'placeholder' => '请输入审核备注',
            ];
        }
        if ($this->hasDynamicTableColumn($tableName, 'seo_title')) {
            $metaFields[] = [
                'identification' => 'seo_title',
                'name' => 'SEO 标题',
                'value' => (string) ($data->seo_title ?? ''),
                'formtype' => 'text',
                'placeholder' => '请输入 SEO 标题',
                'notes' => '不填写时将优先回退到 name 或 title 字段。',
            ];
        }
        if ($this->hasDynamicTableColumn($tableName, 'seo_keywords')) {
            $metaFields[] = [
                'identification' => 'seo_keywords',
                'name' => 'SEO 关键词',
                'value' => (string) ($data->seo_keywords ?? ''),
                'formtype' => 'text',
                'placeholder' => '请输入 SEO 关键词',
                'notes' => '不填写时将自动回退到模型级或通配 SEO。',
            ];
        }
        if ($this->hasDynamicTableColumn($tableName, 'seo_description')) {
            $metaFields[] = [
                'identification' => 'seo_description',
                'name' => 'SEO 描述',
                'value' => (string) ($data->seo_description ?? ''),
                'formtype' => 'textarea',
                'rows' => 4,
                'placeholder' => '请输入 SEO 描述',
                'notes' => '不填写时将自动回退到模型级或通配 SEO。',
            ];
        }

        return $metaFields;
    }

    private function assignColumnValue(array &$data, string $tableName, string $column, $value): void
    {
        if ($this->hasDynamicTableColumn($tableName, $column)) {
            $data[$column] = $value;
        }
    }

    private function getDynamicTableColumns(string $tableName): array
    {
        if (!Schema::hasTable($tableName)) {
            return [];
        }

        return Schema::getColumnListing($tableName);
    }

    private function hasDynamicTableColumn(string $tableName, string $column): bool
    {
        return Schema::hasTable($tableName) && Schema::hasColumn($tableName, $column);
    }

    private function buildContentPreviewGuide(array $pageData, $data = null): string {
        if (empty($pageData['access_identification'])) {
            return '<div>当前模型还没有配置可用的前台访问标识。</div>';
        }

        $listUrl = url('list/' . $pageData['access_identification']);
        $detailText = $data && !empty($data->id)
            ? '<code>' . url('detail/' . $pageData['access_identification'] . '/' . $data->id) . '</code>'
            : '<code>' . url('detail/' . $pageData['access_identification'] . '/{id}') . '</code>';

        return <<<HTML
<div style="line-height: 1.9;">
    <div><strong>前台列表：</strong><code>{$listUrl}</code></div>
    <div><strong>前台详情：</strong>{$detailText}</div>
</div>
HTML;
    }

    private function buildContentWorkbench(array $pageData, array $fields, array $metaFields, $data = null): array
    {
        $isEdit = $data !== null;
        $recordTitle = $this->resolveContentTitle($data);
        $statusMap = [
            '1' => '通过',
            '0' => '不通过',
            '2' => '下架',
        ];
        $statusValue = (string) ($data->status ?? '1');
        $listUrl = url("admin/" . $pageData['moduleName'] . "/model?moduleName=" . $pageData['moduleName'] . "&action=List&model=" . $pageData['model']);
        if (!empty($pageData['page'])) {
            $listUrl .= '&page=' . $pageData['page'];
        }

        $actions = [
            [
                'label' => '返回列表',
                'url' => $listUrl,
                'class' => 'btn btn-default btn-sm',
                'target' => '',
                'confirm' => '',
            ],
        ];
        if (!empty($pageData['access_identification'])) {
            $actions[] = [
                'label' => '前台列表',
                'url' => url('list/' . $pageData['access_identification']),
                'class' => 'btn btn-info btn-sm',
                'target' => '_blank',
                'confirm' => '',
            ];
            if ($isEdit && !empty($data->id)) {
                $actions[] = [
                    'label' => '前台详情',
                    'url' => url('detail/' . $pageData['access_identification'] . '/' . $data->id),
                    'class' => 'btn btn-primary btn-sm',
                    'target' => '_blank',
                    'confirm' => '',
                ];
            }
        }
        if ($isEdit && !empty($data->id)) {
            $actions[] = [
                'label' => '删除当前内容',
                'url' => url("admin/" . $pageData['moduleName'] . "/model?action=Del&moduleName=" . $pageData['moduleName'] . "&model=" . $pageData['model'] . "&id=" . $data->id . (!empty($pageData['page']) ? "&page=" . $pageData['page'] : '')),
                'class' => 'btn btn-danger btn-sm',
                'target' => '',
                'confirm' => '确定要删除内容“' . $recordTitle . '”吗？这会直接删除当前记录。',
            ];
        }

        return [
            'isEdit' => $isEdit,
            'title' => $recordTitle,
            'tableName' => 'module_formtools_' . $pageData['model'],
            'fieldCount' => count($fields),
            'metaFieldCount' => count($metaFields),
            'seoFieldCount' => count(array_filter($metaFields, static fn($field) => str_starts_with((string) ($field['identification'] ?? ''), 'seo_'))),
            'requiredCount' => count(array_filter($fields, static fn($field) => ($field['required'] ?? '') === 'required')),
            'statusLabel' => $statusMap[$statusValue] ?? '未设置',
            'actions' => $actions,
        ];
    }

    private function resolveContentTitle($data): string
    {
        if (!$data) {
            return '新内容';
        }
        $row = get_object_vars($data);
        foreach (['title', 'name', 'cate_name', 'company_name', 'full_name'] as $field) {
            if (!empty($row[$field])) {
                return (string) $row[$field];
            }
        }

        return 'ID #' . ($row['id'] ?? '');
    }

    private function buildContentListUrl(string $moduleName, string $model, array $params = []): string
    {
        $query = array_filter(array_merge([
            'moduleName' => $moduleName,
            'action' => 'List',
            'model' => $model,
        ], $params), static fn ($value) => $value !== null && $value !== '');

        return url('admin/' . strtolower($moduleName) . '/model?' . http_build_query($query));
    }


}
