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
            @include("member::home.public.topnav")
            <!-- end page title -->

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form id="myForm" class="comment-form" method="post">


                                    {{csrf_field()}}
                                    <h4 class="card-title">注意：</h4>
                                    <p class="card-title-desc">
                                        设置一个至少6位字符长的密码，最好包含<code>大写字母、小写字母、数字和特殊符号</code>以增强安全性。<br>
                                        为了保障您的账户安全，请尽量避免在密码中使用<code>个人信息</code>，如<code>生日、电话号码</code>等。
                                    </p>

                                    <div class="mb-3">
                                        <label class="form-label">原密码</label>
                                        <input type="password" class="form-control" name="old_password" placeholder="原密码">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">新密码</label>
                                        <input type="password" class="form-control" name="new_password" placeholder="新密码">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">确认密码</label>
                                        <input type="password" class="form-control" name="confirm_password" placeholder="确认密码">
                                    </div>

                                    <div class="mb-3 ">
                                        <button type="button"
                                                class="btn btn-info waves-effect waves-light col-md-1 col-form-label updateUserPassword">
                                            确认
                                        </button>
                                        <button type="button"
                                                class="btn btn-danger waves-effect waves-light col-md-1 col-form-label"
                                                onclick="history.go(-1)">返回
                                        </button>
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
<script src="{{moduleHomeResource($moduleName,'home/assets/js/user.js')}}"></script>
</body>

</html>
