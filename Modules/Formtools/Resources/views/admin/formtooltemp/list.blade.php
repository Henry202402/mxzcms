@include(moduleAdminTemplate($pageData['moduleName'])."public.header")
<body>
@if(!$pageData['popup'])
    @include(moduleAdminTemplate($pageData['moduleName'])."public.nav")
@endif
<div class="page-container">
    <div class="page-content">
        @if(!$pageData['popup'])
            @include(moduleAdminTemplate($pageData['moduleName'])."public.left")
        @endif
        <div class="content-wrapper">
            <div class="content" style="margin-top: 1rem;">
                @if(!$pageData['popup'])
                    @include(moduleAdminTemplate($pageData['moduleName'])."public.page",['breadcrumb'=>[$pageData['title'],$pageData['subtitle']]])
                @endif
                @if($pageData['searchFields'])
                    <form class="bs-example form-horizontal" method="get">
                        <div class="form-group">
                            @foreach($pageData['searchFields'] as $f)
                                @if(!in_array($f['formtype'],['hidden']))
                                    <div class="col-lg-2 mt-5">
                                        @endif
                                        @include(moduleAdminTemplate("formtools")."formsearchtemplates.".$f['formtype'],compact( 'f'))
                                        @if(!in_array($f['formtype'],['hidden']))
                                    </div>
                                @endif
                            @endforeach
                            <div class="col-lg-2 mt-5">
                                <button type="submit" class="btn btn-sm btn-info">
                                    搜索
                                </button>
                                <a href="{{$pageData['searchClearEmpty']?:url()->current()}}">
                                    <button type="button" class="btn btn-sm btn-danger">
                                        清空
                                    </button>
                                </a>
                            </div>
                        </div>
                    </form>
                @endif
                <div class="">
                    @if($pageData['tips'])
                        <div class="alert alpha-orange-600 border-orange alert-styled-left">
                            <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                            {{$pageData['tips']}}
                        </div>
                    @endif
                    <div class="table-responsive panel panel-default">
                        <div class="panel-heading">
                            @foreach($pageData['listActions'] as $act)
                                @php($topActionUrl = rtrim($act['actionUrl'].'?'.http_build_query($act['param'] ?? []), "?"))
                                <a class="label {{$act['cssClass']}} pull-right m-t-xs"
                                   style="margin-right: 10px;padding: 3px 8px;font-size: 12px;"
                                   @if($act['popup'])
                                       onclick="popupPage('{{$topActionUrl}}')"
                                   @else
                                       href="{{$topActionUrl}}"
                                   @endif
                                   @if(!empty($act['target'])) target="{{$act['target']}}" @endif>
                                    {{$act['actionName']}}
                                </a>
                            @endforeach
                            @if($pageData['leftListActions'])
                                @foreach($pageData['leftListActions'] as $leftListActions)
                                    @foreach($leftListActions as $left)
                                        @php($leftActionUrl = rtrim($left['actionUrl'].'?'.http_build_query($left['param'] ?? []), "?"))
                                        <a class="label  {{$left['cssClass']}} m-t-xs"
                                           style="margin-right: 10px;padding: 3px 8px;font-size: 12px;"
                                           @if($left['popup'])
                                           onclick="popupPage('{{$leftActionUrl}}')"
                                           @else
                                           onclick="clickOperate('{{$left['actionUrl']}}','{{$left['confirm']}}','{{$left['isMoreSelect']}}','{{$left['noNeedId']}}')"
                                           @endif
                                           @if(!empty($left['target'])) target="{{$left['target']}}" @endif
                                        >
                                            {{$left['actionName']}}
                                        </a>
                                    @endforeach
                                    <br>
                                    <div style="margin-bottom: 5px"></div>
                                @endforeach
                            @else
                                {{$pageData['subtitle']?:"数据列表"}}
                            @endif
                        </div>
                        <table class="table table-striped col-sm-12">
                            <thead>
                            <tr>
                                @if($pageData['isShowMoreCheckbox'])
                                    <th>
                                        <input type="checkbox" id="selAll" onclick="doSelectAll()"/>
                                    </th>
                                @endif
                                @foreach($pageData['fields'] as $f)
                                    <th style="min-width: 110px;">{{$f['name']}}</th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($pageData['datas'] as $d)
                                <?php $isShowMoreCheckbox = 0;?>
                                <tr>
                                    @foreach($pageData['fields'] as $fk=>$f)
                                        @if($f['identification']!="rightaction")
                                            @if($pageData['isShowMoreCheckbox'] && $isShowMoreCheckbox==0)
                                                <td>
                                                    <input type="checkbox" name="selectedRow" onclick="doSelectOne()"
                                                           value="{{toArray($d)[$pageData['isShowMoreCheckbox']]}}"/>
                                                </td>
                                                <?php $isShowMoreCheckbox++;?>
                                            @endif
                                            @if($f['callback'])
                                                <td class="{{is_array($f['cssClass'])?$f['cssClass'][toArray($d)[$f['identification']]]:$f['cssClass']}}"
                                                    @if($f['jsfunction']) onclick="{{$f['jsfunction']}}('{{toArray($d)[$f['identification']]}}')" @endif>
                                                    @if($f['datas'])
                                                        <label class="label {{toArray($d)[$f['identification'].'CssClass']?:'label-info'}}">{{$f['callback']($f['datas'][toArray($d)[$f['identification']]])}}</label>
                                                    @elseif(strpos($f['identification'], ',') !== false)
                                                        @foreach(explode(',',$f['identification']) as $name)
                                                            {{toArray($d)[$name]}}<br>
                                                        @endforeach
                                                    @else
                                                        {{$f['callback'](toArray($d)[$f['identification']])}}
                                                    @endif
                                                </td>
                                            @elseif(in_array($f['formtype'],['upload', 'image']))
                                                <td>
                                                    @if(in_array(strtolower(end(explode('.',toArray($d)[$f['identification']]))),['jpg','jpeg','png']))
                                                        <img src="{{GetUrlByPath(toArray($d)[$f['identification']])}}"
                                                             class="cursor-pointer" width="30"
                                                             onclick="clickImage('{{GetUrlByPath(toArray($d)[$f['identification']])}}')">
                                                    @elseif(toArray($d)[$f['identification']])
                                                        <i class="cursor-pointer icon-file-download2"
                                                           onclick="fileDownload('{{GetUrlByPath(toArray($d)[$f['identification']])}}')"
                                                           style="font-size: 25px;"></i>
                                                    @endif
                                                </td>
                                            @else
                                                <td class="{{is_array($f['cssClass'])?$f['cssClass'][toArray($d)[$f['identification']]]:$f['cssClass']}}"
                                                    @if($f['jsfunction']) onclick="{{$f['jsfunction']}}('{{toArray($d)[$f['identification']]}}')" @endif>
                                                    @if($f['datas'])
                                                        <label class="label {{toArray($d)[$f['identification'].'CssClass']?:'label-info'}}">{{$f['datas'][toArray($d)[$f['identification']]]}}</label>
                                                    @elseif(strpos($f['identification'], ',') !== false)
                                                        @foreach(explode(',',$f['identification']) as $name)
                                                            @if(toArray($d)[$name])
                                                                {{toArray($d)[$name]}}<br>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        @if($f['aInfo'])
                                                            <a href="{{$f['aInfo']['href']}}?{{http_build_query(array_merge($f['aInfo']['param']?:[],[$f['aInfo']['actionby']=>toArray($d)[$f['aInfo']['actionby']]]))}}">
                                                                {{toArray($d)[$f['identification']]}}
                                                            </a>
                                                        @else
                                                            {{toArray($d)[$f['identification']]}}
                                                        @endif
                                                    @endif

                                                </td>
                                            @endif




                                        @elseif($f['identification']=="rightaction")
                                            <td>
                                                @if(!in_array(toArray($d)[$f['actionby']],$f['notIdArray']?:[]))
                                                    @foreach($f['datas'] as $act)
                                                        @if(!$act["show"] || ($act["show"] && toArray($d)[$act["show"]['field']] == $act["show"]['value']))
                                                            @if(!in_array(toArray($d)[$f['actionby']],$act['notIdArray']?:[]))
                                                                @if($act['actionType']=="modal")
                                                                    <button type="button"  class="btn {{$act['cssClass']}} btn-sm" data-toggle="modal" data-target="#modal_default_{{toArray($d)['id']}}">{{$act['actionName']}}</button>
                                                                    <!-- Basic modal -->
                                                                    <div id="modal_default_{{toArray($d)['id']}}" class="modal fade">
                                                                        <div class="modal-dialog">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                    <h5 class="modal-title">{{toArray($d)[$act['titleField']]?:$act['titleField']}}的详情</h5>
                                                                                </div>

                                                                                <div class="modal-body" id="modal_default_content_{{toArray($d)['id']}}" style="height: 600px;overflow: auto">
                                                                                    {!! toArray($d)[$act['field']] !!}
                                                                                </div>

                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-link" data-dismiss="modal">关闭</button>
                                                                                    <button type="button" class="btn btn-info" onclick="exportImg('modal_default_content_{{toArray($d)['id']}}')">导出为图片</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- /basic modal -->
                                                                @else
                                                                    <a
                                                                            @if($act['popup'])
                                                                            onclick="popupPage('{{rtrim($act['actionUrl'].'?'.http_build_query(array_merge($act['param']?:[],[$f['actionby']=>toArray($d)[$f['actionby']]])),"?")}}')"
                                                                            @elseif($act['confirm'])
                                                                            onclick="formtoolConfirmNavigate('{{$act['actionUrl']}}?{{http_build_query(array_merge($act['param']?:[],[$f['actionby']=>toArray($d)[$f['actionby']]]))}}')"
                                                                            @else
                                                                            href="{{$act['actionUrl']}}?{{http_build_query(array_merge($act['param']?:[],[$f['actionby']=>toArray($d)[$f['actionby']]]))}}">
                                                                        @endif
                                                                        @if(!empty($act['target'])) target="{{$act['target']}}" @endif

                                                                        <button type="button"
                                                                                class="h-button-edit btn {{$act['cssClass']}} btn-xs">
                                                                            {{$act['actionName']}}
                                                                        </button>
                                                                    </a>
                                                                @endif

                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </td>
                                        @endif
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{count($pageData['fields'])}}">
                                        暂无数据
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>
                <div class="col-sm-12 text-right text-center-xs">
                    {{ $pageData['datas']->appends($pageData['linkAppend'])->links('formtools::admin.public.pagination',["data"=>$pageData['datas']]) }}
                </div>
                @include(moduleAdminTemplate($pageData['moduleName'])."public.footer")
            </div>
        </div>
    </div>
    @include(moduleAdminTemplate($pageData['moduleName'])."public.js")
{{--    <script type="text/javascript" src="{{asset("assets/module")}}/js/pages/components_modals.js"></script>--}}
    <!-- html2canvas：截图用 -->
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script>
        function exportImg(id) {
            const element = document.getElementById(id);

            // 保存原始样式
            const originalStyle = {
                height: element.style.height,
                overflow: element.style.overflow,
            };

            // 展开内容，移除滚动条
            element.style.height = 'auto';
            element.style.overflow = 'visible';
            // 延迟一点，等待样式生效
            setTimeout(() => {
                html2canvas(element, { scale: 2 }).then(canvas => {
                    // 还原样式
                    element.style.height = originalStyle.height;
                    element.style.overflow = originalStyle.overflow;
                    // 下载图片
                    const link = document.createElement('a');
                    link.download = 'export.png';
                    link.href = canvas.toDataURL('image/png');
                    link.click();
                });
            }, 200);
        }
        function formtoolConfirmNavigate(url) {
            layer.confirm('确定要操作吗？', {
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

        //全选、全反选
        var allSelectId = [];

        function doSelectAll() {
            $("input[name=selectedRow]").prop("checked", $("#selAll").is(":checked"));
            allSelectId = $("input:checkbox[name='selectedRow']:checked").map(function (index, elem) {
                return $(elem).val();
            }).get();
            // allSelectIdStr = allSelectId.join(',');
        }

        function doSelectOne() {
            allSelectId = $("input:checkbox[name='selectedRow']:checked").map(function (index, elem) {
                return $(elem).val();
            }).get();
        }

        function clickOperate(url, confirm, isMoreSelect, noNeedId) {
            if (noNeedId) return window.location.href = url;
            if (allSelectId.length <= 0) return layer.msg('请选择记录');
            if (!isMoreSelect && allSelectId.length > 1) return layer.msg('只能选择一条记录');
            var allSelectIdStr = allSelectId.join(',');
            var separator = url.indexOf('?') === -1 ? '?' : '&';
            var targetUrl = url + separator + "id=" + allSelectIdStr;
            if (confirm) {
                layer.confirm('确定要操作吗？', {
                    title: "操作提示",
                    btn: ['确定', '取消'] //可以无限个按钮
                }, function (index, layero) {
                    //按钮【按钮一】的回调
                    return window.location.href = targetUrl;
                }, function (index) {
                    //按钮【按钮二】的回调
                });
            } else {
                return window.location = targetUrl;
            }
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
@foreach(($pageData['inlineScripts'] ?? []) as $inlineScript)
    <script>
{!! $inlineScript !!}
    </script>
@endforeach
</body>
</html>
