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
        ])

        <div class="mx-home-timeline">
            @foreach(getListByModel($data,$data->home_page_num) as $d)
                @php($item = toArray($d))
                <article id="{{ $item['title'] }}" class="mx-home-timeline__item">
                    <div class="mx-home-timeline__dot"></div>
                    <div class="mx-home-timeline__card">
                        <header class="mx-home-timeline__header">
                            <h3 class="mx-home-timeline__title">{{ $item['title'] }}</h3>
                            @if(!empty($item['date']))
                                <span class="mx-home-timeline__date">{{ $item['date'] }}</span>
                            @endif
                        </header>
                        <div class="mx-home-timeline__content">
                            {!! $item['content'] !!}
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</div>
