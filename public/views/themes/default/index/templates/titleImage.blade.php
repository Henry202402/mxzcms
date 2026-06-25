@php $home_config = json_decode($data->home_config,true); @endphp
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

        <div class="mx-home-grid">
            @forelse(getListByModel($data,$data->home_page_num) as $d)
                @php($item = toArray($d))
                <a href="{{url('detail/'.$data->access_identification.'/'.$item['id'])}}" class="mx-home-card text-none-decoration">
                    <div class="mx-home-card__thumb">
                        @if(!empty($item['cover']))
                            <img src="{{ GetUrlByPath($item['cover']) }}" class="brand-item-image" alt="{{ $item['title'] ?? 'image' }}">
                        @else
                            <div class="mx-home-card__thumb-placeholder">{{ mb_substr($item['title'] ?? $data->name, 0, 1) }}</div>
                        @endif
                    </div>
                    <div class="mx-home-card__body">
                        <h3 class="mx-home-card__title">{{ $item['title'] ?? $data->name }}</h3>
                        <p class="mx-home-card__desc">{{ \Illuminate\Support\Str::limit(trim(strip_tags($item['content'] ?? '')), 88) ?: '点击查看完整内容。' }}</p>
                    </div>
                </a>
            @empty
                <div class="mx-empty" style="grid-column:1 / -1;">当前模型还没有可展示的图文数据，请先在后台新增内容。</div>
            @endforelse
        </div>
    </div>
</div>
