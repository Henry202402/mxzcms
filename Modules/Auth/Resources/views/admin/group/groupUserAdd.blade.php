@include(moduleAdminTemplate($moduleName)."public.header")
<style>
    .mx-auth-user-add__hero {
        margin-bottom: 18px;
        padding: 18px 20px;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: linear-gradient(135deg, #ffffff 0%, #f8fbff 100%);
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
    }

    .mx-auth-user-add__title {
        margin: 0 0 8px;
        font-size: 22px;
        font-weight: 700;
        color: #0f172a;
    }

    .mx-auth-user-add__meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        color: #64748b;
        font-size: 13px;
    }

    .mx-auth-user-add__badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 999px;
        background: #e0f2fe;
        color: #0369a1;
        font-weight: 700;
    }

    .mx-auth-user-add__help {
        margin-top: 10px;
        color: #64748b;
        line-height: 1.8;
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
                @include(moduleAdminTemplate($pageData['moduleName'])."public.crumb",['breadcrumb'=>[$pageData['title'],$pageData['subtitle']]])

                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <div class="mx-auth-user-add__hero">
                            <h2 class="mx-auth-user-add__title">添加组成员</h2>
                            <div class="mx-auth-user-add__meta">
                                <span class="mx-auth-user-add__badge">当前权限组：{{$pageData['group']['group_name']}}</span>
                                <span>权限组 ID：{{$pageData['group']['group_id']}}</span>
                                <span>可选未分组用户：{{$pageData['availableUserCount']}}</span>
                            </div>
                            <div class="mx-auth-user-add__help">
                                先搜索用户名、手机号或 UID，再从结果里选择要加入当前权限组的成员。已分配到其它权限组的用户不会再次出现在搜索结果里。
                            </div>
                        </div>
                        <form class="bs-example form-horizontal" method="post" enctype="multipart/form-data"
                        action="{{$pageData['formAction']}}" id="groupUserAddForm">
                            {{csrf_field()}}
                            <div class="form-group">
                                <label class="col-lg-1 control-label">
                                    权限组
                                </label>
                                <div class="col-lg-11">
                                    <select class="select-search" name="group_id" id="groupId" required>
                                        <optgroup label="">
                                            <option value="0">
                                                请选择
                                            </option>
                                            @foreach($pageData['groupList'] as $group)
                                                <option value="{{$group['group_id']}}"
                                                        @if($_GET['group_id']==$group['group_id']) selected @endif
                                                        @if($group['type'] === 'admin') disabled @endif>
                                                    {{$group['group_name']}}@if($group['type'] === 'admin') / 系统管理员@endif
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-1">搜索用户</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control" id="name" placeholder="请输入用户名、手机号或 UID">
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
                                <input type="hidden" name="jumpUrl" value="{{$pageData['jumpUrl']}}">
                                <label class="col-lg-1 control-label"></label>
                                <div class="col-lg-11">
                                        <button type="submit" class="btn btn-sm btn-info">
                                        提交
                                    </button>
                                        <a href="{{$pageData['groupUserUrl']}}" id="groupUserBackLink">
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
    function buildGroupUserJumpUrl(groupId) {
        return "{{url('admin/auth/group/groupUser')}}" + '?group_id=' + groupId;
    }

    $('.h-search').click(function () {
        var groupId = $('#groupId').val();
        var name = $('#name').val();
        if (!groupId || groupId === '0') return layer.msg('请选择权限组');
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
                group_id: groupId,
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
                layer.closeAll();
                console.log(res);
                layer.msg('搜索失败，请稍后重试', {icon: 5});
            }
        });
    });
    $('#name').on('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            $('.h-search').trigger('click');
        }
    });
    $('#groupId').on('change', function () {
        var jumpUrl = buildGroupUserJumpUrl($(this).val());
        $('input[name="jumpUrl"]').val(jumpUrl);
        $('#groupUserBackLink').attr('href', jumpUrl);
        $('#uid').html('');
    });
    $('#groupUserAddForm').on('submit', function () {
        var groupId = $('#groupId').val();
        $('input[name="jumpUrl"]').val(buildGroupUserJumpUrl(groupId));
    });
</script>
<script type="text/javascript"
        src="{{asset("assets/module")}}/js/plugins/forms/selects/select2.min.js"></script>
<script type="text/javascript" src="{{asset("assets/module")}}/js/pages/form_select2.js"></script>
</body>
</html>
