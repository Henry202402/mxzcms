<header id="page-topbar">
    <div class="navbar-header">
        <div class="container-fluid">
            <div class="float-end">

                {{--<div class="dropdown d-none d-sm-inline-block">
                    <button type="button" class="btn header-item waves-effect" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                        <img src="{{asset("assets/member")}}/picture/us.jpg" alt="Header Language" height="16">
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">

                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item notify-item">
                            <img src="{{asset("assets/member")}}/picture/spain.jpg" alt="user-image" class="me-1"
                                 height="12"> <span class="align-middle">Spanish</span>
                        </a>

                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item notify-item">
                            <img src="{{asset("assets/member")}}/picture/germany.jpg" alt="user-image" class="me-1"
                                 height="12"> <span class="align-middle">German</span>
                        </a>

                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item notify-item">
                            <img src="{{asset("assets/member")}}/picture/italy.jpg" alt="user-image" class="me-1"
                                 height="12"> <span class="align-middle">Italian</span>
                        </a>

                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item notify-item">
                            <img src="{{asset("assets/member")}}/picture/russia.jpg" alt="user-image" class="me-1"
                                 height="12"> <span class="align-middle">Russian</span>
                        </a>
                    </div>
                </div>--}}

                <div class="dropdown d-none d-lg-inline-block ms-1">
                    <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                        <i class="mdi mdi-fullscreen"></i>
                    </button>
                </div>

                <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item noti-icon waves-effect"
                            id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                        <i class="mdi mdi-bell-outline" onclick="window.location='{{url("member/message")}}'"></i>
                        <span class="badge rounded-pill bg-danger h-user-no-read-num"></span>
                    </button>
                </div>

                <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="rounded-circle header-profile-user"
                             src="{{GetUrlByPath(session("home_info")['avatar'])}}" alt="个人头像">
                        <span class="d-none d-xl-inline-block ms-1">{{session("home_info")['username']}}</span>
                        <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- item-->
                        <a class="dropdown-item" href="{{url("member/mine")}}"><i
                                    class="bx bx-user font-size-16 align-middle me-1"></i>
                            个人资料</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="{{url("logout")}}"><i
                                    class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i> 退出</a>
                    </div>
                </div>

            </div>
            <div>
                <!-- LOGO -->
                <div class="navbar-brand-box">
                    <a href="{{url("/")}}" class="logo logo-dark">
                                        <span class="logo-sm">
                                            <img src="{{GetLocalFileByPath(cacheGlobalSettingsByKey('member_weblogo'))}}"
                                                 alt="logo" height="40">
                                        </span>
                        <span class="logo-lg">
                                            <img src="{{GetLocalFileByPath(cacheGlobalSettingsByKey('member_weblogo'))}}"
                                                 alt="logo" height="40">
                                        </span>
                    </a>

                    <a href="{{url("/")}}" class="logo logo-light">
                                        <span class="logo-sm">
                                            <img src="{{GetLocalFileByPath(cacheGlobalSettingsByKey('member_weblogo'))}}"
                                                 alt="logo" height="40">
                                        </span>
                        <span class="logo-lg">
                                            <img src="{{GetLocalFileByPath(cacheGlobalSettingsByKey('member_weblogo'))}}"
                                                 alt="logo" height="40">
                                        </span>
                    </a>
                </div>

                <button type="button" class="btn btn-sm px-3 font-size-16 header-item toggle-btn waves-effect"
                        id="vertical-menu-btn">
                    <i class="fa fa-fw fa-bars"></i>
                </button>

                {{--<div class="dropdown dropdown-mega d-none d-lg-inline-block ms-2">
                    <button type="button" class="btn header-item waves-effect" data-bs-toggle="dropdown"
                            aria-haspopup="false" aria-expanded="false">
                        快速入口
                        <i class="mdi mdi-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu dropdown-megamenu">
                        <div class="row">
                            <div class="col-sm-6">

                                <div class="row">
                                    <div class="col-md-4">
                                        <h5 class="font-size-14">UI Components</h5>
                                        <ul class="list-unstyled megamenu-list text-muted">
                                            <li>
                                                <a href="javascript:void(0);">Lightbox</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);">Range Slider</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);">Sweet Alert</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);">Rating</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);">Forms</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);">Tables</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);">Charts</a>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="col-md-4">
                                        <h5 class="font-size-14">Applications</h5>
                                        <ul class="list-unstyled megamenu-list">
                                            <li>
                                                <a href="javascript:void(0);">Ecommerce</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);">Calendar</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);">Email</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);">Projects</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);">Tasks</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);">Contacts</a>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="col-md-4">
                                        <h5 class="font-size-14">Extra Pages</h5>
                                        <ul class="list-unstyled megamenu-list">
                                            <li>
                                                <a href="javascript:void(0);">Light Sidebar</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);">Compact Sidebar</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);">Horizontal layout</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);">Maintenance</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);">Coming Soon</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);">Timeline</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);">FAQs</a>
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h5 class="font-size-14">Components</h5>
                                        <div class="px-lg-2">
                                            <div class="row g-0">
                                                <div class="col">
                                                    <a class="dropdown-icon-item" href="javascript: void(0);">
                                                        <img src="{{asset("assets/member")}}/picture/github.png"
                                                             alt="Github">
                                                        <span>GitHub</span>
                                                    </a>
                                                </div>
                                                <div class="col">
                                                    <a class="dropdown-icon-item" href="javascript: void(0);">
                                                        <img src="{{asset("assets/member")}}/picture/bitbucket.png"
                                                             alt="bitbucket">
                                                        <span>Bitbucket</span>
                                                    </a>
                                                </div>
                                                <div class="col">
                                                    <a class="dropdown-icon-item" href="javascript: void(0);">
                                                        <img src="{{asset("assets/member")}}/picture/dribbble.png"
                                                             alt="dribbble">
                                                        <span>Dribbble</span>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="row g-0">
                                                <div class="col">
                                                    <a class="dropdown-icon-item" href="javascript: void(0);">
                                                        <img src="{{asset("assets/member")}}/picture/dropbox.png"
                                                             alt="dropbox">
                                                        <span>Dropbox</span>
                                                    </a>
                                                </div>
                                                <div class="col">
                                                    <a class="dropdown-icon-item" href="javascript: void(0);">
                                                        <img src="{{asset("assets/member")}}/picture/mail_chimp.png"
                                                             alt="mail_chimp">
                                                        <span>Mail Chimp</span>
                                                    </a>
                                                </div>
                                                <div class="col">
                                                    <a class="dropdown-icon-item" href="javascript: void(0);">
                                                        <img src="{{asset("assets/member")}}/picture/slack.png"
                                                             alt="slack">
                                                        <span>Slack</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div>
                                            <div class="card text-white mb-0 overflow-hidden text-white-50"
                                                 style="background-image: url('{{asset("assets/member")}}/image/megamenu-img.png');background-size: cover;">
                                                <div class="card-img-overlay"></div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-xl-6">
                                                            <h4 class="text-white mb-3">Sale</h4>

                                                            <h5 class="text-white-50">Up to <span
                                                                        class="font-size-24 text-white">50 %</span> Off
                                                            </h5>
                                                            <p>At vero eos accusamus et iusto odio.</p>
                                                            <div class="mb-4">
                                                                <a href="javascript: void(0);"
                                                                   class="btn btn-success btn-sm">View more</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>--}}
            </div>

        </div>
    </div>
</header>
<script>
    window.onload = function () {
        getUserNoReadMessage();
    };

    function getUserNoReadMessage() {
        ajaxData({no_load: 1}, function (res) {
            if(res.data.no_read_num) $('.h-user-no-read-num').html(res.data.no_read_num);
        }, domainPre + 'member/message/getUserNoReadMessage');
    }
</script>
