@php $home_config = json_decode($data->home_config,true);  @endphp
<div @if($home_config['show_home_type']=="color" && $home_config['home_page_bg_color'])
         style='background-color:{{$home_config['home_page_bg_color']}} !important'
     @elseif($home_config['show_home_type']=="img" && $home_config['home_page_bg_img'])
         style='background-image: url({{GetUrlByPath($home_config['home_page_bg_img'])}});background-repeat: no-repeat'
    @endif >
    <div class="container">
        <div class="row">
            <div class="promo-title-wrapper ">
                <h3 class="promo-title" style="
                    @if($home_config['home_page_title_size']) font-size:{{$home_config['home_page_title_size']}} @endif
                    @if($home_config['home_page_title_color']) color:{{$home_config['home_page_title_color']}} @endif
                    ">
                    {{$home_config['home_page_title']?:$data->name}}
                </h3>
                <p class="promo-description" style="
                    @if($home_config['home_page_describe_size']) font-size:{{$home_config['home_page_title_size']}} @endif
                    @if($home_config['home_page_describe_color']) color:{{$home_config['home_page_title_color']}} @endif
                    ">
                    {{$home_config['home_page_describe']}}
                </p>
            </div>
            <div class="col-md-12">
                <div class="changelog-wrapper js-changelog" style="width: 960px ">
                    <div class="changelog-items">
                        @foreach(getListByModel($data,$data->home_page_num) as $d)
                            <div id="{{toArray($d)["title"]}}" class="changelog-item js-changelog-item">
                                <header class="changelog-header">
                                    <h3 class="changelog-version">
                                        <a href="#{{toArray($d)["title"]}}">{{toArray($d)["title"]}}</a>
                                        <span class="pull-right">{{toArray($d)["date"]}}</span>
                                    </h3>
                                </header>
                                <div class="changelog-update-descriptions changelog-update-description " style="padding-left: 0px;">
                                    {!! toArray($d)["content"] !!}
                                </div>
                                <div class="changelog-link"></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
