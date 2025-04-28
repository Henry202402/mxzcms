@include(moduleAdminTemplate($moduleName)."public.header")
<style>
    img {
        cursor: pointer;
        border-radius: 30px;
    }

    .rightBtn {
        float: right;
        position: relative;
        top: -5px;
    }

    .h-fz-16 {
        font-size: 16px;
    }

    .h-mt10 {
        margin-top: 10px;
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
            ['breadcrumb'=>['用户管理','用户列表']])

            <!-- Content area -->
                <div class="content" style="margin-top: 1rem;">
                    <form class="bs-example form-horizontal" method="get">
                        <div class="form-group">
                            <div class="col-lg-2 h-mt10">
                                <input type="text" class="form-control" name="username"
                                       placeholder="手机号/用户名称/昵称" value="{{$_GET['username']}}">
                            </div>
                            <div class="col-lg-2 h-mt10">
                                <input type="text" class="form-control" name="uid"
                                       placeholder="ID" value="{{$_GET['uid']}}">
                            </div>

                            <div class="col-lg-2 h-mt10">
                                <select name="status" class="form-control">
                                    <option value="">状态</option>
                                    <option value="2" @if($_GET['status']==2) selected @endif >启用</option>
                                    <option value="1" @if($_GET['status']==1) selected @endif >禁用</option>
                                </select>
                            </div>

                            <div class="col-lg-2 h-mt10">
                                <input type="text" class="form-control" name="timeRang" id="hTimeRang"
                                       placeholder="时间范围" value="{{$_GET['timeRang']}}" readonly>
                            </div>


                            <div class="col-lg-2 h-mt10">
                                <button type="submit" class="btn btn-sm btn-info">
                                    搜索
                                </button>
                                <a href="{{url()->current()}}">
                                    <button type="button" class="btn btn-sm btn-danger">
                                        清空
                                    </button>
                                </a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive panel panel-default">
                        <div class="panel-heading">
                            <a class="label bg-info pull-right m-t-xs {{permissions('user/userAdd')}}"
                               href="{{moduleAdminJump($moduleName,'user/userAdd')}}">
                                添加
                            </a>
                            列表
                        </div>
                        <table class="table table-bordered triptable-sed">
                            <thead>
                            <tr>
                                <th>用户uid</th>
                                <th>头像</th>
                                <th>名称</th>
                                <th>昵称</th>
                                <th>手机号</th>
                                <th>邮箱</th>
                                <th>上级</th>
                                <th>身份权限</th>
                                <th>状态</th>
                                <th>注册时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($data as $d)
                                <tr>
                                    <td>{{$d['uid']}}</td>
                                    <td>
                                        <img src="{{GetUrlByPath($d['avatar'])}}" alt="" width="50" height="50"
                                             onclick="viewImg(this,300)">
                                    </td>
                                    <td>{{$d['username']}}</td>
                                    <td>{{$d['nickname']}}</td>
                                    <td>{{$d['phone']}}</td>
                                    <td>{{$d['email']}}</td>
                                    <td>{{$d['pid_name']}}</td>
                                    <td>{{$d['group_name']?:'用户'}}</td>
                                    <td>
                                        @if($d['status']==0)
                                            <span class="label bg-danger">禁用</span>
                                        @elseif($d['status']==1)
                                            <span class="label bg-success">启用</span>
                                        @endif
                                    </td>
                                    <td>{{$d['created_at']}}</td>
                                    <td>
                                        <a href="{{moduleAdminJump($moduleName,'user/userAuth?type=1&uid='.$d['uid'])}}">
                                            <button type="button"
                                                    class="btn btn-primary btn-xs {{permissions('user/userAuth')}}">
                                                个人认证
                                            </button>
                                        </a>
                                        <a href="{{moduleAdminJump($moduleName,'user/userAuth?type=1&uid='.$d['uid'])}}">
                                            <button type="button"
                                                    class="btn btn-primary btn-xs {{permissions('user/userAuth')}}">
                                                企业认证
                                            </button>
                                        </a>
                                        <a href="{{moduleAdminJump($moduleName,'user/userDetail?uid='.$d['uid'])}}">
                                            <button type="button"
                                                    class="btn btn-info btn-xs {{permissions('user/userDetail')}}">
                                                修改信息
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

                    <div class="col-sm-12 text-right text-center-xs">
                        @if(count($data)>0)
                            {{ $data->appends($_GET)->links() }}
                        @endif
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
        function audio(id) {
            //页面层
            layer.confirm('审核', {
                btn: ['确认', '取消'], //按钮
                title: '审核',
                area: ['350px', '300px'],
                content: `<div>
                            <input type="radio" name="status" value="1" checked class="h-radio18"><span class="h-radio-span">审核通过</span>
                            <input type="radio" name="status" value="2" class="h-radio18"><span class="h-radio-span">审核不通过</span>
                          </div>
                          <div>
                            <textarea id='remark' style="width: 100%;height:115px;margin-top: 20px;" placeholder="审核备注"/></textarea>
                          </div>
                          <div></div>`
            }, function () {
                var status = $("input[name='status']:checked").val();
                var remark = $("#remark").val();
                if (!status) {
                    return layer.msg('请选择状态', {icon: 5});
                }
                ;
                if (status == 2 && !remark) return layer.msg('请填写备注', {icon: 5});
                $.post("{{moduleAdminJump($moduleName,'settledIn/settledInAudit')}}", {
                        '_token': '{{csrf_token()}}', 'id': id, status: status, remark: remark
                    },
                    function (data) {
                        if (data.status == 200) {
                            layer.msg(data.msg, {icon: 1, time: 1000}, function () {
                                window.location.reload();
                            })
                        } else {
                            layer.msg(data.msg, {icon: 2});
                        }
                    })

            });
        }
    </script>
    <script type="text/javascript" src="{{asset("assets/module")}}/laydate/laydate.js"></script>
    <script>
        //创建日期范围选择
        laydate.render({
            elem: '#hTimeRang',
            {{--min: "{{bGetTimeRang()[0]}}",--}}
            range: true //或 range: '~' 来自定义分割字符
        });
    </script>
</body>
</html>
