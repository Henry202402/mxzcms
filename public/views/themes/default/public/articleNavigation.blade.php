@php
    $navigationRecord = frontendRecordData($detailRecord ?? $data ?? []);
    $prevItem = $navigationRecord['prev_item'] ?? null;
    $nextItem = $navigationRecord['next_item'] ?? null;
    $prevId = $navigationRecord['prev_id'] ?? null;
    $nextId = $navigationRecord['next_id'] ?? ($navigationRecord['last_id'] ?? null);
    $prevTitle = is_array($prevItem) ? ($prevItem['title'] ?? $prevItem['name'] ?? null) : null;
    $nextTitle = is_array($nextItem) ? ($nextItem['title'] ?? $nextItem['name'] ?? null) : null;
@endphp

<div class="article-navigation">
    @if($prevId)
        <a href="{{url("detail/{$param['model']}/{$prevId}")}}" class="article-navigation-prev">
            <span class="article-navigation__label">{{ themeTrans('article.previous') }}</span>
            <strong class="article-navigation__title">{{ $prevTitle ?: 'ID #' . $prevId }}</strong>
        </a>
    @else
        <span class="article-navigation-prev is-disabled">
            <span class="article-navigation__label">{{ themeTrans('article.previous') }}</span>
            <strong class="article-navigation__title">{{ themeTrans('common.none') }}</strong>
        </span>
    @endif

    @if($nextId)
        <a href="{{url("detail/{$param['model']}/{$nextId}")}}" class="article-navigation-next">
            <span class="article-navigation__label">{{ themeTrans('article.next') }}</span>
            <strong class="article-navigation__title">{{ $nextTitle ?: 'ID #' . $nextId }}</strong>
        </a>
    @else
        <span class="article-navigation-next is-disabled">
            <span class="article-navigation__label">{{ themeTrans('article.next') }}</span>
            <strong class="article-navigation__title">{{ themeTrans('common.none') }}</strong>
        </span>
    @endif
</div>
