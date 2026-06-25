@php $home_config = json_decode($data->home_config,true);  @endphp
<div @if($home_config['show_home_type']=="color" && $home_config['home_page_bg_color'])
         style='background-color:{{$home_config['home_page_bg_color']}} !important'
     @elseif($home_config['show_home_type']=="img" && $home_config['home_page_bg_img'])
         style='background-image: url({{GetUrlByPath($home_config['home_page_bg_img'])}});background-repeat: no-repeat'
    @endif >
    <div class="container ">
        @include('themes.default.public.homeSectionHeader', [
            'sectionData' => $data,
            'sectionConfig' => $home_config,
            'sectionMoreUrl' => url('list/' . $data->access_identification),
        ])

        <div class="mx-home-link-grid">
            @forelse(getListByModel($data,$data->home_page_num) as $d)
                @php($itemTitle = $d->title ?? ($d->name ?? ('内容 #' . $d->id)))
                <a href="{{url('detail/'.$data->access_identification.'/'.$d->id)}}" class="mx-home-link-card">
                    <span class="mx-home-link-card__index">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    <span class="mx-home-link-card__title">{{ $itemTitle }}</span>
                    <i class="fa fa-angle-right"></i>
                </a>
            @empty
                <div class="mx-empty" style="grid-column:1 / -1;">当前模型还没有可展示的列表数据，请先在后台新增内容。</div>
            @endforelse
        </div>
    </div>
</div>
