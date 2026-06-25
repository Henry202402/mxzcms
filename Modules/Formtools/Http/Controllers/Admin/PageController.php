<?php

namespace Modules\Formtools\Http\Controllers\Admin;

use Modules\Formtools\Models\FormModel;
use Modules\Formtools\Models\FormPage;
use Modules\Formtools\Models\FormPageCategory;
use Modules\Formtools\Http\Controllers\Home\PageController as HomePageController;
use Modules\Formtools\Support\PageBuilderCatalog;
use Modules\Formtools\Support\PageSourceFormatter;
use Modules\ModulesController;

class PageController extends ModulesController
{
    public function index()
    {
        $pageData = getURIByRoute($this->request);
        $pageData['title'] = '页面列表';
        $pageData['subtitle'] = '多页面编排与资源管理';
        $pageData['datas'] = FormPage::query()
            ->with(['model', 'category'])
            ->orderByDesc('is_home')
            ->orderBy('sort')
            ->orderBy('id', 'desc')
            ->paginate(10);

        $statistics = [
            'total' => FormPage::query()->count(),
            'enabled' => FormPage::query()->where('status', 1)->count(),
            'visual' => FormPage::query()->where('builder_type', 'visual')->count(),
            'bind_model' => FormPage::query()->whereNotNull('model_id')->count(),
            'category' => FormPageCategory::query()->count(),
        ];

        return view('formtools::admin.page.index', [
            'pageData' => $pageData,
            'statistics' => $statistics,
        ]);
    }

    public function pageAdd()
    {
        if ($this->request->isMethod('post')) {
            [$payload, $error] = $this->buildPayload();
            if ($error !== null) {
                return back()->withInput()->with('pageDataMsg', $error);
            }

            $payload['created_at'] = date('Y-m-d H:i:s');
            $payload['updated_at'] = $payload['created_at'];

            $page = new FormPage();
            foreach ($payload as $field => $value) {
                $page->{$field} = $value;
            }

            if ($page->save()) {
                return redirect('/admin/formtools/pageEdit?id=' . $page->id)->with(['pageDataMsg' => '添加成功', 'pageDataStatus' => 200]);
            }

            return back()->withInput()->with('pageDataMsg', '添加失败');
        }

        return $this->renderForm('页面管理', '新增页面');
    }

    public function pageEdit()
    {
        $id = (int) $this->request->query('id', 0);
        $page = FormPage::query()->find($id);
        if (!$page) {
            return redirect('/admin/formtools/pageList')->with('pageDataMsg', '页面不存在');
        }

        if ($this->request->isMethod('post')) {
            [$payload, $error] = $this->buildPayload($page->id);
            if ($error !== null) {
                return back()->withInput()->with('pageDataMsg', $error);
            }

            $payload['updated_at'] = date('Y-m-d H:i:s');

            $res = FormPage::query()->where('id', $page->id)->update($payload);
            if ($res !== false) {
                return redirect('/admin/formtools/pageEdit?id=' . $page->id)->with(['pageDataMsg' => '编辑成功', 'pageDataStatus' => 200]);
            }

            return back()->withInput()->with('pageDataMsg', '编辑失败');
        }

        return $this->renderForm('页面管理', '编辑页面', $page);
    }

    public function preview()
    {
        $id = (int) $this->request->query('id', 0);
        $page = FormPage::query()
            ->with('model')
            ->find($id);
        if (!$page) {
            return redirect('/admin/formtools/pageList')->with('pageDataMsg', '页面不存在');
        }

        return app(HomePageController::class)->renderPage($page, true);
    }

    public function pageSetHome()
    {
        $id = (int) $this->request->query('id', 0);
        $isHome = (int) $this->request->query('is_home', 1);
        $page = FormPage::query()->find($id);
        if (!$page) {
            return back()->with('pageDataMsg', '页面不存在');
        }

        if ($isHome === 1) {
            if ((int) $page->status !== 1) {
                return back()->with('pageDataMsg', '只有启用页面才能设为首页');
            }
            FormPage::query()->where('id', '<>', $page->id)->update(['is_home' => 0]);
            FormPage::query()->where('id', $page->id)->update(['is_home' => 1, 'updated_at' => date('Y-m-d H:i:s')]);
            return back()->with(['pageDataMsg' => '首页页面已切换', 'pageDataStatus' => 200]);
        }

        FormPage::query()->where('id', $page->id)->update(['is_home' => 0, 'updated_at' => date('Y-m-d H:i:s')]);
        return back()->with(['pageDataMsg' => '已取消页面首页接管', 'pageDataStatus' => 200]);
    }

