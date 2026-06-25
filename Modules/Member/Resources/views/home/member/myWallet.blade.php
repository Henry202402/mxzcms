@include("member::home.public.head")

<body data-layout="detached" data-topbar="colored">
<div class="container-fluid">
    <div id="layout-wrapper">
        @include("member::home.public.header")
        @include("member::home.public.leftnav")

        <div class="main-content">
            <div class="page-content">
                @include("member::home.public.topnav")

                <div class="mx-member-info-grid">
                    <div class="mx-member-info-card">
                        <span>账户余额</span>
                        <strong>{{ number_format((float) ($wallet['balance'] ?? 0), 2) }}</strong>
                        <em>元</em>
                    </div>
                    <div class="mx-member-info-card">
                        <span>可提现余额</span>
                        <strong>{{ number_format((float) ($wallet['withdrawable'] ?? 0), 2) }}</strong>
                        <em>元</em>
                    </div>
                    <div class="mx-member-info-card">
                        <span>{{ $config['integral_alias'] ?: '积分' }}</span>
                        <strong>{{ (int) ($wallet['integral'] ?? 0) }}</strong>
                        <em>分</em>
                    </div>
                    <div class="mx-member-info-card">
                        <span>VIP 到期时间</span>
                        <strong>{{ $wallet['vip_time'] ?: '未开通' }}</strong>
                        <em><a href="{{ url('member/myVip') }}">查看 VIP</a></em>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="mx-member-section-head">
                                    <div>
                                        <h4 class="card-title mb-1">钱包概览</h4>
                                        <p class="card-title-desc mb-0">查看账户资产构成和后续操作入口。</p>
                                    </div>
                                </div>
                                <div class="mx-member-service-list">
                                    <div class="mx-member-service-card">
                                        <i class="mdi mdi-wallet-outline"></i>
                                        <div>
                                            <strong>账户余额</strong>
                                            <p>可用于平台内消费或后续资金操作。</p>
                                            <span>{{ number_format((float) ($wallet['balance'] ?? 0), 2) }} 元</span>
                                        </div>
                                    </div>
                                    <div class="mx-member-service-card">
                                        <i class="mdi mdi-cash-fast"></i>
                                        <div>
                                            <strong>可提现余额</strong>
                                            <p>符合规则的金额可申请提现或后续结算。</p>
                                            <span>{{ number_format((float) ($wallet['withdrawable'] ?? 0), 2) }} 元</span>
                                        </div>
                                    </div>
                                    <div class="mx-member-service-card">
                                        <i class="mdi mdi-star-circle-outline"></i>
                                        <div>
                                            <strong>{{ $config['integral_alias'] ?: '积分' }}</strong>
                                            <p>可通过签到、活动和业务奖励持续积累。</p>
                                            <span>{{ (int) ($wallet['integral'] ?? 0) }} 分</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="mx-member-side-panel">
                                    <h5>快捷操作</h5>
                                    <ul class="mx-member-tips">
                                        <li>想看流水记录，直接进入账单页查看全部变动。</li>
                                        <li>积分与签到强相关，可去签到中心持续累积。</li>
                                        <li>VIP 到期前建议提前续费，避免权益中断。</li>
                                    </ul>
                                    <div class="mx-member-safe-actions">
                                        <a href="{{ url('member/myBill') }}" class="btn btn-outline-primary waves-effect">查看账单</a>
                                        <a href="{{ url('member/myVip') }}" class="btn btn-outline-secondary waves-effect">VIP 中心</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include("member::home.public.footer")
        </div>
    </div>
</div>
<div class="rightbar-overlay"></div>

@include("member::home.public.js")

</body>

</html>
