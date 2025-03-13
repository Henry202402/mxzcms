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
    .h-fz-16{
        font-size: 16px;
    }
    .h-mt10{
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
            ['breadcrumb'=>['用户管理',$pageData['subtitle']]])

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
                                <input type="text" class="form-control" name="timeRang" id="hTimeRang"
                                       placeholder="时间范围" value="{{$_GET['timeRang']}}" readonly>
                            </div>

                            <div class="col-lg-2 h-mt10">
                                <select name="other_param" class="form-control">
                                    <option value="">其他</option>
                                    <option value="no_phone" @if($_GET['other_param']=='no_phone') selected @endif >
                                        无手机号
                                    </option>
                                    <option value="no_author" @if($_GET['other_param']=='no_author') selected @endif >
                                        不是作者
                                    </option>
                                    <option value="no_phone_no_author"
                                            @if($_GET['other_param']=='no_phone_no_author') selected @endif >无手机号也不是作者
                                    </option>
                                </select>
                            </div>

                            <div class="col-lg-2 h-mt10">
                                <select name="is_vip" class="form-control">
                                    <option value="">是否购买VIP</option>
                                    <option value="1" @if($_GET['is_vip']==1) selected @endif >有效期</option>
                                    <option value="2" @if($_GET['is_vip']==2) selected @endif >已过期</option>
                                    <option value="3" @if($_GET['is_vip']==3) selected @endif >未购买</option>
                                </select>
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
                            <a class="label bg-danger pull-right m-t-xs mr-10"
                               href="{{moduleAdminJump($moduleName,'user/userList')}}">
                                返回
                            </a>
                            列表
                        </div>
                        <table class="table table-bordered triptable-sed">
                            <thead>
                            <tr>
                                <th>用户uid</th>
                                <th>头像</th>
                                <th>名称</th>
                                <th>手机号</th>
                                <th>昵称</th>
                                <th>钱包</th>
                                <th>笔名</th>
                                <th>网编</th>
                                <th>VIP过期时间</th>
                                <th>状态</th>
                                <th>注销时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($data as $d)
                                <tr>
                                    <td>{{$d['user_info']['uid']}}</td>
                                    <td>
                                        <img src="{{GetUrlByPath($d['user_info']['avatar'])}}" alt="" width="50" height="50"
                                             onclick="viewImg(this,300)">
                                    </td>
                                    <td>{{$d['user_info']['username']}}</td>
                                    <td>{{$d['user_info']['phone']}}</td>
                                    <td>{{$d['user_info']['nickname']}}</td>
                                    <td>
                                        阅读币：{{intval($d['reading_currency'])}}<br>
                                        人气票：{{intval($d['popularity_num'])}}
                                    </td>
                                    <td>{{$d['pen_name']}}</td>
                                    <td>
                                        @if($d['netting_level'])
                                            <span class="label bg-success">{{\App\Http\Controllers\Module\netwriter\Models\NettingConfig::level[$d['netting_level']]}}</span>
                                        @endif
                                    </td>
                                    <td width="123" class=" @if($d['vip_time']>=getDay(2)) text-success h-fz-16 @else text-danger @endif ">{{$d['vip_time']}}</td>
                                    <td>
                                        @if($d['user_info']['status']==0)
                                            <span class="label bg-danger">禁用</span>
                                        @elseif($d['user_info']['status']==1)
                                            <span class="label bg-success">启用</span>
                                        @endif
                                    </td>
                                    <td>{{$d['created_at']}}</td>
                                    <td>

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
    <script type="text/javascript" src="{{moduleAdminResource($moduleName)}}/laydate/laydate.js"></script>
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
