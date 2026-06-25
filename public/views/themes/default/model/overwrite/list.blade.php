<div id="content">
    <div class="container">
        @php
            $pageNum = data_get($model, 'home_config.page_num', $model['page_num'] ?? 0);
            $pagePosition = data_get($model, 'home_config.list_page_template', $model['list_page_template'] ?? 'center');
        @endphp

        <div class="mx-list-shell">
            @include('themes.default.public.listHero', ['model' => $model, 'listContext' => $listContext ?? []])

            <div class="mx-list-panel">
                <div class="mx-note-list">
                    @forelse($data as $l)
                        <article class="mx-note-card">
                            <h3 class="mx-note-card__title">
                                <a href="{{url("detail/{$param['model']}/{$l['id']}")}}">{{$l['title'] ?? '未命名内容'}}</a>
                            </h3>
                            <p class="mx-note-card__desc">
                                {{ \Illuminate\Support\Str::limit(trim(strip_tags($l['content'] ?? '')), 160) ?: '当前内容暂未填写摘要，点击查看完整详情。' }}
                            </p>
                            <div class="mx-note-card__meta">
                                @if(!empty($l['created_at']))
                                    <span><i class="fa fa-clock-o"></i> {{$l['created_at']}}</span>
                                @endif
                                @if(!empty($l['author']))
                                    <span><i class="fa fa-user-o"></i> {{$l['author']}}</span>
                                @endif
                            </div>
                        </article>
                    @empty
                        <div class="mx-empty mx-list-empty">当前模型还没有可展示的数据，请先在后台内容管理中新增内容。</div>
                    @endforelse
                </div>
            </div>

            @if ($pageNum > 0 && $data_source=="local")
                {{$data->appends($_GET)->links('themes.default.public.pagination',['data'=>['side_num'=>2,'page_position'=>$pagePosition]])}}
            @endif
        </div>
    </div>
</div>
