<?php

namespace Modules\Main\Http\Controllers\Home;

use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Modules\Formtools\Models\FormModel;
use Modules\Formtools\Support\FormTemplateResolver;
use Modules\Main\Models\Common;
use Modules\ModulesController;
use Modules\System\Http\Controllers\Common\SessionKey;
use Modules\System\Http\Requests\verifyFunction;

class ModelController extends ModulesController {
    use verifyFunction;
    private $login_unique;


    public function __construct(Request $request) {
        parent::__construct($request);
        $this->login_unique = SessionKey::HomeInfo;
    }

    public function list($access) {
        $uri = getURIByRoute($this->request)['uri'];

        $modelData = FormModel::query()->where('access_identification', $access)->first();
        if (!$modelData) return abort(404);

        $model = $modelData->toArray();
        $model['fields'] = $model['fields'] ? json_decode($model['fields'], true) : [];
        $model['other_config'] = json_decode($model['other_config'], true);
        $model['home_config'] = json_decode($model['home_config'], true);
        $model['home_seo_config'] = json_decode($model['home_seo_config'], true);
        $model['home_seo_detail_config'] = json_decode($model['home_seo_detail_config'], true);
        $model = FormTemplateResolver::normalizeModelData($model);
        $model = $this->normalizeFrontendFieldSchema($model);



        $searchKeyword = trim((string) $this->request->query('keyword', ''));
        $searchFields = $this->resolveFrontendSearchFields($model, $model['other_config']['data_source'] === 'api' ? null : "module_formtools_{$model['identification']}");

        if ($model['other_config']['data_source'] == "api") {
            $apiMappings = $this->parseApiFieldMappings($model['other_config']['data_source_field_mapping'] ?? '');
            $apiResponse = $this->requestApiPayload($model['other_config']['data_source_api_url'] ?? '');
            $list = $this->mapApiList($this->extractApiListItems($apiResponse), $apiMappings);
            $list = $this->filterApiListByActiveStatus($list);
            $pid = $this->request->query('pid');
            if ($pid !== null && $pid !== '') {
                $list = array_values(array_filter($list, fn ($item) => (string) ($item['pid'] ?? '') === (string) $pid));
            }
            $list = $this->filterApiListByKeyword($list, $searchKeyword, $searchFields);
            if (($model['type'] ?? 'multi') !== 'multi') {
                $list = $list[0] ?? [];
            }
        } else {
            $tableName = "module_formtools_{$model['identification']}";
            $list = $this->applyActiveStatusScope(Common::query()->from($tableName), $tableName)->latest('id');
            $pid = $this->request->query('pid');
            if ($pid !== null && $pid !== '' && $this->hasDynamicTableColumn($tableName, 'pid')) {
                $list = $list->where('pid', $pid);
            }
            $this->applyFrontendKeywordSearch($list, $tableName, $searchKeyword, $searchFields);
            if($model['type']=="multi"){
                if ($model['home_config']['page_num'] > 0) {
                    $list = $list->paginate($model['home_config']['page_num']);
                } else {
                    $list = $list->get()->toArray();
                }
            }else{
                $list = $list->first();
            }

        }

        $param['model'] = $access;
        if (substr($uri, 0, 4) == 'api/') {
            return json_encode([
                'status' => 200,
                'msg' => 'success',
                'data' => [
                    'list' => $list,
                    'model' => $model,
                    'param' => $param,
                ],
            ], JSON_UNESCAPED_UNICODE);
        }
        $listTemplate = $this->resolveSingleModelTemplate(
            $model,
            $model['list_template'] ?: FormTemplateResolver::DEFAULT_LIST_TEMPLATE
        );
        return ModelView($listTemplate, [
            'data' => $list,
            'model' => $model,
            'param' => $param,
            'data_source' => $model['other_config']['data_source'],
            'listContext' => $this->buildListContext($model, $list, $searchKeyword, $searchFields),
        ], FormTemplateResolver::DEFAULT_LIST_TEMPLATE);
    }

