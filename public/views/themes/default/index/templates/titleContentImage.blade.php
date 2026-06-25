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

        <div class="mx-home-feature-list">
            @forelse(getListByModel($data,$data->home_page_num) as $d)
                @php($item = toArray($d))
                <a href="{{url('detail/'.$data->access_identification.'/'.$item['id'])}}" class="mx-home-feature text-none-decoration">
                    <div class="mx-home-feature__thumb">
                        @if(!empty($item['cover']))
                            <img src="{{ GetUrlByPath($item['cover'])}}" class="image" alt="{{ $item['title'] ?? 'box image' }}">
                        @else
                            <div class="mx-home-card__thumb-placeholder">{{ mb_substr($item['title'] ?? $data->name, 0, 1) }}</div>
                        @endif
                    </div>
                    <div class="mx-home-feature__body">
                        <h3 class="mx-home-feature__title">{{ $item['title'] ?? '未命名内容' }}</h3>
                        <p class="mx-home-feature__desc">{{ \Illuminate\Support\Str::limit(trim(strip_tags($item['content'] ?? '')), 140) ?: '点击查看完整内容。' }}</p>
                    </div>
                </a>
            @empty
                <div class="mx-empty">当前模型还没有可展示的图文数据，请先在后台新增内容。</div>
            @endforelse
        </div>
    </div>
</div>
