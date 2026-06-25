@php
    $home_config = json_decode($data->home_config, true);
    $contactFields = json_decode($data->fields, true) ?: [];
    $contactItems = getListByModel($data, $data->home_page_num);
@endphp

<section class="mx-home-contact-section"
    @if(($home_config['show_home_type'] ?? '')=="color" && !empty($home_config['home_page_bg_color']))
        style='background-color:{{$home_config['home_page_bg_color']}} !important'
    @elseif(($home_config['show_home_type'] ?? '')=="img" && !empty($home_config['home_page_bg_img']))
        style='background-image: url({{GetUrlByPath($home_config['home_page_bg_img'])}});background-repeat: no-repeat;background-size: cover;background-position: center'
    @endif
>
    <div class="container">
        <div class="mx-home-contact-shell">
            <div class="mx-home-contact-intro">
                <span class="mx-home-contact-intro__eyebrow">Contact Center</span>
                <h3 class="promo-title" style="
                    @if(!empty($home_config['home_page_title_size'])) font-size:{{$home_config['home_page_title_size']}} @endif
                    @if(!empty($home_config['home_page_title_color'])) color:{{$home_config['home_page_title_color']}} @endif
                ">
                    {{$home_config['home_page_title'] ?: $data->name}}
                </h3>
                <p class="promo-description" style="
                    @if(!empty($home_config['home_page_describe_size'])) font-size:{{$home_config['home_page_describe_size']}} @endif
                    @if(!empty($home_config['home_page_describe_color'])) color:{{$home_config['home_page_describe_color']}} @endif
                ">
                    {{$home_config['home_page_describe']}}
                </p>
            </div>

            <div class="mx-home-contact-panel">
                @foreach($contactItems as $d)
                    @php($row = toArray($d))
                    <div class="mx-home-contact-list">
                        @foreach($contactFields as $field)
                            @php($value = $row[$field['identification']] ?? '')
                            @continue($value === null || $value === '')
                            <div class="mx-home-contact-item">
                                <span class="mx-home-contact-item__label">{{$field['name']}}</span>
                                <div class="mx-home-contact-item__value">{!! $value !!}</div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
</div>
