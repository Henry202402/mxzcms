@include("member::home.public.head")

<body data-layout="detached" data-topbar="colored">
<div class="container-fluid">
    <div id="layout-wrapper">
        @include("member::home.public.header")
        @include("member::home.public.leftnav")

        <div class="main-content">
            <div class="page-content">
                @include("member::home.public.topnav")

                <div class="mx-member-hero">
                    <div class="mx-member-hero__content">
                        <span class="mx-member-hero__badge">欢迎回来</span>
                        <h2>{{ $user['nickname'] ?: $user['username'] }}，今天也继续保持高质量运营。</h2>
                        <p>资料完整度 {{ $overview['profile_completion'] }}%，实名状态 {{ $overview['auth_status'] }}，当前未读消息 {{ $overview['unread_message_num'] }} 条。</p>
                        <div class="mx-member-hero__actions">
                            <a href="{{ url('member/mine') }}" class="btn btn-light waves-effect">完善资料</a>
                            <a href="{{ url('member/password') }}" class="btn btn-outline-light waves-effect">修改密码</a>
                        </div>
                    </div>
                    <div class="mx-member-hero__meta">
                        <div class="mx-member-hero__meta-item">
                            <span>注册账号</span>
                            <strong>{{ $user['username'] }}</strong>
                        </div>
                        <div class="mx-member-hero__meta-item">
                            <span>VIP 状态</span>
                            <strong>{{ $overview['vip_time_text'] }}</strong>
                        </div>
                        <div class="mx-member-hero__meta-item">
                            <span>实名认证</span>
                            <strong class="is-{{ $overview['auth_tone'] }}">{{ $overview['auth_status'] }}</strong>
                        </div>
                    </div>
                </div>

                <div class="row">
                    @foreach($overview['stats'] as $stat)
                        <div class="col-xl-3 col-md-6">
                            <a href="{{ $stat['url'] }}" class="mx-member-stat-card is-{{ $stat['tone'] }}">
                                <div>
                                    <span class="mx-member-stat-card__label">{{ $stat['label'] }}</span>
                                    <h3>{{ $stat['value'] }}<small>{{ $stat['suffix'] }}</small></h3>
                                </div>
                                <i class="{{ $stat['icon'] }}"></i>
                            </a>
                        </div>
                    @endforeach
                </div>

                <div class="row">
                    <div class="col-xl-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="mx-member-section-head">
                                    <div>
                                        <h4 class="card-title mb-1">常用功能</h4>
                                        <p class="card-title-desc mb-0">把最常访问的账户操作集中在这里。</p>
                                    </div>
                                </div>
                                <div class="mx-member-quick-grid">
                                    @foreach($overview['quick_links'] as $item)
                                        <a href="{{ $item['url'] }}" class="mx-member-quick-card">
                                            <i class="{{ $item['icon'] }}"></i>
                                            <strong>{{ $item['title'] }}</strong>
                                            <span>{{ $item['desc'] }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="mx-member-section-head">
                                    <div>
                                        <h4 class="card-title mb-1">账户状态</h4>
                                        <p class="card-title-desc mb-0">随时掌握资料和安全完成情况。</p>
                                    </div>
                                </div>
                                <div class="mx-member-status-list">
                                    <div class="mx-member-status-item">
                                        <span>资料完整度</span>
                                        <strong>{{ $overview['profile_completion'] }}%</strong>
                                    </div>
                                    <div class="progress mx-member-progress">
                                        <div class="progress-bar" role="progressbar" style="width: {{ $overview['profile_completion'] }}%"></div>
                                    </div>
                                    <div class="mx-member-status-item">
                                        <span>实名认证</span>
                                        <strong>{{ $overview['auth_status'] }}</strong>
                                    </div>
                                    <div class="mx-member-status-item">
                                        <span>我的会员</span>
                                        <strong>{{ $overview['member_count'] }} 位</strong>
                                    </div>
                                    <div class="mx-member-status-item">
                                        <span>本月签到</span>
                                        <strong>{{ $overview['month_sign_count'] }} 次</strong>
                                    </div>
                                    <div class="mx-member-status-item">
                                        <span>VIP 状态</span>
                                        <strong>{{ $overview['vip_time_text'] }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="mx-member-section-head">
                                    <div>
                                        <h4 class="card-title mb-1">服务入口</h4>
                                        <p class="card-title-desc mb-0">围绕成长、资产和消息的高频服务。</p>
                                    </div>
                                </div>
                                <div class="mx-member-service-list">
                                    @foreach($overview['service_cards'] as $item)
                                        <a href="{{ $item['url'] }}" class="mx-member-service-card">
                                            <i class="{{ $item['icon'] }}"></i>
                                            <div>
                                                <strong>{{ $item['title'] }}</strong>
                                                <p>{{ $item['desc'] }}</p>
                                                <span>{{ $item['meta'] }}</span>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="mx-member-section-head">
                                    <div>
                                        <h4 class="card-title mb-1">扩展模块入口</h4>
                                        <p class="card-title-desc mb-0">已接入到会员中心的扩展功能会出现在这里。</p>
                                    </div>
                                </div>
                                <div class="mx-member-module-grid">
                                    @forelse($list as $val)
                                        @if($val)
                                            <a href="{{ $val['url'] }}" target="_blank" class="mx-member-module-card">
                                                @if($val['icontype']=="imgage")
                                                    <img src="{{ $val['icon'] }}" alt="{{ $val['name'] }}">
                                                @else
                                                    <i class="{{ $val['icon'] }}"></i>
                                                @endif
                                                <span>{{ $val['name'] }}</span>
                                            </a>
                                        @endif
                                    @empty
                                        <div class="mx-member-empty">暂无其他模块入口</div>
                                    @endforelse
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
