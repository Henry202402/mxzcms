<div id="content">
    <div class="container">
        <div class="mx-card-list">
            @forelse($data as $item)
                <a href="{{ url('detail/' . $param['model'] . '/' . $item['id']) }}" class="mx-card text-none-decoration">
                    @if(!empty($item['cover']))
                        <div class="mx-card__thumb" style="aspect-ratio: 16 / 6;">
                            <img src="{{ GetUrlByPath($item['cover']) }}" alt="{{ $item['title'] ?? 'carousel' }}">
                        </div>
                    @endif
                    <h3 class="mx-card__title">{{ $item['title'] ?? '轮播内容' }}</h3>
                    <p class="mx-card__desc">{{ \Illuminate\Support\Str::limit(strip_tags($item['content'] ?? ''), 120) }}</p>
                </a>
            @empty
                <div class="mx-empty">当前还没有轮播内容，请先在后台新增数据。</div>
            @endforelse
        </div>
    </div>
</div>
