@include(moduleAdminTemplate($pageData['moduleName'])."public.header")
<!-- ============================================================== -->
<body>
<style>
    .formtool-workbench {
        margin-bottom: 18px;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }
    .formtool-workbench__head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        padding: 20px 22px;
        border-bottom: 1px solid #edf2f7;
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
    }
    .formtool-workbench__title {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
    }
    .formtool-workbench__desc {
        margin: 6px 0 0;
        color: #64748b;
        line-height: 1.8;
    }
    .formtool-workbench__sub {
        margin-top: 8px;
        color: #94a3b8;
        line-height: 1.8;
    }
    .formtool-workbench__code {
        display: inline-block;
        margin-left: 6px;
        padding: 2px 8px;
        border-radius: 999px;
        background: #eff6ff;
        color: #1d4ed8;
        font-family: Consolas, Monaco, monospace;
        font-size: 12px;
    }
    .formtool-workbench__actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: flex-end;
    }
    .formtool-workbench__actions .btn {
        border-radius: 999px;
        font-weight: 600;
        min-width: 102px;
    }
    .formtool-workbench__stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
        padding: 18px 22px 22px;
    }
    .formtool-workbench__stat {
        padding: 16px 18px;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #fff;
    }
    .formtool-workbench__stat-label {
        margin: 0 0 8px;
        font-size: 12px;
        color: #64748b;
        letter-spacing: .04em;
        text-transform: uppercase;
    }
    .formtool-workbench__stat-value {
        margin: 0;
        font-size: 28px;
        line-height: 1;
        font-weight: 700;
        color: #0f172a;
    }
    .formtool-workbench__stat-desc {
        margin-top: 8px;
        font-size: 13px;
        color: #94a3b8;
    }
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
    @media (max-width: 991px) {
        .formtool-workbench__head {
            flex-direction: column;
        }
        .formtool-workbench__actions {
            justify-content: flex-start;
        }
        .formtool-workbench__stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
    @media (max-width: 767px) {
        .formtool-workbench__stats {
            grid-template-columns: 1fr;
        }
    }
</style>
@if(!empty($pageData['inlineStyle']))
    <style>{!! $pageData['inlineStyle'] !!}</style>
@endif
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
                @if(!empty($pageData['pageWorkbench']) || !empty($pageData['contentWorkbench']))
                    @php($workbench = $pageData['pageWorkbench'] ?? $pageData['contentWorkbench'])
                    <div class="formtool-workbench">
                        <div class="formtool-workbench__head">
                            <div>
                                @if(!empty($pageData['pageWorkbench']))
                                    <h3 class="formtool-workbench__title">{{$workbench['title'] ?? $pageData['subtitle']}}</h3>
                                    @if(!empty($workbench['desc']))
                                        <p class="formtool-workbench__desc">{{$workbench['desc']}}</p>
                                    @endif
                                    @if(!empty($workbench['sub']))
                                        <div class="formtool-workbench__sub">{{$workbench['sub']}}</div>
                                    @endif
                                @else
                                    <h3 class="formtool-workbench__title">{{$workbench['isEdit'] ? '内容编辑工作台' : '内容新增工作台'}}</h3>
                                    <p class="formtool-workbench__desc">先确认内容标题、状态和前台联调入口，再继续编辑正文、封面和 SEO。</p>
                                    <div class="formtool-workbench__sub">
                                        当前内容：<span class="formtool-workbench__code">{{$workbench['title']}}</span>
                                        数据表：<span class="formtool-workbench__code">{{$workbench['tableName']}}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="formtool-workbench__actions">
                                @foreach(($workbench['actions'] ?? []) as $action)
                                    <a href="{{$action['url'] ?? 'javascript:;'}}" class="{{$action['class'] ?? 'btn btn-default'}}" @if(!empty($action['target'])) target="{{$action['target']}}" @endif @if(!empty($action['confirm'])) onclick='return formtoolConfirmAction(@json($action["confirm"]))' @endif>
                                        {{$action['label'] ?? '操作'}}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        <div class="formtool-workbench__stats">
                            @if(!empty($pageData['pageWorkbench']))
                                @foreach(($workbench['stats'] ?? []) as $stat)
                                    <div class="formtool-workbench__stat">
                                        <p class="formtool-workbench__stat-label">{{$stat['label'] ?? ''}}</p>
                                        <p class="formtool-workbench__stat-value" @if(!empty($stat['valueStyle'])) style="{{$stat['valueStyle']}}" @endif>{{$stat['value'] ?? ''}}</p>
                                        @if(!empty($stat['desc']))
                                            <div class="formtool-workbench__stat-desc">{{$stat['desc']}}</div>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <div class="formtool-workbench__stat">
                                    <p class="formtool-workbench__stat-label">内容字段</p>
                                    <p class="formtool-workbench__stat-value">{{$workbench['fieldCount'] ?? 0}}</p>
                                    <div class="formtool-workbench__stat-desc">当前表单会渲染的内容字段总数</div>
                                </div>
                                <div class="formtool-workbench__stat">
                                    <p class="formtool-workbench__stat-label">必填字段</p>
                                    <p class="formtool-workbench__stat-value">{{$workbench['requiredCount'] ?? 0}}</p>
                                    <div class="formtool-workbench__stat-desc">提交前建议优先检查这些字段</div>
                                </div>
                                <div class="formtool-workbench__stat">
                                    <p class="formtool-workbench__stat-label">审核与 SEO</p>
                                    <p class="formtool-workbench__stat-value">{{$workbench['metaFieldCount'] ?? 0}}</p>
                                    <div class="formtool-workbench__stat-desc">当前模型可用的审核和 SEO 字段数量</div>
                                </div>
                                <div class="formtool-workbench__stat">
                                    <p class="formtool-workbench__stat-label">SEO 字段</p>
                                    <p class="formtool-workbench__stat-value">{{$workbench['seoFieldCount'] ?? 0}}</p>
                                    <div class="formtool-workbench__stat-desc">SEO 标题、关键词、描述会集中显示在下方 SEO 区块</div>
                                </div>
                                <div class="formtool-workbench__stat">
                                    <p class="formtool-workbench__stat-label">当前状态</p>
                                    <p class="formtool-workbench__stat-value" style="font-size: 20px; line-height: 1.3;">{{$workbench['statusLabel'] ?? '未设置'}}</p>
                                    <div class="formtool-workbench__stat-desc">编辑态优先确认审核状态，避免前台误显示</div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <form class="form-horizontal" action="{{$pageData['formaction']}}"
                              method="{{$pageData['method']}}" id="{{$pageData['formid']}}"
                              enctype="multipart/form-data">

                            <fieldset class="content-group">
                                <div>
                                    @foreach($pageData['listActions'] as $act)
                                        @php($formActionUrl = rtrim($act['actionUrl'].'?'.http_build_query($act['param'] ?? []), "?"))
                                        <a class="label {{$act['cssClass']}} pull-right m-t-xs"
                                           style="margin-right: 10px;padding: 3px 8px;font-size: 12px;"
                                           @if($act['popup'])
                                               onclick="popupPage('{{$formActionUrl}}')"
                                           @else
                                               href="{{$formActionUrl}}"
                                           @endif
                                           @if(!empty($act['target'])) target="{{$act['target']}}" @endif>
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
                                @if(!empty($pageData['contentTopHtml']))
                                    {!! $pageData['contentTopHtml'] !!}
                                @endif
                                @foreach(($pageData['fieldGroups'] ?? []) as $group)
                                    @php($heading = $group['heading'] ?? null)
                                    <div class="formtool-group-card @if(($heading['formtype'] ?? '') === 'legend') formtool-group-card--legend @endif">
                                        @if($heading)
                                            @include('formtools::admin.formtooltemplates.groupHeading', ['f' => $heading])
                                        @endif
                                        <div class="row">
                                            @foreach(($group['fields'] ?? []) as $f)
                                                @include('formtools::admin.formtooltemp.field', compact('f'))
                                            @endforeach
                                        </div>
                                    </div>
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
                        if ('{{request()->query('popup', '')}}') {
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

    function normalizeTagsValue(value) {
        return value
            .split(',')
            .map(function (item) { return item.trim(); })
            .filter(function (item) { return item !== ''; })
            .filter(function (item, index, arr) { return arr.indexOf(item) === index; })
            .join(', ');
    }

    $('.formtool-tags').on('blur', function () {
        $(this).val(normalizeTagsValue($(this).val()));
    });

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

    $('.formtool-json').each(function () {
        var $field = $(this);
        var $group = $field.closest('.form-group, .col-md-6');
        var $error = $('<span class="help-block text-danger formtool-json-error" style="display:none;"></span>');
        $field.after($error);

        var validateJson = function (formatWhenValid) {
            var raw = $.trim($field.val());
            if (!raw) {
                $group.removeClass('has-error');
                $error.hide().text('');
                return true;
            }
            try {
                var parsed = JSON.parse(raw);
                if (formatWhenValid) {
                    $field.val(JSON.stringify(parsed, null, 2));
                }
                $group.removeClass('has-error');
                $error.hide().text('');
                return true;
            } catch (e) {
                $group.addClass('has-error');
                $error.text($field.data('json-error') || 'JSON 格式不正确').show();
                return false;
            }
        };

        $field.on('blur', function () {
            validateJson(true);
        });
        $field.on('input', function () {
            validateJson(false);
        });
    });

    function formtoolConfirmAction(message) {
        return window.confirm(message);
    }
</script>
@if(!empty($pageData['inlineScript']))
    <script>{!! $pageData['inlineScript'] !!}</script>
@endif
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