    public function detail($access, $id) {
        $uri = getURIByRoute($this->request)['uri'];

        $modelData = FormModel::query()->where('access_identification', $access)->first();
        if (!$modelData) return abort(404);
        $pid = 'pid';//默认上级id
        //通过自定义的字段查询某个外键
        foreach (json_decode($modelData['fields'], true) as $fields) {
            if ($fields['foreign_key']) {
                $pid = $fields['identification'];
                break;
            }
        }
        $model = $modelData->toArray();
        $model['fields'] = $model['fields'] ? json_decode($model['fields'], true) : [];
        $model['other_config'] = json_decode($model['other_config'], true);
        $model['home_config'] = json_decode($model['home_config'], true);
        $model['home_seo_config'] = json_decode($model['home_seo_config'], true);
        $model['home_seo_detail_config'] = json_decode($model['home_seo_detail_config'], true);
        $model = FormTemplateResolver::normalizeModelData($model);
        $model = $this->normalizeFrontendFieldSchema($model);

        if ($model['other_config']['data_source'] == "api") {
            $apiMappings = $this->parseApiFieldMappings($model['other_config']['data_source_field_mapping'] ?? '');
            $apiResponse = $this->requestApiPayload($this->buildApiDetailUrl($model['other_config']['data_source_api_url_detail'] ?? '', (string) $id));
            $data = $this->mapApiItem($this->extractApiDetailItem($apiResponse), $apiMappings);
            if (!$data || !$this->isFrontendAccessibleApiItem($data)) {
                abort(404);
            }
            $list = $this->filterApiListByActiveStatus($this->mapApiList($this->extractApiRelatedItems($apiResponse), $apiMappings));
            $data['prev_id'] = null;
            $data['next_id'] = null;
            $data['last_id'] = null;
            $data['prev_item'] = null;
            $data['next_item'] = null;

        } else {
            $tableName = "module_formtools_{$model['identification']}";
            $data = $this->applyActiveStatusScope(Common::query()->from($tableName), $tableName)
                ->where('id', $id)
                ->first();
            if (!$data) return abort(404);
            $relationKey = $this->hasDynamicTableColumn($tableName, $pid) ? $pid : null;
            //访问数加1
            if ($this->hasDynamicTableColumn($tableName, 'access_count')) {
                Common::query()->from($tableName)->where('id', $id)->increment("access_count");
                $data->access_count = (int) ($data->access_count ?? 0) + 1;
            }
            $listQuery = $this->applyActiveStatusScope(Common::query()->from($tableName), $tableName)
                ->whereNot('id', $id)
                ->latest('id');
            if ($relationKey !== null) {
                $listQuery->where($relationKey, $data[$relationKey]);
            }
            $list = $listQuery->limit(10)->get()->toArray();
            $selectColumns = array_values(array_filter([
                'id',
                $this->hasDynamicTableColumn($tableName, 'title') ? 'title' : null,
                $this->hasDynamicTableColumn($tableName, 'name') ? 'name' : null,
                $this->hasDynamicTableColumn($tableName, 'created_at') ? 'created_at' : null,
            ]));

            $scopeValue = $relationKey !== null ? ($data[$relationKey] ?? null) : null;
            $prevItem = $this->resolveAdjacentDetailItem($tableName, $id, 'previous', $selectColumns, $relationKey, $scopeValue);
            $nextItem = $this->resolveAdjacentDetailItem($tableName, $id, 'next', $selectColumns, $relationKey, $scopeValue);

            $data['prev_item'] = $prevItem ? frontendRecordData($prevItem) : null;
            $data['next_item'] = $nextItem ? frontendRecordData($nextItem) : null;
            $data['prev_id'] = $prevItem->id ?? null;
            $data['next_id'] = $nextItem->id ?? null;
            $data['last_id'] = $nextItem->id ?? null;
        }


        $param['model'] = $access;
        $param['id'] = $id;


        if (substr($uri, 0, 4) == 'api/') {
            return json_encode([
                'status' => 200,
                'msg' => 'success',
                'data' => [
                    'data' => $data,
                    'list' => $list,
                    'model' => $model,
                    'param' => $param,
                ],
            ], JSON_UNESCAPED_UNICODE);
        }

        $detailTemplate = $this->resolveSingleModelTemplate(
            $model,
            $model['detail_template'] ?: FormTemplateResolver::DEFAULT_DETAIL_TEMPLATE
        );
        $detailRecord = frontendRecordData($data);
        return ModelView($detailTemplate, [
            'data' => $data,
            'detailRecord' => $detailRecord,
            'detailInteractions' => $this->buildDetailInteractions($model, $access, $detailRecord),
            'list' => $list,
            'model' => $model,
            'param' => $param,
        ], FormTemplateResolver::DEFAULT_DETAIL_TEMPLATE);
    }

