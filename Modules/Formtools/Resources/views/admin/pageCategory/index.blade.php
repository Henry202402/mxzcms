@php($moduleName = $pageData['moduleName'] ?? 'Formtools')
@php($moduleViewName = strtolower($moduleName))
@include(moduleAdminTemplate($moduleName)."public.header")
<style>
    .page-content > .content-wrapper {
        min-width: 0;
    }

    .ft-page-category {
        display: grid;
        gap: 18px;
        min-width: 0;
    }

    .ft-page-category__note {
        margin-bottom: 0;
        border-radius: 16px;
        border: 1px solid #dbeafe;
        background: linear-gradient(135deg, #eff6ff 0%, #f8fbff 100%);
        color: #1e3a8a;
    }

    .ft-page-category__panel {
        overflow: hidden;
        border-radius: 20px;
        background: #fff;
        border: 1px solid #e5e7eb;
        box-shadow: 0 16px 34px rgba(15, 23, 42, 0.08);
        min-width: 0;
        max-width: 100%;
    }

    .ft-page-category__toolbar {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        padding: 24px;
        border-bottom: 1px solid #eef2f7;
    }

    .ft-page-category__title {
        margin: 0 0 8px;
        color: #0f172a;
        font-size: 20px;
        font-weight: 700;
    }

    .ft-page-category__desc {
        margin: 0;
        color: #64748b;
        line-height: 1.8;
    }

    .ft-page-category__table-wrap {
        padding: 0 22px 18px;
        min-width: 0;
        max-width: 100%;
        overflow-x: auto;
        overflow-y: visible;
    }

    .ft-page-category__table {
        margin-bottom: 0;
        min-width: 920px;
    }

    .ft-page-category__table > thead > tr > th {
        padding: 14px 16px;
        border-bottom: 1px solid #e5e7eb;
        color: #64748b;
        font-size: 12px;
        font-weight: 700;
        background: #f8fafc;
        letter-spacing: .04em;
        text-transform: uppercase;
    }

    .ft-page-category__table > tbody > tr > td {
        padding: 16px;
        border-top: 1px solid #edf2f7;
        vertical-align: top;
    }

    .ft-page-category__code {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 999px;
        background: #eff6ff;
        color: #1d4ed8;
        font-family: Consolas, Monaco, monospace;
        font-size: 12px;
        max-width: 100%;
        overflow-wrap: anywhere;
    }

    .ft-page-category__meta {
        margin-top: 8px;
        color: #64748b;
        line-height: 1.8;
        overflow-wrap: anywhere;
    }

    .ft-page-category__badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        background: #f1f5f9;
        color: #334155;
    }

    .ft-page-category__badge--success {
        background: #dcfce7;
        color: #15803d;
    }

    .ft-page-category__badge--danger {
        background: #fee2e2;
        color: #b91c1c;
    }

    .ft-page-category__actions .btn {
        border-radius: 999px;
        font-weight: 600;
    }

    .ft-page-category__empty {
        padding: 36px 20px !important;
        text-align: center;
        color: #94a3b8;
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

                <div class="ft-page-category">
                    <div class="alert alert-info alert-styled-left ft-page-category__note">
                        <span>页面分类适合做频道分组、导航分层、专题归档。页面列表里绑定分类后，后面做导航拖拽和批量筛选会更顺手。</span>
                    </div>

                    <div class="ft-page-category__panel">
                        <div class="ft-page-category__toolbar">
                            <div>
                                <h3 class="ft-page-category__title">页面分类</h3>
                                <p class="ft-page-category__desc">先把页面归到分类下面，后面做导航树、频道输出和批量模板配置都会更稳。</p>
                            </div>
                            <div>
                                <a href="{{url('admin/formtools/pageCategoryAdd')}}" class="btn btn-info btn-sm">新增分类</a>
                                <a href="{{url('admin/formtools/pageList')}}" class="btn btn-default btn-sm">返回页面列表</a>
                            </div>
                        </div>

                        <div class="table-responsive ft-page-category__table-wrap">
                            <table class="table ft-page-category__table m-b-none">
                                <thead>
                                <tr>
                                    <th>分类信息</th>
                                    <th>状态与排序</th>
                                    <th>已关联页面</th>
                                    <th>时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($pageData['datas'] as $category)
                                    <tr>
                                        <td>
                                            <div><strong>{{$category->name}}</strong></div>
                                            <div class="ft-page-category__meta">分类标识 <span class="ft-page-category__code">{{$category->identification}}</span></div>
                                            @if($category->remark)
                                                <div class="ft-page-category__meta">备注：{{$category->remark}}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <div><span class="ft-page-category__badge {{$category->status ? 'ft-page-category__badge--success' : 'ft-page-category__badge--danger'}}">{{$category->status ? '已启用' : '已停用'}}</span></div>
                                            <div class="ft-page-category__meta">排序：{{$category->sort}}</div>
                                        </td>
                                        <td>
                                            <div class="ft-page-category__meta">{{(int) ($pageCountMap[$category->id] ?? 0)}} 个页面</div>
                                        </td>
                                        <td>
                                            <div class="ft-page-category__meta">创建时间：{{$category->created_at ?: '-'}}</div>
                                            <div class="ft-page-category__meta">更新时间：{{$category->updated_at ?: '-'}}</div>
                                        </td>
                                        <td class="ft-page-category__actions">
                                            <a href="{{url('admin/formtools/pageCategoryEdit?id='.$category->id)}}" class="btn btn-success btn-xs">编辑</a>
                                            <a href="#" onclick="del('{{url('admin/formtools/pageCategoryDelete?id='.$category->id)}}');return false;" class="btn btn-danger btn-xs">删除</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="ft-page-category__empty">暂无页面分类，先新增一个分组。</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 text-right text-center-xs" style="margin-top: 18px;">
                    {{ $pageData['datas']->appends($_GET?:[])->links($moduleViewName.'::admin.public.pagination',["data"=>$pageData['datas']]) }}
                </div>

                @include(moduleAdminTemplate($moduleName)."public.footer")
            </div>
        </div>
    </div>
</div>
</body>
