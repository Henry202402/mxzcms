@php
    $detailRecord = frontendRecordData($detailRecord ?? $data ?? []);
    $detailFields = $detailFields ?? ($model['frontend_schema']['detail'] ?? []);
    $detailContent = trim((string) ($detailRecord['content'] ?? ''));
    $detailInteractions = $detailInteractions ?? [];
    $downloadFieldGroups = $detailInteractions['download_fields'] ?? [];
@endphp

@if($detailContent !== '')
    <div class="article-content mx-detail-content">
        {!! $detailContent !!}
    </div>
@else
    <div class="mx-detail-fields">
        @foreach($detailFields as $field)
            @php($fieldKey = $field['identification'] ?? '')
            @php($value = $fieldKey !== '' ? ($detailRecord[$fieldKey] ?? null) : null)
            @continue($value === null || $value === '' || in_array($fieldKey, ['title', 'name', 'content'], true) || array_key_exists($fieldKey, $downloadFieldGroups))
            <div class="mx-detail-field">
                <h3 class="mx-detail-field__label">{{ $field['name'] ?? $fieldKey }}</h3>
                <div class="mx-detail-field__value">
                    {!! is_array($value) ? implode(' / ', $value) : nl2br(e((string) $value)) !!}
                </div>
            </div>
        @endforeach
    </div>
@endif

@if($downloadFieldGroups)
    <div class="mx-detail-downloads">
        <h3 class="mx-detail-downloads__title">资料下载</h3>
        <div class="mx-detail-downloads__list">
            @foreach($downloadFieldGroups as $downloadField)
                <div class="mx-detail-download">
                    <div class="mx-detail-download__label">{{ $downloadField['label'] ?? '附件下载' }}</div>
                    <div class="mx-detail-download__items">
                        @foreach(($downloadField['items'] ?? []) as $downloadItem)
                            <a href="{{ $downloadItem['url'] }}" class="mx-detail-download__item">
                                <i class="fa fa-download"></i>
                                <span>{{ $downloadItem['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

@include('themes.default.public.themeAdvert', ['identification' => 'detail_inline', 'displayMode' => 'multiple', 'columns' => 2, 'limit' => 2])
@include('themes.default.public.themeAdvert', ['identification' => 'detail_bottom', 'displayMode' => 'single', 'columns' => 1, 'limit' => 1])
