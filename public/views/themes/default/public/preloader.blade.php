@php
    $pageWidthClass = cacheGlobalSettingsByKey('page_width') === 'pull-container' ? 'mx-page-width-pull-container' : 'mx-page-width-container';
    $siteBg = cacheGlobalSettingsByKey('global_bgcolor') ?: '#f8fbff';
    $siteFont = cacheGlobalSettingsByKey('global_font') ?: '"PingFang SC", "Microsoft YaHei", "Helvetica Neue", Arial, sans-serif';
@endphp
<body class="js-preload-me {{ $pageWidthClass }}" style="--mx-site-bg: {{ $siteBg }}; --mx-site-font: {!! e($siteFont) !!};">
<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
    your browser</a> to improve your experience.</p>
<![endif]-->
@if(cacheGlobalSettingsByKey("preloader")=="on")
<div class="preloader js-preloader">
    <div class="preloader-animation"></div>
</div>
@endif
