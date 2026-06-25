<?php

namespace Modules\Formtools\Http\Controllers\Admin;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\ModulesController;

class TestController extends ModulesController
{
    public function index()
    {
        $all = $this->request->all();
        $pageData = getURIByRoute($this->request);

        $statusMap = $this->statusMap();
        $datas = $this->buildPaginator($this->filterDemoRecords($all));

        return FormTool::create()
            ->fieldsBatch([
                'id' => ['name' => 'ID'],
                'title' => ['name' => '标题'],
                'status' => ['name' => '状态', 'datas' => $statusMap],
                'owner' => ['name' => '负责人'],
                'updated_at' => ['name' => '更新时间'],
            ])
            ->searchFieldsBatch([
                'keyword' => [
                    'placeholder' => '标题或负责人',
                    'value' => $all['keyword'] ?? '',
                ],
                'status' => [
                    'placeholder' => '状态',
                    'value' => $all['status'] ?? '',
                    'formtype' => 'select',
                    'datas' => $this->statusOptions(),
                ],
            ])
            ->rightAction([
                FormTool::action('详情', url('admin/formtools/demo/detail'), FormTool::btn_primary, [
                    'target' => '_blank',
                ]),
                FormTool::action('弹窗编辑', url('admin/formtools/demo/edit'), FormTool::btn_info, [
                    'popup' => true,
                    'param' => ['popup' => 1],
                ]),
                [
                    'actionName' => '查看正文',
                    'actionUrl' => '#',
                    'cssClass' => FormTool::btn_success,
                    'actionType' => 'modal',
                    'field' => 'content',
                    'titleField' => 'title',
                ],
            ], 'id')
            ->appendRightAction(FormTool::action('删除示例', url('admin/formtools/demo/delete'), FormTool::btn_danger, [
                'confirm' => true,
            ]))
            ->pageTitle('FormTool 示例')
            ->formTitle('列表页示例')
            ->listAction([
                FormTool::action('新增示例', url('admin/formtools/demo/add'), 'bg-info'),
            ])
            ->appendListAction(FormTool::action('弹窗新增', url('admin/formtools/demo/add'), 'bg-primary', [
                'popup' => true,
                'param' => ['popup' => 1],
            ]))
            ->appendListAction(FormTool::action('查看详情页', url('admin/formtools/demo/detail?id=1'), 'bg-default', [
                'target' => '_blank',
            ]))
            ->linkAppend($all)
            ->inlineScript("console.log('FormTool demo list ready');")
            ->listView($pageData, $datas);
    }

    public function add()
    {
        return $this->renderForm();
    }

    public function edit()
    {
        $record = $this->findRecord($this->request->input('id'));
        if (!$record) {
            return oneFlash([0, '示例数据不存在']);
        }

        return $this->renderForm($record);
    }

    public function detail()
    {
        $record = $this->findRecord($this->request->input('id', 1));
        if (!$record) {
            return oneFlash([0, '示例数据不存在']);
        }

        $pageData = getURIByRoute($this->request);
        $detailData = $this->buildDetailData($record);

        return FormTool::create()
            ->group('detail_basic', '基础信息', [
                'notes' => '详情页也可以复用分组和列布局配置。',
                'columns' => 2,
            ], function (FormTool $form) {
                $form->field('title', '标题');
                $form->field('status', ['name' => '状态', 'datas' => $this->statusMap()]);
                $form->field('owner', '负责人');
                $form->field('updated_at', '更新时间');
            })
            ->beginGroup('detail_content', '内容与附件', [
                'formtype' => 'legend',
                'columns' => 2,
            ])
            ->field('summary', ['name' => '摘要', 'formtype' => 'textarea'])
            ->field('content', ['name' => '正文', 'formtype' => 'editor'])
            ->row('content')
            ->field('attachment', ['name' => '附件', 'formtype' => 'upload'])
            ->field('cover', ['name' => '封面', 'formtype' => 'image'])
            ->endGroup()
            ->group('detail_config', '扩展配置', [
                'columns' => 2,
            ], function (FormTool $form) {
                $form->field('tags', ['name' => '标签', 'formtype' => 'tags']);
                $form->field('seo_json', ['name' => 'SEO 配置', 'formtype' => 'json']);
                $form->field('extra_code', ['name' => '扩展脚本', 'formtype' => 'code']);
                $form->width('tags', 4);
                $form->width('seo_json', 8);
                $form->full('extra_code');
            })
            ->links('quick_links', '相关入口', [
                ['name' => '返回列表示例', 'href' => url('admin/formtools/demo')],
                ['name' => '打开编辑示例', 'href' => url('admin/formtools/demo/edit?id=' . $record['id'])],
            ])
            ->pageTitle('FormTool 示例')
            ->formTitle('详情页示例')
            ->tips('这页用于查看不同字段类型在详情模板中的展示效果。')
            ->popup()
            ->inlineScript("console.log('FormTool demo detail ready');")
            ->detailView(array_merge($pageData, [
                'detail' => $detailData,
            ]));
    }

