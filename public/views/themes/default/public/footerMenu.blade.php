<div class="container">
    <div class="mx-footer-bottom">
        <div class="mx-footer-brand">
            <div class="mx-footer-brand__content">
                <a href="{{url('/')}}" class="logo-image mx-footer-brand__logo" style="display:inline-block;margin-bottom:12px;">
                    <img src="{{GetLocalFileByPath(cacheGlobalSettingsByKey('weblogo'))}}" alt="logo" style="display:block;width:auto;height:auto;max-width:88px;max-height:88px;object-fit:contain;">
                </a>
                <h4 class="mx-footer-brand__title" style="margin:0;">{{ cacheGlobalSettingsByKey('base_name') }}</h4>
            </div>
        </div>

        <div class="mx-footer-meta">
            @if($homeMenu['footerMenu'])
                <ul class="mx-footer-links">
                    @foreach($homeMenu['footerMenu'] as $menu)
                        <li>
                            <a href="{{$menu['url']}}" target="{{$menu['target'] ?? '_self'}}" @if(($menu['target'] ?? '_self') === '_blank') rel="noopener noreferrer" @endif>
                                {{$menu['name']}}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif

            <p class="mx-footer-copyright">
                <a href="{{url('/')}}">{{cacheGlobalSettingsByKey('website_copyright')}}</a>
                <span>&copy; {{ date('Y') }}</span>
                @if(cacheGlobalSettingsByKey('website_icp'))
                    <a href="https://beian.miit.gov.cn/" target="_blank" rel="noopener noreferrer">{{cacheGlobalSettingsByKey('website_icp')}}</a>
                @endif
                <span>Powered By 梦小记CMS</span>
            </p>
        </div>
    </div>

    <span class="scroll-top js-scroll-top mx-scroll-top"><i class="fa fa-angle-up"></i></span>
</div>
