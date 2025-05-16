@include(moduleAdminTemplate($moduleName)."public.header")
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
                <div class="">
                    <div class="table-responsive panel panel-default">
                        <div class="panel-heading">
                            @if(!$pageData['datas']->id)
                                <a class="label pull-right m-t-xs" style="margin-top: -8px;"
                                   href="{{url("admin/".$moduleName."/model?moduleName={$pageData['moduleName']}&action=Add&model=".$pageData['model'])}}">
                                    <button type="button" class="btn bg-info">
                                        新增
                                    </button>
                                </a>
                            @endif
                            单页模型
                        </div>
                        <table class="table table-striped m-b-none col-sm-12">

                            @if($pageData['datas']->id)
                                <tr>
                                    <th width="120">创建时间</th>
                                    <td>{{$pageData['datas']->created_at}}</td>
                                </tr>
                                @foreach($pageData['modeldetaill'] as $f)
                                    <tr>
                                        <th style="vertical-align: top;">{{$f['name']?:$f['remark']}}</th>
                                        <td>
                                            @if($f['datas'])
                                                {{$f['datas'][toArray($pageData['datas'])[$f['identification']]]}}
                                            @elseif(in_array($f['formtype'],['upload','uploadAjax', 'image', 'imageAjax']))
                                                @if(in_array(strtolower(end(explode('.',toArray($pageData['datas'])[$f['identification']]))),['jpg','jpeg','png']))
                                                    <img src="{{GetUrlByPath(toArray($pageData['datas'])[$f['identification']])}}"
                                                         class="cursor-pointer" width="30"
                                                         onclick="clickImage('{{GetUrlByPath(toArray($pageData['datas'])[$f['identification']])}}')">
                                                @elseif(toArray($pageData['datas'])[$f['identification']])
                                                    <i class="cursor-pointer icon-file-download2"
                                                       onclick="fileDownload('{{GetUrlByPath(toArray($pageData['datas'])[$f['identification']])}}')"
                                                       style="font-size: 25px;"></i>
                                                @endif
                                            @else
                                                {!! toArray($pageData['datas'])[$f['identification']]  !!}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="2">

                                        <a href="{{url("admin/".$moduleName."/model?action=Edit&moduleName={$pageData['moduleName']}&model=".$pageData['model']."&id=".$pageData['datas']->id)}}">
                                            <button type="button" class="h-button-edit btn btn-success btn-xs">
                                                编辑
                                            </button>
                                        </a>
                                        <a onclick="del('{{url("admin/{$moduleName}/model?action=Del&moduleName={$pageData['moduleName']}&model={$pageData['model']}&id={$pageData['datas']->id}")}}')"
                                           class="btn btn-danger btn-xs ">
                                            删除
                                        </a>

                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="2">暂无数据</td>
                                </tr>
                            @endif

                        </table>
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
        function del(url) {
            layer.confirm('确定要删除吗？', {
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
