@include(moduleAdminTemplate($moduleName)."public.header")
@php
    $record = $pageData['datas'] ?? null;
    $resolveRecordTitle = function ($row) {
        $rowArray = $row ? toArray($row) : [];
        foreach (['title', 'name', 'cate_name', 'company_name', 'full_name'] as $field) {
            if (!empty($rowArray[$field])) {
                return $rowArray[$field];
            }
        }
        return !empty($rowArray['id']) ? 'ID #' . $rowArray['id'] : '暂无内容';
    };
    $resolveValue = function ($row, $field) {
        $rowArray = $row ? toArray($row) : [];
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
        return [
            'type' => ($field['formtype'] ?? '') === 'editor' ? 'html' : 'text',
            'value' => $value,
        ];
    };
    $recordTitle = $resolveRecordTitle($record);
@endphp
<style>
    .ft-single-index {
        display: grid;
        gap: 18px;
    }
    .ft-single-overview {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
    }
    .ft-single-stat {
        padding: 18px 20px;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 10px 28px rgba(15, 23, 42, 0.05);
    }
    .ft-single-stat__label {
        margin: 0 0 8px;
        font-size: 12px;
        color: #64748b;
        letter-spacing: .04em;
        text-transform: uppercase;
    }
    .ft-single-stat__value {
        margin: 0;
        font-size: 28px;
        line-height: 1;
        font-weight: 700;
        color: #0f172a;
    }
    .ft-single-stat__desc {
        margin-top: 8px;
        font-size: 13px;
        color: #94a3b8;
    }
    .ft-single-panel {
        overflow: hidden;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.06);
    }
    .ft-single-toolbar {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        padding: 20px 22px;
        border-bottom: 1px solid #edf2f7;
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
    }
    .ft-single-toolbar__title {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
    }
    .ft-single-toolbar__desc {
        margin: 6px 0 0;
        color: #64748b;
        line-height: 1.8;
    }
    .ft-single-toolbar__sub {
        margin-top: 8px;
        color: #94a3b8;
        line-height: 1.8;
    }
    .ft-single-code {
        display: inline-block;
        margin-left: 6px;
        padding: 2px 8px;
        border-radius: 999px;
        background: #eff6ff;
        color: #1d4ed8;
        font-family: Consolas, Monaco, monospace;
        font-size: 12px;
    }
    .ft-single-toolbar__actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: flex-end;
    }
    .ft-single-toolbar__actions .btn {
        border-radius: 999px;
        font-weight: 600;
        min-width: 110px;
    }
    .ft-single-table-wrap {
        padding: 18px 22px 18px;
    }
    .ft-single-table {
        margin-bottom: 0;
    }
    .ft-single-table > tbody > tr > th {
        width: 180px;
        padding: 16px;
        border-top: 1px solid #edf2f7;
        background: #f8fafc;
        color: #475569;
    }
    .ft-single-table > tbody > tr > td {
        padding: 16px;
        border-top: 1px solid #edf2f7;
        vertical-align: top;
    }
    .ft-single-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    .ft-single-actions .btn {
        border-radius: 999px;
        font-weight: 600;
    }
    .ft-single-empty {
        padding: 34px 20px !important;
        text-align: center;
        color: #94a3b8;
    }
    @media (max-width: 1199px) {
        .ft-single-overview {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
    @media (max-width: 991px) {
        .ft-single-toolbar {
            flex-direction: column;
        }
        .ft-single-toolbar__actions {
            justify-content: flex-start;
        }
    }
    @media (max-width: 767px) {
        .ft-single-overview {
            grid-template-columns: 1fr;
        }
        .ft-single-table-wrap {
            padding-left: 14px;
            padding-right: 14px;
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
                <div class="ft-single-index">
                    <div class="ft-single-overview">
                        <div class="ft-single-stat">
                            <p class="ft-single-stat__label">内容状态</p>
                            <p class="ft-single-stat__value">{{!empty($record) && !empty($record->id) ? '已创建' : '未创建'}}</p>
                            <div class="ft-single-stat__desc">单页模型通常只保留一条主内容</div>
                        </div>
                        <div class="ft-single-stat">
                            <p class="ft-single-stat__label">显示字段</p>
                            <p class="ft-single-stat__value">{{count($pageData['modeldetaill'] ?? [])}}</p>
                            <div class="ft-single-stat__desc">当前详情页中会展示的字段数量</div>
                        </div>
                        <div class="ft-single-stat">
                            <p class="ft-single-stat__label">创建时间</p>
                            <p class="ft-single-stat__value" style="font-size: 18px; line-height: 1.35;">{{(!empty($record) && !empty($record->created_at)) ? $record->created_at : '-'}}</p>
                            <div class="ft-single-stat__desc">便于快速判断当前单页内容更新时间</div>
                        </div>
                        <div class="ft-single-stat">
                            <p class="ft-single-stat__label">当前内容</p>
                            <p class="ft-single-stat__value" style="font-size: 18px; line-height: 1.35;">{{$recordTitle}}</p>
                            <div class="ft-single-stat__desc">优先显示标题、名称等核心字段</div>
                        </div>
                    </div>
                    <div class="ft-single-panel">
                        <div class="ft-single-toolbar">
                            <div>
                                <h3 class="ft-single-toolbar__title">单页模型</h3>
                                <p class="ft-single-toolbar__desc">单页模型更适合用作协议、关于我们、联系我们这类固定内容页，当前页面会直接展示唯一记录。</p>
                                <div class="ft-single-toolbar__sub">
                                    当前模型：<span class="ft-single-code">{{$pageData['model']}}</span>
                                    @if(!empty($pageData['access_identification']))
                                        前台访问：<span class="ft-single-code">{{$pageData['access_identification']}}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="ft-single-toolbar__actions">
                                @if(empty($record) || empty($record->id))
                                    <a href="{{url("admin/".$moduleName."/model?moduleName={$pageData['moduleName']}&action=Add&model=".$pageData['model'])}}" class="btn btn-info btn-sm">新增内容</a>
                                @else
                                    <a href="{{url("admin/".$moduleName."/model?action=Edit&moduleName={$pageData['moduleName']}&model=".$pageData['model']."&id=".$record->id)}}" class="btn btn-success btn-sm">编辑内容</a>
                                    @if(!empty($pageData['access_identification']))
                                        <a href="{{url('detail/'.$pageData['access_identification'].'/'.$record->id)}}" target="_blank" class="btn btn-primary btn-sm">前台详情</a>
                                    @endif
                                    <a onclick="delSingle('{{url("admin/{$moduleName}/model?action=Del&moduleName={$pageData['moduleName']}&model={$pageData['model']}&id={$record->id}")}}','{{ addslashes($recordTitle) }}')" class="btn btn-danger btn-sm">删除内容</a>
                                @endif
                            </div>
                        </div>
                        <div class="table-responsive ft-single-table-wrap">
                        <table class="table ft-single-table m-b-none">

                            @if(!empty($record) && !empty($record->id))
                                @if(!empty($pageData['showCreatedAt']))
                                    <tr>
                                        <th>创建时间</th>
                                        <td>{{$record->created_at ?? '-'}}</td>
                                    </tr>
                                @endif
                                @foreach($pageData['modeldetaill'] as $f)
                                    @php($display = $resolveValue($record, $f))
                                    <tr>
                                        <th style="vertical-align: top;">{{$f['name']?:$f['remark']}}</th>
                                        <td>
                                            @if($display['type'] === 'image')
                                                    <img src="{{$display['value']}}"
                                                         class="cursor-pointer" width="30"
                                                         onclick="clickImage('{{$display['value']}}')">
                                            @elseif($display['type'] === 'file')
                                                    <i class="cursor-pointer icon-file-download2"
                                                       onclick="fileDownload('{{$display['value']}}')"
                                                       style="font-size: 25px;"></i>
                                            @elseif($display['type'] === 'html')
                                                {!! $display['value'] !!}
                                            @else
                                                {{ $display['value'] }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="2">
                                        <div class="ft-single-actions">
                                            <a href="{{url("admin/".$moduleName."/model?action=Edit&moduleName={$pageData['moduleName']}&model=".$pageData['model']."&id=".$record->id)}}" class="btn btn-success btn-xs">
                                                编辑
                                            </a>
                                            <a onclick="delSingle('{{url("admin/{$moduleName}/model?action=Del&moduleName={$pageData['moduleName']}&model={$pageData['model']}&id={$record->id}")}}','{{ addslashes($recordTitle) }}')"
                                               class="btn btn-danger btn-xs ">
                                                删除
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="2" class="ft-single-empty">暂无数据</td>
                                </tr>
                            @endif

                        </table>
                        </div>
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
    <script>
        function delSingle(url, title) {
            layer.confirm('确定要删除单页内容“' + title + '”吗？删除后需要重新新增才能恢复。', {
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
    </script>
</body>
</html>
