@include("themes.default.public.head")
@include("themes.default.public.preloader")

<div class="page js-page ">
    @include("themes.default.public.topMenu",['model'=>['home_page_title'=>true]])

    @if(cacheGlobalSettingsByKey("home_screen")=="on")
        <div class="header-back header-back-default header-back-full-page js-full-page"
             @if(cacheGlobalSettingsByKey('home_screen_image')) style="background-image: url({{GetLocalFileByPath(cacheGlobalSettingsByKey('home_screen_image'))}})" @endif

        >
            {!! cacheGlobalSettingsByKey('home_screen_code') !!}
        </div>
    @endif

    <div class="">
        @foreach($models as $index=>$model)
            @if($model->list_template)
                @include("themes.default.index.templates.".$model->list_template,[
                    "cssClass"=>$index%2==0?"background-gradient-grey":"",
                    "data"=>$model
                    ])
            @endif
        @endforeach
    </div>
    <footer class="js-footer-is-fixed">
            @include("themes.default.public.bottomMenu")
        <div class="footer">
            @include("themes.default.public.footerMenu")
        </div>
    </footer>
</div>
@include("themes.default.public.js")
</body>
</html>
