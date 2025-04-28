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
                            <a class="label bg-info pull-right m-t-xs"
                               href="{{url("admin/formtools/fieldAdd?id=".$pageData['id'])}}">
                                新增
                            </a>
                            {{$pageData['data']->name}} 字段管理
                        </div>
                        <table class="table table-striped m-b-none col-sm-12">
                            <thead>
                            <tr>
                                <th>字段备注</th>
                                <th>字段名称</th>
                                <th>是否必填</th>
                                <th>是否索引</th>
                                <th>字段标识</th>
                                <th>表单类型</th>
                                <th>字段类型</th>
                                <th>字段规则</th>
                                <th>最大长度</th>
                                <th>列表显示</th>
                                <th>关联模型</th>
                                <th>关联字段</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>

                            @forelse($colunmListDetaill as $key=>$d)
                                <tr>
                                    <td>{{$d['remark']}}</td>
                                    <td>{{$d['name']}}</td>
                                    <td>{{$d['required']}}</td>
                                    <td>{{$d['isindex']}}</td>
                                    <td>{{$d['identification']}}</td>
                                    <td>{{$d['formtype']}}</td>
                                    <td>{{$d['fieldtype']}}</td>
                                    <td>{{$d['rule']}}</td>
                                    <td>{{$d['maxlength']}}</td>
                                    <td>
                                        后台列表：
                                        @if($d['is_show_list']==1) <label class="text-success">√</label> @else <label class="text-danger">×</label> @endif<br>
                                        后台搜索：
                                        @if($d['is_show_admin_list_search']==1) <label class="text-success">√</label> @else <label class="text-danger">×</label> @endif<br>
                                        前台表单：
                                        @if($d['is_show_home_form']==1) <label class="text-success">√</label> @else <label class="text-danger">×</label> @endif<br>
                                        前台搜索：
                                        @if($d['is_show_home_list_search']==1) <label class="text-success">√</label> @else <label class="text-danger">×</label> @endif<br>
                                    </td>
                                    <td>{{$d['foreign']}}</td>
                                    <td>{{$d['foreign_key']}}</td>
                                    <td>
                                        @if($key>0)
                                            <a href="{{url("admin/formtools/fieldMove?move_type=1&id=".$pageData['id']."&identification=".$d['identification'])}}"
                                               class="btn btn-primary btn-xs">
                                                上移
                                            </a>
                                        @endif
                                        @if($key!=(count($colunmListDetaill)-1))
                                            <a href="{{url("admin/formtools/fieldMove?move_type=2&id=".$pageData['id']."&identification=".$d['identification'])}}"
                                               class="btn btn-info btn-xs">
                                                下移
                                            </a>
                                        @endif
                                        <a href="{{url("admin/formtools/fieldEdit?&id=".$pageData['id']."&identification=".$d['identification'])}}">
                                            <button type="button" class="h-button-edit btn btn-success btn-xs">
                                                编辑
                                            </button>
                                        </a>
                                        <a onclick="del('{{url("admin/formtools/fieldDel?id=".$pageData['id']."&identification=".$d['identification'])}}')"
                                           class="btn btn-danger btn-xs ">
                                            删除
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8">
                                        暂无数据
                                    </td>
                                </tr>
                            @endforelse


                            </tbody>
                        </table>
                        {{--<div class="col-sm-12 text-right text-center-xs">
                            @if(count($data)>0)
                                {{ $pageData['data']->links() }}
                            @endif
                        </div>--}}
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
    {{--<script type="text/javascript" src="{{asset("assets/module")}}/js/pages/dashboard.js"></script>--}}
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
    </script>
</body>
</html>