    public function good($access, $id)
    {
        $modelData = FormModel::query()->where('access_identification', $access)->first();
        if (!$modelData) {
            return $this->detailActionResponse(false, '模型不存在', [], 404);
        }

        $model = $modelData->toArray();
        $model['other_config'] = json_decode($model['other_config'], true);
        $model = FormTemplateResolver::normalizeModelData($model);
        if (($model['other_config']['data_source'] ?? 'local') !== 'local') {
            return $this->detailActionResponse(false, '当前模型暂不支持点赞统计', [], 400);
        }

        $tableName = 'module_formtools_' . $model['identification'];
        if (!$this->hasDynamicTableColumn($tableName, 'good_count')) {
            return $this->detailActionResponse(false, '当前模型未启用点赞统计字段', [], 400);
        }

        $record = $this->applyActiveStatusScope(Common::query()->from($tableName), $tableName)
            ->where('id', (int) $id)
            ->first();
        if (!$record) {
            return $this->detailActionResponse(false, '内容不存在或未通过审核', [], 404);
        }

        Common::query()->from($tableName)->where('id', (int) $id)->increment('good_count');
        $goodCount = (int) ($record->good_count ?? 0) + 1;

        return $this->detailActionResponse(true, '点赞成功', [
            'good_count' => $goodCount,
        ]);
    }

    public function download($access, $id, $field, $index = 0)
    {
        $modelData = FormModel::query()->where('access_identification', $access)->first();
        if (!$modelData) {
            abort(404);
        }

        $model = $modelData->toArray();
        $model['fields'] = $model['fields'] ? json_decode($model['fields'], true) : [];
        $model['other_config'] = json_decode($model['other_config'], true);
        $model = FormTemplateResolver::normalizeModelData($model);
        $model = $this->normalizeFrontendFieldSchema($model);
        if (($model['other_config']['data_source'] ?? 'local') !== 'local') {
            abort(404);
        }

        $tableName = 'module_formtools_' . $model['identification'];
        $record = $this->applyActiveStatusScope(Common::query()->from($tableName), $tableName)
            ->where('id', (int) $id)
            ->first();
        if (!$record) {
            abort(404);
        }

        $detailRecord = frontendRecordData($record);
        $downloads = $this->resolveDownloadableFields($model, $detailRecord, $access);
        $fieldKey = (string) $field;
        $fieldDownloads = $downloads[$fieldKey]['items'] ?? [];
        $fileIndex = max(0, (int) $index);
        $downloadItem = $fieldDownloads[$fileIndex] ?? null;
        if (!$downloadItem || empty($downloadItem['path'])) {
            abort(404);
        }

        if ($this->hasDynamicTableColumn($tableName, 'download_count')) {
            Common::query()->from($tableName)->where('id', (int) $id)->increment('download_count');
        }

        return redirect($downloadItem['url']);
    }

