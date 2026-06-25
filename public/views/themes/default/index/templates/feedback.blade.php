@php $home_config = json_decode($data->home_config,true); @endphp
<div @if($home_config['show_home_type']=="color" && $home_config['home_page_bg_color'])
         style='background-color:{{$home_config['home_page_bg_color']}} !important'
     @elseif($home_config['show_home_type']=="img" && $home_config['home_page_bg_img'])
         style='background-image: url({{GetUrlByPath($home_config['home_page_bg_img'])}});background-repeat: no-repeat'
     @endif>
    <div class="container">
        @include('themes.default.public.homeSectionHeader', [
            'sectionData' => $data,
            'sectionConfig' => $home_config,
            'sectionMoreUrl' => url('list/' . $data->access_identification),
            'sectionMoreText' => '立即留言',
        ])

        <div class="mx-home-grid">
            @forelse(getListByModel($data, $data->home_page_num) as $d)
                <article class="mx-home-card mx-home-card--feedback">
                    <div class="mx-home-card__body">
                        <div class="mx-home-feedback-meta">
                            <strong>{{ $d->full_name ?: '匿名访客' }}</strong>
                            <span>{{ $d->company ?: '访客留言' }}</span>
                        </div>
                        <h3 class="mx-home-card__title">{{ $d->title ?? '一条新的留言' }}</h3>
                        <p class="mx-home-card__desc">{{ \Illuminate\Support\Str::limit(trim((string) ($d->content ?? '')), 120) }}</p>
                        <div class="mx-home-card__meta">
                            @if(!empty($d->created_at))
                                <span><i class="fa fa-clock-o"></i> {{ $d->created_at }}</span>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <div class="mx-empty" style="grid-column:1 / -1;">当前还没有首页留言展示数据，你可以先从前台或后台新增几条留言。</div>
            @endforelse
        </div>
    </div>
</div>
