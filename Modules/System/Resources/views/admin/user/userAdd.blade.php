@include(moduleAdminTemplate($moduleName)."public.header")
<style>
    .fileinput-preview {
        border: 1px #ccc solid;
        margin-bottom: .2rem;
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

            <!-- Page header -->
            <div class="page-header">
                @include(moduleAdminTemplate($moduleName)."public.page",
         ['breadcrumb'=>['用户管理','用户添加']])
            </div>
            <!-- /page header -->


            <!-- Content area -->
            <div class="content" style="margin-top: 1rem;">


                <div class="panel panel-flat">
                    <div class="panel-heading">

                        <form id="myForm" class="form-horizontal" method="post" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <fieldset class="content-group">

                                <div class="form-group ">
                                    <label class="control-label col-lg-1">用户名</label>
                                    <div class="col-lg-11">
                                        <input type="text" required name="username" value=""
                                               class="form-control form-control-rounded">
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label class="control-label col-lg-1">昵称</label>
                                    <div class="col-lg-11">
                                        <input type="text" required name="nickname" value=""
                                               class="form-control form-control-rounded">
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label class="control-label col-lg-1">区号</label>
                                    <div class="col-lg-11">
                                        <select name="phone_code" class="form-control">
                                            @foreach(getPhoneCode() as $key=>$c)
                                                <option value="{{$key}}">{{$c}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label class="control-label col-lg-1">手机号码</label>
                                    <div class="col-lg-11">
                                        <input type="text" required name="phone" value=""
                                               class="form-control form-control-rounded">
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label class="control-label col-lg-1">密码</label>
                                    <div class="col-lg-11">
                                        <input type="text" required name="password" value=""
                                               class="form-control form-control-rounded">
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label class="control-label col-lg-1">确认密码</label>
                                    <div class="col-lg-11">
                                        <input type="text" required name="password2" value=""
                                               class="form-control form-control-rounded">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-lg-1">性别</label>
                                    <div class="col-lg-11">

                                        <label class="radio-inline">
                                            <input type="radio" name="male" class="styled h-radio" value="男"
                                                   checked>
                                            <span class="h-span-val">男</span>
                                        </label>

                                        <label class="radio-inline">
                                            <input type="radio" name="male" class="styled h-radio" value="女">
                                            <span class="h-span-val">女</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group local_url">
                                    <label class="control-label col-lg-1">头像</label>
                                    <div class="fileinput-new-div col-lg-11" data-provides="fileinput">
                                        <div class="fileinput-preview" data-trigger="fileinput"
                                             style="width: 100px; height:100px;">
                                            <img id="addImg" class="img-fluid " style="height: 95px;"/>
                                        </div>
                                        <span class="btn btn-primary  btn-file">
                                    <span class="fileinput-new">选择</span>
                                    <span class="fileinput-exists">更换</span>
                                    <input type="file" id="images" name="avatar"
                                           onchange="showFile('images','addImg')"></span>
                                        <a href="#" onclick="deleteImg('addImg')"
                                           class="btn btn-danger fileinput-exists"
                                           data-dismiss="fileinput">删除</a>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-1 control-label"></label>
                                    <div class="col-lg-11">
                                        <button type="button" class="btn btn-sm btn-info h-sub">
                                            提交
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="history.go(-1)">
                                            返回
                                        </button>
                                    </div>
                                </div>
                            </fieldset>

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
    $('.h-sub').click(function () {
        layer.load(1);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            "method": "post",
            "url": "{{moduleAdminJump($moduleName,'user/userAdd')}}",
            "data": new FormData($('#myForm')[0]),
            "dataType": 'json',
            "cache": false,
            "processData": false,
            "contentType": false,
            "success": function (res) {
                layer.closeAll();
                if (res.status == 200) {
                    layer.msg(res.msg, {icon: 1, time: 1000}, function () {
                        window.location.href = "{{moduleAdminJump($moduleName,'user/userList')}}";
                    });
                } else {
                    layer.msg(res.msg, {icon: 2})
                }
            },
            "error": function (res) {
                layer.closeAll();
                layer.msg("系统错误，请稍后重试", {icon: 5})
            }
        });
    });
</script>
<script type="text/javascript"
        src="{{moduleAdminResource($moduleName)}}/js/plugins/forms/selects/select2.min.js"></script>
<script type="text/javascript" src="{{moduleAdminResource($moduleName)}}/js/pages/form_select2.js"></script>
</body>
</html>