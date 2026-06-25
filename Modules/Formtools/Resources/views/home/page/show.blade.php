<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $pageTitle = trim((string) ($page->seo_title ?: $page->name));
        $pageDescription = trim((string) $page->seo_description);
        $pageKeywords = trim((string) $page->seo_keywords);
        $pageUrl = $publicUrl ?: request()->fullUrl();
    @endphp
    <title>{{ $pageTitle }}</title>
    @if($page->seo_keywords)
        <meta name="keywords" content="{{ $pageKeywords }}">
    @endif
    @if($page->seo_description)
        <meta name="description" content="{{ $pageDescription }}">
    @endif
    <meta name="robots" content="{{ !$isPreview && $page->status ? 'index,follow' : 'noindex,nofollow' }}">
    <link rel="canonical" href="{{ $pageUrl }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:url" content="{{ $pageUrl }}">
    @if($pageDescription !== '')
        <meta property="og:description" content="{{ $pageDescription }}">
        <meta name="twitter:description" content="{{ $pageDescription }}">
    @endif
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
        <link rel="stylesheet" href="{{ asset('views/modules/formtools/assets/page-core.css') }}">
    @if(trim((string) $page->custom_css) !== '')
        <style>
{!! $page->custom_css !!}
        </style>
    @endif
</head>
<body>
    @if($isPreview)
        <div style="padding:10px 16px;background:#0f172a;color:#fff;font-size:13px;">
            后台预览模式
            @if($publicUrl)
                <span style="margin-left:12px;opacity:.82;">正式地址：{{ $publicUrl }}</span>
            @endif
        </div>
    @endif
    <div class="mx-page-body" id="mx-page-{{ $page->identification }}">
        @if(trim((string) $page->page_html) !== '')
            {!! $page->page_html !!}
        @elseif(trim((string) $layoutHtml) !== '')
            {!! $layoutHtml !!}
        @else
            <div class="mx-page-section__inner" style="padding: 40px 0;">
                <div class="mx-page-empty">
                    当前页面还没有布局内容。你可以先在后台填写 <code>布局 JSON</code> 或 <code>页面 HTML</code>，再回来预览。
                </div>
            </div>
        @endif
    </div>

    <script src="{{ asset('views/modules/formtools/assets/page-core.js') }}"></script>
    @if(trim((string) $page->custom_js) !== '')
        <script>
{!! $page->custom_js !!}
        </script>
    @endif
</body>
</html>