    public function save()
    {
        $all = $this->request->all();
        if ($if = ifCondition([
            'title' => '标题不能为空',
            'status' => '请选择状态',
        ], $all)) {
            return returnArr($if['status'], $if['msg']);
        }

        return returnArr(200, '示例表单已提交，可以直接按当前控制器结构替换成真实业务保存逻辑。', [
            'jumpUrl' => $all['jumpUrl'] ?? url('admin/formtools/demo'),
        ]);
    }

    public function delete()
    {
        return oneFlash([200, '这是示例页，未真正删除数据。', url('admin/formtools/demo')]);
    }

    private function renderForm(array $record = [])
    {
        $pageData = getURIByRoute($this->request);
        $formData = $this->buildFormData($record);

        return FormTool::create()
            ->group('basic_section', '基础信息', [
                'notes' => '这组字段演示最常见的录入项组合。',
                'columns' => 2,
            ], function (FormTool $form) use ($formData) {
                $form->text('title', '标题', $formData['title'], [
                    'required' => true,
                    'placeholder' => '请输入标题',
                ]);
                $form->select('status', '状态', $formData['status'], $this->statusOptions(), [
                    'notes' => '列表页与详情页会复用同一套状态映射。',
                ]);
                $form->text('owner', '负责人', $formData['owner'], [
                    'placeholder' => '请输入负责人',
                ]);
                $form->switchField('is_recommend', '推荐显示', $formData['is_recommend']);
            })
            ->beginGroup('content_legend', '内容与附件', [
                'formtype' => 'legend',
                'columns' => 2,
            ])
            ->textarea('summary', '摘要', $formData['summary'], [
                'rows' => 4,
                'placeholder' => '请输入摘要',
            ])
            ->editor('content', '正文', $formData['content'])
            ->row('content')
            ->file('attachment', '附件', $formData['attachment'], [
                'notes' => '`file()` 是 `upload` 的快捷别名。',
            ])
            ->image('cover', '封面', $formData['cover'])
            ->endGroup()
            ->group('config_section', '配置类字段', [
                'columns' => 2,
            ], function (FormTool $form) use ($formData) {
                $form->json('seo_json', 'SEO 配置', $formData['seo_json'], [
                    'rows' => 8,
                    'notes' => '适合放结构化配置。',
                ]);
                $form->code('extra_code', '扩展脚本', $formData['extra_code'], [
                    'rows' => 6,
                    'notes' => '适合放模板片段或代码段。',
                ]);
                $form->tags('tags', '标签', $formData['tags'], [
                    'notes' => '多个标签请使用英文逗号分隔。',
                ]);
                $form->width('tags', 4);
                $form->width('seo_json', 8);
                $form->full('extra_code');
            })
            ->word('demo_guide', '使用提示', $this->buildDemoGuideHtml())
            ->links('quick_links', '相关操作', [
                ['name' => '返回列表示例', 'href' => url('admin/formtools/demo')],
                ['name' => '查看详情示例', 'href' => url('admin/formtools/demo/detail?id=' . ($formData['id'] ?: 1))],
            ])
            ->hidden('id', $formData['id'])
            ->csrf_field()
            ->jumpPrevUrl()
            ->pageTitle('FormTool 示例')
            ->formTitle($record ? '编辑页示例' : '新增页示例')
            ->listAction([
                FormTool::action('返回列表', url('admin/formtools/demo'), FormTool::label_default),
            ])
            ->formAction(url('admin/formtools/demo/save'))
            ->actionType('ajax')
            ->popup()
            ->inlineScript("console.log('FormTool demo form ready');")
            ->formView($pageData);
    }

    private function buildPaginator(array $items, int $perPage = 10): LengthAwarePaginator
    {
        $page = max(1, intval($this->request->input('page', 1)));
        $collection = collect($items);

        return new LengthAwarePaginator(
            $collection->forPage($page, $perPage)->values(),
            $collection->count(),
            $perPage,
            $page,
            [
                'path' => url()->current(),
                'query' => $this->request->query(),
            ]
        );
    }

