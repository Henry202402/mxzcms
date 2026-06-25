@php
    $sectionData = $sectionData ?? $data ?? null;
    $sectionConfig = $sectionConfig ?? (json_decode($sectionData->home_config ?? '[]', true) ?: []);
    $sectionTitle = $sectionTitle ?? ($sectionConfig['home_page_title'] ?? ($sectionData->name ?? '内容区块'));
    $sectionDescription = $sectionDescription ?? ($sectionConfig['home_page_describe'] ?? '');
    $sectionMoreUrl = $sectionMoreUrl ?? null;
    $sectionMoreText = $sectionMoreText ?? themeTrans('common.show_more');
@endphp

<div class="mx-section-heading">
    <div class="mx-section-heading__main">
        <span class="mx-section-heading__eyebrow">Featured Section</span>
        <h2 class="mx-section-heading__title"
            @if(!empty($sectionConfig['home_page_title_size']) || !empty($sectionConfig['home_page_title_color']))
                style="
                    @if(!empty($sectionConfig['home_page_title_size'])) font-size:{{ $sectionConfig['home_page_title_size'] }} @endif
                    @if(!empty($sectionConfig['home_page_title_color'])) color:{{ $sectionConfig['home_page_title_color'] }} @endif
                "
            @endif
        >
            {{ $sectionTitle }}
        </h2>
        @if($sectionDescription)
            <p class="mx-section-heading__description"
               @if(!empty($sectionConfig['home_page_describe_size']) || !empty($sectionConfig['home_page_describe_color']))
                   style="
                       @if(!empty($sectionConfig['home_page_describe_size'])) font-size:{{ $sectionConfig['home_page_describe_size'] }} @endif
                       @if(!empty($sectionConfig['home_page_describe_color'])) color:{{ $sectionConfig['home_page_describe_color'] }} @endif
                   "
               @endif
            >
                {{ $sectionDescription }}
            </p>
        @endif
    </div>

    @if($sectionMoreUrl)
        <div class="mx-section-heading__action">
            <a href="{{ $sectionMoreUrl }}" class="mx-section-link">
                <span>{{ $sectionMoreText }}</span>
                <i class="fa fa-angle-right"></i>
            </a>
        </div>
    @endif
</div>