    public function handle($access) {
        $modelData = FormModel::query()->where('access_identification', $access)->first();
        if (!$modelData) {
            return $this->handleFrontendSubmitResponse(false, '模型不存在', [], 404);
        }

        $model = $modelData->toArray();
        $model['fields'] = $model['fields'] ? json_decode($model['fields'], true) : [];
        $model['other_config'] = json_decode($model['other_config'], true);
        $model['home_config'] = json_decode($model['home_config'], true);
        $model['home_seo_config'] = json_decode($model['home_seo_config'], true);
        $model['home_seo_detail_config'] = json_decode($model['home_seo_detail_config'], true);
        $model = FormTemplateResolver::normalizeModelData($model);
        $model = $this->normalizeFrontendFieldSchema($model);

        if ($this->request->isMethod('post')) {
            return $this->submitFrontendHandle($access, $model);
        }

        return ModelView($this->resolveHandleTemplate($model), [
            'data' => $this->loadHandlePageData($model),
            'model' => $model,
            'param' => ['model' => $access],
        ], FormTemplateResolver::DEFAULT_HANDLE_TEMPLATE);
    }

    private function normalizeFrontendFieldSchema(array $model): array {
        $fields = $model['fields'] ?? [];
        foreach ($fields as $index => $field) {
            $fields[$index]['is_show_home_form'] = (string) ($field['is_show_home_form'] ?? '1');
            $fields[$index]['is_show_home_list'] = (string) ($field['is_show_home_list'] ?? '1');
            $fields[$index]['is_show_home_detail'] = (string) ($field['is_show_home_detail'] ?? '1');
            $fields[$index]['is_show_home_list_search'] = (string) ($field['is_show_home_list_search'] ?? '2');
        }

        $model['fields'] = $fields;
        $model['frontend_schema'] = [
            'form' => array_values(array_filter($fields, function ($field) {
                return (string) ($field['is_show_home_form'] ?? '1') === '1';
            })),
            'list' => array_values(array_filter($fields, function ($field) {
                return (string) ($field['is_show_home_list'] ?? '1') === '1';
            })),
            'detail' => array_values(array_filter($fields, function ($field) {
                return (string) ($field['is_show_home_detail'] ?? '1') === '1';
            })),
            'search' => array_values(array_filter($fields, function ($field) {
                return (string) ($field['is_show_home_list_search'] ?? '2') === '1';
            })),
        ];

        return $model;
    }

    private function submitFrontendHandle(string $access, array $model)
    {
        $tableName = 'module_formtools_' . $model['identification'];
        $insertData = [];
        $uploadKeys = [];

        foreach ($model['frontend_schema']['form'] ?? [] as $field) {
            $key = $field['identification'];
            $value = $this->request->input($key, '');
            $insertData[$key] = is_array($value) ? implode(',', $value) : $value;
            if (in_array($field['formtype'] ?? '', ['upload', 'image'], true)) {
                $uploadKeys[] = $key;
            }
        }

        foreach ($uploadKeys as $key) {
            unset($insertData[$key]);
            if (!empty($_FILES[$key]['size']) && $_FILES[$key]['size'] > 0) {
                try {
                    $insertData[$key] = UploadFile(\Request(), $key, "file/" . date("Y/m/d/") . uniqid(), ALLOWEXT, __E("upload_driver"));
                } catch (\Exception $exception) {
                    return $this->handleFrontendSubmitResponse(false, $exception->getMessage());
                }
            }
        }

        if ($this->hasDynamicTableColumn($tableName, 'created_at')) {
            $insertData['created_at'] = getDay();
        }
        if ($this->hasDynamicTableColumn($tableName, 'updated_at')) {
            $insertData['updated_at'] = getDay();
        }
        if ($this->hasDynamicTableColumn($tableName, 'uid')) {
            $insertData['uid'] = (int) (session()->get($this->login_unique)['uid'] ?? 0);
        }

        if ($this->hasDynamicTableColumn($tableName, 'status')) {
            $insertData['status'] = 0;
        }

        $id = Common::query()->from($tableName)->insertGetId($insertData);
        if (!$id) {
            return $this->handleFrontendSubmitResponse(false, '提交失败，请稍后重试');
        }

        return $this->handleFrontendSubmitResponse(true, '提交成功', [
            'id' => $id,
            'jumpUrl' => $this->resolveFrontendJumpUrl($access, $model, $id),
        ]);
    }

