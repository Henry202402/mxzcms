@php($moduleName = $pageData['moduleName'] ?? 'Formtools')
@include(moduleAdminTemplate($moduleName)."public.header")
<style>
    .ft-page-category-form {
        display: grid;
        gap: 18px;
    }

    .ft-page-category-form__note {
        margin-bottom: 0;
        border-radius: 16px;
        border: 1px solid #dbeafe;
        background: linear-gradient(135deg, #eff6ff 0%, #f8fbff 100%);
        color: #1e3a8a;
    }

    .ft-page-category-form__panel {
        overflow: hidden;
        border-radius: 20px;
        background: #fff;
        border: 1px solid #e5e7eb;
        box-shadow: 0 16px 34px rgba(15, 23, 42, 0.08);
    }

    .ft-page-category-form__header {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        padding: 22px 24px 16px;
        border-bottom: 1px solid #eef2f7;
    }

    .ft-page-category-form__title {
        margin: 0 0 8px;
        color: #0f172a;
        font-size: 18px;
        font-weight: 700;
    }

    .ft-page-category-form__desc {
        margin: 0;
        color: #64748b;
        line-height: 1.8;
    }

    .ft-page-category-form__body {
        padding: 24px;
    }

    .ft-page-category-form__grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px 20px;
    }

    .ft-page-category-form__full {
        grid-column: 1 / -1;
    }

    .ft-page-category-form__label {
        display: block;
        margin-bottom: 8px;
        color: #334155;
        font-size: 13px;
        font-weight: 700;
    }

    .ft-page-category-form__control,
    .ft-page-category-form textarea {
        width: 100%;
        border-radius: 12px;
        border: 1px solid #dbe2ea;
        padding: 10px 12px;
        color: #0f172a;
    }

    .ft-page-category-form textarea {
        min-height: 120px;
        resize: vertical;
    }

    .ft-page-category-form__radio-group {
        display: flex;
        flex-wrap: wrap;
        gap: 18px;
        padding-top: 10px;
    }

    .ft-page-category-form__radio {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: #334155;
        font-weight: 600;
    }

    .ft-page-category-form__help {
        margin-top: 8px;
        color: #94a3b8;
        font-size: 12px;
        line-height: 1.7;
    }

    .ft-page-category-form__actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .ft-page-category-form__actions .btn {
        border-radius: 999px;
        min-width: 120px;
        font-weight: 600;
    }

    @media (max-width: 991px) {
        .ft-page-category-form__grid {
            grid-template-columns: 1fr;
        }
    }
</style>
<body>
@include(moduleAdminTemplate($moduleName)."public.nav")
<div class="page-container">
    <div class="page-content">
        @include(moduleAdminTemplate($moduleName)."public.left")
        <div class="content-wrapper">
            <div class="content" style="margin-top: 1rem;">
                @include(moduleAdminTemplate($moduleName)."public.crumb",['breadcrumb'=>[$pageData['title'],$pageData['subtitle']]])

                <form method="post" class="ft-page-category-form">
                    @csrf
                    @if($formData['id'])
                        <input type="hidden" name="id" value="{{$formData['id']}}">
                    @endif

                    <div class="alert alert-info alert-styled-left ft-page-category-form__note">
                        <span>分类主要解决“页面归哪组、怎么出导航、怎么批量管理”。建议先规划好频道或专题分组，再逐步把页面挂进去。</span>
                    </div>

                    <div class="ft-page-category-form__panel">
                        <div class="ft-page-category-form__header">
                            <div>
                                <h3 class="ft-page-category-form__title">分类信息</h3>
                                <p class="ft-page-category-form__desc">分类可以用于频道、专题、栏目树的第一层分组。</p>
                            </div>
                            <a href="{{url('admin/formtools/pageCategoryList')}}" class="btn btn-default btn-sm">返回分类列表</a>
                        </div>
                        <div class="ft-page-category-form__body">
                            <div class="ft-page-category-form__grid">
                                <div>
                                    <label class="ft-page-category-form__label">分类名称</label>
                                    <input type="text" name="name" value="{{$formData['name']}}" class="ft-page-category-form__control" placeholder="例如：企业信息">
                                </div>
                                <div>
                                    <label class="ft-page-category-form__label">分类标识</label>
                                    <input type="text" name="identification" value="{{$formData['identification']}}" class="ft-page-category-form__control" placeholder="例如：company">
                                    <div class="ft-page-category-form__help">仅允许小写字母、数字、下划线和中划线。</div>
                                </div>
                                <div>
                                    <label class="ft-page-category-form__label">排序</label>
                                    <input type="number" name="sort" value="{{$formData['sort']}}" class="ft-page-category-form__control" placeholder="0">
                                </div>
                                <div>
                                    <label class="ft-page-category-form__label">状态</label>
                                    <div class="ft-page-category-form__radio-group">
                                        <label class="ft-page-category-form__radio"><input type="radio" name="status" value="1" {{$formData['status'] === '1' ? 'checked' : ''}}> 启用</label>
                                        <label class="ft-page-category-form__radio"><input type="radio" name="status" value="0" {{$formData['status'] === '0' ? 'checked' : ''}}> 停用</label>
                                    </div>
                                </div>
                                <div class="ft-page-category-form__full">
                                    <label class="ft-page-category-form__label">备注</label>
                                    <textarea name="remark">{{$formData['remark']}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ft-page-category-form__actions">
                        <a href="{{url('admin/formtools/pageCategoryList')}}" class="btn btn-default">取消</a>
                        <button type="submit" class="btn btn-primary">保存分类</button>
                    </div>
                </form>

                @include(moduleAdminTemplate($moduleName)."public.footer")
            </div>
        </div>
    </div>
</div>
</body>
