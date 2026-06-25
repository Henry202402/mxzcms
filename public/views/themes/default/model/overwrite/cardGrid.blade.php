<div id="content">
    <div class="container">
        @php
            $pageNum = data_get($model, 'home_config.page_num', $model['page_num'] ?? 0);
            $pagePosition = data_get($model, 'home_config.list_page_template', $model['list_page_template'] ?? 'center');
        @endphp

        <div class="mx-list-shell">
            @include('themes.default.public.listHero', ['model' => $model, 'listContext' => $listContext ?? []])

            <div class="mx-list-panel">
                <div class="mx-card-grid">
                    @forelse($data as $l)
                        @php($item = frontendRecordData($l))
                        @php($itemTitle = $item['title'] ?? ($item['name'] ?? ('内容 #' . ($item['id'] ?? ''))))
                        <article class="mx-card">
                            @if(!empty($item['cover']))
                                <a href="{{url("detail/{$param['model']}/{$item['id']}")}}" class="mx-card__thumb">
                                    <img src="{{GetUrlByPath($item['cover'])}}" alt="{{$itemTitle}}">
                                </a>
                            @endif
                            <h3 class="mx-card__title">
                                <a href="{{url("detail/{$param['model']}/{$item['id']}")}}">{{$itemTitle}}</a>
                            </h3>
                            <p class="mx-card__desc">{{ \Illuminate\Support\Str::limit(trim(strip_tags($item['description'] ?? $item['content'] ?? '')), 110) ?: '点击查看完整内容详情。' }}</p>
                            <div class="mx-card__meta">
                                @if(!empty($item['created_at']))
                                    <span><i class="fa fa-clock-o"></i> {{$item['created_at']}}</span>
                                @endif
                                @if(!empty($item['author']))
                                    <span><i class="fa fa-user-o"></i> {{$item['author']}}</span>
                                @endif
                                @if(!empty($item['cate_name']))
                                    <span><i class="fa fa-folder-o"></i> {{$item['cate_name']}}</span>
                                @endif
                            </div>
                        </article>
                    @empty
                        <div class="mx-empty" style="grid-column:1 / -1;">当前模型还没有可展示的数据，请先在后台内容管理中新增内容。</div>
                    @endforelse
                </div>
            </div>

            @if ($pageNum > 0 && $data_source=="local")
                {{$data->appends($_GET)->links('themes.default.public.pagination',['data'=>['side_num'=>2,'page_position'=>$pagePosition]])}}
            @endif
        </div>
    </div>
</div>
