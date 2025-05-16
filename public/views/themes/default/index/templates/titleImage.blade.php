@php $home_config = json_decode($data->home_config,true);  @endphp
<div @if($home_config['show_home_type']=="color" && $home_config['home_page_bg_color'])
         style='background-color:{{$home_config['home_page_bg_color']}} !important'
     @elseif($home_config['show_home_type']=="img" && $home_config['home_page_bg_img'])
         style='background-image: url({{GetUrlByPath($home_config['home_page_bg_img'])}});background-repeat: no-repeat'
    @endif >
    <div class="container">
        <div class="row ">
            <div class="col-md-12">

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

                <div class="row">
                    <ul class="brands ">


                        <li class="brand-item">
                            <a href="#" class="brand-item-link">

                                <img src="{{HOME_ASSET}}default/assets/img/demos/brands/1.png" class="brand-item-image" alt="brand logo">

                            </a>
                        </li>

                        <li class="brand-item">
                            <a href="#" class="brand-item-link">

                                <img src="{{HOME_ASSET}}default/assets/img/demos/brands/2.png" class="brand-item-image" alt="brand logo">

                            </a>
                        </li>

                        <li class="brand-item">
                            <a href="#" class="brand-item-link">

                                <img src="{{HOME_ASSET}}default/assets/img/demos/brands/3.png" class="brand-item-image" alt="brand logo">

                            </a>
                        </li>

                        <li class="brand-item">
                            <a href="#" class="brand-item-link">

                                <img src="{{HOME_ASSET}}default/assets/img/demos/brands/4.png" class="brand-item-image" alt="brand logo">

                            </a>
                        </li>

                        <li class="brand-item">
                            <a href="#" class="brand-item-link">

                                <img src="{{HOME_ASSET}}default/assets/img/demos/brands/5.png" class="brand-item-image" alt="brand logo">

                            </a>
                        </li>

                        <li class="brand-item">
                            <a href="#" class="brand-item-link">

                                <img src="{{HOME_ASSET}}default/assets/img/demos/brands/6.png" class="brand-item-image" alt="brand logo">

                            </a>
                        </li>

                        <li class="brand-item">
                            <a href="#" class="brand-item-link">

                                <img src="{{HOME_ASSET}}default/assets/img/demos/brands/7.png" class="brand-item-image" alt="brand logo">

                            </a>
                        </li>

                        <li class="brand-item">
                            <a href="#" class="brand-item-link">

                                <img src="{{HOME_ASSET}}default/assets/img/demos/brands/10.png" class="brand-item-image" alt="brand logo">

                            </a>
                        </li>

                        <li class="brand-item">
                            <a href="#" class="brand-item-link">

                                <img src="{{HOME_ASSET}}default/assets/img/demos/brands/12.png" class="brand-item-image" alt="brand logo">

                            </a>
                        </li>

                        <li class="brand-item">
                            <a href="#" class="brand-item-link">

                                <img src="{{HOME_ASSET}}default/assets/img/demos/brands/14.png" class="brand-item-image" alt="brand logo">

                            </a>
                        </li>

                        <li class="brand-item">
                            <a href="#" class="brand-item-link">

                                <img src="{{HOME_ASSET}}default/assets/img/demos/brands/15.png" class="brand-item-image" alt="brand logo">

                            </a>
                        </li>

                        <li class="brand-item">
                            <a href="#" class="brand-item-link">

                                <img src="{{HOME_ASSET}}default/assets/img/demos/brands/16.png" class="brand-item-image" alt="brand logo">

                            </a>
                        </li>

                        <li class="brand-item">
                            <a href="#" class="brand-item-link">

                                <img src="{{HOME_ASSET}}default/assets/img/demos/brands/17.png" class="brand-item-image" alt="brand logo">

                            </a>
                        </li>

                        <li class="brand-item">
                            <a href="#" class="brand-item-link">

                                <img src="{{HOME_ASSET}}default/assets/img/demos/brands/18.png" class="brand-item-image" alt="brand logo">

                            </a>
                        </li>

                        <li class="brand-item">
                            <a href="#" class="brand-item-link">

                                <img src="{{HOME_ASSET}}default/assets/img/demos/brands/9.png" class="brand-item-image" alt="brand logo">

                            </a>
                        </li>

                        <li class="brand-item">
                            <a href="#" class="brand-item-link">

                                <img src="{{HOME_ASSET}}default/assets/img/demos/brands/11.png" class="brand-item-image" alt="brand logo">

                            </a>
                        </li>

                        <li class="brand-item">
                            <a href="#" class="brand-item-link">

                                <img src="{{HOME_ASSET}}default/assets/img/demos/brands/13.png" class="brand-item-image" alt="brand logo">

                            </a>
                        </li>

                        <li class="brand-item">
                            <a href="#" class="brand-item-link">

                                <img src="{{HOME_ASSET}}default/assets/img/demos/brands/8.png" class="brand-item-image" alt="brand logo">

                            </a>
                        </li>

                    </ul>
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
