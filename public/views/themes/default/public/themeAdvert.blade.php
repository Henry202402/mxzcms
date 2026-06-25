@php
    $advertIdentification = trim((string) ($identification ?? 'homepage_notice'));
    $advertTarget = trim((string) ($target ?? '_blank'));
    $advertWrapperClass = trim((string) ($wrapperClass ?? 'container'));
    $advertItemClass = trim((string) ($itemClass ?? 'mx-theme-advert-item'));
    $advertDisplayMode = trim((string) ($displayMode ?? 'single'));
    $advertColumns = max(1, intval($columns ?? ($advertDisplayMode === 'single' ? 1 : 3)));
    $advertLimit = intval($limit ?? ($advertDisplayMode === 'single' ? 1 : 0));
    $advertList = collect();
    foreach (hook("ShowAD", ['moduleName' => "AD", 'pageData' => ['identification' => $advertIdentification, 'adtype' => 'advert']]) as $advertGroup) {
        foreach ($advertGroup as $advertItem) {
            $advertList->push($advertItem);
        }
    }
    if ($advertLimit > 0) {
        $advertList = $advertList->take($advertLimit);
    }
@endphp

@once
    <style>
        .mx-theme-advert-wrap { padding: 24px 0; }
        .mx-theme-advert-grid { display: grid; gap: 20px; }
        .mx-theme-advert-item { padding: 24px; background: #fff; border-radius: 18px; box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08); overflow: hidden; }
        .mx-theme-advert-item.show-alert { border-left: 4px solid #f59e0b; background: #fff8eb; }
        .mx-theme-advert-item.show-custom { border-left: 4px solid #3b82f6; }
        .mx-theme-advert-title { margin: 0 0 10px; line-height: 1.4; }
        .mx-theme-advert-content { color: #475569; line-height: 1.9; }
        .mx-theme-advert-media { margin-bottom: 14px; }
        .mx-theme-advert-media img,
        .mx-theme-advert-media video { width: 100%; border-radius: 14px; display: block; }
        .mx-theme-advert-cta { display: inline-flex; align-items: center; gap: 8px; margin-top: 14px; color: #2563eb; font-weight: 600; }
    </style>
@endonce

@if($advertList->count())
    <div class="mx-theme-advert-wrap">
        <div class="mx-theme-advert {{ $advertWrapperClass }}" data-identification="{{ $advertIdentification }}">
            <div class="mx-theme-advert-grid" style="grid-template-columns: repeat({{ $advertColumns }}, minmax(0, 1fr));">
                @foreach($advertList as $advert)
                    @php($advertConfig = is_array($advert->json ?? null) ? $advert->json : (json_decode($advert->json ?? '[]', true) ?: []))
                    @php($advertType = trim((string) ($advert->type ?? 'text')))
                    @php($advertUrl = trim((string) ($advertConfig['ad_url'] ?? '')))
                    @php($advertTitle = trim((string) ($advertConfig['ad_name'] ?? $advert->remark ?? '广告内容')))
                    @php($advertTitleColor = trim((string) ($advertConfig['ad_name_color'] ?? '')))
                    @php($advertImage = trim((string) ($advertConfig['ad_img'] ?? '')))
                    @php($advertVideo = trim((string) ($advertConfig['ad_video'] ?? '')))
                    @php($advertContent = trim((string) ($advertConfig['ad_content'] ?? '')))
                    <div class="{{ $advertItemClass }} type-{{ $advertType }} show-{{ $advert->showtype }}"
                         data-show-type="advert"
                         data-show-id="{{ $advert->id }}"
                         data-show-slot="{{ $advertIdentification }}"
                         data-show-url="{{ url('ad/showAdvert') }}">
                        @if($advertType === 'img' && $advertImage !== '')
                            <div class="mx-theme-advert-media">
                                <a href="{{ $advertUrl ?: 'javascript:;' }}"
                                   class="mx-theme-track-link"
                                   @if($advertUrl !== '') target="{{ $advertTarget }}" @endif
                                   data-track-type="advert"
                                   data-track-id="{{ $advert->id }}"
                                   data-track-slot="{{ $advertIdentification }}"
                                   data-track-url="{{ url('ad/clickAdvert') }}">
                                    <img src="{{ GetUrlByPath($advertImage) }}" alt="{{ $advertTitle }}" class="img-responsive">
                                </a>
                            </div>
                            @if($advertTitle !== '')
                                <h3 class="mx-theme-advert-title" @if($advertTitleColor !== '') style="color: {{ $advertTitleColor }};" @endif>{{ $advertTitle }}</h3>
                            @endif
                        @elseif($advertType === 'video' && $advertVideo !== '')
                            <div class="mx-theme-advert-media">
                                <video controls preload="metadata">
                                    <source src="{{ GetUrlByPath($advertVideo) }}">
                                </video>
                            </div>
                            @if($advertTitle !== '')
                                <h3 class="mx-theme-advert-title" @if($advertTitleColor !== '') style="color: {{ $advertTitleColor }};" @endif>{{ $advertTitle }}</h3>
                            @endif
                        @else
                            @if($advertTitle !== '')
                                <h3 class="mx-theme-advert-title" @if($advertTitleColor !== '') style="color: {{ $advertTitleColor }};" @endif>{{ $advertTitle }}</h3>
                            @endif
                        @endif

                        @if($advertType === 'richtext')
                            <div class="mx-theme-advert-content">{!! $advertContent !!}</div>
                        @elseif($advertType !== 'img' && $advertContent !== '')
                            <div class="mx-theme-advert-content">{!! $advertContent !!}</div>
                        @endif

                        @if($advertUrl !== '')
                            <a href="{{ $advertUrl }}"
                               target="{{ $advertTarget }}"
                               class="mx-theme-track-link mx-theme-advert-cta"
                               data-track-type="advert"
                               data-track-id="{{ $advert->id }}"
                               data-track-slot="{{ $advertIdentification }}"
                               data-track-url="{{ url('ad/clickAdvert') }}">
                                <span>{{ $advertDisplayMode === 'single' ? '立即查看' : '查看详情' }}</span>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
