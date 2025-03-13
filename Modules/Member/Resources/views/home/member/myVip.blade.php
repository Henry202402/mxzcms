@include("member::home.public.head")
<style>
    .h-center {
        text-align: center;
    }

    .h-p-40 {
        padding-left: 40px;
        padding-right: 40px;
    }

    .h-vip-price {
        color: red;
    }

    .h-old-vip-price {
        font-size: 11px;
        text-decoration: line-through;
        color: #9E9E9E;
    }
</style>
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
                    <div class="col-md-12 col-xl-3">
                        <div class="card mb-0  h-100">
                            <div class="card-body">

                                <div class="profile-widgets py-3">

                                    <div class="text-center">
                                        <div>
                                            <img src="{{GetUrlByPath(session("home_info")['avatar'])}}" alt="avatar-2"
                                                 class="avatar-lg mx-auto img-thumbnail rounded-circle">
                                            {{--<div class="online-circle"><i class="fas fa-circle text-success"></i></div>--}}
                                        </div>

                                        <div class="mt-3 ">
                                            <a href="javascript: void(0);" class="text-dark fw-medium font-size-16">
                                                {{session("home_info")['username']}}
                                            </a>
                                            <p class="text-body mt-1 mb-1">普通用户</p>
                                            @if($auth['status']==1)
                                                <span class="badge bg-success">已实名</span>
                                            @else
                                                <span class="badge bg-danger">未实名</span>
                                            @endif
                                        </div>

                                        <div class="row mt-4 border border-start-0 border-end-0 p-3">
                                            <div class="col-md-7">
                                                <h6 class="text-muted">
                                                    VIP
                                                </h6>
                                                <h6 class="mb-0">
                                                    @if($wallet['vip_time'])
                                                        <span class="@if($wallet['vip_time']>getDay()) text-success @else text-danger @endif">{{$wallet['vip_time']}}</span>
                                                    @else
                                                        未购买
                                                    @endif

                                                </h6>
                                            </div>

                                            <div class="col-md-5">
                                                <h6 class="text-muted">
                                                    列表
                                                </h6>
                                                <h6 class="mb-0">
                                                    <a href="{{url('member/vipRecord')}}" class="text-success">购买记录</a>
                                                </h6>
                                            </div>

                                        </div>

                                        <div class="mt-4">


                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>

                    </div>

                    <div class="col-md-12 col-xl-9">
                        <div class="card">
                            <div class="card-body">
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#experience" role="tab">
                                            <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                            <span class="d-none d-sm-block">VIP列表</span>
                                        </a>
                                    </li>
                                </ul>
                                <!-- Tab panes -->
                                <div class="tab-content p-3 text-muted">
                                    <div class="tab-pane active" id="experience" role="tabpanel">
                                        <div class="timeline-count mt-5">
                                            <!-- Timeline row Start -->
                                            <div class="row">

                                                @foreach($data as $d)
                                                    <div class="timeline-box col-lg-4">
                                                        <div class="timeline-spacing">
                                                            <div class="item-lable bg-primary rounded">
                                                                <p class="text-center text-white">{{$d['name']}}</p>
                                                            </div>
                                                            <div class="timeline-line active">
                                                                <div class="dot bg-primary"></div>
                                                            </div>
                                                            <div class="vertical-line">
                                                                <div class="wrapper-line bg-light"></div>
                                                            </div>
                                                            <div class="bg-light text-start p-4 rounded mx-3">
                                                                <h5>
                                                                    价格：<span
                                                                            class="h-vip-price">{{$d['discount_price']*1}}</span>
                                                                    元
                                                                    @if($d['price']>$d['discount_price'])
                                                                        <span class="h-old-vip-price">原价：{{$d['price']*1}} 元</span>
                                                                    @endif
                                                                </h5>
                                                                <p class="text-muted mb-0">
                                                                    {!! $d['describe'] !!}
                                                                </p>
                                                                <div class="h-center">
                                                                    <button type="button"
                                                                            class="btn btn-danger btn-rounded waves-effect waves-light mt-4 h-p-40"
                                                                            onclick="buyVip({{$d['id']}})">
                                                                        购买
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <!-- Timeline row Over -->

                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="card-body m-t-20">
                                <hr>
                                {!! $config['vip_rule'] !!}
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
<div id="qrcode" class="h-package-qrcode" style="padding: 20px;display: none"></div>
@include("member::home.public.js")
<script src="{{moduleHomeResource($moduleName,'home/assets/js/user.js')}}"></script>
<script src="{{moduleHomeResource($moduleName,'home/assets/js/jquery.qrcode.min.js')}}"></script>
<script>
    // 支付的表单数据
    var paymentFormData = {
        payType: 'vip',
        pay_method: 0, //支付方式 0微信 1支付宝
        id: 0, //id
    };

    function buyVip(id) {
        paymentFormData.id = id;
        confirmPay();
    }
</script>
<script src="{{moduleHomeResource($moduleName,'home/assets/js/pay.js')}}"></script>
</body>

</html>
