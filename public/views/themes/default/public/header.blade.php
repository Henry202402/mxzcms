@if($model['home_config']['detail_page_title'])
    <div class="header-back header-back-small" @if($model['home_config']['detail_page_show_type']=="color" && $model['home_config']['detail_page_bg_color'])
style='background-color:{{$model['home_config']['detail_page_bg_color']}} !important'
@elseif($model['home_config']['detail_page_show_type']=="img" && $model['home_config']['detail_page_bg_img'])
style='background-image: url({{GetUrlByPath($model['home_config']['detail_page_bg_img'])}});background-repeat: no-repeat'
@endif >
        <div class="header-back-container">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="page-info page-info-simple">
                            <h1 class="page-title">{!! $model['home_config']['detail_page_title'] !!}</h1>
                            <h2 class="page-description">{!! $model['home_config']['detail_page_describe'] !!}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
