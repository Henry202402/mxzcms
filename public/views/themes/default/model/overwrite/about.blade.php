<div id="content">
    <div class="container">
        @php($record = frontendRecordData($detailRecord ?? $data ?? []))
        <div class="mx-detail-shell">
            @include('themes.default.public.detailHero', [
                'model' => $model,
                'data' => $data,
                'detailRecord' => $record,
                'detailTitle' => $model['home_config']['detail_page_title'] ?? ($model['home_config']['home_page_title'] ?? ($model['name'] ?? '关于我们')),
                'detailSummary' => $model['home_config']['detail_page_describe'] ?? ($model['home_config']['home_page_describe'] ?? ''),
            ])

            <article class="mx-detail-main">
                <div class="category-info article-content mx-detail-content" id="list-view">
                    {!! $record['content'] ?? '' !!}
                </div>
            </article>
        </div>
    </div>
</div>
