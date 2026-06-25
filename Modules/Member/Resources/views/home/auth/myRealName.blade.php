@include("member::home.public.head")
<body data-layout="detached" data-topbar="colored">
<div class="container-fluid">
    <div id="layout-wrapper">
        @include("member::home.public.header")
        @include("member::home.public.leftnav")

        <div class="main-content">
            <div class="page-content">
                @include("member::home.public.topnav")

                @if ($auth)
                    <div class="mx-member-auth-summary">
                        <div class="mx-member-auth-status">
                            <span>实名状态</span>
                            <h4>
                                @if($auth['status']==0)
                                    待审核
                                @elseif($auth['status']==1)
                                    已认证
                                @else
                                    认证失败
                                @endif
                            </h4>
                            <p>当前认证类型：{{ \Modules\Member\Models\Auth::type()[$auth['type']] }}</p>
                            <div class="mx-member-auth-actions">
                                @if($auth['type']==1)
                                    <a href="{{url('member/addRealName?type=2&change=1')}}" class="btn btn-light waves-effect">升级企业认证</a>
                                @endif
                                @if($auth['status']==2)
                                    <a href="{{url('member/editRealName?type='.$auth['type'].'&id='.$auth['id'])}}" class="btn btn-outline-light waves-effect">重新提交</a>
                                @endif
                            </div>
                        </div>
                        <div class="mx-member-auth-details">
                            <div class="mx-member-auth-detail">
                                <span>实名类型</span>
                                <strong>{{ \Modules\Member\Models\Auth::type()[$auth['type']] }}</strong>
                            </div>
                            <div class="mx-member-auth-detail">
                                <span>审核状态</span>
                                <strong>
                                    @if($auth['status']==0)
                                        待审核
                                    @elseif($auth['status']==1)
                                        已认证
                                    @else
                                        认证失败
                                    @endif
                                </strong>
                            </div>
                            @if($auth['status']==2 && $auth['remark'])
                                <div class="mx-member-auth-detail">
                                    <span>审核备注</span>
                                    <strong>{{ $auth['remark'] }}</strong>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="mx-member-section-head">
                                <div>
                                    <h4 class="card-title mb-1">实名信息</h4>
                                    <p class="card-title-desc mb-0">以下信息用于展示当前审核通过或最后一次提交的实名资料。</p>
                                </div>
                            </div>

                            <div class="mx-member-auth-details">
                                @if($auth['type']==1)
                                    <div class="mx-member-auth-detail">
                                        <span>真实姓名</span>
                                        <strong>{{ $auth['real_name'] }}</strong>
                                    </div>
                                    <div class="mx-member-auth-detail">
                                        <span>身份证号码</span>
                                        <strong>{{ mb_substr($auth['id_card'],0,4).'********'.mb_substr($auth['id_card'],-4,4) }}</strong>
                                    </div>
                                @elseif($auth['type']==2)
                                    <div class="mx-member-auth-detail">
                                        <span>公司名称</span>
                                        <strong>{{ $auth['company_name'] }}</strong>
                                    </div>
                                    <div class="mx-member-auth-detail">
                                        <span>统一社会信用代码</span>
                                        <strong>{{ $auth['unified_social_credit_code'] }}</strong>
                                    </div>
                                    <div class="mx-member-auth-detail">
                                        <span>法人名称</span>
                                        <strong>{{ $auth['legal_person'] }}</strong>
                                    </div>
                                    <div class="mx-member-auth-detail">
                                        <span>法人身份证号</span>
                                        <strong>{{ mb_substr($auth['legal_id_card'],0,4).'********'.mb_substr($auth['legal_id_card'],-4,4) }}</strong>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card">
                        <div class="card-body">
                            <div class="mx-member-section-head">
                                <div>
                                    <h4 class="card-title mb-1">选择认证类型</h4>
                                    <p class="card-title-desc mb-0">实名认证后可获得更高可信度和更多账户能力。</p>
                                </div>
                            </div>
                            <div class="mx-member-auth-options">
                                <div class="mx-member-auth-option">
                                    <i class="fas fa-user"></i>
                                    <h5>个人认证</h5>
                                    <p>适用于个人用户，完成后可提升账号可信度并解锁更多能力。</p>
                                    <a href="{{url('member/addRealName?type=1')}}" class="btn btn-primary waves-effect waves-light">前往认证</a>
                                </div>
                                <div class="mx-member-auth-option">
                                    <i class="fas fa-users"></i>
                                    <h5>企业认证</h5>
                                    <p>适用于企业与组织账号，认证完成后可展示更完整的主体信息。</p>
                                    <a href="{{url('member/addRealName?type=2')}}" class="btn btn-primary waves-effect waves-light">前往认证</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            @include("member::home.public.footer")
        </div>
    </div>
</div>
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
