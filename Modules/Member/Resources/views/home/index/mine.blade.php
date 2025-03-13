@include("member::home.public.head")

<body data-layout="detached" data-topbar="colored">

<!-- <body data-layout="horizontal" data-topbar="dark"> -->

<div class="container-fluid">
    <!-- Begin page -->
    <div id="layout-wrapper">

    @include("member::home.public.header")

    <!-- ========== Left Sidebar Start ========== -->
    @include("member::home.public.leftnav")
    <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                            <h4 class="page-title mb-0 font-size-18">修改资料</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="{{url("member")}}">会员中心</a></li>
                                    <li class="breadcrumb-item active">个人资料</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form method="post" autocomplete="off" id="myForm">


                                    {{csrf_field()}}
                                    <h4 class="card-title">注意：</h4>
                                    <p class="card-title-desc">
                                        1、修改资料内容请注意<code>敏感词</code><br>
                                        2、邮箱请填写真实邮箱，方便后续接收通知
                                    </p>

                                    <div class="mb-3">
                                        <label class="form-label">用户名</label>
                                        <input type="text" class="form-control" value="{{$user['username']}}" disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">昵称</label>
                                        <input type="text" class="form-control" name="nickname" value="{{$user['nickname']}}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">邮箱</label>
                                        <input type="text" class="form-control" name="email" value="{{$user['email']}}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">个性签名</label>
                                        <input type="text" class="form-control" name="signature" value="{{$user['signature']}}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">头像</label>
                                        <div class="input-group">
                                            <img src="{{GetUrlByPath($user['avatar'])}}"
                                                 style="width: 38px;margin-right: 5px;">
                                            <input type="file" class="form-control" id="inputGroupFile02" name="avatar">
                                            <label class="input-group-text" for="inputGroupFile02">Upload</label>
                                        </div>
                                    </div>

                                    <div class="mb-3 ">
                                        <button type="button" class="btn btn-info col-md-1 h-sub">确认</button>
                                        <button type="button" class="btn btn-danger col-md-1">返回</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->


            </div>
            <!-- End Page-content -->

            @include("member::home.public.footer")
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

</div>
<!-- end container-fluid -->

<!-- Right bar overlay-->
<div class="rightbar-overlay"></div>

@include("member::home.public.js")
<script>
    $('.h-sub').click(function () {
        ajaxForm('myForm', function (data) {
            layer.closeAll();
            if (data.status == 200) {
                layer.msg(data.msg, {icon: 1, time: 500}, function () {
                    window.location.reload();
                })
            } else {
                layer.msg(data.msg, {icon: 2})
            }
        });
    });
</script>
</body>

</html>
