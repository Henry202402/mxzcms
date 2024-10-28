@include(moduleAdminTemplate($moduleName)."public.header")

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

                    <div class="table-responsive panel panel-default">
                        <div class="panel-heading">
                            列表
                        </div>
                        <table class="table table-bordered triptable-sed">
                            <thead>
                            <tr>
                                <th>#ID</th>
                                <th>模块名称</th>
                                <th>域名</th>
                                <th>域名数量</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($data as $d)
                                <tr>
                                    <td>{{$d['id']}}</td>
                                    <td>{{$d['name']}}</td>
                                    <td>{{$d['domain']['domain']}}</td>
                                    <td>{{$d['domain']['num']}}</td>
                                    <td>
                                        <a href="javascript:void(0)"
                                           onclick="editPage({{$d['id']}})"
                                           class="{{permissions('novel/sequenceEdit')}}">
                                            <button type="button" class="h-button-edit btn btn-info btn-xs">
                                                编辑
                                            </button>
                                        </a>
                                    </td>
                                </tr>
                                {{--编辑权限组--}}
                                <div id="groupEdit{{$d['id']}}" style="display: none">
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label class="col-lg-4 control-label">
                                                模块名称
                                            </label>
                                            <div class="col-lg-9">
                                                <input type="text" id="name{{$d['id']}}"
                                                       class="form-control"
                                                       value="{{$d['name']}}" disabled>
                                                <input type="hidden" id="module_id{{$d['id']}}" class="form-control"
                                                       value="{{$d['id']}}">
                                            </div>
                                            <div class="col-lg-3">
                                                <button type="button" onclick="edit({{$d['id']}})"
                                                        class="btn btn-sm btn-info">
                                                    提交
                                                </button>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-12 control-label" style="margin-top: 6px;">
                                                域名列表(多个域名，按回车键隔开)
                                            </label>
                                            <div class="col-lg-9">
                                                <textarea id="domain{{$d['id']}}" class="form-control"
                                                          rows="14">{!! str_replace(",","\n",$d['domain']['domain']) !!}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                    <!-- Footer -->
                @include(moduleAdminTemplate($moduleName)."public.footer")
                <!-- /footer -->

                </div>
                <!-- /content area -->

            </div>
            <!-- /main content -->

        </div>
    </div>

    <!-- 						Content End		 						-->
    <!-- ============================================================== -->
    @include(moduleAdminTemplate($moduleName)."public.js")
    <script>

        function editPage(id) {
            //页面层
            layer.open({
                type: 1,
                title: alert_info,
                skin: 'layui-layer-rim', //加上边框
                area: ['500px', '500px'], //宽高
                content: $('#groupEdit' + id)
            });
        }

        function edit(id) {
            var module_id = $('#module_id' + id).val();
            var domain = $('#domain' + id).val();
            if (!module_id) return layer.msg('模块不能为空', {icon: 2});

            $.post('{{moduleAdminJump($moduleName,'setting/moduleBindDomainSubmit')}}',
                {
                    _method: 'PUT',
                    _token: '{{csrf_token()}}',
                    id: id,
                    module_id: module_id,
                    domain: domain,
                },
                function (data) {
                    if (data.status == 200) {
                        layer.msg(data.msg, {icon: 1, time: 1000}, function () {
                            window.location.reload()
                        });
                    } else {
                        layer.msg(data.msg, {icon: 2});
                    }
                });
        }
    </script>
</body>
</html>