    private function handleFrontendSubmitResponse(bool $success, string $message, array $data = [], int $status = 200)
    {
        if ($this->request->expectsJson() || $this->request->ajax() || str_starts_with(getURIByRoute($this->request)['uri'] ?? '', 'api/')) {
            return response()->json([
                'status' => $success ? 200 : $status,
                'msg' => $message,
                'data' => $data,
            ], $success ? 200 : $status);
        }

        if ($success) {
            return back()->with([
                'pageDataMsg' => $message,
                'pageDataStatus' => 200,
            ]);
        }

        return back()->with('pageDataMsg', $message);
    }

    private function resolveFrontendSearchFields(array $model, ?string $tableName = null): array
    {
        $fields = collect($model['frontend_schema']['search'] ?? [])
            ->map(function ($field) {
                return [
                    'identification' => trim((string) ($field['identification'] ?? '')),
                    'name' => trim((string) ($field['name'] ?? '')),
                ];
            })
            ->filter(function ($field) {
                return $field['identification'] !== '';
            })
            ->values()
            ->all();

        if (!$fields) {
            $fallbackFields = ['title', 'name', 'content'];
            foreach ($fallbackFields as $fallbackField) {
                if ($tableName !== null && !$this->hasDynamicTableColumn($tableName, $fallbackField)) {
                    continue;
                }
                $fields[] = [
                    'identification' => $fallbackField,
                    'name' => $this->resolveDefaultSearchFieldLabel($fallbackField),
                ];
            }
        }

        if ($tableName !== null) {
            $fields = array_values(array_filter($fields, function ($field) use ($tableName) {
                return $this->hasDynamicTableColumn($tableName, $field['identification']);
            }));
        }

        return $fields;
    }

    private function resolveDefaultSearchFieldLabel(string $field): string
    {
        return match ($field) {
            'title' => '标题',
            'name' => '名称',
            'content' => '内容',
            default => $field,
        };
    }

    private function applyFrontendKeywordSearch($query, string $tableName, string $keyword, array $searchFields): void
    {
        if ($keyword === '' || !$searchFields) {
            return;
        }

        $query->where(function ($subQuery) use ($keyword, $searchFields, $tableName) {
            $hasCondition = false;
            foreach ($searchFields as $field) {
                $column = $field['identification'] ?? '';
                if ($column === '' || !$this->hasDynamicTableColumn($tableName, $column)) {
                    continue;
                }
                if (!$hasCondition) {
                    $subQuery->where($column, 'like', '%' . $keyword . '%');
                    $hasCondition = true;
                } else {
                    $subQuery->orWhere($column, 'like', '%' . $keyword . '%');
                }
            }
        });
    }

    private function filterApiListByKeyword($list, string $keyword, array $searchFields): array
    {
        $list = is_array($list) ? $list : [];
        if ($keyword === '' || !$searchFields) {
            return $list;
        }

        $keyword = Str::lower($keyword);
        return array_values(array_filter($list, function ($item) use ($keyword, $searchFields) {
            foreach ($searchFields as $field) {
                $value = data_get($item, $field['identification'] ?? '');
                if ($value === null) {
                    continue;
                }
                $valueText = Str::lower(trim(strip_tags(is_scalar($value) ? (string) $value : json_encode($value, JSON_UNESCAPED_UNICODE))));
                if ($valueText !== '' && str_contains($valueText, $keyword)) {
                    return true;
                }
            }

            return false;
        }));
    }

