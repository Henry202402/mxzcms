@php
    $bannerIdentification = trim((string) ($identification ?? 'homepage'));
    $bannerClass = trim((string) ($class ?? 'header-back header-back-default header-back-full-page js-full-page'));
    $bannerWrapperClass = trim((string) ($wrapperClass ?? 'header-back-container'));
    $bannerTarget = trim((string) ($target ?? '_blank'));
    $bannerDisplayMode = trim((string) ($displayMode ?? 'single'));
    $bannerContainerClass = trim((string) ($containerClass ?? 'container'));
    $bannerCardClass = trim((string) ($cardClass ?? 'mx-theme-banner-card'));
    $bannerLimit = intval($limit ?? ($bannerDisplayMode === 'single' ? 1 : 0));
    $bannerList = collect();
    foreach (hook("ShowAD", ['moduleName' => "AD", 'pageData' => ['identification' => $bannerIdentification, 'adtype' => 'banner']]) as $bannerGroup) {
        foreach ($bannerGroup as $bannerItem) {
            $bannerList->push($bannerItem);
        }
    }
    if ($bannerLimit > 0) {
        $bannerList = $bannerList->take($bannerLimit);
    }
@endphp

@once
    <style>
        .mx-theme-banner-grid-wrap { padding: 30px 0; }
        .mx-theme-banner-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px; }
        .mx-theme-banner-card { position: relative; min-height: 220px; border-radius: 20px; overflow: hidden; color: #fff; background: #1f2937; box-shadow: 0 18px 40px rgba(15, 23, 42, 0.18); }
        .mx-theme-banner-card__bg { position: absolute; inset: 0; background-size: cover; background-position: center; transform: scale(1.02); }
        .mx-theme-banner-card__mask { position: absolute; inset: 0; background: linear-gradient(180deg, rgba(15,23,42,0.08), rgba(15,23,42,0.78)); }
        .mx-theme-banner-card__content { position: relative; z-index: 2; display: flex; flex-direction: column; justify-content: flex-end; min-height: 220px; padding: 24px; }
        .mx-theme-banner-card__title { margin: 0 0 8px; font-size: 24px; line-height: 1.3; color: #fff; }
        .mx-theme-banner-card__desc { margin: 0; font-size: 14px; line-height: 1.8; color: rgba(255,255,255,0.9); }
        .mx-theme-banner-card__link { display: block; color: inherit; text-decoration: none; }
    </style>
@endonce

@if($bannerDisplayMode === 'single')
    @php($banner = $bannerList->first())
    @if($banner)
        @php($bannerOtherParam = is_array($banner->other_param ?? null) ? $banner->other_param : (json_decode($banner->other_param ?? '[]', true) ?: []))
        @php($bannerUrl = trim((string) ($banner->url ?? '')))
        @php($bannerTitle = trim((string) ($banner->title ?? ($bannerOtherParam[0]['name'] ?? 'Banner'))))
        <div class="{{ $bannerClass }}"
             data-show-type="banner"
             data-show-id="{{ $banner->id }}"
             data-show-slot="{{ $bannerIdentification }}"
             data-show-url="{{ url('ad/showBanner') }}"
             style="background-image: url({{ GetUrlByPath($banner->image) }});background-repeat: no-repeat;background-size: cover;background-position: center;">
            <div class="{{ $bannerWrapperClass }}">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            @if($bannerUrl !== '')
                                <a href="{{ $bannerUrl }}"
                                   class="page-info helper center mx-theme-banner-track"
                                   target="{{ $bannerTarget }}"
                                   data-track-type="banner"
                                   data-track-id="{{ $banner->id }}"
                                   data-track-slot="{{ $bannerIdentification }}"
                                   data-track-url="{{ url('ad/clickBanner') }}"
                                   aria-label="{{ $bannerTitle }}">
                            @else
                                <div class="page-info helper center">
                            @endif
                                    @forelse($bannerOtherParam as $key => $bannerItem)
                                        @if($key === 0)
                                            <h1 class="page-title">{{ $bannerItem['name'] }}</h1>
                                        @else
                                            <h2 class="page-description">{{ $bannerItem['name'] }}</h2>
                                        @endif
                                    @empty
                                        <h1 class="page-title">{{ $bannerTitle }}</h1>
                                        @if(!empty($banner->describe))
                                            <h2 class="page-description">{{ $banner->describe }}</h2>
                                        @endif
                                    @endforelse
                            @if($bannerUrl !== '')
                                </a>
                            @else
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@elseif($bannerList->count())
    <div class="mx-theme-banner-grid-wrap">
        <div class="{{ $bannerContainerClass }}">
            <div class="mx-theme-banner-grid">
                @foreach($bannerList as $banner)
                    @php($bannerOtherParam = is_array($banner->other_param ?? null) ? $banner->other_param : (json_decode($banner->other_param ?? '[]', true) ?: []))
                    @php($bannerUrl = trim((string) ($banner->url ?? '')))
                    @php($bannerTitle = trim((string) ($banner->title ?? ($bannerOtherParam[0]['name'] ?? 'Banner'))))
                    @php($bannerDesc = trim((string) (($bannerOtherParam[1]['name'] ?? '') ?: ($banner->describe ?? ''))))
                    <div class="{{ $bannerCardClass }}"
                         data-show-type="banner"
                         data-show-id="{{ $banner->id }}"
                         data-show-slot="{{ $bannerIdentification }}"
                         data-show-url="{{ url('ad/showBanner') }}">
                        <div class="mx-theme-banner-card__bg" style="background-image:url({{ GetUrlByPath($banner->image) }});"></div>
                        <div class="mx-theme-banner-card__mask"></div>
                        <div class="mx-theme-banner-card__content">
                            @if($bannerUrl !== '')
                                <a href="{{ $bannerUrl }}"
                                   class="mx-theme-banner-card__link"
                                   target="{{ $bannerTarget }}"
                                   data-track-type="banner"
                                   data-track-id="{{ $banner->id }}"
                                   data-track-slot="{{ $bannerIdentification }}"
                                   data-track-url="{{ url('ad/clickBanner') }}">
                                    <h3 class="mx-theme-banner-card__title">{{ $bannerTitle }}</h3>
                                    @if($bannerDesc !== '')
                                        <p class="mx-theme-banner-card__desc">{{ $bannerDesc }}</p>
                                    @endif
                                </a>
                            @else
                                <div>
                                    <h3 class="mx-theme-banner-card__title">{{ $bannerTitle }}</h3>
                                    @if($bannerDesc !== '')
                                        <p class="mx-theme-banner-card__desc">{{ $bannerDesc }}</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
