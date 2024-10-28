<div class="one-page-sidebar">
    <div class="one-page-sidebar-header">
        <div class="one-page-logo">

            <a href="{{url("/")}}" class="logo-image ">
                <img src="{{GetLocalFileByPath(cacheGlobalSettingsByKey('weblogo'))}}" alt="logo">
            </a>

        </div>

        <div class="one-page-meta">
            <ul class="one-page-meta-list">
                <li><a href="{{url("/")}}"><i class="one-page-meta-list-icon fa fa-home"></i>网站首页</a></li>
                <li><a href="{{url("member/message")}}"><i class="one-page-meta-list-icon fa fa-envelope"></i>站内信({{$userInfo['messageNum']}})</a></li>
                <li><a href="{{url("logout")}}"><i class="one-page-meta-list-icon fa fa-user-times"></i>注销</a></li>
            </ul>
        </div>

    </div>

    <div class="one-page-nav-wrapper js-custom-scrollbar">
        <ul class="one-page-nav js-one-page-nav js-menu-vertical" data-prepend-to=".js-prepend-mobile-menu">

            <li @if(request()->route()->uri=="member" || request()->route()->uri=="member/index") class="is-active" @endif >
                <a href="{{url("member")}}" class=""><span class="one-page-nav-icon"><i class="fa fa-dashboard"></i></span>会员中心</a>
            </li>

            <li @if(request()->route()->uri=="member/mine") class="is-active" @endif >
                <a href="{{url("member/mine")}}" class=""><span class="one-page-nav-icon"><i class="fa fa-user"></i></span>个人资料</a>
            </li>

            {{--<li class="">
                <a href="#download-source" class=""><span class="one-page-nav-icon"><i class="fa fa-money"></i></span>我的钱包</a>
            </li>

            <li class="">
                <a href="#download-source" class=""><span class="one-page-nav-icon"><i class="fa fa-behance"></i></span>我的积分</a>
            </li>

            <li class="">
                <a href="#download-source" class=""><span class="one-page-nav-icon"><i class="fa fa-cart-plus"></i></span>我的订单</a>
            </li>--}}

            <li class="">
                <a href="{{url("logout")}}" class=""><span class="one-page-nav-icon"><i class="fa fa-sign-out"></i></span>退出</a>
            </li>

        </ul>
    </div>

    <footer class="one-page-sidebar-footer">
        Powered By <a target="_blank" href="https://www.mxzcms.com">梦小记CMS</a>
    </footer>

</div>
