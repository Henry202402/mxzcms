@php
    $detailRecord = frontendRecordData($detailRecord ?? $data ?? []);
    $detailTitle = $detailTitle ?? ($detailRecord['title'] ?? ($detailRecord['name'] ?? ($model['name'] ?? '详情内容')));
    $detailSummary = $detailSummary ?? '';
    if ($detailSummary === '') {
        $detailSummary = trim(strip_tags((string) ($detailRecord['description'] ?? $detailRecord['content'] ?? '')));
        $detailSummary = \Illuminate\Support\Str::limit($detailSummary, 180);
    }
    $detailCover = trim((string) ($detailRecord['cover'] ?? ''));
    $detailInteractions = $detailInteractions ?? [];
    $detailDownloads = $detailInteractions['downloads'] ?? [];
    $detailMetaItems = $detailMetaItems ?? [];
    if (!$detailMetaItems) {
        if (!empty($detailRecord['created_at'])) {
            $detailMetaItems[] = ['icon' => 'fa fa-clock-o', 'text' => $detailRecord['created_at']];
        }
        if (!empty($detailRecord['updated_at'])) {
            $detailMetaItems[] = ['icon' => 'fa fa-refresh', 'text' => '更新于 ' . $detailRecord['updated_at']];
        }
        if (array_key_exists('access_count', $detailRecord)) {
            $detailMetaItems[] = ['icon' => 'fa fa-eye', 'text' => '浏览 ' . $detailRecord['access_count']];
        }
        if (array_key_exists('good_count', $detailRecord)) {
            $detailMetaItems[] = ['icon' => 'fa fa-thumbs-up', 'text' => '点赞 ' . (int) ($detailRecord['good_count'] ?? 0)];
        }
        if ($detailDownloads) {
            $detailMetaItems[] = ['icon' => 'fa fa-download', 'text' => '下载 ' . (int) ($detailRecord['download_count'] ?? 0)];
        }
    }
@endphp

<div class="mx-detail-hero @if($detailCover) mx-detail-hero--with-cover @endif">
    <div class="mx-detail-hero__main">
        <div class="mx-detail-hero__eyebrow">Detail Experience</div>
        <h1 class="mx-detail-hero__title">{{ $detailTitle }}</h1>
        @if($detailSummary)
            <p class="mx-detail-hero__description">{{ $detailSummary }}</p>
        @endif
        @if($detailMetaItems)
            <div class="mx-detail-hero__meta">
                @foreach($detailMetaItems as $metaItem)
                    @continue(empty($metaItem['text']))
                    <span class="mx-detail-meta-pill">
                        @if(!empty($metaItem['icon']))
                            <i class="{{ $metaItem['icon'] }}"></i>
                        @endif
                        <em>{{ $metaItem['text'] }}</em>
                    </span>
                @endforeach
            </div>
        @endif
        @if(!empty($detailInteractions['can_like']) || $detailDownloads)
            <div class="mx-detail-hero__actions">
                @if(!empty($detailInteractions['can_like']))
                    <button type="button"
                            class="mx-detail-action-btn js-detail-like"
                            data-url="{{ $detailInteractions['like_url'] }}"
                            data-initial-text="点赞支持"
                            data-loading-text="提交中">
                        <i class="fa fa-thumbs-up"></i>
                        <span>点赞支持</span>
                        <strong data-like-count>{{ (int) ($detailRecord['good_count'] ?? 0) }}</strong>
                    </button>
                @endif
                @foreach(array_slice($detailDownloads, 0, 2) as $downloadItem)
                    <a href="{{ $downloadItem['url'] }}" class="mx-detail-action-btn mx-detail-action-btn--ghost">
                        <i class="fa fa-download"></i>
                        <span>{{ $downloadItem['label'] }}</span>
                    </a>
                @endforeach
            </div>
            @if(count($detailDownloads) > 2)
                <div class="mx-detail-hero__tips">更多附件可在正文下方的“资料下载”区域查看。</div>
            @endif
        @endif
    </div>

    @if($detailCover)
        <div class="mx-detail-hero__media">
            <img src="{{ GetUrlByPath($detailCover) }}" alt="{{ $detailTitle }}">
        </div>
    @endif
</div>

@include('themes.default.public.themeBanner', ['identification' => 'detail_top', 'displayMode' => 'single', 'class' => 'header-back header-back-default'])

@if(!empty($detailInteractions['can_like']))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var likeButton = document.querySelector('.js-detail-like');
            if (!likeButton) {
                return;
            }

            likeButton.addEventListener('click', function () {
                if (likeButton.disabled) {
                    return;
                }

                var url = likeButton.getAttribute('data-url');
                var tokenNode = document.getElementById('token');
                var likeCountNode = likeButton.querySelector('[data-like-count]');
                var originalText = likeButton.getAttribute('data-initial-text') || '点赞支持';
                var loadingText = likeButton.getAttribute('data-loading-text') || '提交中';
                likeButton.disabled = true;
                likeButton.classList.add('is-loading');
                likeButton.querySelector('span').textContent = loadingText;

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': tokenNode ? tokenNode.getAttribute('content') : '',
                        'Accept': 'application/json'
                    }
                }).then(function (response) {
                    return response.json();
                }).then(function (result) {
                    if (result && result.status === 200 && result.data && likeCountNode) {
                        likeCountNode.textContent = result.data.good_count || 0;
                    } else {
                        throw new Error((result && result.msg) || '点赞失败');
                    }
                }).catch(function (error) {
                    if (window.layer && typeof window.layer.msg === 'function') {
                        window.layer.msg(error.message || '点赞失败');
                    }
                }).finally(function () {
                    likeButton.disabled = false;
                    likeButton.classList.remove('is-loading');
                    likeButton.querySelector('span').textContent = originalText;
                });
            });
        });
    </script>
@endif
