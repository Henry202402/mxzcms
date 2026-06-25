<div id="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <article class="panel panel-default">
                    <div class="panel-body">
                        <h2 style="margin-top: 0;">{{$data['title'] ?? $data['name'] ?? ('#'.$data['id'])}}</h2>
                        @if(!empty($data['created_at']))
                            <p class="text-muted">{{$data['created_at']}}</p>
                        @endif
                        @if(!empty($data['cover']))
                            <p><img src="{{GetUrlByPath($data['cover'])}}" alt="{{$data['title'] ?? $data['name'] ?? 'cover'}}" style="max-width: 100%;"></p>
                        @endif
                        <div style="line-height: 1.9; word-break: break-word;">
                            {!! $data['content'] ?? ($data['describe'] ?? '') !!}
                        </div>
                    </div>
                </article>
                @if(!empty($list) && count($list))
                    <div class="panel panel-default">
                        <div class="panel-heading">相关内容</div>
                        <div class="list-group" style="margin-bottom: 0;">
                            @foreach($list as $item)
                                <a class="list-group-item" href="{{url("detail/{$param['model']}/{$item['id']}")}}">{{$item['title'] ?? $item['name'] ?? ('#'.$item['id'])}}</a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
