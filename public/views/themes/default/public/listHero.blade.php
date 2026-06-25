@php
    $listContext = $listContext ?? [];
    $pageTitle = $listContext['pageTitle'] ?? ($model['home_config']['home_page_title'] ?? ($model['name'] ?? '内容列表'));
    $pageDescription = $listContext['pageDescription'] ?? ($model['home_config']['home_page_describe'] ?? '浏览最新内容、快速筛选重点信息。');
    $searchKeyword = $listContext['keyword'] ?? '';
    $hasKeyword = (bool) ($listContext['hasKeyword'] ?? false);
    $searchFieldNames = $listContext['searchFieldNames'] ?? [];
    $searchFieldSummary = $listContext['searchFieldSummary'] ?? '';
    $resultCount = (int) ($listContext['resultCount'] ?? 0);
    $totalCount = (int) ($listContext['totalCount'] ?? $resultCount);
    $currentUrl = url()->current();
    $queryWithoutKeyword = request()->except(['keyword', 'page']);
@endphp

<div class="mx-list-hero">
    <div class="mx-list-hero__main">
        <div class="mx-list-hero__eyebrow">Content Explorer</div>
        <h1 class="mx-list-hero__title">{{ $pageTitle }}</h1>
        <p class="mx-list-hero__description">{{ $pageDescription }}</p>
        <div class="mx-list-hero__stats">
            <span class="mx-list-stat">
                <strong>{{ $resultCount }}</strong>
                <em>{{ $hasKeyword ? '当前结果' : '当前内容' }}</em>
            </span>
            <span class="mx-list-stat">
                <strong>{{ $totalCount }}</strong>
                <em>{{ $hasKeyword ? '总匹配数' : '内容总量' }}</em>
            </span>
            @if($searchFieldNames)
                <span class="mx-list-stat">
                    <strong>{{ count($searchFieldNames) }}</strong>
                    <em>搜索字段</em>
                </span>
            @endif
        </div>
    </div>

    <div class="mx-search-panel">
        <form method="get" action="{{ $currentUrl }}" class="mx-search-form">
            @foreach($queryWithoutKeyword as $queryKey => $queryValue)
                @if(is_array($queryValue))
                    @foreach($queryValue as $subValue)
                        <input type="hidden" name="{{ $queryKey }}[]" value="{{ $subValue }}">
                    @endforeach
                @else
                    <input type="hidden" name="{{ $queryKey }}" value="{{ $queryValue }}">
                @endif
            @endforeach

            <label class="mx-search-form__label" for="mx-list-search-keyword">站内搜索</label>
            <div class="mx-search-form__row">
                <div class="mx-search-form__field">
                    <i class="fa fa-search"></i>
                    <input
                        id="mx-list-search-keyword"
                        type="text"
                        name="keyword"
                        value="{{ $searchKeyword }}"
                        class="form-control"
                        placeholder="{{ $searchFieldSummary ? '可搜索：' . $searchFieldSummary : '输入标题、名称或内容关键词' }}"
                    >
                </div>
                <button type="submit" class="button">搜索内容</button>
                @if($hasKeyword)
                    <a href="{{ $currentUrl . ($queryWithoutKeyword ? ('?' . http_build_query($queryWithoutKeyword)) : '') }}" class="mx-search-form__reset">清空</a>
                @endif
            </div>
        </form>

        <div class="mx-search-panel__meta">
            @if($searchFieldNames)
                <span class="mx-search-chip">字段：{{ $searchFieldSummary }}</span>
            @endif
            @if($hasKeyword)
                <span class="mx-search-chip mx-search-chip--active">关键词：{{ $searchKeyword }}</span>
            @else
                <span class="mx-search-chip">可直接按关键词筛选列表内容</span>
            @endif
        </div>
    </div>
</div>

@include('themes.default.public.themeBanner', ['identification' => 'list_top', 'displayMode' => 'single', 'class' => 'header-back header-back-default'])
@include('themes.default.public.themeAdvert', ['identification' => 'list_inline', 'displayMode' => 'multiple', 'columns' => 2, 'limit' => 2])
