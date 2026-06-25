@php
    $record = frontendRecordData($detailRecord ?? $data ?? []);
    $detailFields = $model['frontend_schema']['detail'] ?? [];
    $title = $record['title'] ?? ($record['name'] ?? ($model['name'] ?? '详情内容'));
@endphp

<div id="content">
    <div class="container">
        <div class="mx-detail-shell">
            @include('themes.default.public.detailHero', [
                'model' => $model,
                'data' => $data,
                'detailRecord' => $record,
                'detailTitle' => $title,
            ])

            <article class="mx-detail-main">
                @include('themes.default.public.detailContent', [
                    'detailRecord' => $record,
                    'detailFields' => $detailFields,
                    'model' => $model,
                ])

                @include("themes.default.public.articleNavigation")
            </article>
        </div>
    </div>
</div>
