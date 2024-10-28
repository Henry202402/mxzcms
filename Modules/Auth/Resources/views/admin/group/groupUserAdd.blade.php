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


    @include(moduleAdminTemplate($moduleName)."public.left")


    <!-- Main content -->
        <div class="content-wrapper">

            <!-- Content area -->
            <div class="content" style="margin-top: 1rem;">
                @include(moduleAdminTemplate($pageData['moduleName'])."public.crumb",['breadcrumb'=>[$pageData['title'],$pageData['subtitle']]])

                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <form class="bs-example form-horizontal" method="post" enctype="multipart/form-data"
                        action="{{$pageData['formAction']}}">
                            {{csrf_field()}}
                            <div class="form-group">
                                <label class="col-lg-1 control-label">
                                    权限组
                                </label>
                                <div class="col-lg-11">
                                    <select class="select-search" name="group_id" required>
                                        <optgroup label="">
                                            <option value="0">
                                                请选择
                                            </option>
                                            @foreach($pageData['groupList'] as $group)
                                                <option value="{{$group['group_id']}}"
                                                        @if($_GET['group_id']==$group['group_id']) selected @endif>
                                                    {{$group['group_name']}}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-1">搜索用户</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control" id="name">
                                </div>
                                <div class="col-lg-1">
                                    <button type="button" class="btn btn-sm btn-info h-search">
                                        搜索
                                    </button>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-1">成员列表</label>
                                <div class="col-lg-11">
                                    <select class="select-search" name="uid" id="uid" required>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="submitType" value="addGroupUser">
                                <label class="col-lg-1 control-label"></label>
                                <div class="col-lg-11">
                                    <button type="submit" class="btn btn-sm btn-info">
                                        提交
                                    </button>
                                    <a href="{{url('admin/auth/group/groupUser?group_id='.$_GET['group_id'])}}">
                                        <button type="button" class="btn btn-sm btn-danger">
                                            返回
                                        </button>
                                    </a>
                                </div>
                            </div>
                        </form>
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

<!-- 						Content End		 						-->
<!-- ============================================================== -->
@include(moduleAdminTemplate($moduleName)."public.js")
<script>
    $('.h-search').click(function () {
        var name = $('#name').val();
        if (!name) return layer.msg('请输入名称');
        layer.load(1);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            "method": "post",
            "url": "{{url()->current()}}",
            "data": {
                is_search: 1,
                group_id: '{{$_GET['group_id']}}',
                name: name,
            },
            "dataType": 'json',
            "success": function (res) {
                layer.closeAll();
                if (res.status == 200) {
                    $('#uid').html(res.data);
                } else {
                    layer.msg(res.msg, {icon: 5})
                }
            },
            "error": function (res) {
                console.log(res);
            }
        })
    });
</script>
<script type="text/javascript"
        src="{{moduleAdminResource($moduleName)}}/js/plugins/forms/selects/select2.min.js"></script>
<script type="text/javascript" src="{{moduleAdminResource($moduleName)}}/js/pages/form_select2.js"></script>
</body>
</html>