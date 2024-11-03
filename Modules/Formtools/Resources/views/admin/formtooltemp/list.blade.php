@include(moduleAdminTemplate($pageData['moduleName'])."public.header")
<body>
@include(moduleAdminTemplate($pageData['moduleName'])."public.nav")
<div class="page-container">
    <div class="page-content">
        @include(moduleAdminTemplate($pageData['moduleName'])."public.left")
        <div class="content-wrapper">
            <div class="content" style="margin-top: 1rem;">
                @include(moduleAdminTemplate($pageData['moduleName'])."public.page",['breadcrumb'=>[$pageData['title'],$pageData['subtitle']]])
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
                    <div class="table-responsive panel panel-default">
                        <div class="panel-heading">
                            @foreach($pageData['listActions'] as $act)
                                <a class="label  pull-right m-t-xs"
                                   style="margin-top: -9px"
                                   href="{{rtrim($act['actionUrl'].'?'.http_build_query(array_merge($act['param']?:[],[$f['actionby']=>toArray($d)[$f['actionby']]])),"?")}}">
                                    <button type="button" class="btn {{$act['cssClass']}}">
                                        {{$act['actionName']}}
                                    </button>
                                </a>
                            @endforeach
                            @if($pageData['leftListActions'])
                                @foreach($pageData['leftListActions'] as $leftListActions)
                                    @foreach($leftListActions as $left)
                                        <a class="label  {{$left['cssClass']}} m-t-xs"
                                           style="margin-right: 10px;padding: 3px 8px;font-size: 12px;"
                                           onclick="clickOperate('{{$left['actionUrl']}}','{{$left['confirm']}}','{{$left['isMoreSelect']}}')"
                                        >
                                            {{$left['actionName']}}-------
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
                                    <th>{{$f['name']}}</th>
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
                                                                <a
                                                                        @if($act['confirm'])
                                                                        onclick="confirm('{{$act['actionUrl']}}?{{http_build_query(array_merge($act['param']?:[],[$f['actionby']=>toArray($d)[$f['actionby']]]))}}')"
                                                                        @else
                                                                        href="{{$act['actionUrl']}}?{{http_build_query(array_merge($act['param']?:[],[$f['actionby']=>toArray($d)[$f['actionby']]]))}}">
                                                                    @endif

                                                                    <button type="button"
                                                                            class="h-button-edit btn {{$act['cssClass']}} btn-xs">
                                                                        {{$act['actionName']}}
                                                                    </button>
                                                                </a>
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
    <script>
        function confirm(url) {
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

        function clickOperate(url, confirm, isMoreSelect) {
            if (allSelectId.length <= 0) return layer.msg('请选择记录');
            if (!isMoreSelect && allSelectId.length > 1) return layer.msg('只能选择一条记录');
            var allSelectIdStr = allSelectId.join(',');
            if (confirm) {
                layer.confirm('确定要操作吗？', {
                    title: "操作提示",
                    btn: ['确定', '取消'] //可以无限个按钮
                }, function (index, layero) {
                    //按钮【按钮一】的回调
                    window.location.href = url + "?id=" + allSelectIdStr;
                }, function (index) {
                    //按钮【按钮二】的回调
                });
            } else {
                window.location = url + "?id=" + allSelectIdStr;
            }
        }

    </script>
</body>
</html>
