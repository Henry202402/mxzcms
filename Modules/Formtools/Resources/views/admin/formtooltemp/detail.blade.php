@include(moduleAdminTemplate($pageData['moduleName'])."public.header")
<!-- ============================================================== -->
<body>
<style>
    .formtool-group-card {
        margin: 0 0 18px;
        padding: 18px 20px 6px;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.04);
    }
    .formtool-group-card--legend {
        border-color: #dbeafe;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
    }
    .formtool-group-card .text-bold {
        margin-top: 0;
        margin-bottom: 12px;
        color: #0f172a;
    }
</style>
@if(!$pageData['popup'])
    @include(moduleAdminTemplate($pageData['moduleName'])."public.nav")
@endif
<!-- Page container -->
<div class="page-container">
    <!-- Page content -->
    <div class="page-content">
    @if(!$pageData['popup'])
        @include(moduleAdminTemplate($pageData['moduleName'])."public.left")
    @endif
    <!-- Main content -->
        <div class="content-wrapper">
            <!-- Content area -->
            <div class="content">
                @if(!$pageData['popup'])
                    @include(moduleAdminTemplate($pageData['moduleName'])."public.page",['breadcrumb'=>[$pageData['title'],$pageData['subtitle']]])
                @endif
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <form class="form-horizontal" action="{{$pageData['formaction']}}"
                              method="{{$pageData['method']}}" id="{{$pageData['formid']}}"
                              enctype="multipart/form-data">
                            <fieldset class="content-group">
                                <legend class="text-bold">{{$pageData['subtitle']}}</legend>
                                @php($detailData = isset($pageData['detail']) ? toArray($pageData['detail']) : [])
                                @if($pageData['tips'])
                                    <div class="alert alpha-orange-600 border-orange alert-styled-left">
                                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                                        {{$pageData['tips']}}
                                    </div>
                                @endif
                                @foreach(($pageData['fieldGroups'] ?? []) as $group)
                                    @php($heading = $group['heading'] ?? null)
                                    <div class="formtool-group-card @if(($heading['formtype'] ?? '') === 'legend') formtool-group-card--legend @endif">
                                        @if($heading)
                                            @include('formtools::admin.formtooltemplates.groupHeading', ['f' => $heading])
                                        @endif
                                        <div class="row">
                                            @foreach(($group['fields'] ?? []) as $f)
                                                @include('formtools::admin.formtooltemp.detailField', compact('f', 'detailData'))
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                                <div class="form-group">
                                    <label class="col-lg-1 control-label"></label>
                                    <div class="col-lg-11">
                                        <a href="javascript:history.back();" type="button"
                                           class="btn btn-sm btn-danger">
                                            {{$pageData['backName']}}
                                        </a>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
                @include(moduleAdminTemplate($pageData['moduleName'])."public.footer")
            </div>
            <!-- /content area -->
        </div>
        <!-- /main content -->
    </div>
    <!-- /page content -->
</div>
<!-- /page container -->
<!-- 						Content End		 						-->
<!-- ============================================================== -->
@include(moduleAdminTemplate($pageData['moduleName'])."public.js")
<script>
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
@foreach(($pageData['inlineScripts'] ?? []) as $inlineScript)
    <script>
{!! $inlineScript !!}
    </script>
@endforeach
{{--必须放在最后--}}
</body>
</html>
