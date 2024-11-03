@include(moduleAdminTemplate($moduleName)."public.header")
<style>
    .h-invitation-list {
        cursor: pointer;
    }
</style>
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
                                <a class="label pull-right m-t-xs" style="margin-top: -7px;"
                                   href="#" onclick="resetModelData('{{url('admin/formtools/resetModelData')}}')">
                                    <button type="button" class="h-button-edit btn  btn-danger btn-xs">
                                        重置模型数据
                                    </button>
                                </a>
                                <a class="label pull-right m-t-xs" style="margin-top: -7px;"
                                   href="#" onclick="synmodel('{{url('admin/formtools/synmodel')}}')" >
                                    <button type="button" class="h-button-edit btn btn-success btn-xs">
                                        同步默认模型
                                    </button>
                                </a>
                                <a class="label pull-right m-t-xs" style="margin-top: -7px;"
                                   href="{{url("admin/formtools/modelAdd")}}" >
                                    <button type="button" class="h-button-edit btn btn-info  btn-xs">
                                        新增
                                    </button>
                                </a>
                                模型列表
                            </div>
                            <table class="table table-striped m-b-none col-sm-12">
                                <thead>
                                <tr>
                                    <th>id</th>
                                    <th>名称</th>
                                    <th>模型标识</th>
                                    <th>一级菜单名称</th>
                                    <th>菜单icon</th>
                                    <th>时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>

                                @forelse($pageData['datas'] as $d)
                                    <tr>
                                        <td>{{$d->id}}</td>
                                        <td>{{$d->name}}</td>
                                        <td>{{$d->identification}}</td>
                                        <td>{{$d->menuname}}</td>
                                        <td>{{$d->icon}}</td>
                                        <td>{{date("Y-m-d H:i:s"),$d->created_at}}</td>
                                        <td>
                                            <a href="{{url("admin/formtools/fieldList?id=".$d->id)}}">
                                                <button type="button" class="h-button-edit btn btn-info btn-xs">
                                                    字段管理
                                                </button>
                                            </a>
                                            <a href="{{url("admin/formtools/modelEdit?id=".$d->id)}}">
                                                <button type="button" class="h-button-edit btn btn-success btn-xs">
                                                    编辑
                                                </button>
                                            </a>
                                            <a onclick="del('{{url("admin/formtools/modelDelete?id=".$d->id)}}')"
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
{{--<script type="text/javascript" src="{{moduleAdminResource($moduleName)}}/js/pages/dashboard.js"></script>--}}
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
        function synmodel(url) {
            layer.confirm('确定要同步默认模型吗？', {
                title: "操作提示",
                btn: ['确定', '取消'] //可以无限个按钮
            }, function (index, layero) {
                //按钮【按钮一】的回调
                window.location.href = url;
            }, function (index) {
                //按钮【按钮二】的回调
            });
        }
        function resetModelData(url) {
            layer.confirm('确定要重置模型数据吗？', {
                title: "操作提示",
                btn: ['确定', '取消'] //可以无限个按钮
            }, function (index, layero) {
                //按钮【按钮一】的回调
                window.location.href = url;
            }, function (index) {
                //按钮【按钮二】的回调
            });
        }
    </script>
</body>
</html>
