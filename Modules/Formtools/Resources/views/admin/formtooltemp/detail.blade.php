@include(moduleAdminTemplate($pageData['moduleName'])."public.header")
<!-- ============================================================== -->
<body>
@include(moduleAdminTemplate($pageData['moduleName'])."public.nav")
<!-- Page container -->
<div class="page-container">
    <!-- Page content -->
    <div class="page-content">
    @include(moduleAdminTemplate($pageData['moduleName'])."public.left")
    <!-- Main content -->
        <div class="content-wrapper">
            <!-- Content area -->
            <div class="content">
                @include(moduleAdminTemplate($pageData['moduleName'])."public.page",['breadcrumb'=>[$pageData['title'],$pageData['subtitle']]])
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <form class="form-horizontal" action="{{$pageData['formaction']}}"
                              method="{{$pageData['method']}}" id="{{$pageData['formid']}}"
                              enctype="multipart/form-data">
                            <fieldset class="content-group">
                                <legend class="text-bold">{{$pageData['subtitle']}}</legend>
                                @foreach($pageData['fields'] as $f)
                                    @if($f['datas'])
                                        <div class="form-group row">
                                            <label class="col-lg-1 control-label">{{$f['name']}}</label>
                                            <div class="col-lg-11">
                                                @if($f['formtype']=="select")
                                                    <select class="form-control" name="{{$f['identification']}}"
                                                            id="{{$f['identification']}}"
                                                        {{$f['disabled']}}
                                                    >
                                                        @foreach($f['datas'] as $k=>$v)
                                                            <option value="{{$k}}"
                                                                {{toArray($pageData['detail'])[$f['identification']]==$k?'selected':''}}
                                                            >{{$v}}</option>
                                                        @endforeach
                                                    </select>
                                                @elseif($f['formtype']=="radio" || $f['formtype']=="checkbox")
                                                    @foreach($f['datas'] as $k=>$v)
                                                        <label class="{{$f['formtype']}}-inline">
                                                            <input type="{{$f['formtype']}}" name="{{$f['identification']}}"
                                                                   value="{{$v['value']}}"
                                                                   @if(in_array($v['value'],explode(",",toArray($pageData['detail'])[$f['identification']])))
                                                                          checked
                                                                   @endif
                                                                {{$f['disabled']}}
                                                            >{{$v['name']}}
                                                        </label>
                                                    @endforeach
                                                @else
                                                    <input type="text" id="{{$f['identification']}}"
                                                           name="{{$f['identification']}}"
                                                           class="form-control"
                                                           placeholder="{{$f['placeholder']?:$f['name']}}"
                                                           value="{{$f['datas'][toArray($pageData['detail'])[$f['identification']]]}}"
                                                        {{$f['disabled']}}
                                                    >
                                                @endif

                                            </div>
                                        </div>
                                    @elseif(in_array($f['formtype'],['upload', 'image']))
                                        <div class="form-group row">
                                            <label class="col-lg-1 control-label">{{$f['name']}}</label>
                                            <div class="col-lg-11">
                                                <div class="media no-margin-top">
                                                    <div class="media-left">
                                                        @if(in_array(end(explode('.',toArray($pageData['detail'])[$f['identification']])),['jpg','jpeg','png']))
                                                            <img src="{{GetUrlByPath(toArray($pageData['detail'])[$f['identification']])}}"
                                                                 class="cursor-pointer" width="30"
                                                                 onclick="clickImage('{{GetUrlByPath(toArray($pageData['detail'])[$f['identification']])}}')">
                                                        @else
                                                            <i class="cursor-pointer icon-file-download2"
                                                               onclick="fileDownload('{{GetUrlByPath(toArray($pageData['detail'])[$f['identification']])}}')"
                                                               style="font-size: 25px;"></i>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        @include(moduleAdminTemplate("formtools")."formtooltemplates.".$f['formtype'],compact( 'f'))
                                    @endif

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
{{--必须放在最后--}}
</body>
</html>
