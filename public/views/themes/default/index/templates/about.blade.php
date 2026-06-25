@php $home_config = json_decode($data->home_config,true);  @endphp
<div @if($home_config['show_home_type']=="color" && $home_config['home_page_bg_color'])
style='background-color:{{$home_config['home_page_bg_color']}} !important'
@elseif($home_config['show_home_type']=="img" && $home_config['home_page_bg_img'])
style='background-image: url({{GetUrlByPath($home_config['home_page_bg_img'])}});background-repeat: no-repeat'
@endif >
    <div class="container">
        @include('themes.default.public.homeSectionHeader', [
            'sectionData' => $data,
            'sectionConfig' => $home_config,
            'sectionMoreUrl' => url('list/' . $data->access_identification),
        ])

        <div class="mx-home-richtext">
            @foreach(getListByModel($data,$data->home_page_num) as $d)
                @foreach(json_decode($data->fields,true) as $field)
                    {!! toArray($d)[$field['identification']] !!}
                @endforeach
            @endforeach
        </div>
    </div>
</div>