    private function filterApiListByActiveStatus(array $list): array
    {
        return array_values(array_filter($list, function ($item) {
            return $this->isFrontendAccessibleApiItem($item);
        }));
    }

    private function isFrontendAccessibleApiItem($item): bool
    {
        $record = frontendRecordData($item);
        if (!array_key_exists('status', $record)) {
            return true;
        }

        return (string) ($record['status'] ?? '') === '1';
    }

    private function parseApiFieldMappings(?string $mappingText): array
    {
        $mappingText = str_replace("\r\n", "\n", (string) $mappingText);
        $mappings = [];
        foreach (explode("\n", $mappingText) as $line) {
            $line = trim($line);
            if ($line === '' || !str_contains($line, '=>')) {
                continue;
            }
            [$target, $source] = array_map('trim', explode('=>', $line, 2));
            if ($target === '' || $source === '') {
                continue;
            }
            $mappings[$target] = $source;
        }

        return $mappings;
    }

    private function requestApiPayload(string $url): array
    {
        $url = trim($url);
        if ($url === '') {
            return [];
        }

        $response = curl_request($url);
        if (!is_string($response) || trim($response) === '') {
            return [];
        }

        $decoded = json_decode($response, true);
        return is_array($decoded) ? $decoded : [];
    }

    private function buildApiDetailUrl(string $detailUrl, string $id): string
    {
        $detailUrl = trim($detailUrl);
        if ($detailUrl === '') {
            return '';
        }

        if (str_contains($detailUrl, '{id}')) {
            return str_replace('{id}', $id, $detailUrl);
        }
        if (str_contains($detailUrl, ':id')) {
            return str_replace(':id', $id, $detailUrl);
        }
        if (preg_match('/[?&]id=$/', $detailUrl)) {
            return $detailUrl . rawurlencode($id);
        }

        return rtrim($detailUrl, '/') . '/' . rawurlencode($id);
    }

    private function extractApiListItems(array $payload): array
    {
        $items = $payload['data'] ?? $payload['list'] ?? $payload['items'] ?? [];
        if (!is_array($items)) {
            return [];
        }

        return array_values(array_filter($items, fn ($item) => is_array($item) || is_object($item)));
    }

    private function extractApiDetailItem(array $payload): array
    {
        $item = $payload['data'] ?? $payload['item'] ?? [];
        if (is_object($item) || is_array($item)) {
            return frontendRecordData($item);
        }

        $items = $this->extractApiListItems($payload);
        return $items[0] ?? [];
    }

    private function extractApiRelatedItems(array $payload): array
    {
        $items = $payload['other'] ?? $payload['related'] ?? $payload['list'] ?? [];
        if (!is_array($items)) {
            return [];
        }

        return array_values(array_filter($items, fn ($item) => is_array($item) || is_object($item)));
    }

    private function mapApiList(array $items, array $mappings): array
    {
        return array_values(array_filter(array_map(function ($item) use ($mappings) {
            $mapped = $this->mapApiItem($item, $mappings);
            return $mapped ?: null;
        }, $items)));
    }

    private function mapApiItem($item, array $mappings): array
    {
        $record = frontendRecordData($item);
        if (!$record) {
            return [];
        }

        if (!$mappings) {
            return $record;
        }

        foreach ($mappings as $target => $source) {
            $value = data_get($record, $source);
            if ($value === null && array_key_exists($source, $record)) {
                $value = $record[$source];
            }
            if ($value !== null) {
                $record[$target] = $value;
            }
        }

        return $record;
    }

