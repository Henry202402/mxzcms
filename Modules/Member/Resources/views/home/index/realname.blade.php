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
                            <h4 class="page-title mb-0 font-size-18">实名认证</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="{{url("member")}}">会员中心</a></li>
                                    <li class="breadcrumb-item active">实名认证</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="text-center mb-5">
                            <h4>请选择你的认证类型</h4>
                            <p class="text-muted">To achieve this, it would be necessary to have achieveuniform grammar,
                                pronunciation and more common words If several languages coalesce</p>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-xl-4 col-md-4">
                        <div class="card plan-box">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-start">
                                    <div class="flex-1 me-3">
                                        <h5>个人认证</h5>
                                        <p class="text-muted">Neque quis est</p>
                                    </div>
                                    <div class="ms-auto">
                                        <i class="bx bx-walk h1 text-primary"></i>
                                    </div>
                                </div>
                                <div class="py-4 mt-4 text-center bg-soft-light">
                                    <h1 class="m-0"><sup><small>$</small></sup> 19/ <span class="font-size-13">Per
                                                    month</span></h1>
                                </div>

                                <div class="plan-features p-4 text-muted mt-2">
                                    <p><i class="mdi mdi-check-bold text-primary me-4"></i>Unlimited access to
                                        licence</p>
                                    <p><i class="mdi mdi-check-bold text-primary me-4"></i>GB Storage</p>
                                    <p><i class="mdi mdi-check-bold text-primary me-4"></i>No Domain</p>
                                    <p><i class="mdi mdi-check-bold text-primary me-4"></i>SEO optimization</p>
                                    <p><i class="mdi mdi-check-bold text-primary me-4"></i>Unlmited Users</p>
                                    <p><i class="mdi mdi-check-bold text-primary me-4"></i>500 GB Bandwidth</p>
                                </div>

                                <div class="text-center">
                                    <a href="javascript: void(0);" class="btn btn-primary waves-effect waves-light">去认证</a>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-4">
                        <div class="card plan-box">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-start">
                                    <div class="flex-1 me-3">
                                        <h5>企业认证</h5>
                                        <p class="text-muted">Quis autem iure</p>
                                    </div>
                                    <div class="ms-auto">
                                        <i class="bx bx-run h1 text-primary"></i>
                                    </div>
                                </div>
                                <div class="py-4 mt-4 text-center bg-soft-light">
                                    <h1 class="m-0"><sup><small>$</small></sup> 29/ <span class="font-size-13">Per
                                                    month</span></h1>
                                </div>
                                <div class="plan-features p-4 text-muted mt-2">
                                    <p><i class="mdi mdi-check-bold text-primary me-4"></i>Unlimited access to
                                        licence</p>
                                    <p><i class="mdi mdi-check-bold text-primary me-4"></i>GB Storage</p>
                                    <p><i class="mdi mdi-check-bold text-primary me-4"></i>No Domain</p>
                                    <p><i class="mdi mdi-check-bold text-primary me-4"></i>SEO optimization</p>
                                    <p><i class="mdi mdi-check-bold text-primary me-4"></i>Unlmited Users</p>
                                    <p><i class="mdi mdi-check-bold text-primary me-4"></i>500 GB Bandwidth</p>
                                </div>

                                <div class="text-center">
                                    <a href="javascript: void(0);" class="btn btn-primary waves-effect waves-light">去认证</a>
                                </div>

                            </div>
                        </div>
                    </div>

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

</body>

</html>