    public function pageDelete()
    {
        $id = (int) $this->request->query('id', 0);
        $page = FormPage::query()->find($id);
        if (!$page) {
            return redirect('/admin/formtools/pageList')->with('pageDataMsg', '页面不存在');
        }

        $res = FormPage::query()->where('id', $id)->delete();
        if ($res) {
            return redirect('/admin/formtools/pageList')->with(['pageDataMsg' => '删除成功', 'pageDataStatus' => 200]);
        }

        return redirect('/admin/formtools/pageList')->with('pageDataMsg', '删除失败');
    }

    public function pageCopy()
    {
        $id = (int) $this->request->query('id', 0);
        $page = FormPage::query()->find($id);
        if (!$page) {
            return redirect('/admin/formtools/pageList')->with('pageDataMsg', '页面不存在');
        }

        $copy = $page->toArray();
        unset($copy['id']);

        $copy['name'] = $page->name . ' - 副本';
        $copy['identification'] = $this->generateUniqueValue($page->identification, 'identification');
        $copy['slug'] = $this->generateUniqueValue($page->slug, 'slug');
        $copy['created_at'] = date('Y-m-d H:i:s');
        $copy['updated_at'] = $copy['created_at'];

        $res = FormPage::query()->insert($copy);
        if ($res) {
            return redirect('/admin/formtools/pageList')->with(['pageDataMsg' => '复制成功', 'pageDataStatus' => 200]);
        }

        return redirect('/admin/formtools/pageList')->with('pageDataMsg', '复制失败');
    }

    private function renderForm(string $title, string $subtitle, ?FormPage $page = null)
    {
        $pageData = getURIByRoute($this->request);
        $pageData['title'] = $title;
        $pageData['subtitle'] = $subtitle;

        $formData = [
            'id' => $page->id ?? '',
            'name' => old('name', $page->name ?? ''),
            'identification' => old('identification', $page->identification ?? ''),
            'slug' => old('slug', $page->slug ?? ''),
            'type' => old('type', $page->type ?? 'custom'),
            'category_id' => old('category_id', (string) ($page->category_id ?? '')),
            'model_id' => old('model_id', (string) ($page->model_id ?? '')),
            'template' => old('template', $page->template ?? 'default'),
            'builder_type' => old('builder_type', $page->builder_type ?? 'visual'),
            'status' => old('status', (string) ($page->status ?? 1)),
            'is_nav' => old('is_nav', (string) ($page->is_nav ?? 0)),
            'is_home' => old('is_home', (string) ($page->is_home ?? 0)),
            'sort' => old('sort', (string) ($page->sort ?? 0)),
            'remark' => old('remark', $page->remark ?? ''),
            'seo_title' => old('seo_title', $page->seo_title ?? ''),
            'seo_keywords' => old('seo_keywords', $page->seo_keywords ?? ''),
            'seo_description' => old('seo_description', $page->seo_description ?? ''),
            'layout_schema' => old('layout_schema', $page->layout_schema ?? $this->defaultLayoutSchema()),
            'page_html' => old('page_html', $page->page_html ?? ''),
            'custom_css' => old('custom_css', $page->custom_css ?? ''),
            'custom_js' => old('custom_js', $page->custom_js ?? ''),
        ];

        return view('formtools::admin.page.form', [
            'pageData' => $pageData,
            'formData' => $formData,
            'categories' => FormPageCategory::query()->where('status', 1)->orderBy('sort')->orderBy('id')->get(),
            'models' => FormModel::query()->withoutReserved()->orderBy('name')->get(),
            'blockCatalog' => PageBuilderCatalog::blocks(),
            'previewUrl' => $page ? $page->getPreviewUrl() : '',
            'publicUrl' => $formData['slug'] !== '' ? url('p/' . ltrim($formData['slug'], '/')) : '',
        ]);
    }