    private function buildListContext(array $model, $list, string $searchKeyword, array $searchFields): array
    {
        $fieldNames = array_values(array_filter(array_map(function ($field) {
            return trim((string) ($field['name'] ?? ''));
        }, $searchFields)));

        return [
            'keyword' => $searchKeyword,
            'hasKeyword' => $searchKeyword !== '',
            'searchFields' => $searchFields,
            'searchFieldNames' => $fieldNames,
            'searchFieldSummary' => implode(' / ', $fieldNames),
            'resultCount' => $this->resolveListItemCount($list),
            'totalCount' => $this->resolveListTotalCount($list),
            'pageTitle' => $model['home_config']['home_page_title'] ?? ($model['name'] ?? '内容列表'),
            'pageDescription' => $model['home_config']['home_page_describe'] ?? '浏览最新内容、快速筛选重点信息。',
        ];
    }

    private function resolveListItemCount($list): int
    {
        if ($list instanceof AbstractPaginator) {
            return count($list->items());
        }
        if (is_array($list)) {
            return count($list);
        }
        if ($list instanceof \Countable) {
            return count($list);
        }

        return $list ? 1 : 0;
    }

    private function resolveListTotalCount($list): int
    {
        if ($list instanceof AbstractPaginator) {
            return (int) $list->total();
        }

        return $this->resolveListItemCount($list);
    }

    private function buildDetailInteractions(array $model, string $access, array $detailRecord): array
    {
        if (($model['other_config']['data_source'] ?? 'local') !== 'local') {
            return [
                'can_like' => false,
                'like_url' => '',
                'downloads' => [],
                'download_fields' => [],
            ];
        }

        $tableName = 'module_formtools_' . $model['identification'];
        $recordId = (int) ($detailRecord['id'] ?? 0);
        $downloadFields = $this->resolveDownloadableFields($model, $detailRecord, $access);
        $downloads = [];
        foreach ($downloadFields as $fieldDownloads) {
            foreach ($fieldDownloads['items'] as $downloadItem) {
                $downloads[] = $downloadItem;
            }
        }

        return [
            'can_like' => $recordId > 0 && $this->hasDynamicTableColumn($tableName, 'good_count'),
            'like_url' => $recordId > 0 ? url('detail/' . $access . '/' . $recordId . '/good') : '',
            'downloads' => $downloads,
            'download_fields' => $downloadFields,
        ];
    }

    private function resolveDownloadableFields(array $model, array $detailRecord, string $access): array
    {
        $recordId = (int) ($detailRecord['id'] ?? 0);
        if ($recordId <= 0) {
            return [];
        }

        $downloads = [];
        foreach (($model['frontend_schema']['detail'] ?? []) as $field) {
            if (!$this->isDownloadableField($field)) {
                continue;
            }

            $fieldKey = (string) ($field['identification'] ?? '');
            if ($fieldKey === '') {
                continue;
            }

            $storedFiles = $this->splitStoredFiles($detailRecord[$fieldKey] ?? '');
            if (!$storedFiles) {
                continue;
            }

            $items = [];
            foreach ($storedFiles as $index => $storedPath) {
                $items[] = [
                    'label' => count($storedFiles) > 1 ? (($field['name'] ?? $fieldKey) . ' ' . ($index + 1)) : ($field['name'] ?? $fieldKey),
                    'path' => $storedPath,
                    'url' => url('detail/' . $access . '/' . $recordId . '/download/' . $fieldKey . '/' . $index),
                    'direct_url' => $this->resolveFileUrl($storedPath),
                ];
            }

            $downloads[$fieldKey] = [
                'label' => $field['name'] ?? $fieldKey,
                'items' => $items,
            ];
        }

        return $downloads;
    }

    private function isDownloadableField(array $field): bool
    {
        return in_array((string) ($field['formtype'] ?? ''), ['upload', 'uploadAjax', 'file'], true);
    }

