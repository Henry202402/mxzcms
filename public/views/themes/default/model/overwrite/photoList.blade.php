<div id="content">
    <div class="container">
        @php
            $pageNum = data_get($model, 'home_config.page_num', $model['page_num'] ?? 0);
            $pagePosition = data_get($model, 'home_config.list_page_template', $model['list_page_template'] ?? 'center');
        @endphp

        <div class="mx-list-shell">
            @include('themes.default.public.listHero', ['model' => $model, 'listContext' => $listContext ?? []])

            <div class="mx-list-panel">
                <div class="mx-list-grid">
                    @forelse($data as $item)
                        <a href="{{ url('detail/' . $param['model'] . '/' . $item['id']) }}" class="mx-card text-none-decoration">
                            <div class="mx-card__thumb">
                                <img src="{{ GetUrlByPath($item['cover'] ?? '') }}" alt="{{ $item['title'] ?? 'photo' }}">
                            </div>
                            <h3 class="mx-card__title">{{ $item['title'] ?? '未命名相册' }}</h3>
                            <p class="mx-card__desc">{{ \Illuminate\Support\Str::limit(strip_tags($item['content'] ?? ''), 88) }}</p>
                            <div class="mx-card__meta">
                                @if(!empty($item['created_at']))
                                    <span><i class="fa fa-clock-o"></i> {{ $item['created_at'] }}</span>
                                @endif
                            </div>
                        </a>
                    @empty
                        <div class="mx-empty" style="grid-column:1 / -1;">当前还没有相册内容，请先在后台新增图片数据。</div>
                    @endforelse
                </div>
            </div>

            @if ($pageNum > 0 && is_object($data) && method_exists($data, 'links'))
                {{ $data->appends($_GET)->links('themes.default.public.pagination',['data'=>['side_num'=>2,'page_position'=>$pagePosition]]) }}
            @endif
        </div>
    </div>
</div>
