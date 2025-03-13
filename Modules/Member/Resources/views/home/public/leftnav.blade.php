<div class="vertical-menu" style="overflow: auto;">

    <div class="h-100">

        <div class="user-wid text-center py-4">
            <div class="user-img">
                <img src="{{GetUrlByPath(session("home_info")['avatar'])}}" alt=""
                     class="avatar-md mx-auto rounded-circle">
            </div>

            <div class="mt-3">

                <a href="javascript: void(0);"
                   class="text-dark fw-medium font-size-16">{{session("home_info")['username']}}</a>
                <p class="text-body mt-1 mb-0 font-size-13">普通用户</p>

            </div>
        </div>

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">个人中心</li>

                <li @if($tig['active']=='index')class="mm-active"@endif>
                    <a href="{{url("member")}}" class="waves-effect">
                        <i class="mdi mdi-airplay"></i>
                        <span>会员中心</span>
                    </a>
                </li>

                <li @if($tig['active']=='myRealName')class="mm-active"@endif>
                    <a href="{{url("member/myRealName")}}" class="waves-effect">
                        <i class="mdi mdi-calendar-text"></i>
                        <span>我的实名</span>
                    </a>
                </li>
                <li class="menu-title">代理管理</li>

                <li @if($tig['active']=='myMembers')class="mm-active"@endif>
                    <a href="{{url("member/myMembers")}}" class="waves-effect">
                        <i class="fas fa-user-friends"></i>
                        <span>我的会员</span>
                    </a>
                </li>

                <li class="menu-title">VIP中心</li>

                <li @if($tig['active']=='signIn')class="mm-active"@endif>
                    <a href="{{url("member/signIn")}}" class="waves-effect">
                        <i class="mdi mdi-pencil"></i>
                        <span>签到</span>
                    </a>
                </li>
                <li @if($tig['active']=='myVip')class="mm-active"@endif>
                    <a href="{{url("member/myVip")}}" class="waves-effect">
                        <i class="fas fa-yen-sign"></i>
                        <span>我的VIP</span>
                    </a>
                </li>


                <li class="menu-title">财务管理</li>

                <li @if($tig['active']=='myWallet')class="mm-active"@endif>
                    <a href="{{url("member/myWallet")}}" class="waves-effect">
                        <i class="fas fa-credit-card"></i>
                        <span>我的钱包</span>
                    </a>
                </li>

                <li @if($tig['active']=='myBill')class="mm-active"@endif>
                    <a href="{{url("member/myBill")}}" class="waves-effect">
                        <i class="fas fa-list-alt"></i>
                        <span>我的账单</span>
                    </a>
                </li>

                {{--<li @if($tig['active']=='myWithdrawal')class="mm-active"@endif>
                    <a href="javascript: void(0);" class="waves-effect">
                        <i class="mdi mdi-chart-donut"></i>
                        <span>我的提现</span>
                    </a>
                </li>--}}
                <li class="menu-title">系统管理</li>
                <li @if($tig['active']=='systemMessage')class="mm-active"@endif>
                    <a href="{{url("member/message")}}" class="waves-effect">
                        <i class="mdi mdi-bell-outline"></i>
                        <span>站内信</span>
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
