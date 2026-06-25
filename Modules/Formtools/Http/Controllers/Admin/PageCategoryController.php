<?php

namespace Modules\Formtools\Http\Controllers\Admin;

use Modules\Formtools\Models\FormPage;
use Modules\Formtools\Models\FormPageCategory;
use Modules\ModulesController;

class PageCategoryController extends ModulesController
{
    public function index()
    {
        $pageData = getURIByRoute($this->request);
        $pageData['title'] = '页面分类';
        $pageData['subtitle'] = '管理页面分组与导航结构';
        $pageData['datas'] = FormPageCategory::query()
            ->orderBy('sort')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('formtools::admin.pageCategory.index', [
            'pageData' => $pageData,
            'pageCountMap' => FormPage::query()
                ->selectRaw('category_id, count(*) as aggregate')
                ->whereNotNull('category_id')
                ->groupBy('category_id')
                ->pluck('aggregate', 'category_id'),
        ]);
    }

    public function add()
    {
        if ($this->request->isMethod('post')) {
            [$payload, $error] = $this->buildPayload();
            if ($error !== null) {
                return back()->withInput()->with('pageDataMsg', $error);
            }

            $payload['created_at'] = date('Y-m-d H:i:s');
            $payload['updated_at'] = $payload['created_at'];
            $res = FormPageCategory::query()->insert($payload);

            if ($res) {
                return redirect('/admin/formtools/pageCategoryList')->with(['pageDataMsg' => '添加成功', 'pageDataStatus' => 200]);
            }

            return redirect('/admin/formtools/pageCategoryList')->with('pageDataMsg', '添加失败');
        }

        return $this->renderForm('页面分类', '新增分类');
    }

    public function edit()
    {
        $id = (int) $this->request->query('id', 0);
        $category = FormPageCategory::query()->find($id);
        if (!$category) {
            return redirect('/admin/formtools/pageCategoryList')->with('pageDataMsg', '分类不存在');
        }

        if ($this->request->isMethod('post')) {
            [$payload, $error] = $this->buildPayload($id);
            if ($error !== null) {
                return back()->withInput()->with('pageDataMsg', $error);
            }

            $payload['updated_at'] = date('Y-m-d H:i:s');
            $res = FormPageCategory::query()->where('id', $id)->update($payload);

            if ($res !== false) {
                return redirect('/admin/formtools/pageCategoryList')->with(['pageDataMsg' => '编辑成功', 'pageDataStatus' => 200]);
            }

            return redirect('/admin/formtools/pageCategoryList')->with('pageDataMsg', '编辑失败');
        }

        return $this->renderForm('页面分类', '编辑分类', $category);
    }

    public function delete()
    {
        $id = (int) $this->request->query('id', 0);
        $category = FormPageCategory::query()->find($id);
        if (!$category) {
            return redirect('/admin/formtools/pageCategoryList')->with('pageDataMsg', '分类不存在');
        }

        if (FormPage::query()->where('category_id', $id)->exists()) {
            return redirect('/admin/formtools/pageCategoryList')->with('pageDataMsg', '该分类下仍有关联页面，请先调整页面分类');
        }

        $res = FormPageCategory::query()->where('id', $id)->delete();
        if ($res) {
            return redirect('/admin/formtools/pageCategoryList')->with(['pageDataMsg' => '删除成功', 'pageDataStatus' => 200]);
        }

        return redirect('/admin/formtools/pageCategoryList')->with('pageDataMsg', '删除失败');
    }

    private function renderForm(string $title, string $subtitle, ?FormPageCategory $category = null)
    {
        $pageData = getURIByRoute($this->request);
        $pageData['title'] = $title;
        $pageData['subtitle'] = $subtitle;

        return view('formtools::admin.pageCategory.form', [
            'pageData' => $pageData,
            'formData' => [
                'id' => $category->id ?? '',
                'name' => old('name', $category->name ?? ''),
                'identification' => old('identification', $category->identification ?? ''),
                'sort' => old('sort', (string) ($category->sort ?? 0)),
                'status' => old('status', (string) ($category->status ?? 1)),
                'remark' => old('remark', $category->remark ?? ''),
            ],
        ]);
    }

    private function buildPayload(int $ignoreId = 0): array
    {
        $name = trim((string) $this->request->input('name', ''));
        $identification = strtolower(trim((string) $this->request->input('identification', '')));
        $sort = (int) $this->request->input('sort', 0);
        $status = (int) $this->request->input('status', 1);
        $remark = trim((string) $this->request->input('remark', ''));

        if ($name === '' || $identification === '') {
            return [null, '请填写完整'];
        }

        if (!preg_match('/^[a-z0-9_-]+$/', $identification)) {
            return [null, '分类标识只能包含小写字母、数字、下划线和中划线'];
        }

        $query = FormPageCategory::query()->where('identification', $identification);
        if ($ignoreId > 0) {
            $query->where('id', '<>', $ignoreId);
        }

        if ($query->exists()) {
            return [null, '分类标识已存在'];
        }

        return [[
            'name' => $name,
            'identification' => $identification,
            'sort' => $sort,
            'status' => $status === 1 ? 1 : 0,
            'remark' => $remark,
        ], null];
    }
}
