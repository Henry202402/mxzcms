@include(moduleAdminTemplate($moduleName)."public.header")
@php
    $resolveValue = function ($row, $field) {
        $rowArray = toArray($row);
        $value = $rowArray[$field['identification']] ?? '';
        $options = $field['datas'] ?? [];
        if (is_array($options) && array_key_exists((string) $value, $options)) {
            return ['type' => 'text', 'value' => $options[(string) $value]];
        }
        if (is_array($options) && array_key_exists($value, $options)) {
            return ['type' => 'text', 'value' => $options[$value]];
        }
        if (in_array($field['formtype'] ?? '', ['upload', 'uploadAjax', 'image', 'imageAjax'], true)) {
            $ext = strtolower(pathinfo((string) $value, PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true) && $value !== '') {
                return ['type' => 'image', 'value' => GetUrlByPath($value)];
            }
            if ($value !== '') {
                return ['type' => 'file', 'value' => GetUrlByPath($value)];
            }
        }
        return ['type' => 'text', 'value' => $value];
    };
    $resolveRowTitle = function ($row) {
        $rowArray = toArray($row);
        foreach (['title', 'name', 'cate_name', 'company_name', 'full_name'] as $key) {
            if (!empty($rowArray[$key])) {
                return $rowArray[$key];
            }
        }
        return 'ID #' . ($rowArray['id'] ?? '');
    };
    $resolveStatusMeta = function ($row) {
        $status = (string) (toArray($row)['status'] ?? '');
        return match ($status) {
            '1' => ['label' => '通过', 'class' => 'ft-content-badge ft-content-badge--approve'],
            '0' => ['label' => '待审核', 'class' => 'ft-content-badge ft-content-badge--pending'],
            '2' => ['label' => '下架', 'class' => 'ft-content-badge ft-content-badge--offline'],
            default => ['label' => '未设置', 'class' => 'ft-content-badge ft-content-badge--unknown'],
        };
    };
    $resolveQuickStatusActions = function ($row) use ($moduleName, $pageData) {
        $rowArray = toArray($row);
        $currentStatus = (string) ($rowArray['status'] ?? '');
        $baseQuery = [
            'action' => 'QuickStatus',
            'moduleName' => $pageData['moduleName'],
            'model' => $pageData['model'],
            'id' => $rowArray['id'] ?? 0,
            'page' => $pageData['datas']->currentPage(),
            'keyword' => $pageData['searchKeyword'] ?? '',
            'search_field' => $pageData['searchField'] ?? '',
            'status' => $pageData['statusFilter'] ?? '',
        ];

        return [
            [
                'label' => '通过',
                'class' => 'btn btn-xs ft-status-btn ft-status-btn--approve',
                'isCurrent' => $currentStatus === '1',
                'url' => url("admin/" . $moduleName . "/model?" . http_build_query(array_merge($baseQuery, ['status_action' => 'approve']))),
            ],
            [
                'label' => '待审核',
                'class' => 'btn btn-xs ft-status-btn ft-status-btn--pending',
                'isCurrent' => $currentStatus === '0',
                'url' => url("admin/" . $moduleName . "/model?" . http_build_query(array_merge($baseQuery, ['status_action' => 'reject']))),
            ],
            [
                'label' => '下架',
                'class' => 'btn btn-xs ft-status-btn ft-status-btn--offline',
                'isCurrent' => $currentStatus === '2',
                'url' => url("admin/" . $moduleName . "/model?" . http_build_query(array_merge($baseQuery, ['status_action' => 'offline']))),
            ],
        ];
    };
