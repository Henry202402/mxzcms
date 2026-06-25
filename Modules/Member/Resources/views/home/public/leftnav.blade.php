<div class="vertical-menu">

    <div class="h-100">
        @php
            $homeUser = session('home_info');
            $currentActive = $tig['active'] ?? 'index';
        @endphp

        <div class="user-wid text-center py-4">
            <div class="user-img">
                <img src="{{GetUrlByPath($homeUser['avatar'])}}" alt=""
                     class="avatar-md mx-auto rounded-circle">
            </div>

            <div class="mt-3">

                <a href="javascript: void(0);"
                   class="text-dark fw-medium font-size-16">{{ $homeUser['nickname'] ?: $homeUser['username'] }}</a>
                <p class="text-body mt-1 mb-0 font-size-13">{{ $homeUser['email'] ?: ($homeUser['phone'] ?: '欢迎回来') }}</p>
                <div class="mx-member-side-actions">
                    <a href="{{ url('member/mine') }}">编辑资料</a>
                    <a href="{{ url('member/password') }}">安全设置</a>
                </div>

            </div>
        </div>

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">首页总览</li>

                <li @if($currentActive=='index')class="mm-active"@endif>
                    <a href="{{url("member")}}" class="waves-effect">
                        <i class="mdi mdi-view-dashboard-outline"></i>
                        <span>控制台</span>
                    </a>
                </li>

                <li class="menu-title">资料与安全</li>

                <li @if($currentActive=='mine')class="mm-active"@endif>
                    <a href="{{url("member/mine")}}" class="waves-effect">
                        <i class="mdi mdi-account-edit-outline"></i>
                        <span>个人资料</span>
                    </a>
                </li>

                <li @if($currentActive=='password')class="mm-active"@endif>
                    <a href="{{url("member/password")}}" class="waves-effect">
                        <i class="mdi mdi-shield-lock-outline"></i>
                        <span>账号安全</span>
                    </a>
                </li>

                <li @if($currentActive=='myRealName')class="mm-active"@endif>
                    <a href="{{url("member/myRealName")}}" class="waves-effect">
                        <i class="mdi mdi-card-account-details-outline"></i>
                        <span>实名认证</span>
                    </a>
                </li>

                <li class="menu-title">成长与服务</li>

                <li @if($currentActive=='myMembers')class="mm-active"@endif>
                    <a href="{{url("member/myMembers")}}" class="waves-effect">
                        <i class="mdi mdi-account-multiple-outline"></i>
                        <span>我的会员</span>
                    </a>
                </li>
                <li @if($currentActive=='signIn')class="mm-active"@endif>
                    <a href="{{url("member/signIn")}}" class="waves-effect">
                        <i class="mdi mdi-calendar-check-outline"></i>
                        <span>签到</span>
                    </a>
                </li>
                <li @if($currentActive=='myVip')class="mm-active"@endif>
                    <a href="{{url("member/myVip")}}" class="waves-effect">
                        <i class="mdi mdi-crown-outline"></i>
                        <span>我的VIP</span>
                    </a>
                </li>

                <li class="menu-title">资产与消息</li>

                <li @if($currentActive=='myWallet')class="mm-active"@endif>
                    <a href="{{url("member/myWallet")}}" class="waves-effect">
                        <i class="mdi mdi-wallet-outline"></i>
                        <span>我的钱包</span>
                    </a>
                </li>

                <li @if($currentActive=='myBill')class="mm-active"@endif>
                    <a href="{{url("member/myBill")}}" class="waves-effect">
                        <i class="mdi mdi-receipt-text-outline"></i>
                        <span>我的账单</span>
                    </a>
                </li>

                {{--<li @if($tig['active']=='myWithdrawal')class="mm-active"@endif>
                    <a href="javascript: void(0);" class="waves-effect">
                        <i class="mdi mdi-chart-donut"></i>
                        <span>我的提现</span>
                    </a>
                </li>--}}
                <li @if($currentActive=='systemMessage')class="mm-active"@endif>
                    <a href="{{url("member/message")}}" class="waves-effect">
                        <i class="mdi mdi-bell-outline"></i>
                        <span>站内信</span>
                    </a>
                </li>
                <li class="menu-title">快捷操作</li>
                <li>
                    <a href="{{url('/')}}" target="_blank" class="waves-effect">
                        <i class="mdi mdi-home-outline"></i>
                        <span>返回前台</span>
                    </a>
                </li>
                <li>
                    <a href="{{url("logout")}}" class="waves-effect">
                        <i class="bx bx-power-off"></i>
                        <span>退出</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
