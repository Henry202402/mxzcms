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
                    <div class="col-xl-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-8">
                                                <div>
                                                    <p class="text-muted fw-medium mt-1 mb-2">余额</p>
                                                    <h4>{{$wallet['balance']*1}} 元</h4>
                                                </div>
                                            </div>

                                            <div class="col-4">
                                                <div>
                                                    <div id="radial-chart-1" class="apex-charts"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <p class="mb-0">　</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-8">
                                                <div>
                                                    <p class="text-muted fw-medium mt-1 mb-2">可提现余额</p>
                                                    <h4>{{$wallet['withdrawable']*1}} 元</h4>
                                                </div>
                                            </div>

                                            <div class="col-4">
                                                <div>
                                                    <div id="radial-chart-2" class="apex-charts"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <p class="mb-0">　</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-8">
                                                <div>
                                                    <p class="text-muted fw-medium mt-1 mb-2">{{$config['integral_alias']}}</p>
                                                    <h4>{{$wallet['integral']*1}} 积分</h4>
                                                </div>
                                            </div>

                                            <div class="col-4">
                                                <div>
                                                    <div id="radial-chart-1" class="apex-charts"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <p class="mb-0">　</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-8">
                                                <div>
                                                    <p class="text-muted fw-medium mt-1 mb-2">VIP时间</p>
                                                    <h4>{{$wallet['vip_time']?:'无'}}</h4>
                                                </div>
                                            </div>

                                            <div class="col-4">
                                                <div>
                                                    <div id="radial-chart-2" class="apex-charts"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <p class="mb-0">
                                            <a href="{{url('member/myVip')}}">
                                                购买VIP
                                            </a>
                                        </p>
                                    </div>
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
