@include(moduleAdminTemplate($moduleName)."public.header")
<style>
    .h-cp {
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

        <!-- Main sidebar -->
        <div class="sidebar sidebar-main">
            <div class="sidebar-content">
                @include(moduleAdminTemplate($moduleName)."public.left")
            </div>
        </div>
        <!-- /main sidebar -->


        <!-- Main content -->
        <div class="content-wrapper">

            <!-- Page header -->
            <div class="page-header">

            @include(moduleAdminTemplate($moduleName)."public.page",
            ['breadcrumb'=>[$pageData['title'],$pageData['subtitle']]])

            <!-- Content area -->
                <div class="content" style="margin-top: 1rem;">


                    <!-- Bordered striped table -->
                    <div class="col-sm-12">
                        <div class="table-responsive panel panel-default">
                            <div class="panel-heading">
                                <a class="label bg-info pull-right m-t-xs {{permissions('secure/scheduledTasksAdd')}}"
                                   href="{{moduleAdminJump($moduleName,'secure/scheduledTasksAdd')}}">
                                    添加
                                </a>
                                任务列表
                            </div>
                            <table class="table table-striped m-b-none col-sm-12">
                                <thead>
                                <tr>
                                    <th>id</th>
                                    <th>模块</th>
                                    <th>任务名称</th>
                                    <th>状态</th>
                                    <th>执行周期</th>
                                    <th>上次执行时间</th>
                                    <th>备注</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($pageData['data'] as $d)
                                    <tr>
                                        <td>{{$d['id']}}</td>
                                        <td>
                                            {{$pageData['moduleList'][$d['module']]}}
                                        </td>
                                        <td>{{$d['name']}}</td>
                                        <td class="h-cp @if($d['status']==1) text-success @else text-danger @endif">
                                            <label>{{\Modules\System\Services\ServiceModel::taskStatus()[$d['status']]}}</label>
                                            @if($d['status']==1)
                                                <i class="icon-play4"
                                                   onclick="_confirm('{{moduleAdminJump($moduleName,'secure/scheduledTasksEdit')}}',{
                                                           '_method':'PUT','_token':'{{csrf_token()}}','id':'{{$d['id']}}','update_type':1,
                                                           'status':2,
                                                           },'你确定要停用吗？')"></i>
                                            @else
                                                <i class="icon-pause2"
                                                   onclick="_confirm('{{moduleAdminJump($moduleName,'secure/scheduledTasksEdit')}}',{
                                                           '_method':'PUT','_token':'{{csrf_token()}}','id':'{{$d['id']}}','update_type':1,
                                                           'status':1,
                                                           },'你确定要启用吗？')"></i>
                                            @endif
                                        </td>
                                        <td>
                                            {{\Modules\System\Services\ServiceModel::type_msg($d['day'],$d['hour'],$d['minute'])[$d['type']]}}
                                        </td>
                                        <td>{{$d['last_execution_time']}}</td>
                                        <td>{{$d['remark']}}</td>
                                        <td>

                                            <button type="button"
                                                    class="btn btn-success btn-xs {{permissions('secure/scheduledTasksExecute')}}"
                                                    onclick="_confirm('{{moduleAdminJump($moduleName,'secure/scheduledTasksExecute')}}',{
                                                            '_method':'PUT','_token':'{{csrf_token()}}','id':'{{$d['id']}}'
                                                            },'你确定要执行吗？')">
                                                执行
                                            </button>
                                            <button type="button"
                                                    class="btn btn-primary btn-xs {{permissions('secure/scheduledTasksLog')}}"
                                                    onclick="scheduledTasksLog('{{$d['id']}}')">
                                                日志
                                            </button>
                                            <a href="{{moduleAdminJump($moduleName,'secure/scheduledTasksEdit?id='.$d['id'])}}"
                                               class="{{permissions('secure/scheduledTasksEdit')}}">
                                                <button type="button" class="h-button-edit btn btn-info btn-xs">
                                                    编辑
                                                </button>
                                            </a>
                                            <button type="button"
                                                    class="btn btn-danger btn-xs {{permissions('secure/scheduledTasksDelete')}}"
                                                    onclick="_confirm('{{moduleAdminJump($moduleName,'secure/scheduledTasksDelete')}}',{
                                                            '_method':'DELETE','_token':'{{csrf_token()}}','id':'{{$d['id']}}'
                                                            },'你确定要删除吗？')">
                                                删除
                                            </button>
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
                    <div class="col-sm-12">
                        <div class="table-responsive panel panel-default">
                            <div class="panel-heading">
                                未添加任务列表
                            </div>
                            <table class="table table-striped m-b-none col-sm-12">
                                <thead>
                                <tr>
                                    <th>模块</th>
                                    <th>路径方法</th>
                                    <th>备注</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($pageData['noAddList'] as $d)
                                    <tr>
                                        <td>
                                            {{$pageData['moduleList'][$d[0]]}}
                                        </td>
                                        <td>
                                            {{$d[1].'@'.$d[2]}}
                                        </td>
                                        <td>
                                            {{$d[3]}}
                                        </td>
                                        <td>
                                            <a href="{{moduleAdminJump($moduleName,'secure/scheduledTasksAdd?info='.$d[4])}}"
                                               class="{{permissions('secure/scheduledTasksEdit')}}">
                                                <button type="button" class="h-button-edit btn btn-info btn-xs">
                                                    添加
                                                </button>
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

                    <!-- Footer -->
                @include(moduleAdminTemplate($moduleName)."public.footer")
                <!-- /footer -->

                </div>
                <!-- /content area -->

            </div>
            <!-- /main content -->

        </div>
        <!-- /page content -->
    </div>

    <!-- /content -->
    <!-- 						Content End		 						-->
    <!-- ============================================================== -->
    @include(moduleAdminTemplate($moduleName)."public.js")
    <script>
        //推送设置
        function scheduledTasksLog(id) {
            var scheduledTasksLog = layer.open({
                title: '任务执行日志',
                type: 2,
                area: ['50%', '550px'],
                content: '{{moduleAdminJump($moduleName,'secure/scheduledTasksLog?id=')}}' + id,
                closeBtn: 1,
                btn: ['关闭']
            });
            // layer.full(scheduledTasksLog);
        }
    </script>
</body>
</html>
