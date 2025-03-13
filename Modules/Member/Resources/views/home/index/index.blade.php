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
                            <h4 class="page-title mb-0 font-size-18">会员中心</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">会员中心</a></li>
                                    <li class="breadcrumb-item active">首页预览</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- end page title -->

            {{--<div class="row">
                <div class="col-xl-12">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-8">
                                            <div>
                                                <p class="text-muted fw-medium mt-1 mb-2">Orders</p>
                                                <h4>1,368</h4>
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <div>
                                                <div id="radial-chart-1" class="apex-charts"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <p class="mb-0"><span class="badge badge-soft-success me-2"> 0.8% <i class="mdi mdi-arrow-up"></i> </span> From previous period</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-8">
                                            <div>
                                                <p class="text-muted fw-medium mt-1 mb-2">Revenue</p>
                                                <h4>$ 32,695</h4>
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <div>
                                                <div id="radial-chart-2" class="apex-charts"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <p class="mb-0"><span class="badge badge-soft-success me-2"> 0.6% <i class="mdi mdi-arrow-up"></i> </span> From previous period</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-8">
                                            <div>
                                                <p class="text-muted fw-medium mt-1 mb-2">Orders</p>
                                                <h4>1,368</h4>
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <div>
                                                <div id="radial-chart-1" class="apex-charts"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <p class="mb-0"><span class="badge badge-soft-success me-2"> 0.8% <i class="mdi mdi-arrow-up"></i> </span> From previous period</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-8">
                                            <div>
                                                <p class="text-muted fw-medium mt-1 mb-2">Revenue</p>
                                                <h4>$ 32,695</h4>
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <div>
                                                <div id="radial-chart-2" class="apex-charts"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <p class="mb-0"><span class="badge badge-soft-success me-2"> 0.6% <i class="mdi mdi-arrow-up"></i> </span> From previous period</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>--}}
            <!-- end row -->

                <div class="row">
                    <div class="col-xl-12">
                        <div class="card bg-info text-white-50">
                            <div class="card-body">
                                <h5 class="mb-4 text-white"><i class="fas fa-info"></i> 温馨提示</h5>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <blockquote class="blockquote font-size-16 mb-0">
                                            <p class="">
                                                欢迎您，{{session("home_info")['username']}}，
                                                {{--你上次登录IP：1111.1111.111.111 登录时间：2024.21.24 12:12:58 不是我登录?，--}}
                                                <a href="{{url("member/password")}}">修改密码</a>
                                            </p>
                                        </blockquote>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-4">模块入口</h4>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            @if($list)
                                                @foreach($list as $key=>$val)
                                                    @if($val)
                                                        <div class="col-lg-2 text-center">
                                                            <a href="{{$val['url']}}" target="_blank">
                                                                @if($val['icontype']=="imgage")
                                                                    <img src="{{$val['icon']}}" width="100" height="100"
                                                                         class="rounded avatar-lg">
                                                                @else
                                                                    <i class="{{$val['icon']}} display-5 box-icon-large"></i>
                                                                @endif

                                                                <p class="mt-2 mb-lg-0">{{$val['name']}}</p>
                                                            </a>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @else
                                                <div class="col-lg-12 text-center">
                                                    <p class="mt-2 mb-lg-0">暂无其他模块入口</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>


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
