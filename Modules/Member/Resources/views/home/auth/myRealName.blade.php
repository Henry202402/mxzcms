@include("member::home.public.head")
<style>
    .h-btn-xs{
        margin: 0 15px;
        padding: 1px 15px;
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

                @if ($auth)
                    <div class="row">
                        <div class="col-md-12 col-xl-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">实名信息</h5>

                                    <p class="card-title-desc">
                                        Real name information
                                    </p>

                                    <div class="mt-3">
                                        <p class="font-size-12 text-muted mb-1">实名类型</p>
                                        <h6 style="display: flex;">
                                            {{\Modules\Member\Models\Auth::type()[$auth['type']]}}
                                            @if($auth['type']==1)
                                                <a href="{{url('member/addRealName?type=2&change=1')}}">
                                                    <button type="button" class="btn btn-primary h-btn-xs">变更企业认证</button>
                                                </a>
                                            @endif
                                        </h6>
                                    </div>

                                    @if($auth['type']==1)
                                        <div class="mt-3">
                                            <p class="font-size-12 text-muted mb-1">真实姓名</p>
                                            <h6>{{$auth['real_name']}}</h6>
                                        </div>

                                        <div class="mt-3">
                                            <p class="font-size-12 text-muted mb-1">身份证号码</p>
                                            <h6>{{mb_substr($auth['id_card'],0,4).'********'.mb_substr($auth['id_card'],-4,4)}}</h6>
                                        </div>
                                    @elseif($auth['type']==2)
                                        <div class="mt-3">
                                            <p class="font-size-12 text-muted mb-1">公司名称</p>
                                            <h6>{{$auth['company_name']}}</h6>
                                        </div>

                                        <div class="mt-3">
                                            <p class="font-size-12 text-muted mb-1">统一社会信用代码</p>
                                            <h6>{{$auth['unified_social_credit_code']}}</h6>
                                        </div>
                                        <div class="mt-3">
                                            <p class="font-size-12 text-muted mb-1">法人名称</p>
                                            <h6>{{$auth['legal_person']}}</h6>
                                        </div>
                                        <div class="mt-3">
                                            <p class="font-size-12 text-muted mb-1">法人名称身份证号</p>
                                            <h6>{{mb_substr($auth['legal_id_card'],0,4).'********'.mb_substr($auth['legal_id_card'],-4,4)}}</h6>
                                        </div>
                                    @endif
                                    <div class="mt-3">
                                        <p class="font-size-12 text-muted mb-1">状态</p>

                                        @if($auth['status']==0)
                                            <h6 class="text-primary">待审核</h6>
                                        @elseif($auth['status']==1)
                                            <h6 class="text-success">已认证</h6>
                                        @elseif($auth['status']==2)
                                            <h6 class="text-danger">认证失败</h6>
                                        @endif

                                    </div>

                                    @if($auth['status']==2)
                                        @if($auth['remark'])
                                            <div class="mt-3">
                                                <p class="font-size-12 text-muted mb-1">审核备注</p>
                                                <h6>{{$auth['remark']}}</h6>
                                            </div>
                                        @endif
                                        <div class="mb-3 mt-5">
                                            <a href="{{url('member/editRealName?type='.$auth['type'].'&id='.$auth['id'])}}">
                                                <button type="button"
                                                        class="btn btn-info col-md-2">
                                                    编辑认证
                                                </button>
                                            </a>
                                        </div>
                                    @endif


                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row justify-content-center" style="margin-top: 35px;">
                        <div class="col-lg-6">
                            <div class="text-center mb-5">
                                <h4>实名认证</h4>
                                <p style="margin-bottom: 0rem;">为了保障您的账户安全及享受更多服务，请完成实名认证。</p>
                                <p style="margin-bottom: 0rem;">实名认证后，您将获得更安全的使用体验和更多功能权限。</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="card plan-box">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-1 me-3">
                                            <h5>个人认证</h5>
                                            <p class="text-muted">Personal authentication</p>
                                        </div>
                                        <div class="ms-auto">
                                            <i class="fas fa-user h1 text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="plan-features p-4 text-muted mt-2">
                                        <p><i class="mdi mdi-check-bold text-primary me-4"></i>完成个人认证，享受更多专属权益和服务！</p>
                                        <p><i class="mdi mdi-check-bold text-primary me-4"></i>认证后，您的账号将获得平台标识，提升可信度。
                                        </p>
                                    </div>

                                    <div class="text-center">
                                        <a href="{{url('member/addRealName?type=1')}}"
                                           class="btn btn-primary waves-effect waves-light">前往</a>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6">
                            <div class="card plan-box">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-1 me-3">
                                            <h5>企业认证</h5>
                                            <p class="text-muted">Enterprise certification</p>
                                        </div>
                                        <div class="ms-auto">
                                            <i class="fas fa-users h1 text-primary"></i>
                                        </div>
                                    </div>

                                    <div class="plan-features p-4 text-muted mt-2">
                                        <p><i class="mdi mdi-check-bold text-primary me-4"></i>完成企业认证，享受更多专属权益和服务！</p>
                                        <p><i class="mdi mdi-check-bold text-primary me-4"></i>认证后，您的企业将获得平台标识，提升可信度。
                                        </p>
                                    </div>

                                    <div class="text-center">
                                        <a href="{{url('member/addRealName?type=2')}}"
                                           class="btn btn-primary waves-effect waves-light">前往</a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
            @endif
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