    private function filterDemoRecords(array $filters): array
    {
        $keyword = trim((string) ($filters['keyword'] ?? ''));
        $status = trim((string) ($filters['status'] ?? ''));

        return array_values(array_filter($this->demoRecords(), function (array $record) use ($keyword, $status) {
            if ($keyword !== '' && stripos($record['title'] . ' ' . $record['owner'], $keyword) === false) {
                return false;
            }

            if ($status !== '' && (string) $record['status'] !== $status) {
                return false;
            }

            return true;
        }));
    }

    private function findRecord($id): ?array
    {
        $id = intval($id);
        foreach ($this->demoRecords() as $record) {
            if (intval($record['id']) === $id) {
                return $record;
            }
        }

        return null;
    }

    private function buildFormData(array $record): array
    {
        return [
            'id' => $record['id'] ?? '',
            'title' => $record['title'] ?? '',
            'status' => $record['status'] ?? 1,
            'owner' => $record['owner'] ?? '',
            'is_recommend' => $record['is_recommend'] ?? 1,
            'summary' => $record['summary'] ?? '',
            'content' => $record['content'] ?? '',
            'attachment' => $record['attachment'] ?? '',
            'cover' => $record['cover'] ?? '',
            'seo_json' => $this->prettyJson($record['seo'] ?? []),
            'extra_code' => $record['extra_code'] ?? '',
            'tags' => isset($record['tags']) ? implode(',', $record['tags']) : '',
        ];
    }

    private function buildDetailData(array $record): array
    {
        return array_merge($record, [
            'tags' => implode(',', $record['tags'] ?? []),
            'seo_json' => $this->prettyJson($record['seo'] ?? []),
        ]);
    }

    private function statusMap(): array
    {
        return [
            1 => '启用',
            0 => '停用',
        ];
    }

    private function statusOptions(): array
    {
        return FormTool::optionsFromMap($this->statusMap());
    }

    private function prettyJson(array $data): string
    {
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    private function buildDemoGuideHtml(): string
    {
        return '<div class="text-muted">这个示例页会同时展示快捷字段方法、弹窗模式、AJAX 提交和详情模板效果，后续新模块可以直接照着这套结构起页。</div>';
    }

    private function demoRecords(): array
    {
        return [
            [
                'id' => 1,
                'title' => '品牌专题页',
                'status' => 1,
                'owner' => '内容运营',
                'is_recommend' => 1,
                'summary' => '用于演示图文内容、SEO 配置和附件下载的基础场景。',
                'content' => '<p>这里是列表页 modal 动作读取的正文内容。</p><p>你可以把它换成真实业务字段。</p>',
                'attachment' => '/uploads/demo/brand-plan.pdf',
                'cover' => '/uploads/demo/brand-cover.jpg',
                'tags' => ['专题', '演示', '表单'],
                'seo' => [
                    'title' => '品牌专题页',
                    'keywords' => '品牌,专题,演示',
                    'description' => 'FormTool 示例页的 SEO 配置。',
                ],
                'extra_code' => "console.log('brand demo');",
                'updated_at' => '2026-06-20 10:30:00',
            ],
            [
                'id' => 2,
                'title' => '下载中心条目',
                'status' => 0,
                'owner' => '产品经理',
                'is_recommend' => 0,
                'summary' => '用于演示附件字段和状态控制。',
                'content' => '<p>这里可以展示下载须知、版本说明或配置说明。</p>',
                'attachment' => '/uploads/demo/download-guide.zip',
                'cover' => '/uploads/demo/download-cover.png',
                'tags' => ['下载', '配置'],
                'seo' => [
                    'title' => '下载中心条目',
                    'keywords' => '下载,指南',
                    'description' => '下载中心示例。',
                ],
                'extra_code' => "console.log('download demo');",
                'updated_at' => '2026-06-19 18:20:00',
            ],
            [
                'id' => 3,
                'title' => '帮助中心公告',
                'status' => 1,
                'owner' => '站点管理员',
                'is_recommend' => 1,
                'summary' => '用于演示标签、链接和只读说明场景。',
                'content' => '<p>帮助中心公告可复用同一套列表、表单和详情模板。</p>',
                'attachment' => '',
                'cover' => '/uploads/demo/help-cover.jpg',
                'tags' => ['帮助', '公告'],
                'seo' => [
                    'title' => '帮助中心公告',
                    'keywords' => '帮助,公告',
                    'description' => '帮助中心示例。',
                ],
                'extra_code' => "console.log('help demo');",
                'updated_at' => '2026-06-18 09:00:00',
            ],
        ];
    }
}