    private function buildPayload(int $ignoreId = 0): array
    {
        $name = trim((string) $this->request->input('name', ''));
        $identification = strtolower(trim((string) $this->request->input('identification', '')));
        $slug = trim((string) $this->request->input('slug', ''));
        $slug = trim(str_replace('\\', '/', $slug), '/');
        $type = trim((string) $this->request->input('type', 'custom'));
        $categoryId = (int) $this->request->input('category_id', 0);
        $builderType = trim((string) $this->request->input('builder_type', 'visual'));
        $template = trim((string) $this->request->input('template', 'default'));
        $remark = trim((string) $this->request->input('remark', ''));
        $layoutSchema = trim((string) $this->request->input('layout_schema', ''));
        $pageHtml = (string) $this->request->input('page_html', '');
        $customCss = (string) $this->request->input('custom_css', '');
        $customJs = (string) $this->request->input('custom_js', '');
        $seoTitle = PageSourceFormatter::normalizeMetaText((string) $this->request->input('seo_title', ''), 120);
        $seoKeywords = PageSourceFormatter::normalizeMetaText((string) $this->request->input('seo_keywords', ''), 255);
        $seoDescription = PageSourceFormatter::normalizeMetaText((string) $this->request->input('seo_description', ''), 255);
        $status = (int) $this->request->input('status', 1);
        $isNav = (int) $this->request->input('is_nav', 0);
        $isHome = (int) $this->request->input('is_home', 0);
        $sort = (int) $this->request->input('sort', 0);
        $modelId = (int) $this->request->input('model_id', 0);

        if ($name === '' || $identification === '' || $slug === '') {
            return [null, '请填写完整'];
        }

        if (!preg_match('/^[a-z0-9_-]+$/', $identification)) {
            return [null, '页面标识只能包含小写字母、数字、下划线和中划线'];
        }

        if (!preg_match('#^[A-Za-z0-9/_-]+$#', $slug)) {
            return [null, '访问路径只能包含字母、数字、斜杠、下划线和中划线'];
        }

        $allowedTypes = ['single', 'list', 'custom', 'landing'];
        if (!in_array($type, $allowedTypes, true)) {
            return [null, '页面类型不正确'];
        }

        $allowedBuilderTypes = ['visual', 'html'];
        if (!in_array($builderType, $allowedBuilderTypes, true)) {
            return [null, '编辑模式不正确'];
        }

        if ($template === '') {
            $template = 'default';
        }

        if ($layoutSchema === '' && $builderType === 'visual') {
            $layoutSchema = $this->defaultLayoutSchema();
        }

        if ($layoutSchema !== '') {
            json_decode($layoutSchema, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return [null, '布局 JSON 格式不正确'];
            }
            $layoutSchema = PageSourceFormatter::formatSchemaJson($layoutSchema);
        }

        $pageHtml = PageSourceFormatter::formatHtml($pageHtml);
        $customCss = PageSourceFormatter::formatCss($customCss);
        $customJs = PageSourceFormatter::formatJs($customJs);

        $identificationQuery = FormPage::query()->where('identification', $identification);
        $slugQuery = FormPage::query()->where('slug', $slug);
        if ($ignoreId > 0) {
            $identificationQuery->where('id', '<>', $ignoreId);
            $slugQuery->where('id', '<>', $ignoreId);
        }
        if ($identificationQuery->exists()) {
            return [null, '页面标识已存在'];
        }
        if ($slugQuery->exists()) {
            return [null, '访问路径已存在'];
        }

        if ($isHome === 1 && $status !== 1) {
            return [null, '设为首页的页面必须保持启用状态'];
        }

        if ($modelId > 0 && !FormModel::query()->withoutReserved()->where('id', $modelId)->exists()) {
            return [null, '绑定模型不存在'];
        }
        if ($categoryId > 0 && !FormPageCategory::query()->where('id', $categoryId)->exists()) {
            return [null, '页面分类不存在'];
        }

        if ($isHome === 1) {
            FormPage::query()->where('id', '<>', $ignoreId)->update(['is_home' => 0]);
        }

        return [[
            'name' => $name,
            'identification' => $identification,
            'slug' => $slug,
            'type' => $type,
            'category_id' => $categoryId > 0 ? $categoryId : null,
            'model_id' => $modelId > 0 ? $modelId : null,
            'template' => $template,
            'builder_type' => $builderType,
            'status' => $status === 1 ? 1 : 0,
            'is_nav' => $isNav === 1 ? 1 : 0,
            'is_home' => $isHome === 1 ? 1 : 0,
            'sort' => $sort,
            'remark' => $remark,
            'seo_title' => $seoTitle,
            'seo_keywords' => $seoKeywords,
            'seo_description' => $seoDescription,
            'layout_schema' => $layoutSchema,
            'page_html' => $pageHtml,
            'custom_css' => $customCss,
            'custom_js' => $customJs,
        ], null];
    }

    private function defaultLayoutSchema(): string
    {
        $schema = [
            'sections' => [
                [
                    'type' => 'section',
                    'style' => [
                        'padding' => '48px 0',
                        'background' => '#ffffff',
                    ],
                    'children' => [
                        [
                            'type' => 'heading',
                            'props' => [
                                'level' => 'h1',
                                'text' => '页面标题',
                            ],
                            'style' => [
                                'margin' => '0 0 16px',
                                'color' => '#0f172a',
                            ],
                        ],
                        [
                            'type' => 'text',
                            'props' => [
                                'text' => '这里先放页面说明、卖点或模型输出区域。后续拖拽编辑器会直接生成这份布局 JSON。',
                            ],
                            'style' => [
                                'color' => '#475569',
                                'lineHeight' => '1.8',
                            ],
                        ],
                    ],
                ],
            ],
        ];

        return json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    private function generateUniqueValue(string $base, string $field): string
    {
        $base = trim($base);
        $candidate = $base . '-copy';
        $index = 2;

        while (FormPage::query()->where($field, $candidate)->exists()) {
            $candidate = $base . '-copy-' . $index;
            $index++;
        }

        return $candidate;
    }
}
