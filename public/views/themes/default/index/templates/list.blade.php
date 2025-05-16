@php $home_config = json_decode($data->home_config,true);  @endphp
<div @if($home_config['show_home_type']=="color" && $home_config['home_page_bg_color'])
         style='background-color:{{$home_config['home_page_bg_color']}} !important'
     @elseif($home_config['show_home_type']=="img" && $home_config['home_page_bg_img'])
         style='background-image: url({{GetUrlByPath($home_config['home_page_bg_img'])}});background-repeat: no-repeat'
    @endif >
    <div class="container ">
        <div class="row">
            <div class="col-md-12">
                <!-- Promo Title -->
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
                <!-- End of Promo Title -->

                <div class="row">
                    <div class="col-md-12">
                        <!-- Category List -->
                        <div class="category-list">
                            <style>
                                .category-list-content-item{
                                    width: 33%;
                                    float: left;
                                }
                            </style>
                            <ul class="category-list-content">

                                @foreach(getListByModel($data,$data->home_page_num) as $d)
                                    <li class="category-list-content-item">
                                        <a href="{{url("detail/".$data->access_identification."/".$d->id)}}" class="category-list-content-item-text">{{$d->title}}</a>
                                    </li>
                                @endforeach

                            </ul>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="helper center">
                            <a href="{{url("list/".$data->access_identification)}}" class="faq-grid-show-more">查看更多 <i class="fa fa-angle-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
