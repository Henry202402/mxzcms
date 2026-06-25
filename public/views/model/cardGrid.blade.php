@php
    $items = $data;
@endphp
<div id="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @if(empty($items) || (is_countable($items) && count($items) === 0))
                    <div class="alert alert-info">暂无内容</div>
                @else
                    <div class="row">
                        @foreach($items as $item)
                            @php($record = frontendRecordData($item))
                            @php($itemTitle = $record['title'] ?? ($record['name'] ?? ('#' . ($record['id'] ?? ''))))
                            <div class="col-md-4" style="margin-bottom: 20px;">
                                <div class="panel panel-default" style="height: 100%; margin-bottom: 0;">
                                    @if(!empty($record['cover']))
                                        <a href="{{url("detail/{$param['model']}/{$record['id']}")}}">
                                            <img src="{{GetUrlByPath($record['cover'])}}" alt="{{$itemTitle}}" style="width: 100%; height: 220px; object-fit: cover;">
                                        </a>
                                    @endif
                                    <div class="panel-body">
                                        <h3 style="margin-top: 0;">
                                            <a href="{{url("detail/{$param['model']}/{$record['id']}")}}">{{$itemTitle}}</a>
                                        </h3>
                                        <p style="color: #666; line-height: 1.8; min-height: 76px;">
                                            {{ \Illuminate\Support\Str::limit(trim(strip_tags($record['description'] ?? $record['content'] ?? '')), 100) ?: '点击查看完整内容详情。' }}
                                        </p>
                                        @if(!empty($record['created_at']))
                                            <p class="text-muted" style="margin-bottom: 0;">{{$record['created_at']}}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if(method_exists($data, 'links') && ($model['home_config']['page_num'] ?? 0) > 0)
                        <div class="text-center">
                            {{$data->appends($_GET)->links()}}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
