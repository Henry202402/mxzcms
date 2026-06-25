@include("member::home.public.head")
<body data-layout="detached" data-topbar="colored">
<div class="container-fluid">
    <div id="layout-wrapper">
        @include("member::home.public.header")
        @include("member::home.public.leftnav")

        <div class="main-content">
            <div class="page-content">
                @include("member::home.public.topnav")

                <div class="mx-member-auth-summary">
                    <div class="mx-member-auth-status">
                        <span>VIP 服务</span>
                        <h4>{{ $wallet['vip_time'] ? '当前会员有效中' : '当前尚未开通 VIP' }}</h4>
                        <p>
                            @if($wallet['vip_time'])
                                到期时间：{{ $wallet['vip_time'] }}
                            @else
                                开通 VIP 可获得更多权益和会员专属服务。
                            @endif
                        </p>
                        <div class="mx-member-auth-actions">
                            <a href="{{ url('member/vipRecord') }}" class="btn btn-light waves-effect">购买记录</a>
                        </div>
                    </div>
                    <div class="mx-member-auth-details">
                        <div class="mx-member-auth-detail">
                            <span>实名认证</span>
                            <strong>{{ $auth && $auth['status'] == 1 ? '已实名' : '未实名' }}</strong>
                        </div>
                        <div class="mx-member-auth-detail">
                            <span>账户余额</span>
                            <strong>{{ number_format((float) ($wallet['balance'] ?? 0), 2) }} 元</strong>
                        </div>
                        <div class="mx-member-auth-detail">
                            <span>可用套餐</span>
                            <strong>{{ count($data) }} 个</strong>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="mx-member-section-head">
                            <div>
                                <h4 class="card-title mb-1">VIP 套餐列表</h4>
                                <p class="card-title-desc mb-0">根据你的使用场景选择合适的会员套餐。</p>
                            </div>
                        </div>
                        <div class="mx-member-vip-grid">
                            @foreach($data as $d)
                                <div class="mx-member-vip-card">
                                    <div class="mx-member-vip-card__name">{{ $d['name'] }}</div>
                                    <div class="mx-member-vip-card__price">
                                        {{ $d['discount_price'] * 1 }} 元
                                        @if($d['price']>$d['discount_price'])
                                            <small>{{ $d['price'] * 1 }} 元</small>
                                        @endif
                                    </div>
                                    <div class="mx-member-vip-card__desc">{!! $d['describe'] !!}</div>
                                    <div class="mx-member-vip-card__footer">
                                        <span>{{ $d['number'] * 1 }} {{ \Modules\Member\Models\VipOrder::type()[$d['type']] }}</span>
                                        <button type="button" class="btn btn-danger waves-effect waves-light" onclick="buyVip({{ $d['id'] }})">购买</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                @if(!empty($config['vip_rule']))
                    <div class="card">
                        <div class="card-body">
                            <div class="mx-member-section-head">
                                <div>
                                    <h4 class="card-title mb-1">VIP 规则说明</h4>
                                    <p class="card-title-desc mb-0">开通前请先阅读相关规则与权益说明。</p>
                                </div>
                            </div>
                            {!! $config['vip_rule'] !!}
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
