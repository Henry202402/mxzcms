<div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-3 col-xs-12">
                <div class="footer-logo-wrapper">
                    <!-- Logo Image -->
                    <a href="{{url('/')}}" class="logo-image ">
                        <img src="{{GetLocalFileByPath(cacheGlobalSettingsByKey('weblogo'))}}" alt="logo">
                    </a>
                    <!-- End of Logo Image -->
                </div>
            </div>
            <div class="col-md-9 col-sm-9 col-xs-12">
                <div class="footer-wrapper">
                    <!-- Scroll top -->
                    <span style="position: fixed;bottom: 10px;right: 20px;left: unset;top: unset;" class="scroll-top js-scroll-top"><i class="fa fa-angle-up"></i></span>
                    <!-- End of Scroll top -->
                    <!-- Footer Menu -->
                    <ul class="footer-menu helper right">
                        @foreach($homeMenu['footerMenu'] as $menu)
                            <li>
                                <a href="{{$menu['url']}}">
                                    {{$menu['name']}}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <!-- End of Footer Menu -->
                    <!-- Copyright -->
                    <p class="copyright helper right">
                        <a href="{{url('/')}}">{{cacheGlobalSettingsByKey('website_copyright')}}</a> &copy;版权所有. {{date('Y')}}
                        <a href="https://beian.miit.gov.cn/" target="_blank">{{cacheGlobalSettingsByKey('website_icp')}}</a>
                        Powered By <a target="_blank" href="https://www.mxzcms.com">梦小记CMS</a>
                    </p>
                    <!-- End of Copyright -->
                </div>
            </div>
        </div>
    </div>
