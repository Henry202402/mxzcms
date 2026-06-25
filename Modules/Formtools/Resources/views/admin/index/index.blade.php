@include(moduleAdminTemplate($moduleName)."public.header")
<style>
    .h-invitation-list {
        cursor: pointer;
    }

    .ft-model-index {
        display: grid;
        gap: 18px;
    }

    .ft-model-note {
        margin-bottom: 0;
        border-radius: 14px;
        border: 1px solid #dbeafe;
        background: linear-gradient(135deg, #f8fbff 0%, #eef5ff 100%);
        color: #1e3a8a;
        box-shadow: 0 10px 26px rgba(30, 64, 175, 0.08);
    }

    .ft-model-overview {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
    }

    .ft-home-status {
        padding: 18px 20px;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        background: linear-gradient(135deg, #ffffff 0%, #f8fbff 100%);
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
    }

    .ft-home-status--page {
        border-color: #bfdbfe;
        background: linear-gradient(135deg, #eff6ff 0%, #f8fbff 100%);
    }

    .ft-home-status--module {
        border-color: #fde68a;
        background: linear-gradient(135deg, #fffbeb 0%, #fffdf5 100%);
    }

    .ft-home-status--default {
        border-color: #e2e8f0;
        background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
    }

    .ft-home-status__top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .ft-home-status__label {
        margin: 0;
        color: #64748b;
        font-size: 12px;
        letter-spacing: .04em;
        text-transform: uppercase;
    }

    .ft-home-status__badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 999px;
        background: #dbeafe;
        color: #1d4ed8;
        font-size: 12px;
        font-weight: 700;
    }

    .ft-home-status--module .ft-home-status__badge {
        background: #fef3c7;
        color: #b45309;
    }

    .ft-home-status--default .ft-home-status__badge {
        background: #e2e8f0;
        color: #475569;
    }

    .ft-home-status__name {
        margin: 12px 0 8px;
        color: #0f172a;
        font-size: 24px;
        font-weight: 700;
    }

    .ft-home-status__summary,
    .ft-home-status__detail {
        color: #64748b;
        line-height: 1.8;
    }

    .ft-home-status__actions {
        margin-top: 12px;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .ft-model-stat {
        padding: 18px 20px;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 10px 28px rgba(15, 23, 42, 0.05);
    }

    .ft-model-stat__label {
        margin: 0 0 8px;
        font-size: 12px;
        color: #64748b;
        letter-spacing: .04em;
        text-transform: uppercase;
    }

    .ft-model-stat__value {
        margin: 0;
        font-size: 28px;
        line-height: 1;
        font-weight: 700;
        color: #0f172a;
    }

    .ft-model-stat__desc {
        margin-top: 8px;
        font-size: 13px;
        color: #94a3b8;
    }

    .ft-model-panel {
        overflow: hidden;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.06);
    }

    .ft-model-toolbar {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        padding: 20px 22px;
        border-bottom: 1px solid #edf2f7;
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
    }

    .ft-model-toolbar__title {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
    }

    .ft-model-toolbar__desc {
        margin: 6px 0 0;
        color: #64748b;
        line-height: 1.8;
    }

    .ft-model-toolbar__actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: flex-end;
    }

    .ft-model-toolbar__group {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 6px;
    }

    .ft-model-toolbar__group-label {
        font-size: 12px;
        color: #94a3b8;
        letter-spacing: .04em;
        text-transform: uppercase;
    }

    .ft-model-toolbar__group-row {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: flex-end;
    }

    .ft-model-toolbar__actions .btn {
        min-width: 118px;
        border-radius: 999px;
        font-weight: 600;
        box-shadow: none;
    }

    .ft-model-toolbar__actions .btn-group .btn {
        min-width: 0;
    }

    .ft-model-toolbar__dropdown {
        min-width: 240px;
        margin-top: 10px;
        padding: 8px;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        box-shadow: 0 18px 34px rgba(15, 23, 42, 0.12);
    }

    .ft-model-toolbar__dropdown > li > a {
        padding: 9px 12px;
        border-radius: 10px;
        color: #334155;
        line-height: 1.6;
    }

    .ft-model-toolbar__dropdown > li > a:hover {
        background: #f8fafc;
        color: #0f172a;
    }

    .ft-model-toolbar__dropdown-note {
        margin-top: 4px;
        color: #94a3b8;
        font-size: 12px;
        line-height: 1.6;
    }

    .ft-model-toolbar__warning {
        margin-top: 2px;
        color: #b45309;
        font-size: 12px;
        line-height: 1.6;
        text-align: right;
    }

    .ft-model-table-wrap {
        padding: 0 22px 18px;
    }

    .ft-model-table {
        margin-bottom: 0;
    }

    .ft-model-table > thead > tr > th {
        padding: 14px 16px;
        border-bottom: 1px solid #e5e7eb;
        color: #64748b;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
        background: #f8fafc;
    }

    .ft-model-table > tbody > tr > td {
        padding: 18px 16px;
        border-top: 1px solid #edf2f7;
        vertical-align: top;
    }

    .ft-model-table > tbody > tr:hover {
        background: #f8fbff;
    }

    .ft-model-main {
        min-width: 220px;
    }

    .ft-model-main__title {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
    }

    .ft-model-main__meta,
    .ft-model-config__item,
    .ft-model-menu__item {
        margin-top: 8px;
        color: #64748b;
        line-height: 1.8;
    }

    .ft-model-code {
        display: inline-block;
        margin-left: 6px;
        padding: 2px 8px;
        border-radius: 999px;
        background: #eff6ff;
        color: #1d4ed8;
        font-family: Consolas, Monaco, monospace;
        font-size: 12px;
    }

    .ft-model-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 10px;
    }

    .ft-model-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        background: #f1f5f9;
        color: #334155;
    }

    .ft-model-badge--primary {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .ft-model-badge--success {
        background: #dcfce7;
        color: #15803d;
    }

    .ft-model-badge--warning {
        background: #fef3c7;
        color: #b45309;
    }

    .ft-model-badge--danger {
        background: #fee2e2;
        color: #b91c1c;
    }

    .ft-model-actions {
        display: grid;
        gap: 10px;
        min-width: 250px;
    }

    .ft-model-action-group {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
    }

    .ft-model-action-label {
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

    .ft-model-action-label--danger {
        background: #fff1f2;
        color: #be123c;
    }

    .ft-model-actions .btn {
        border-radius: 999px;
        font-weight: 600;
    }

    .ft-model-empty {
        padding: 34px 20px !important;
        text-align: center;
        color: #94a3b8;
    }

    .ft-model-pagination {
        margin-top: 18px;
    }

    .ft-model-pagination .pagination {
        margin: 0;
    }

    .ft-model-modal-table > tbody > tr > td,
    .ft-model-modal-table > tbody > tr > th {
        vertical-align: middle;
    }

    @media (max-width: 1199px) {
        .ft-model-overview {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 991px) {
        .ft-model-toolbar {
            flex-direction: column;
        }

        .ft-model-toolbar__actions {
            justify-content: flex-start;
        }

        .ft-model-toolbar__group {
            align-items: flex-start;
        }

        .ft-model-toolbar__group-row {
            justify-content: flex-start;
        }

        .ft-model-toolbar__warning {
            text-align: left;
        }
    }

    @media (max-width: 767px) {
        .ft-model-overview {
            grid-template-columns: 1fr;
        }

        .ft-model-table-wrap {
            padding: 0 14px 14px;
        }

        .ft-model-toolbar {
            padding: 18px 14px;
        }

        .ft-model-actions {
            min-width: 0;
        }
    }
</style>
<body>

<!--                        Topbar End                              -->
<!-- ============================================================== -->


<!-- ============================================================== -->
<!-- 						Navigation Start 						-->
<!-- ============================================================== -->

@include(moduleAdminTemplate($moduleName)."public.nav")
<!-- ============================================================== -->
<!-- 						Navigation End	 						-->
<!-- ============================================================== -->

<!-- Page container -->
<div class="page-container">

    <!-- Page content -->
    <div class="page-content">

    @include(moduleAdminTemplate($moduleName)."public.left")


    <!-- Main content -->
        <div class="content-wrapper">


            <!-- Content area -->
            <div class="content" style="margin-top: 1rem;">
            @include(moduleAdminTemplate($moduleName)."public.crumb",['breadcrumb'=>[$pageData['title'],$pageData['subtitle']]])
            @php
                $modelRows = collect(method_exists($pageData['datas'], 'items') ? $pageData['datas']->items() : $pageData['datas']);
                $apiCount = $modelRows->filter(function ($item) {
                    $otherConfig = json_decode($item->other_config ?: '[]', true) ?: [];
                    return ($otherConfig['data_source'] ?? 'local') === 'api';
                })->count();
                $homeVisibleCount = $modelRows->where('show_home_page', 'yes')->count();
                $singleCount = $modelRows->where('type', 'single')->count();
                $totalModels = method_exists($pageData['datas'], 'total') ? $pageData['datas']->total() : $modelRows->count();
            @endphp

            <!-- Bordered striped table -->
                <div class="ft-model-index">
                    <div class="alert alert-info alert-styled-left ft-model-note">
                        <span>建议按“模型设置 -> 字段设置 -> 内容管理 -> 数据统计 -> 前台预览”的顺序使用，这样更容易把一个模型从创建一路配置到上线。</span>
                    </div>
                    <div class="ft-home-status ft-home-status--{{$homepageStatus['source']}}">
                        <div class="ft-home-status__top">
                            <p class="ft-home-status__label">当前首页来源</p>
                            <span class="ft-home-status__badge">{{$homepageStatus['title']}}</span>
                        </div>
                        <div class="ft-home-status__name">{{$homepageStatus['name']}}</div>
                        <div class="ft-home-status__summary">{{$homepageStatus['summary']}}</div>
                        <div class="ft-home-status__detail">{{$homepageStatus['detail']}}</div>
                        <div class="ft-home-status__actions">
                            @if($homepageStatus['manage_url'])
                                <a href="{{$homepageStatus['manage_url']}}" class="btn btn-info btn-sm">{{$homepageStatus['manage_text']}}</a>
                            @endif
                            @if($homepageStatus['cancel_url'])
                                <a href="{{$homepageStatus['cancel_url']}}" class="btn btn-warning btn-sm">{{$homepageStatus['cancel_text']}}</a>
                            @endif
                            <a href="{{url('admin/formtools/pageList')}}" class="btn btn-default btn-sm">页面管理</a>
                            <a href="{{$homepageStatus['module_url']}}" class="btn btn-default btn-sm">{{$homepageStatus['module_text']}}</a>
                            <a href="{{url('admin/formtools/index')}}" class="btn btn-default btn-sm">模型管理</a>
                        </div>
                    </div>
                    <div class="ft-model-overview">
                        <div class="ft-model-stat">
                            <p class="ft-model-stat__label">模型总数</p>
                            <p class="ft-model-stat__value">{{$totalModels}}</p>
                            <div class="ft-model-stat__desc">当前系统已接入的模型数量</div>
                        </div>
                        <div class="ft-model-stat">
                            <p class="ft-model-stat__label">API 数据源</p>
                            <p class="ft-model-stat__value">{{$apiCount}}</p>
                            <div class="ft-model-stat__desc">本页已配置第三方接口的模型</div>
                        </div>
                        <div class="ft-model-stat">
                            <p class="ft-model-stat__label">首页展示</p>
                            <p class="ft-model-stat__value">{{$homeVisibleCount}}</p>
                            <div class="ft-model-stat__desc">本页开启首页区块展示的模型</div>
                        </div>
                        <div class="ft-model-stat">
                            <p class="ft-model-stat__label">单页模型</p>
                            <p class="ft-model-stat__value">{{$singleCount}}</p>
                            <div class="ft-model-stat__desc">适合协议、介绍、说明类内容</div>
                        </div>
                    </div>
                    <div class="ft-model-panel">
                        <div class="ft-model-toolbar">
                            <div>
                                <h3 class="ft-model-toolbar__title">模型列表</h3>
                                <p class="ft-model-toolbar__desc">这里集中展示模型名称、模板、数据来源和常用入口，查找和操作都会更顺手。</p>
                                <div class="ft-model-toolbar__dropdown-note">常用功能放在上面，初始化和演示相关功能放在“更多操作”，需要谨慎使用的功能单独标红。</div>
                            </div>
                            <div class="ft-model-toolbar__actions">
                                <div class="ft-model-toolbar__group">
                                    <span class="ft-model-toolbar__group-label">常用操作</span>
                                    <div class="ft-model-toolbar__group-row">
                                        <a href="{{url("admin/formtools/modelAdd")}}" class="btn btn-info btn-sm">新增模型</a>
                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal_iconified">导入数据表结构</button>
                                    </div>
                                </div>
                                <div class="ft-model-toolbar__group">
                                    <span class="ft-model-toolbar__group-label">更多操作</span>
                                    <div class="ft-model-toolbar__group-row">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                更多操作 <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right ft-model-toolbar__dropdown">
                                                <li>
                                                    <a href="#" onclick="synmodel('{{url('admin/formtools/synmodel')}}');return false;">
                                                        恢复默认模型配置
                                                        <div class="ft-model-toolbar__dropdown-note">恢复系统自带的基础模型，适合首次搭建或需要重新整理模型时使用。</div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#" onclick="seedDemoContent('{{url('admin/formtools/seedDemoContent')}}');return false;">
                                                        灌入演示内容
                                                        <div class="ft-model-toolbar__dropdown-note">补充一批示例内容，方便先预览页面效果，再替换成正式内容。</div>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="ft-model-toolbar__group">
                                    <span class="ft-model-toolbar__group-label">危险操作</span>
                                    <div class="ft-model-toolbar__group-row">
                                        <a href="#" onclick="resetModelData('{{url('admin/formtools/resetModelData')}}');return false;" class="btn btn-danger btn-sm">重建模型结构与演示数据</a>
                                    </div>
                                    <div class="ft-model-toolbar__warning">会重新整理模型和示例内容，适合测试站或初始化阶段使用，正式站点操作前请先确认。</div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive ft-model-table-wrap">
                        <table class="table ft-model-table m-b-none">
                            <thead>
                            <tr>
                                <th>模型信息</th>
                                <th>前台模板</th>
                                <th>菜单与首页</th>
                                <th>时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>

                            @forelse($pageData['datas'] as $d)
                                @php($homeConfig = \Modules\Formtools\Support\FormTemplateResolver::normalizeHomeConfig(json_decode($d->home_config ?: '[]', true) ?: []))
                                @php($otherConfig = json_decode($d->other_config ?: '[]', true) ?: [])
                                <tr>
                                    <td>
                                        <div class="ft-model-main">
                                            <h4 class="ft-model-main__title">
                                                {{$d->name}}
                                                <span class="ft-model-code">#{{$d->id}}</span>
                                            </h4>
                                            <div class="ft-model-main__meta">
                                                模型标识 <span class="ft-model-code">{{$d->identification}}</span>
                                            </div>
                                            <div class="ft-model-main__meta">
                                                访问标识 <span class="ft-model-code">{{$d->access_identification ?: '未配置'}}</span>
                                            </div>
                                            <div class="ft-model-badges" style="margin-top: 12px;">
                                                @if(($otherConfig['data_source'] ?? 'local') === 'api')
                                                    <span class="ft-model-badge ft-model-badge--warning">API 数据源</span>
                                                @else
                                                    <span class="ft-model-badge ft-model-badge--success">本地数据源</span>
                                                @endif
                                                @if($d->type=="single")
                                                    <span class="ft-model-badge ft-model-badge--primary">单页模型</span>
                                                @else
                                                    <span class="ft-model-badge ft-model-badge--primary">多页模型</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="ft-model-badges">
                                            <span class="ft-model-badge ft-model-badge--primary">列表：{{$homeConfig['list_template'] ?: 'list'}}</span>
                                            <span class="ft-model-badge">详情：{{$homeConfig['detail_template'] ?: 'detail'}}</span>
                                        </div>
                                        <div class="ft-model-config__item">搜索模板：{{$homeConfig['search_template'] ?: '默认'}}</div>
                                        <div class="ft-model-config__item">分页位置：{{$homeConfig['list_page_template'] ?: 'center'}}</div>
                                    </td>
                                    <td>
                                        <div class="ft-model-menu__item">一级菜单：{{$d->menuname ?: '未配置'}}</div>
                                        <div class="ft-model-menu__item">菜单图标：{{$d->icon ?: '未配置'}}</div>
                                        <div class="ft-model-badges" style="margin-top: 12px;">
                                            @if($d->show_home_page=="yes")
                                                <span class="ft-model-badge ft-model-badge--success">首页显示</span>
                                            @else
                                                <span class="ft-model-badge ft-model-badge--danger">首页隐藏</span>
                                            @endif
                                            <span class="ft-model-badge">排序：{{$d->home_page_sort ?: 0}}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="ft-model-menu__item">创建时间：{{$d->created_at}}</div>
                                        @if(!empty($d->updated_at))
                                            <div class="ft-model-menu__item">更新时间：{{$d->updated_at}}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="ft-model-actions">
                                            <div class="ft-model-action-group">
                                                <span class="ft-model-action-label">配置</span>
                                                <a href="{{url("admin/formtools/modelEdit?id=".$d->id)}}" class="btn btn-success btn-xs">模型配置</a>
                                                <a href="{{url("admin/formtools/fieldList?id=".$d->id)}}" class="btn btn-info btn-xs">字段管理</a>
                                            </div>
                                            <div class="ft-model-action-group">
                                                <span class="ft-model-action-label">运营</span>
                                                <a href="{{url("admin/formtools/model?moduleName={$moduleName}&action=List&model=".$d->identification)}}" class="btn btn-primary btn-xs">内容管理</a>
                                                <a href="{{url("admin/formtools/modelStatistics?id=".$d->id)}}" class="btn btn-default btn-xs">数据统计</a>
                                                @if($d->access_identification)
                                                    <a href="{{url("list/".$d->access_identification)}}" target="_blank" class="btn btn-default btn-xs">前台预览</a>
                                                @endif
                                            </div>
                                            <div class="ft-model-action-group">
                                                <span class="ft-model-action-label ft-model-action-label--danger">风险</span>
                                                <a onclick="del('{{url("admin/formtools/modelDelete?id=".$d->id)}}')" class="btn btn-danger btn-xs">删除</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="ft-model-empty">
                                        暂无数据
                                    </td>
                                </tr>
                            @endforelse


                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
                <!-- /bordered striped table -->

                <div class="col-sm-12 text-right text-center-xs ft-model-pagination">
                    {{ $pageData['datas']->appends($_GET?:[])->links($moduleName.'::admin.public.pagination',["data"=>$pageData['datas']]) }}
                </div>


                @include(moduleAdminTemplate($moduleName)."public.footer")


            </div>
            <!-- /content area -->


        </div>
        <!-- /page content -->

    </div>
    <!-- /page container -->

    <div id="modal_iconified" class="modal fade">
        <form action="" id="modal_iconified_form">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h5 class="modal-title">从数据表获取模型结构</h5>
                    </div>

                    <div class="modal-body">
                        <table class="table ft-model-modal-table">
                            <tr>
                                <th>表备注</th>
                                <th>表名</th>
                                <th>选中</th>
                            </tr>
                            @foreach($tablesList as $table)
                                <tr>
                                    <td>{{$table['comment']}}</td>
                                    <td>{{$table['name']}}</td>
                                    <td><input name="tablename" value="{{$table['name']}}" type="checkbox"/></td>
                                </tr>
                            @endforeach

                        </table>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal"><i class="icon-cross"></i> 取消</button>
                        <button type="button" onclick="GetModels()" class="btn btn-primary"><i class="icon-check"></i> 确定</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- 						Content End		 						-->
    <!-- ============================================================== -->
    @include(moduleAdminTemplate($moduleName)."public.js")
    {{--<script type="text/javascript" src="{{asset("assets/module")}}/js/pages/dashboard.js"></script>--}}
    <script>
        function del(url) {
            layer.confirm('确定要删除吗？', {
                title: "操作提示",
                btn: ['确定', '取消'] //可以无限个按钮
            }, function (index, layero) {
                //按钮【按钮一】的回调
                window.location.href = url;
            }, function (index) {
                //按钮【按钮二】的回调
            });
        }

        function synmodel(url) {
            layer.confirm('确定要恢复默认模型配置吗？当前模型配置会被默认模型覆盖。', {
                title: "操作提示",
                btn: ['确定', '取消'] //可以无限个按钮
            }, function (index, layero) {
                //按钮【按钮一】的回调
                window.location.href = url;
            }, function (index) {
                //按钮【按钮二】的回调
            });
        }

        function seedDemoContent(url) {
            layer.confirm('确定要灌入演示内容吗？已有内容表中存在数据时，Seeder 会尽量跳过已有内容。', {
                title: "操作提示",
                btn: ['确定', '取消']
            }, function () {
                window.location.href = url;
            }, function () {
            });
        }

        function resetModelData(url) {
            layer.confirm('确定要重建模型结构与演示数据吗？这会重新执行安装迁移，并恢复默认模型与演示内容。', {
                title: "操作提示",
                btn: ['确定', '取消'] //可以无限个按钮
            }, function (index, layero) {
                //按钮【按钮一】的回调
                window.location.href = url;
            }, function (index) {
                //按钮【按钮二】的回调
            });
        }

        function GetModels() {
            var selectedValues =[];
            var tableList = $("input[name='tablename']:checked");
            tableList.each(function() {
                selectedValues.push($(this).val());
            });

            if(selectedValues.length==0){
                layer.msg('请选中数据表！');
                return;
            }
            layer.load(2,{shade: [0.5, '#000']})
            $.ajax({
                type: 'POST',
                url: "{{url('admin/formtools/getModel')}}",
                data: {'table':selectedValues,"_token":'{{csrf_token()}}'},
                dataType:"json",
                success: function(data){
                    layer.closeAll('loading');
                    layer.msg(data.msg);
                    if(data.status==200){
                        setTimeout(function (){
                            location.reload()
                        },2000)
                    }
                },
                timeout: 600,
                error: function(jqXHR,textStatus){
                    layer.closeAll('loading');
                    layer.msg('请求失败，请稍后重试!');
                }
            })
        }
    </script>
</body>
</html>
