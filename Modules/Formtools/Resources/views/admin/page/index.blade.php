@php($moduleName = $pageData['moduleName'] ?? 'Formtools')
@php($moduleViewName = strtolower($moduleName))
@include(moduleAdminTemplate($moduleName)."public.header")
<style>
    .page-content > .content-wrapper {
        min-width: 0;
    }

    .ft-page-index {
        display: grid;
        gap: 18px;
        min-width: 0;
    }

    .ft-page-note {
        margin-bottom: 0;
        border-radius: 16px;
        border: 1px solid #dbeafe;
        background: linear-gradient(135deg, #eff6ff 0%, #f8fbff 100%);
        color: #1e3a8a;
    }

    .ft-page-overview {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        min-width: 0;
    }

    .ft-page-stat {
        padding: 18px 20px;
        border-radius: 18px;
        background: #fff;
        border: 1px solid #e5e7eb;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
    }

    .ft-page-stat__label {
        margin: 0;
        color: #64748b;
        font-size: 13px;
    }

    .ft-page-stat__value {
        margin: 10px 0 8px;
        font-size: 30px;
        font-weight: 700;
        color: #0f172a;
    }

    .ft-page-stat__desc {
        color: #94a3b8;
        line-height: 1.7;
        font-size: 12px;
    }

    .ft-page-panel {
        overflow: hidden;
        border-radius: 20px;
        background: #fff;
        border: 1px solid #e5e7eb;
        box-shadow: 0 16px 34px rgba(15, 23, 42, 0.08);
        min-width: 0;
        max-width: 100%;
    }

    .ft-page-toolbar {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        padding: 24px 24px 18px;
        border-bottom: 1px solid #eef2f7;
    }

    .ft-page-toolbar__title {
        margin: 0 0 8px;
        color: #0f172a;
        font-size: 20px;
        font-weight: 700;
    }

    .ft-page-toolbar__desc {
        margin: 0;
        color: #64748b;
        line-height: 1.8;
    }

    .ft-page-toolbar__actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .ft-page-table-wrap {
        padding: 0 22px 18px;
        min-width: 0;
        max-width: 100%;
        overflow-x: auto;
        overflow-y: visible;
    }

    .ft-page-table {
        margin-bottom: 0;
        min-width: 1080px;
    }

    .ft-page-table > thead > tr > th {
        padding: 14px 16px;
        border-bottom: 1px solid #e5e7eb;
        color: #64748b;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
        background: #f8fafc;
    }

    .ft-page-table > tbody > tr > td {
        padding: 18px 16px;
        border-top: 1px solid #edf2f7;
        vertical-align: top;
    }

    .ft-page-table > tbody > tr:hover {
        background: #f8fbff;
    }

    .ft-page-main__title {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
        overflow-wrap: anywhere;
    }

    .ft-page-meta {
        margin-top: 8px;
        color: #64748b;
        line-height: 1.8;
        overflow-wrap: anywhere;
    }

    .ft-page-code {
        display: inline-block;
        margin-left: 6px;
        padding: 2px 8px;
        border-radius: 999px;
        background: #eff6ff;
        color: #1d4ed8;
        font-family: Consolas, Monaco, monospace;
        font-size: 12px;
        max-width: 100%;
        overflow-wrap: anywhere;
    }

    .ft-page-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 12px;
    }

    .ft-page-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        background: #f1f5f9;
        color: #334155;
    }

    .ft-page-badge--primary {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .ft-page-badge--success {
        background: #dcfce7;
        color: #15803d;
    }

    .ft-page-badge--warning {
        background: #fef3c7;
        color: #b45309;
    }

    .ft-page-badge--danger {
        background: #fee2e2;
        color: #b91c1c;
    }

    .ft-page-actions {
        display: grid;
        gap: 10px;
        min-width: 200px;
    }

    .ft-page-action-row {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
    }

    .ft-page-action-label {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 42px;
        padding: 4px 10px;
        border-radius: 999px;
        background: #f8fafc;
        color: #64748b;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
    }

    .ft-page-empty {
        padding: 36px 20px !important;
        text-align: center;
        color: #94a3b8;
    }

    .ft-page-pagination {
        margin-top: 18px;
    }

    .ft-page-actions .btn {
        border-radius: 999px;
        font-weight: 600;
    }

    @media (max-width: 991px) {
        .ft-page-toolbar {
            flex-direction: column;
        }
    }

    @media (max-width: 767px) {
        .ft-page-overview {
            grid-template-columns: 1fr;
        }

        .ft-page-table-wrap {
            padding: 0 14px 14px;
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

                <div class="ft-page-index">
                    <div class="alert alert-info alert-styled-left ft-page-note">
                        <span>页面管理负责“一个真实页面怎么编排、怎么挂模型、怎么带独立资源”。第一版先支持多页面、布局 JSON、HTML、CSS、JS，后续拖拽编辑器可以直接接到这里。</span>
                    </div>

                    <div class="ft-page-overview">
                        <div class="ft-page-stat">
                            <p class="ft-page-stat__label">页面总数</p>
                            <p class="ft-page-stat__value">{{$statistics['total']}}</p>
                            <div class="ft-page-stat__desc">已创建的页面数量，支持单页、列表页、自定义页和专题页。</div>
                        </div>
                        <div class="ft-page-stat">
                            <p class="ft-page-stat__label">启用页面</p>
                            <p class="ft-page-stat__value">{{$statistics['enabled']}}</p>
                            <div class="ft-page-stat__desc">当前允许前台访问的页面数量。</div>
                        </div>
                        <div class="ft-page-stat">
                            <p class="ft-page-stat__label">可视化模式</p>
                            <p class="ft-page-stat__value">{{$statistics['visual']}}</p>
                            <div class="ft-page-stat__desc">已采用布局 JSON 的页面，后续拖拽编辑器会直接写入这份结构。</div>
                        </div>
                        <div class="ft-page-stat">
                            <p class="ft-page-stat__label">绑定模型</p>
                            <p class="ft-page-stat__value">{{$statistics['bind_model']}}</p>
                            <div class="ft-page-stat__desc">已经和现有模型建立数据关系的页面数量。</div>
                        </div>
                        <div class="ft-page-stat">
                            <p class="ft-page-stat__label">页面分类</p>
                            <p class="ft-page-stat__value">{{$statistics['category']}}</p>
                            <div class="ft-page-stat__desc">用于频道分组、导航分层和专题归档的分类数量。</div>
                        </div>
                    </div>

                    <div class="ft-page-panel">
                        <div class="ft-page-toolbar">
                            <div>
                                <h3 class="ft-page-toolbar__title">页面列表</h3>
                                <p class="ft-page-toolbar__desc">每条记录就是一个实际页面。你可以给它独立路由、模板、布局 JSON、HTML 结构、CSS、JS，再决定是否绑定模型。</p>
                            </div>
                            <div class="ft-page-toolbar__actions">
                                <a href="{{url('admin/formtools/pageAdd')}}" class="btn btn-info btn-sm">新增页面</a>
                                <a href="{{url('admin/formtools/pageCategoryList')}}" class="btn btn-default btn-sm">页面分类</a>
                                <a href="{{url('admin/formtools/index')}}" class="btn btn-default btn-sm">返回模型列表</a>
                            </div>
                        </div>

                        <div class="table-responsive ft-page-table-wrap">
                            <table class="table ft-page-table m-b-none">
                                <thead>
                                <tr>
                                    <th>页面信息</th>
                                    <th>编排与资源</th>
                                    <th>绑定与状态</th>
                                    <th>时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($pageData['datas'] as $page)
                                    <tr>
                                        <td>
                                            <div class="ft-page-main">
                                                <h4 class="ft-page-main__title">
                                                    {{$page->name}}
                                                    <span class="ft-page-code">#{{$page->id}}</span>
                                                </h4>
                                                <div class="ft-page-meta">页面标识 <span class="ft-page-code">{{$page->identification}}</span></div>
                                                <div class="ft-page-meta">正式路径 <span class="ft-page-code">{{$page->getPublicPath() ?: '未设置'}}</span></div>
                                                <div class="ft-page-meta">后台预览 <span class="ft-page-code">{{$page->getPreviewUrl() ?: '保存后生成'}}</span></div>
                                                @if($page->remark)
                                                    <div class="ft-page-meta">备注：{{$page->remark}}</div>
                                                @endif
                                                <div class="ft-page-badges">
                                                    <span class="ft-page-badge ft-page-badge--primary">{{strtoupper($page->type)}}</span>
                                                    @if($page->status)
                                                        <span class="ft-page-badge ft-page-badge--success">已启用</span>
                                                    @else
                                                        <span class="ft-page-badge ft-page-badge--danger">已停用</span>
                                                    @endif
                                                    @if($page->is_home)
                                                        <span class="ft-page-badge ft-page-badge--warning">首页</span>
                                                    @endif
                                                    @if($page->is_nav)
                                                        <span class="ft-page-badge">导航可见</span>
                                                    @endif
                                                @if($page->category)
                                                    <span class="ft-page-badge">分类：{{$page->category->name}}</span>
                                                @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="ft-page-badges" style="margin-top: 0;">
                                                @if($page->builder_type === 'visual')
                                                    <span class="ft-page-badge ft-page-badge--warning">布局 JSON</span>
                                                @else
                                                    <span class="ft-page-badge ft-page-badge--primary">HTML 代码</span>
                                                @endif
                                                <span class="ft-page-badge">模板：{{$page->template ?: 'default'}}</span>
                                            </div>
                                            <div class="ft-page-meta">布局结构：{{trim((string) $page->layout_schema) !== '' ? '已配置' : '未配置'}}</div>
                                            <div class="ft-page-meta">HTML 结构：{{trim((string) $page->page_html) !== '' ? '已配置' : '未配置'}}</div>
                                            <div class="ft-page-meta">独立 CSS：{{trim((string) $page->custom_css) !== '' ? '已配置' : '未配置'}}，独立 JS：{{trim((string) $page->custom_js) !== '' ? '已配置' : '未配置'}}</div>
                                        </td>
                                        <td>
                                            <div class="ft-page-meta">绑定模型：{{$page->model->name ?? '未绑定'}}</div>
                                            <div class="ft-page-meta">排序：{{$page->sort}}</div>
                                            <div class="ft-page-meta">首页接管：{{$page->is_home ? '是' : '否'}}</div>
                                            <div class="ft-page-meta">SEO 标题：{{$page->seo_title ?: '未设置'}}</div>
                                        </td>
                                        <td>
                                            <div class="ft-page-meta">创建时间：{{$page->created_at ?: '-'}}</div>
                                            <div class="ft-page-meta">更新时间：{{$page->updated_at ?: '-'}}</div>
                                        </td>
                                        <td>
                                            <div class="ft-page-actions">
                                                @php($menuAddQuery = http_build_query(array_filter([
                                                    'm' => request()->query('m'),
                                                    'name' => $page->name,
                                                    'url' => $page->getPublicPath(),
                                                    'menu_type' => 'page',
                                                    'source_module' => 'Formtools',
                                                    'source_value' => 'page:' . $page->identification,
                                                ], function ($value) {
                                                    return !is_null($value) && $value !== '';
                                                })))
                                                <div class="ft-page-action-row">
                                                    <span class="ft-page-action-label">编辑</span>
                                                    <a href="{{url('admin/formtools/pageEdit?id='.$page->id)}}" class="btn btn-success btn-xs">页面配置</a>
                                                    <a href="{{url('admin/formtools/pageCopy?id='.$page->id)}}" class="btn btn-primary btn-xs">复制页面</a>
                                                    <a href="{{url('admin/theme/themeMenuAdd'.($menuAddQuery ? ('?' . $menuAddQuery) : ''))}}" class="btn btn-default btn-xs">加入菜单</a>
                                                </div>
                                                <div class="ft-page-action-row">
                                                    <span class="ft-page-action-label">首页</span>
                                                    @if($page->is_home)
                                                        <a href="{{url('admin/formtools/pageSetHome?id='.$page->id.'&is_home=0')}}" class="btn btn-warning btn-xs">取消首页</a>
                                                    @elseif($page->status)
                                                        <a href="{{url('admin/formtools/pageSetHome?id='.$page->id.'&is_home=1')}}" class="btn btn-warning btn-xs">设为首页</a>
                                                    @else
                                                        <span class="btn btn-default btn-xs disabled">停用页不可设首页</span>
                                                    @endif
                                                </div>
                                                <div class="ft-page-action-row">
                                                    <span class="ft-page-action-label">预览</span>
                                                    @if($page->getPreviewUrl())
                                                        <a href="{{$page->getPreviewUrl()}}" target="_blank" class="btn btn-info btn-xs">后台预览</a>
                                                    @endif
                                                    @if($page->getPublicUrl())
                                                        <a href="{{$page->getPublicUrl()}}" target="_blank" class="btn btn-default btn-xs">正式地址</a>
                                                    @endif
                                                </div>
                                                <div class="ft-page-action-row">
                                                    <span class="ft-page-action-label">风险</span>
                                                    <a href="#" onclick="del('{{url('admin/formtools/pageDelete?id='.$page->id)}}');return false;" class="btn btn-danger btn-xs">删除</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="ft-page-empty">暂无页面，先新增一张页面开始编排。</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 text-right text-center-xs ft-page-pagination">
                    {{ $pageData['datas']->appends($_GET?:[])->links($moduleViewName.'::admin.public.pagination',["data"=>$pageData['datas']]) }}
                </div>

                @include(moduleAdminTemplate($moduleName)."public.footer")
            </div>
        </div>
    </div>
</div>
</body>
