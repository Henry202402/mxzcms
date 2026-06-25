@include("themes.default.public.head")
@include("themes.default.public.preloader")

<div class="page js-page ">
    @include("themes.default.public.topMenu",['model'=>['home_page_title'=>true]])

    @include('themes.default.public.themeBanner', ['identification' => 'homepage', 'displayMode' => 'single'])
    @include('themes.default.public.themeAdvert', ['identification' => 'homepage_notice', 'displayMode' => 'single', 'columns' => 1])

    <div class="mx-home-sections">
        @foreach($models as $index=>$model)
            @php($homeConfig = json_decode($model->home_config, true) ?: [])
            @if($homeConfig)
                <section class="mx-home-section">
                    @includeIf("themes.default.index.templates." . ($homeConfig['list_template'] ?? 'list'), [
                        "data" => $model
                    ])
                </section>
            @endif
        @endforeach
    </div>
    @include('themes.default.public.themeBanner', ['identification' => 'homepage_grid', 'displayMode' => 'multiple', 'limit' => 3])
    @include('themes.default.public.themeAdvert', ['identification' => 'homepage_cards', 'displayMode' => 'multiple', 'columns' => 3, 'limit' => 3])
    <footer>
            @include("themes.default.public.bottomMenu")
        <div class="footer">
            @include("themes.default.public.footerMenu")
        </div>
    </footer>
</div>
@include("themes.default.public.js")
</body>
</html>
