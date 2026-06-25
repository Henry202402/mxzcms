@php
    $langList = \Modules\Main\Services\ServiceModel::getLangList();
    $multilingualEnabled = \App\Support\I18n\ThemeTranslator::isMultilingualEnabled();
    $showLanguageSwitcher = $multilingualEnabled && count($langList) > 1;
    $currentLangCode = currentHomeLang();
    $currentLangName = $langList[$currentLangCode] ?? $currentLangCode;
    $langShortMap = ['zh-CN' => '简', 'zh-TW' => '繁', 'en' => 'EN'];
    $currentLangShort = $langShortMap[$currentLangCode] ?? strtoupper(str_replace(['zh-', 'ZH-'], '', $currentLangCode));
    $openLogin = (int) cacheGlobalSettingsByKey('website_open_login') === 1;
    $openRegister = (int) cacheGlobalSettingsByKey('website_open_reg') === 1;
    $guestActionUrl = $openLogin ? url('login') : ($openRegister ? url('register') : '');
    $guestActionText = $openLogin && $openRegister
        ? themeTrans('login.login_or_register')
        : ($openLogin ? themeTrans('login.submit') : themeTrans('login.register'));
    $navColor = cacheGlobalSettingsByKey('nav_color') ?: '#ffffff';
    $navBg = cacheGlobalSettingsByKey('nav_bgcolor') ?: '#0f172a';
    $navHoverColor = cacheGlobalSettingsByKey('nav_hover_color') ?: '#60a5fa';
    $navDropdownBg = cacheGlobalSettingsByKey('nav_dropdown_bgcolor') ?: '#0f172a';
    $navDropdownColor = cacheGlobalSettingsByKey('nav_dropdown_color') ?: '#e2e8f0';
    $navRadius = cacheGlobalSettingsByKey('nav_radius') ?: '18px';
    $navShadow = match (cacheGlobalSettingsByKey('nav_shadow_style')) {
        'none' => 'none',
        'strong' => '0 18px 38px rgba(15, 23, 42, 0.26)',
        default => '0 12px 30px rgba(15, 23, 42, 0.18)',
    };
    $logoAnimationClass = trim((string) (cacheGlobalSettingsByKey('logo_animated') ?: ''));
    $logoAnimationSpeed = cacheGlobalSettingsByKey('logo_animation_speed') ?: '6s';
    $logoHoverPause = cacheGlobalSettingsByKey('logo_hover_pause') === 'yes' ? 'logo-hover-pause' : '';
    $navPosition = cacheGlobalSettingsByKey('nav_position') ?: 'static';
@endphp
<div class="header mx-nav-{{ $navPosition }} @if(!$model['home_page_title'] && !$model['home_page_describe']) background-2 @else header-over large @endif "
     style="background: {{ $navBg }}; --mx-nav-color: {{ $navColor }}; --mx-nav-hover-color: {{ $navHoverColor }}; --mx-nav-dropdown-bg: {{ $navDropdownBg }}; --mx-nav-dropdown-color: {{ $navDropdownColor }}; --mx-nav-radius: {{ $navRadius }}; --mx-nav-shadow: {{ $navShadow }};">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-5">
                <!-- Logo Image -->
                <a href="{{url('/')}}" class="logo-image {{ $logoAnimationClass }} {{ $logoHoverPause }}" style="--mx-logo-animation-duration: {{ $logoAnimationSpeed }};">
                    <img src="{{GetLocalFileByPath(cacheGlobalSettingsByKey('weblogo'))}}" alt="logo">
                </a>
                <!-- End of Logo Image -->
            </div>
            <div class="col-md-9 col-sm-6 col-xs-7">
                <!-- Menu -->
                <nav class="right helper mx-header-nav">
                    <ul class="menu sf-menu js-menu mx-primary-menu">
                        @foreach($homeMenu['topMenu'] as $menu)
                            @include('themes.default.public.menuItem', ['menu' => $menu, 'depth' => 0])
                        @endforeach
                    </ul>

                    <div class="mx-header-tools">
                        @if($showLanguageSwitcher)
                            <div class="mx-header-language">
                                <a href="javascript:;" class="mx-header-tool mx-header-language__toggle" aria-haspopup="true" title="{{ $currentLangName }}">
                                    <i class="fa fa-globe"></i>
                                    <span class="mx-header-language__label">{{ $currentLangShort }}</span>
                                    <i class="fa fa-angle-down mx-nav-caret"></i>
                                </a>
                                <ul class="mx-header-language__menu">
                                    @foreach($langList as $langCode => $langName)
                                        <li>
                                            <a href="{{ url('/lang?lang=' . $langCode) }}" class="@if($currentLangCode === $langCode) is-active @endif" title="{{ $langName }}">
                                                <span class="mx-header-language__code">{{ $langShortMap[$langCode] ?? strtoupper(str_replace(['zh-', 'ZH-'], '', $langCode)) }}</span>
                                                <span class="mx-header-language__name">{{ $langName }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if($userInfo)
                            <a href="{{url("member")}}" class="mx-header-tool">
                                {{$userInfo['nickname']?:$userInfo['username']}}
                            </a>
                        @elseif($guestActionUrl)
                            <a href="{{ $guestActionUrl }}" class="mx-header-tool">
                                {{ $guestActionText }}
                            </a>
                        @endif
                    </div>
                </nav>
                <!-- End of Menu -->
            </div>
        </div>
    </div>
</div>
