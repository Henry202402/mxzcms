@include(moduleAdminTemplate($moduleName)."public.header")
<style>
    .h-invitation-list {
        cursor: pointer;
    }

    .ft-field-index {
        display: grid;
        gap: 18px;
    }

    .ft-field-note {
        margin-bottom: 0;
        border-radius: 14px;
        border: 1px solid #dbeafe;
        background: linear-gradient(135deg, #f8fbff 0%, #eef5ff 100%);
        color: #1e3a8a;
        box-shadow: 0 10px 26px rgba(30, 64, 175, 0.08);
    }

    .ft-field-overview {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
    }

    .ft-field-stat {
        padding: 18px 20px;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 10px 28px rgba(15, 23, 42, 0.05);
    }

    .ft-field-stat__label {
        margin: 0 0 8px;
        font-size: 12px;
        color: #64748b;
        letter-spacing: .04em;
        text-transform: uppercase;
    }

    .ft-field-stat__value {
        margin: 0;
        font-size: 28px;
        line-height: 1;
        font-weight: 700;
        color: #0f172a;
    }

    .ft-field-stat__desc {
        margin-top: 8px;
        font-size: 13px;
        color: #94a3b8;
    }

    .ft-field-panel {
        overflow: hidden;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.06);
    }

    .ft-field-toolbar {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        padding: 20px 22px;
        border-bottom: 1px solid #edf2f7;
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
    }

    .ft-field-toolbar__title {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
    }

    .ft-field-toolbar__desc {
        margin: 6px 0 0;
        color: #64748b;
        line-height: 1.8;
    }

    .ft-field-toolbar__sub {
        margin-top: 8px;
        color: #94a3b8;
        line-height: 1.8;
    }

    .ft-field-toolbar__actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: flex-end;
    }

    .ft-field-toolbar__actions .btn,
    .ft-field-toolbar__actions .label {
        border-radius: 999px;
        font-weight: 600;
        min-width: 110px;
        text-align: center;
        box-shadow: none;
    }

    .ft-field-table-wrap {
        padding: 0 22px 18px;
    }

    .ft-field-table {
        margin-bottom: 0;
    }

    .ft-field-table > thead > tr > th {
        padding: 14px 16px;
        border-bottom: 1px solid #e5e7eb;
        color: #64748b;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
        background: #f8fafc;
    }

    .ft-field-table > tbody > tr > td {
        padding: 18px 16px;
        border-top: 1px solid #edf2f7;
        vertical-align: top;
    }

    .ft-field-table > tbody > tr:hover {
        background: #f8fbff;
    }

    .ft-field-main__title {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
    }

    .ft-field-meta,
    .ft-field-logic__item,
    .ft-field-db__item {
        margin-top: 8px;
        color: #64748b;
        line-height: 1.8;
    }

    .ft-field-code {
        display: inline-block;
        margin-left: 6px;
        padding: 2px 8px;
        border-radius: 999px;
        background: #eff6ff;
        color: #1d4ed8;
        font-family: Consolas, Monaco, monospace;
        font-size: 12px;
    }

    .ft-field-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 12px;
    }

    .ft-field-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        background: #f1f5f9;
        color: #334155;
    }

    .ft-field-badge--success {
        background: #dcfce7;
        color: #15803d;
    }

    .ft-field-badge--danger {
        background: #fee2e2;
        color: #b91c1c;
    }

    .ft-field-badge--primary {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .ft-field-switches {
        display: grid;
        gap: 8px;
    }

    .ft-field-switch {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 10px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: #fff;
    }

    .ft-field-switch__name {
        color: #475569;
        line-height: 1.6;
    }

    .ft-field-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        min-width: 220px;
    }

    .ft-field-actions .btn {
        border-radius: 999px;
        font-weight: 600;
    }

    .ft-field-empty {
        padding: 34px 20px !important;
        text-align: center;
        color: #94a3b8;
    }

    @media (max-width: 1199px) {
        .ft-field-overview {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 991px) {
        .ft-field-toolbar {
            flex-direction: column;
        }

        .ft-field-toolbar__actions {
            justify-content: flex-start;
        }
    }

    @media (max-width: 767px) {
        .ft-field-overview {
            grid-template-columns: 1fr;
        }

        .ft-field-table-wrap {
            padding: 0 14px 14px;
        }

        .ft-field-toolbar {
            padding: 18px 14px;
        }

        .ft-field-actions {
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
                $configuredFieldCount = count($configuredFields ?? []);
                $tableFieldCount = count($tableColumns ?? []);
                $frontendFormCount = collect($configuredFields ?? [])->where('is_show_home_form', '1')->count();
                $frontendSearchCount = collect($configuredFields ?? [])->where('is_show_home_list_search', '1')->count();
                $downloadFieldCount = collect($configuredFields ?? [])->filter(function ($field) {
                    return in_array((string) ($field['formtype'] ?? ''), ['upload', 'uploadAjax', 'file'], true);
                })->count();
            @endphp

            <!-- Bordered striped table -->
                <div class="ft-field-index">
                    <div class="alert alert-info alert-styled-left ft-field-note">
                        <span>在这里可以看清字段是否会显示在前台表单、搜索和详情页里；如果是附件字段，还需要开启 <strong>前台详情</strong> 并上传文件，前台才会出现下载入口。</span>
                    </div>
                    <div class="ft-field-overview">
                        <div class="ft-field-stat">
                            <p class="ft-field-stat__label">配置字段数</p>
                            <p class="ft-field-stat__value">{{$configuredFieldCount}}</p>
                            <div class="ft-field-stat__desc">当前模型 JSON 配置中的字段总数</div>
                        </div>
                        <div class="ft-field-stat">
                            <p class="ft-field-stat__label">数据表字段数</p>
                            <p class="ft-field-stat__value">{{$tableFieldCount}}</p>
                            <div class="ft-field-stat__desc">{{$tableName}} 当前实际存在的业务字段</div>
                        </div>
                        <div class="ft-field-stat">
                            <p class="ft-field-stat__label">前台表单字段</p>
                            <p class="ft-field-stat__value">{{$frontendFormCount}}</p>
                            <div class="ft-field-stat__desc">会参与前台提交表单渲染的字段数量</div>
                        </div>
                        <div class="ft-field-stat">
                            <p class="ft-field-stat__label">前台搜索字段</p>
                            <p class="ft-field-stat__value">{{$frontendSearchCount}}</p>
                            <div class="ft-field-stat__desc">会参与前台列表搜索筛选的字段数量</div>
                        </div>
                        <div class="ft-field-stat">
                            <p class="ft-field-stat__label">附件字段</p>
                            <p class="ft-field-stat__value">{{$downloadFieldCount}}</p>
                            <div class="ft-field-stat__desc">使用 upload / uploadAjax / file 的字段数量</div>
                        </div>
                    </div>
                    <div class="ft-field-panel">
                        <div class="ft-field-toolbar">
                            <div>
                                <h3 class="ft-field-toolbar__title">{{$pageData['data']->name}} 字段管理</h3>
                                <p class="ft-field-toolbar__desc">字段配置和真实数据表一起看，便于确认字段是否已经落库、前后台是否参与展示，以及关联模型是否配置完整。</p>
                                <div class="ft-field-toolbar__sub">
                                    当前数据表：<span class="ft-field-code">{{$tableName}}</span>
                                    当前模型标识：<span class="ft-field-code">{{$pageData['data']->identification}}</span>
                                </div>
                            </div>
                            <div class="ft-field-toolbar__actions">
                                <a class="btn btn-info btn-sm" href="{{url("admin/formtools/fieldAdd?id=".$pageData['id'])}}">新增字段</a>
                                <a class="btn btn-success btn-sm" href="{{url("admin/formtools/modelEdit?id=".$pageData['id'])}}">模型配置</a>
                                <a class="btn btn-primary btn-sm" href="{{url("admin/formtools/model?moduleName={$moduleName}&action=List&model=".$pageData['data']->identification)}}">内容管理</a>
                                @if($pageData['data']->access_identification)
                                    <a class="btn btn-default btn-sm" target="_blank" href="{{url("list/".$pageData['data']->access_identification)}}">前台预览</a>
                                @endif
                            </div>
                        </div>
                        <div class="table-responsive ft-field-table-wrap">
                        <table class="table ft-field-table m-b-none">
                            <thead>
                            <tr>
                                <th>字段信息</th>
                                <th>数据表对应</th>
                                <th>展示与搜索</th>
                                <th>关联与规则</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>

                            @forelse($colunmListDetaill as $key=>$d)
                                <tr>
                                    <td>
                                        <div class="ft-field-main">
                                            <h4 class="ft-field-main__title">
                                                {{$d['remark'] ?: $d['name']}}
                                                <span class="ft-field-code">#{{$key + 1}}</span>
                                            </h4>
                                            <div class="ft-field-meta">
                                                字段名称 <span class="ft-field-code">{{$d['name'] ?: '未配置'}}</span>
                                            </div>
                                            <div class="ft-field-meta">
                                                字段标识 <span class="ft-field-code">{{$d['identification']}}</span>
                                            </div>
                                            <div class="ft-field-badges">
                                                @if(($d['required'] ?? '') === 'required')
                                                    <span class="ft-field-badge ft-field-badge--danger">必填</span>
                                                @else
                                                    <span class="ft-field-badge">非必填</span>
                                                @endif
                                                <span class="ft-field-badge ft-field-badge--primary">表单：{{$d['formtype'] ?: 'text'}}</span>
                                                @if(!empty($d['is_download_field']))
                                                    <span class="ft-field-badge ft-field-badge--success">附件字段</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="ft-field-db__item">
                                            数据表字段 <span class="ft-field-code">{{$tableName}}.{{$d['identification']}}</span>
                                        </div>
                                        <div class="ft-field-db__item">
                                            字段类型：<span class="ft-field-code">{{$d['fieldtype'] ?: 'string'}}</span>
                                        </div>
                                        <div class="ft-field-db__item">
                                            最大长度：<span class="ft-field-code">{{(string) ($d['maxlength'] ?? '0') !== '' ? $d['maxlength'] : '0'}}</span>
                                        </div>
                                        <div class="ft-field-badges">
                                            @if(!empty($d['column_exists']))
                                                <span class="ft-field-badge ft-field-badge--success">已落库</span>
                                            @else
                                                <span class="ft-field-badge ft-field-badge--danger">未落库</span>
                                            @endif
                                            <span class="ft-field-badge">索引：{{$d['isindex'] ?: 'NOINDEX'}}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="ft-field-switches">
                                            <div class="ft-field-switch">
                                                <span class="ft-field-switch__name">后台列表</span>
                                                @if(($d['is_show_list'] ?? '2') == 1)
                                                    <span class="ft-field-badge ft-field-badge--success">开启</span>
                                                @else
                                                    <span class="ft-field-badge ft-field-badge--danger">关闭</span>
                                                @endif
                                            </div>
                                            <div class="ft-field-switch">
                                                <span class="ft-field-switch__name">后台搜索</span>
                                                @if(($d['is_show_admin_list_search'] ?? '2') == 1)
                                                    <span class="ft-field-badge ft-field-badge--success">开启</span>
                                                @else
                                                    <span class="ft-field-badge ft-field-badge--danger">关闭</span>
                                                @endif
                                            </div>
                                            <div class="ft-field-switch">
                                                <span class="ft-field-switch__name">前台表单</span>
                                                @if(($d['is_show_home_form'] ?? '2') == 1)
                                                    <span class="ft-field-badge ft-field-badge--success">开启</span>
                                                @else
                                                    <span class="ft-field-badge ft-field-badge--danger">关闭</span>
                                                @endif
                                            </div>
                                            <div class="ft-field-switch">
                                                <span class="ft-field-switch__name">前台列表</span>
                                                @if(($d['is_show_home_list'] ?? '1') == 1)
                                                    <span class="ft-field-badge ft-field-badge--success">开启</span>
                                                @else
                                                    <span class="ft-field-badge ft-field-badge--danger">关闭</span>
                                                @endif
                                            </div>
                                            <div class="ft-field-switch">
                                                <span class="ft-field-switch__name">前台详情</span>
                                                @if(($d['is_show_home_detail'] ?? '1') == 1)
                                                    <span class="ft-field-badge ft-field-badge--success">开启</span>
                                                @else
                                                    <span class="ft-field-badge ft-field-badge--danger">关闭</span>
                                                @endif
                                            </div>
                                            @if(!empty($d['is_download_field']))
                                                <div class="ft-field-switch">
                                                    <span class="ft-field-switch__name">下载入口</span>
                                                    @if(!empty($d['download_ready']))
                                                        <span class="ft-field-badge ft-field-badge--success">满足展示条件</span>
                                                    @else
                                                        <span class="ft-field-badge ft-field-badge--danger">需开启前台详情</span>
                                                    @endif
                                                </div>
                                            @endif
                                            <div class="ft-field-switch">
                                                <span class="ft-field-switch__name">前台搜索</span>
                                                @if(($d['is_show_home_list_search'] ?? '2') == 1)
                                                    <span class="ft-field-badge ft-field-badge--success">开启</span>
                                                @else
                                                    <span class="ft-field-badge ft-field-badge--danger">关闭</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="ft-field-logic__item">字段规则：<span class="ft-field-code">{{$d['rule'] ?: 'unlimited'}}</span></div>
                                        @if(!empty($d['is_download_field']))
                                            <div class="ft-field-logic__item">下载说明：<span class="ft-field-code">前台详情开启 + 内容有文件值</span></div>
                                        @endif
                                        <div class="ft-field-logic__item">关联模型：<span class="ft-field-code">{{$d['foreign'] ?: '无'}}</span></div>
                                        <div class="ft-field-logic__item">关联字段：<span class="ft-field-code">{{$d['foreign_key'] ?: '无'}}</span></div>
                                    </td>
                                    <td>
                                        <div class="ft-field-actions">
                                            @if($key>0)
                                                <a href="{{url("admin/formtools/fieldMove?move_type=1&id=".$pageData['id']."&identification=".$d['identification'])}}"
                                                   class="btn btn-primary btn-xs">
                                                    上移
                                                </a>
                                            @endif
                                            @if($key!=(count($colunmListDetaill)-1))
                                                <a href="{{url("admin/formtools/fieldMove?move_type=2&id=".$pageData['id']."&identification=".$d['identification'])}}"
                                                   class="btn btn-info btn-xs">
                                                    下移
                                                </a>
                                            @endif
                                            <a href="{{url("admin/formtools/fieldEdit?&id=".$pageData['id']."&identification=".$d['identification'])}}"
                                               class="btn btn-success btn-xs">
                                                编辑
                                            </a>
                                            <a onclick="delField('{{url("admin/formtools/fieldDel?id=".$pageData['id']."&identification=".$d['identification'])}}','{{ addslashes($d['remark'] ?: $d['name'] ?: $d['identification']) }}','{{ $d['identification'] }}')"
                                               class="btn btn-danger btn-xs ">
                                                删除
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="ft-field-empty">
                                        暂无数据
                                    </td>
                                </tr>
                            @endforelse


                            </tbody>
                        </table>
                        </div>
                        {{--<div class="col-sm-12 text-right text-center-xs">
                            @if(count($data)>0)
                                {{ $pageData['data']->links() }}
                            @endif
                        </div>--}}
                    </div>
                </div>
                <!-- /bordered striped table -->


                @include(moduleAdminTemplate($moduleName)."public.footer")


            </div>
            <!-- /content area -->


        </div>
        <!-- /page content -->

    </div>
    <!-- /page container -->

    <!-- 						Content End		 						-->
    <!-- ============================================================== -->
    @include(moduleAdminTemplate($moduleName)."public.js")
    {{--<script type="text/javascript" src="{{asset("assets/module")}}/js/pages/dashboard.js"></script>--}}
    <script>
        function delField(url, title, identification) {
            layer.confirm('确定要删除字段“' + title + '”吗？这会同时移除字段配置和真实数据表列 `' + identification + '`。', {
                title: "操作提示",
                btn: ['确定', '取消'] //可以无限个按钮
            }, function (index, layero) {
                //按钮【按钮一】的回调
                window.location.href = url;
            }, function (index) {
                //按钮【按钮二】的回调
            });
        }
    </script>
</body>
</html>
