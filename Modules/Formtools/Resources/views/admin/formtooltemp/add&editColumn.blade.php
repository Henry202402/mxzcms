@include(moduleAdminTemplate($pageData['moduleName'])."public.header")
<!-- ============================================================== -->
<body>
<style>
    .formtool-switch {
        position: relative;
        display: inline-block;
        width: 46px;
        height: 24px;
        margin-bottom: 0;
        vertical-align: middle;
    }
    .formtool-switch input {
        display: none;
    }
    .formtool-switch-slider {
        position: absolute;
        inset: 0;
        background: #d9d9d9;
        border-radius: 999px;
        transition: all .2s ease;
        cursor: pointer;
    }
    .formtool-switch-slider:before {
        content: "";
        position: absolute;
        width: 18px;
        height: 18px;
        left: 3px;
        top: 3px;
        background: #fff;
        border-radius: 50%;
        transition: all .2s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .25);
    }
    .formtool-switch input:checked + .formtool-switch-slider {
        background: #4caf50;
    }
    .formtool-switch input:checked + .formtool-switch-slider:before {
        transform: translateX(22px);
    }
    .formtool-switch input[disabled] + .formtool-switch-slider {
        opacity: .6;
        cursor: not-allowed;
    }
    .formtool-switch-label {
        display: inline-block;
        margin-left: 8px;
        vertical-align: middle;
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
                                <div>
                                    @foreach($pageData['listActions'] as $act)
                                        @php($columnActionUrl = rtrim($act['actionUrl'].'?'.http_build_query($act['param'] ?? []), "?"))
                                        <a class="label {{$act['cssClass']}} pull-right m-t-xs"
                                           style="margin-right: 10px;padding: 3px 8px;font-size: 12px;"
                                           @if($act['popup'])
                                           onclick="popupPage('{{$columnActionUrl}}')"
                                           @else
                                           href="{{$columnActionUrl}}"
                                           @endif
                                           @if(!empty($act['target'])) target="{{$act['target']}}" @endif
                                        >
                                            {{$act['actionName']}}
                                        </a>
                                    @endforeach
                                    @if(!$pageData["fields"]['legend'])
                                        <legend class="text-bold">{{$pageData['subtitle']}}</legend>
                                    @endif
                                </div>
                                @if($pageData['tips'])
                                    <div class="alert alpha-orange-600 border-orange alert-styled-left">
                                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                                        {{$pageData['tips']}}
                                    </div>
                                @endif
                                @foreach($pageData['fields'] as $f)
                                    @include('formtools::admin.formtooltemp.field', compact('f'))
                                @endforeach
                                <div style="clear: both;"></div>
                                <div class="col-md-6" style="margin-bottom: 15px;">
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
                layer.closeAll();
                layer.msg('请联系管理员', {icon: 5});
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

    $('.formtool-switch-input').each(function () {
        var $input = $(this);
        var $hidden = $input.closest('div').find('input[type="hidden"][name="' + $input.data('target-name') + '"]').first();
        var $label = $input.closest('div').find('.formtool-switch-label').first();

        function syncSwitchState() {
            var checked = $input.is(':checked');
            if ($hidden.length) {
                $hidden.val(checked ? $input.data('on-value') : $input.data('off-value'));
            }
            if ($label.length) {
                $label.text(checked ? $input.data('on-label') : $input.data('off-label'));
            }
        }

        $input.on('change', syncSwitchState);
        syncSwitchState();
    });
</script>
@foreach(($pageData['inlineScripts'] ?? []) as $inlineScript)
    <script>
{!! $inlineScript !!}
    </script>
@endforeach
{{--必须放在最后--}}
<script type="text/javascript"
        src="{{asset("assets/module")}}/js/plugins/forms/selects/select2.min.js"></script>
<script type="text/javascript" src="{{asset("assets/module")}}/js/pages/form_select2.js"></script>
</body>
</html>