    private function splitStoredFiles($value): array
    {
        if (is_array($value)) {
            return array_values(array_filter(array_map(static fn ($item) => trim((string) $item), $value), static fn ($item) => $item !== ''));
        }

        $value = trim((string) $value);
        if ($value === '') {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', $value)), static fn ($item) => $item !== ''));
    }

    private function resolveFileUrl(string $path): string
    {
        if (preg_match('/^https?:\/\//i', $path)) {
            return $path;
        }

        return GetUrlByPath($path);
    }

    private function detailActionResponse(bool $success, string $message, array $data = [], int $status = 200)
    {
        if ($this->request->expectsJson() || $this->request->ajax() || str_starts_with(getURIByRoute($this->request)['uri'] ?? '', 'api/')) {
            return response()->json([
                'status' => $success ? 200 : $status,
                'msg' => $message,
                'data' => $data,
            ], $success ? 200 : $status);
        }

        return back()->with('pageDataMsg', $message);
    }

    private function resolveAdjacentDetailItem(
        string $tableName,
        int $currentId,
        string $direction,
        array $selectColumns,
        ?string $relationKey = null,
        $scopeValue = null
    ) {
        $scopedItem = null;
        if ($relationKey !== null && $scopeValue !== null && $scopeValue !== '') {
            $scopedItem = $this->queryAdjacentDetailItem(
                $tableName,
                $currentId,
                $direction,
                $selectColumns,
                [$relationKey => $scopeValue]
            );
        }

        if ($scopedItem) {
            return $scopedItem;
        }

        return $this->queryAdjacentDetailItem($tableName, $currentId, $direction, $selectColumns);
    }

    private function queryAdjacentDetailItem(
        string $tableName,
        int $currentId,
        string $direction,
        array $selectColumns,
        array $extraWhere = []
    ) {
        $query = $this->applyActiveStatusScope(Common::query()->from($tableName), $tableName);

        foreach ($extraWhere as $column => $value) {
            $query->where($column, $value);
        }

        if ($direction === 'previous') {
            return $query->where('id', '>', $currentId)
                ->orderBy('id')
                ->first($selectColumns);
        }

        return $query->where('id', '<', $currentId)
            ->orderByDesc('id')
            ->first($selectColumns);
    }

    private function resolveSingleModelTemplate(array $model, string $template): string
    {
        if (($model['type'] ?? 'multi') !== 'single' || $template !== FormTemplateResolver::DEFAULT_LIST_TEMPLATE) {
            return $template;
        }

        return match ($model['identification'] ?? '') {
            'about_us' => 'about',
            'contact_us' => 'contacts',
            default => $template,
        };
    }

    private function resolveHandleTemplate(array $model): string
    {
        if (($model['list_template'] ?? '') === 'feedback' || ($model['identification'] ?? '') === 'feedback') {
            return 'feedback';
        }

        return FormTemplateResolver::DEFAULT_HANDLE_TEMPLATE;
    }

    private function loadHandlePageData(array $model): array
    {
        if (($model['list_template'] ?? '') !== 'feedback' && ($model['identification'] ?? '') !== 'feedback') {
            return [];
        }

        $tableName = 'module_formtools_' . $model['identification'];
        if (!Schema::hasTable($tableName)) {
            return [];
        }

        return $this->applyActiveStatusScope(Common::query()->from($tableName), $tableName)
            ->latest('id')
            ->limit(6)
            ->get()
            ->toArray();
    }

    private function resolveFrontendJumpUrl(string $access, array $model, int $id): string
    {
        if (($model['list_template'] ?? '') === 'feedback' || ($model['identification'] ?? '') === 'feedback') {
            return url('list/' . $access);
        }

        if (($model['type'] ?? 'multi') === 'single') {
            return url('list/' . $access);
        }

        return url('detail/' . $access . '/' . $id);
    }

    private function applyActiveStatusScope($query, string $tableName)
    {
        if ($this->hasDynamicTableColumn($tableName, 'status')) {
            $query->where('status', 1);
        }

        return $query;
    }

    private function hasDynamicTableColumn(string $tableName, string $column): bool
    {
        return Schema::hasColumn($tableName, $column);
    }
}
