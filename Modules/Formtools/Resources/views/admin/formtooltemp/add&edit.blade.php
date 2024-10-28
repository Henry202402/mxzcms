@include(moduleAdminTemplate($pageData['moduleName'])."public.header")
<!-- ============================================================== -->
<body>
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
                                <div>
                                    @foreach($pageData['listActions'] as $act)
                                        <a class="label  {{$act['cssClass']}}  pull-right m-t-xs"
                                           style="margin-right: 10px;padding: 3px 8px;font-size: 12px;"
                                           @if($act['popup'])
                                                   onclick="popupPage('{{rtrim($act['actionUrl'].'?'.http_build_query(array_merge($act['param']?:[],[$f['actionby']=>toArray($d)[$f['actionby']]])),"?")}}')"
                                           @else
                                           href="{{rtrim($act['actionUrl'].'?'.http_build_query(array_merge($act['param']?:[],[$f['actionby']=>toArray($d)[$f['actionby']]])),"?")}}"
                                                @endif
                                        >
                                            {{$act['actionName']}}
                                        </a>
                                    @endforeach
                                    @if(!$pageData["fields"]['legend'])
                                        <legend class="text-bold">{{$pageData['subtitle']}}</legend>
                                    @endif
                                </div>
                                @foreach($pageData['fields'] as $f)
                                    @include(moduleAdminTemplate("formtools")."formtooltemplates.".$f['formtype'],compact( 'f'))
                                @endforeach
                                <div class="form-group">
                                    <label class="col-lg-1 control-label"></label>
                                    <div class="col-lg-11">
                                        @if($pageData['formaction'])
                                            @if($pageData['actionType']=='ajax')
                                                <button type="button" class="btn btn-sm btn-info" id="post_button">
                                                    {{$pageData['actionName']}}
                                                </button>
                                            @else
                                                <button type="submit" class="btn btn-sm btn-info" id="post_button">
                                                    {{$pageData['actionName']}}
                                                </button>
                                            @endif
                                        @endif
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
    @if($pageData['actionType']=='ajax')
    $('#post_button').click(function () {
        layer.load(1);
        $.ajax({
            "method": "post",
            "url": "{{$pageData['formaction']}}",
            "data": new FormData($('#{{$pageData['formid']}}')[0]),
            "dataType": 'json',
            "cache": false,
            "processData": false,
            "contentType": false,
            "success": function (res) {
                layer.closeAll();
                if (res.status == 200) {
                    layer.msg(res.msg, {icon: 1, time: 1000}, function () {
                        if ('{{$_GET['popup']}}') {
                            // 关闭当前的layer弹窗
                            parent.layer.close(parent.layer.index);
                            // 刷新父页面
                            parent.location.reload();
                        }else if (res.data.jumpUrl) {
                            window.location.href = res.data.jumpUrl;
                        } else {
                            window.location.reload();
                        }
                    });
                } else {
                    layer.msg(res.msg, {icon: 5})
                }
            },
            "error": function (res) {
                console.log(res);
            }
        })
    });

    @endif
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

    function popupPage(url) {
        var popupPageOpen = layer.open({
            title: '',
            type: 2,
            content: url,
            closeBtn: 1,
            area:['50%','65%'],
        });
        /*layer.full(popupPageOpen);*/
        $('.layui-layer-iframe').css({
            'transform': 'translateZ(10000px)',
            'scrollbar-width': 'none',
        });
    }
</script>
{{--必须放在最后--}}
<script type="text/javascript"
        src="{{moduleAdminResource($moduleName)}}/js/plugins/forms/selects/select2.min.js"></script>
<script type="text/javascript" src="{{moduleAdminResource($moduleName)}}/js/pages/form_select2.js"></script>
</body>
</html>