@endphp
<style>
    .ft-content-index {
        display: grid;
        gap: 18px;
    }

    .ft-content-overview {
        display: grid;
        grid-template-columns: repeat(6, minmax(0, 1fr));
        gap: 14px;
    }

    .ft-content-stat {
        padding: 18px 20px;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 10px 28px rgba(15, 23, 42, 0.05);
    }

    .ft-content-stat__label {
        margin: 0 0 8px;
        font-size: 12px;
        color: #64748b;
        letter-spacing: .04em;
        text-transform: uppercase;
    }

    .ft-content-stat__value {
        margin: 0;
        font-size: 28px;
        line-height: 1;
        font-weight: 700;
        color: #0f172a;
    }

    .ft-content-stat__desc {
        margin-top: 8px;
        font-size: 13px;
        color: #94a3b8;
    }

    .ft-content-panel {
        overflow: hidden;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.06);
    }

    .ft-content-toolbar {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        padding: 20px 22px;
        border-bottom: 1px solid #edf2f7;
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
    }

    .ft-content-toolbar__title {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
    }

    .ft-content-toolbar__desc {
        margin: 6px 0 0;
        color: #64748b;
        line-height: 1.8;
    }

    .ft-content-toolbar__sub {
        margin-top: 8px;
        color: #94a3b8;
        line-height: 1.8;
    }

    .ft-content-code {
        display: inline-block;
        margin-left: 6px;
        padding: 2px 8px;
        border-radius: 999px;
        background: #eff6ff;
        color: #1d4ed8;
        font-family: Consolas, Monaco, monospace;
        font-size: 12px;
    }

    .ft-content-toolbar__actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: flex-end;
    }

    .ft-content-toolbar__actions .btn {
        border-radius: 999px;
        font-weight: 600;
        min-width: 110px;
    }

    .ft-content-search {
        padding: 18px 22px 0;
    }

    .ft-content-search__form {
        display: grid;
        grid-template-columns: 180px 160px minmax(220px, 1fr) auto;
        gap: 12px;
        align-items: end;
    }

    .ft-content-search__actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .ft-content-search__actions .btn {
        border-radius: 999px;
        font-weight: 600;
        min-width: 94px;
    }

    .ft-content-table-wrap {
        padding: 18px 22px 18px;
    }

    .ft-content-table {
        margin-bottom: 0;
    }

    .ft-content-table__checkbox {
        width: 42px;
        text-align: center;
    }

    .ft-content-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 72px;
        padding: 5px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
    }

    .ft-content-badge--approve {
        background: #dcfce7;
        color: #166534;
    }

    .ft-content-badge--pending {
        background: #fef3c7;
        color: #92400e;
    }

    .ft-content-badge--offline {
        background: #fee2e2;
        color: #991b1b;
    }

    .ft-content-badge--unknown {
        background: #e2e8f0;
        color: #475569;
    }

    .ft-content-table > thead > tr > th {
        padding: 14px 16px;
        border-bottom: 1px solid #e5e7eb;
        color: #64748b;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
        background: #f8fafc;
    }

    .ft-content-table > tbody > tr > td {
        padding: 16px;
        border-top: 1px solid #edf2f7;
        vertical-align: top;
    }

    .ft-content-table > tbody > tr:hover {
        background: #f8fbff;
    }

    .ft-content-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        min-width: 146px;
    }

    .ft-content-actions .btn {
        border-radius: 999px;
        font-weight: 600;
    }

    .ft-status-btn {
        min-width: 62px;
        border-width: 1px;
    }

    .ft-status-btn--approve {
        color: #166534;
        background: #f0fdf4;
        border-color: #bbf7d0;
    }

    .ft-status-btn--pending {
        color: #92400e;
        background: #fffbeb;
        border-color: #fde68a;
    }

    .ft-status-btn--offline {
        color: #991b1b;
        background: #fef2f2;
        border-color: #fecaca;
    }

    .ft-status-btn.is-current,
    .ft-status-btn[disabled] {
        opacity: 1;
        box-shadow: inset 0 0 0 1px rgba(15, 23, 42, 0.08);
        cursor: default;
        pointer-events: none;
    }

    .ft-content-empty {
        padding: 34px 20px !important;
        text-align: center;
        color: #94a3b8;
    }

    .ft-content-pagination {
        margin-top: 18px;
    }

    .ft-content-pagination .pagination {
        margin: 0;
    }

    @media (max-width: 1199px) {
        .ft-content-overview {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 991px) {
        .ft-content-toolbar {
            flex-direction: column;
        }

        .ft-content-toolbar__actions {
            justify-content: flex-start;
        }

        .ft-content-search__form {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 767px) {
        .ft-content-overview {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .ft-content-search,
        .ft-content-table-wrap {
            padding-left: 14px;
            padding-right: 14px;
        }
    }

    @media (max-width: 575px) {
        .ft-content-overview {
            grid-template-columns: 1fr;
        }
    }
</style>
<!-- ============================================================== -->
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

            <!-- Bordered striped table -->
                <div class="ft-content-index">
                    <div class="ft-content-overview">
                        <div class="ft-content-stat">
                            <p class="ft-content-stat__label">内容总数</p>
                            <p class="ft-content-stat__value">{{$pageData['totalCount'] ?? 0}}</p>
                            <div class="ft-content-stat__desc">当前模型数据表中的全部记录数</div>
                        </div>
                        <div class="ft-content-stat">
                            <p class="ft-content-stat__label">当前结果</p>
                            <p class="ft-content-stat__value">{{$pageData['filteredCount'] ?? 0}}</p>
                            <div class="ft-content-stat__desc">当前筛选条件下的匹配结果数</div>
                        </div>
                        <div class="ft-content-stat">
                            <p class="ft-content-stat__label">列表字段</p>
                            <p class="ft-content-stat__value">{{count($pageData['modeldetaill'] ?? [])}}</p>
                            <div class="ft-content-stat__desc">当前后台列表实际展示的字段数量</div>
                        </div>
                        <div class="ft-content-stat">
                            <p class="ft-content-stat__label">可搜索字段</p>
                            <p class="ft-content-stat__value">{{$pageData['searchEnabledCount'] ?? 0}}</p>
                            <div class="ft-content-stat__desc">已开启后台搜索的字段数量</div>
                        </div>
                        <div class="ft-content-stat">
                            <p class="ft-content-stat__label">待审核</p>
                            <p class="ft-content-stat__value">{{$pageData['statusCountMap']['0'] ?? 0}}</p>
                            <div class="ft-content-stat__desc">当前仍需审核的内容数量</div>
                        </div>
                        <div class="ft-content-stat">
                            <p class="ft-content-stat__label">已下架</p>
                            <p class="ft-content-stat__value">{{$pageData['statusCountMap']['2'] ?? 0}}</p>
                            <div class="ft-content-stat__desc">当前被下架的内容数量</div>
                        </div>
                        @if(!empty($pageData['metricAvailableMap']['access_count']))
                            <div class="ft-content-stat">
                                <p class="ft-content-stat__label">总浏览量</p>
                                <p class="ft-content-stat__value">{{$pageData['metricTotalMap']['access_count'] ?? 0}}</p>
                                <div class="ft-content-stat__desc">当前模型所有内容累计访问次数</div>
                            </div>
                        @endif
                        @if(!empty($pageData['metricAvailableMap']['good_count']))
                            <div class="ft-content-stat">
                                <p class="ft-content-stat__label">总点赞量</p>
                                <p class="ft-content-stat__value">{{$pageData['metricTotalMap']['good_count'] ?? 0}}</p>
                                <div class="ft-content-stat__desc">当前模型所有内容累计点赞次数</div>
                            </div>
                        @endif
                        @if(!empty($pageData['metricAvailableMap']['download_count']))
                            <div class="ft-content-stat">
                                <p class="ft-content-stat__label">总下载量</p>
                                <p class="ft-content-stat__value">{{$pageData['metricTotalMap']['download_count'] ?? 0}}</p>
                                <div class="ft-content-stat__desc">当前模型所有内容累计下载次数</div>
                            </div>
                        @endif
                        @if(!empty($pageData['metricAvailableMap']['uid']))
                            <div class="ft-content-stat">
                                <p class="ft-content-stat__label">发布者数</p>
                                <p class="ft-content-stat__value">{{$pageData['publisherCount'] ?? 0}}</p>
                                <div class="ft-content-stat__desc">当前模型已产生内容记录的 uid 数量</div>
                            </div>
                        @endif
                    </div>
                    <div class="ft-content-panel">
                        <div class="ft-content-toolbar">
                            <div>
                                <h3 class="ft-content-toolbar__title">内容列表</h3>
                                <p class="ft-content-toolbar__desc">把当前模型的内容数据、列表字段和搜索入口放在一屏内查看，编辑和删除流程也更明确。</p>
                                <div class="ft-content-toolbar__sub">
                                    当前模型：<span class="ft-content-code">{{$pageData['model']}}</span>
                                    数据表：<span class="ft-content-code">{{$pageData['tableName']}}</span>
                                </div>
                            </div>
                            <div class="ft-content-toolbar__actions">
                                <a href="{{url("admin/formtools/modelStatistics?id=".$pageData['currentModelId'])}}" class="btn btn-default btn-sm">数据统计</a>
                                <a href="{{url("admin/".$moduleName."/model?moduleName={$pageData['moduleName']}&action=Add&model=".$pageData['model'])}}" class="btn btn-info btn-sm">新增内容</a>
                            </div>
                        </div>
                        <div class="ft-content-search">
                            <form method="get" class="ft-content-search__form">
                                <input type="hidden" name="moduleName" value="{{$pageData['moduleName']}}">
                                <input type="hidden" name="action" value="List">
                                <input type="hidden" name="model" value="{{$pageData['model']}}">
                                <div>
                                    <label>搜索字段</label>
                                    <select name="search_field" class="form-control">
                                        <option value="">全部可搜索字段</option>
                                        @foreach(($pageData['searchableFields'] ?? []) as $searchField)
                                            <option value="{{$searchField['identification']}}" @if(($pageData['searchField'] ?? '') === $searchField['identification']) selected @endif>
                                                {{$searchField['name']}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label>审核状态</label>
                                    <select name="status" class="form-control">
                                        <option value="">全部状态</option>
                                        @foreach(($pageData['statusOptions'] ?? []) as $statusValue => $statusLabel)
                                            <option value="{{$statusValue}}" @if(($pageData['statusFilter'] ?? '') === (string) $statusValue) selected @endif>
                                                {{$statusLabel}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label>关键词</label>
                                    <input type="text" name="keyword" class="form-control" value="{{$pageData['searchKeyword'] ?? ''}}" placeholder="请输入关键词">
                                </div>
                                <div class="ft-content-search__actions">
                                    <button type="submit" class="btn btn-info btn-sm">搜索</button>
                                    <a href="{{url("admin/".$moduleName."/model?moduleName={$pageData['moduleName']}&action=List&model=".$pageData['model'])}}" class="btn btn-danger btn-sm">清空</a>
                                </div>
                            </form>
                        </div>
                        <form method="post" action="{{url("admin/".$moduleName."/model")}}" id="ftBatchForm">
                            {{csrf_field()}}
                            <input type="hidden" name="moduleName" value="{{$pageData['moduleName']}}">
                            <input type="hidden" name="action" value="Batch">
                            <input type="hidden" name="model" value="{{$pageData['model']}}">
                            <input type="hidden" name="page" value="{{$pageData['datas']->currentPage()}}">
                            <input type="hidden" name="keyword" value="{{$pageData['searchKeyword'] ?? ''}}">
                            <input type="hidden" name="search_field" value="{{$pageData['searchField'] ?? ''}}">
                            <input type="hidden" name="status" value="{{$pageData['statusFilter'] ?? ''}}">
                            <div class="ft-content-search" style="padding-top: 18px;">
                                <div class="ft-content-search__form" style="grid-template-columns: minmax(220px, 320px) auto;">
                                    <div>
                                        <label>批量操作</label>
                                        <select name="batch_action" class="form-control">
                                            <option value="">请选择批量操作</option>
                                            <option value="approve">批量审核通过</option>
                                            <option value="reject">批量设为待审核</option>
                                            <option value="offline">批量下架</option>
                                            <option value="delete">批量删除</option>
                                        </select>
                                    </div>
                                    <div class="ft-content-search__actions">
                                        <button type="button" class="btn btn-default btn-sm" onclick="toggleSelectAllRows(true)">全选本页</button>
                                        <button type="button" class="btn btn-default btn-sm" onclick="toggleSelectAllRows(false)">取消选择</button>
                                        <button type="button" class="btn btn-warning btn-sm" onclick="submitBatchAction()">执行批量操作</button>
                                    </div>
                                </div>
                            </div>
                        <div class="table-responsive ft-content-table-wrap">
                        <table class="table ft-content-table m-b-none">
                            <thead>
                            <tr>
                                <th class="ft-content-table__checkbox"><input type="checkbox" id="ftSelectAll"></th>
                                <th>ID</th>
                                @foreach($pageData['modeldetaill'] as $f)
                                    <th>{{$f['name']?:$f['remark']}}</th>
                                @endforeach
                                <th>审核状态</th>
                                @if(!empty($pageData['showCreatedAt']))
                                    <th>创建时间</th>
                                @endif
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>

                            @forelse($pageData['datas'] as $d)
                                <tr>
                                    <td class="ft-content-table__checkbox">
                                        <input type="checkbox" class="ft-row-checkbox" name="ids[]" value="{{$d->id}}">
                                    </td>
                                    <td>{{$d->id}}</td>
                                    @foreach($pageData['modeldetaill'] as $f)
                                        @php($display = $resolveValue($d, $f))
                                        <td>
                                            @if($display['type'] === 'image')
                                                    <img src="{{$display['value']}}"
                                                         class="cursor-pointer" width="30"
                                                         onclick="clickImage('{{$display['value']}}')">
                                            @elseif($display['type'] === 'file')
                                                    <i class="cursor-pointer icon-file-download2"
                                                       onclick="fileDownload('{{$display['value']}}')"
                                                       style="font-size: 25px;"></i>
                                            @else
                                                {{ $display['value'] }}
                                            @endif
                                        </td>
                                    @endforeach
                                    @php($statusMeta = $resolveStatusMeta($d))
                                    <td><span class="{{$statusMeta['class']}}">{{$statusMeta['label']}}</span></td>
                                    @if(!empty($pageData['showCreatedAt']))
                                        <td>{{$d->created_at ?? '-'}}</td>
                                    @endif
                                    <td>
                                        <div class="ft-content-actions">
                                            @if(!empty($pageData['hasStatusColumn']))
                                                @foreach($resolveQuickStatusActions($d) as $statusAction)
                                                    @if($statusAction['isCurrent'])
                                                        <button type="button" class="{{$statusAction['class']}} is-current" disabled>
                                                            {{$statusAction['label']}}
                                                        </button>
                                                    @else
                                                        <button type="button"
                                                                class="{{$statusAction['class']}}"
                                                                onclick="quickStatus('{{$statusAction['url']}}','{{ addslashes($resolveRowTitle($d)) }}','{{$statusAction['label']}}')">
                                                            {{$statusAction['label']}}
                                                        </button>
                                                    @endif
                                                @endforeach
                                            @endif
                                            <a href="{{url("admin/".$moduleName."/model?" . http_build_query(['action' => 'Edit', 'moduleName' => $pageData['moduleName'], 'model' => $pageData['model'], 'id' => $d->id, 'page' => $pageData['datas']->currentPage(), 'keyword' => $pageData['searchKeyword'] ?? '', 'search_field' => $pageData['searchField'] ?? '', 'status' => $pageData['statusFilter'] ?? '']))}}" class="btn btn-success btn-xs">
                                                编辑
                                            </a>
                                            <a onclick="delContent('{{url("admin/".$moduleName."/model?" . http_build_query(['action' => 'Del', 'moduleName' => $pageData['moduleName'], 'model' => $pageData['model'], 'id' => $d->id, 'page' => $pageData['datas']->currentPage(), 'keyword' => $pageData['searchKeyword'] ?? '', 'search_field' => $pageData['searchField'] ?? '', 'status' => $pageData['statusFilter'] ?? '']))}}','{{ addslashes($resolveRowTitle($d)) }}','{{$d->id}}')"
                                               class="btn btn-danger btn-xs ">
                                                删除
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="20" class="ft-content-empty">
                                        暂无数据
                                    </td>
                                </tr>
                            @endforelse


                            </tbody>
                        </table>
                        </div>
                        </form>
                    </div>
                </div>
                <!-- /bordered striped table -->

                <div class="col-sm-12 text-right text-center-xs ft-content-pagination">
                    {{ $pageData['datas']->appends($_GET?:[])->links($moduleName.'::admin.public.pagination',["data"=>$pageData['datas']]) }}
                </div>


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
    <script>
        function quickStatus(url, title, actionText) {
            layer.confirm('确定要把内容“' + title + '”设置为“' + actionText + '”吗？', {
                title: "审核提示",
                btn: ['确定', '取消']
            }, function () {
                window.location.href = url;
            });
        }

        function delContent(url, title, id) {
            layer.confirm('确定要删除内容“' + title + '”吗？这会直接删除当前记录，ID 为 ' + id + '。', {
                title: "操作提示",
                btn: ['确定', '取消'] //可以无限个按钮
            }, function (index, layero) {
                //按钮【按钮一】的回调
                window.location.href = url;
            }, function (index) {
                //按钮【按钮二】的回调
            });
        }

        function clickImage(src, w = 300) {
            if (w <= 0) w = 300;
            if (!src) return
            //自定义页
            layer.open({
                title: "",
                type: 1,
                skin: 'layui-layer-demo', //样式类名
                closeBtn: 0, //不显示关闭按钮
                anim: 7,
                shadeClose: true, //开启遮罩关闭
                content: "<img width='" + w + "' src='" + src + "'>"
            });
        }

        function fileDownload(src) {
            window.location.href = src;
        }

        var ftSelectAll = document.getElementById('ftSelectAll');
        if (ftSelectAll) {
            ftSelectAll.addEventListener('change', function () {
                toggleSelectAllRows(this.checked);
            });
        }

        function toggleSelectAllRows(checked) {
            document.querySelectorAll('.ft-row-checkbox').forEach(function (item) {
                item.checked = checked;
            });
            if (ftSelectAll) {
                ftSelectAll.checked = checked;
            }
        }

        function submitBatchAction() {
            var form = document.getElementById('ftBatchForm');
            var action = form.querySelector('[name="batch_action"]').value;
            var checkedRows = document.querySelectorAll('.ft-row-checkbox:checked').length;
            if (!action) {
                layer.msg('请先选择批量操作');
                return;
            }
            if (checkedRows <= 0) {
                layer.msg('请先选择要处理的内容');
                return;
            }
            var actionTextMap = {
                approve: '批量审核通过',
                reject: '批量设为待审核',
                offline: '批量下架',
                delete: '批量删除'
            };
            layer.confirm('确定要执行“' + (actionTextMap[action] || action) + '”吗？本次会处理已选择的 ' + checkedRows + ' 条内容。', {
                title: "操作提示",
                btn: ['确定', '取消']
            }, function () {
                form.submit();
            });
        }
    </script>
</body>
</html>
