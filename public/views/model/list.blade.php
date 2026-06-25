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
                            <div class="col-md-12" style="margin-bottom: 20px;">
                                <div class="panel panel-default" style="margin-bottom: 0;">
                                    <div class="panel-body">
                                        <div class="row">
                                            @if(!empty($item['cover']))
                                                <div class="col-md-3">
                                                    <a href="{{url("detail/{$param['model']}/{$item['id']}")}}">
                                                        <img src="{{GetUrlByPath($item['cover'])}}" alt="{{$item['title'] ?? $item['name'] ?? 'cover'}}" style="width: 100%; max-height: 180px; object-fit: cover;">
                                                    </a>
                                                </div>
                                                <div class="col-md-9">
                                            @else
                                                <div class="col-md-12">
                                            @endif
                                                    <h3 style="margin-top: 0;">
                                                        <a href="{{url("detail/{$param['model']}/{$item['id']}")}}">{{$item['title'] ?? $item['name'] ?? ('#'.$item['id'])}}</a>
                                                    </h3>
                                                    @if(!empty($item['created_at']))
                                                        <p class="text-muted" style="margin-bottom: 10px;">{{$item['created_at']}}</p>
                                                    @endif
                                                    @if(!empty($item['content']))
                                                        <div style="color: #666; line-height: 1.8; max-height: 88px; overflow: hidden;">{!! strip_tags($item['content']) !!}</div>
                                                    @endif
                                                </div>
                                        </div>
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
