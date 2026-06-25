<div id="content">
    <div class="container">
        @php($record = frontendRecordData($detailRecord ?? $data ?? []))
        <div class="mx-detail-shell">
            @include('themes.default.public.detailHero', [
                'model' => $model,
                'data' => $data,
                'detailRecord' => $record,
            ])

            <article class="mx-detail-main">
                @include('themes.default.public.detailContent', [
                    'detailRecord' => $record,
                    'detailFields' => $model['frontend_schema']['detail'] ?? [],
                    'model' => $model,
                ])
                @include("themes.default.public.articleNavigation")
            </article>

            <section class="mx-detail-section">
                <h3 class="mx-detail-section__title">{{ themeTrans('article.related') }}</h3>
                <div class="mx-detail-related">
                    @foreach($list as $key => $l)
                        <div class="mx-detail-related__item">
                            <span class="mx-detail-related__index">{{ $key + 1 }}</span>
                            <div class="mx-detail-related__content">
                                <a href="{{url("detail/{$param['model']}/{$l['id']}")}}">{{ $l['title'] ?? ($l['name'] ?? ('内容 #' . $l['id'])) }}</a>
                                @if(!empty($l['created_at']))
                                    <span class="mx-detail-related__meta">{{ $l['created_at'] }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    </div>
</div>
