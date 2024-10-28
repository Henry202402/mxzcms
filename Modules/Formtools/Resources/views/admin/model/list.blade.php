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
                            <a class="label bg-info pull-right m-t-xs"
                               href="{{url("admin/".$moduleName."/model?moduleName={$pageData['moduleName']}&action=Add&model=".$pageData['model'])}}">
                                新增
                            </a>
                            模型列表
                        </div>
                        <table class="table table-striped m-b-none col-sm-12">
                            <thead>
                            <tr>
                                <th>ID</th>
                                @foreach($pageData['modeldetaill'] as $f)
                                    <th>{{$f['name']?:$f['remark']}}</th>
                                @endforeach
                                <th>创建时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>

                            @forelse($pageData['datas'] as $d)
                                <tr>
                                    <td>{{$d->id}}</td>
                                    @foreach($pageData['modeldetaill'] as $f)
                                        <td>
                                            @if($f['datas'])
                                                {{$f['datas'][toArray($d)[$f['identification']]]}}
                                            @elseif(in_array($f['formtype'],['upload','uploadAjax', 'image', 'imageAjax']))
                                                @if(in_array(strtolower(end(explode('.',toArray($d)[$f['identification']]))),['jpg','jpeg','png']))
                                                    <img src="{{GetUrlByPath(toArray($d)[$f['identification']])}}"
                                                         class="cursor-pointer" width="30"
                                                         onclick="clickImage('{{GetUrlByPath(toArray($d)[$f['identification']])}}')">
                                                @elseif(toArray($d)[$f['identification']])
                                                    <i class="cursor-pointer icon-file-download2"
                                                       onclick="fileDownload('{{GetUrlByPath(toArray($d)[$f['identification']])}}')"
                                                       style="font-size: 25px;"></i>
                                                @endif
                                            @else
                                                {{toArray($d)[$f['identification']]}}
                                            @endif
                                        </td>
                                    @endforeach
                                    <td>{{$d->created_at}}</td>
                                    <td>

                                        <a href="{{url("admin/".$moduleName."/model?action=Edit&moduleName={$pageData['moduleName']}&model=".$pageData['model']."&id=".$d->id.'&page='.$pageData['datas']->currentPage())}}">
                                            <button type="button" class="h-button-edit btn btn-success btn-xs">
                                                编辑
                                            </button>
                                        </a>
                                        <a onclick="del('{{url("admin/{$moduleName}/model?action=Del&moduleName={$pageData['moduleName']}&model={$pageData['model']}&id={$d->id}&page={$pageData["datas"]->currentPage()}")}}')"
                                           class="btn btn-danger btn-xs ">
                                            删除
                                        </a>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="20">
                                        暂无数据
                                    </td>
                                </tr>
                            @endforelse


                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /bordered striped table -->

                <div class="col-sm-12 text-right text-center-xs">
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
