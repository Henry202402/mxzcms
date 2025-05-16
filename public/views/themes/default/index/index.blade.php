@include("themes.default.public.head")
@include("themes.default.public.preloader")

<div class="page js-page ">
    @include("themes.default.public.topMenu",['model'=>['home_page_title'=>true]])

    @foreach(hook("ShowAD",['moduleName'=>"AD",'pageData'=>['identification'=>'homepage','adtype'=>'banner']]) as $item)
        @foreach($item as $item2)
            <div class="header-back header-back-default header-back-full-page js-full-page"
             style="background-image: url({{GetUrlByPath($item2->image)}});background-repeat: no-repeat;background-size: cover;background-position: center;">
                <div class="header-back-container">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="page-info helper center">
                                    @foreach(json_decode($item2->other_param,true) as $key=>$item3)
                                         @if($key===0)
                                                <h1 class="page-title">{{$item3['name']}}</h1>
                                         @else
                                                <h2 class="page-description">{{$item3['name']}}</h2>
                                         @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endforeach

    <div class="">
        @foreach($models as $index=>$model)
            @if($model->home_config)
                @include("themes.default.index.templates.".json_decode($model->home_config,true)['list_template'],[
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
