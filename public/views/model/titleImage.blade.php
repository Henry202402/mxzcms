<div id="content">
    <div class="container">
        <div class="row">
            @foreach($data as $item)
                <div class="col-md-4" style="margin-bottom: 25px;">
                    <div class="panel panel-default" style="height: 100%;">
                        @if(!empty($item['cover']))
                            <a href="{{url("detail/{$param['model']}/{$item['id']}")}}">
                                <img src="{{GetUrlByPath($item['cover'])}}" alt="{{$item['title'] ?? $item['name'] ?? 'cover'}}" style="width: 100%; height: 220px; object-fit: cover;">
                            </a>
                        @endif
                        <div class="panel-body">
                            <h4 style="margin: 0;"><a href="{{url("detail/{$param['model']}/{$item['id']}")}}">{{$item['title'] ?? $item['name'] ?? ('#'.$item['id'])}}</a></h4>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @if(method_exists($data, 'links') && ($model['home_config']['page_num'] ?? 0) > 0)
            <div class="text-center">{{$data->appends($_GET)->links()}}</div>
        @endif
    </div>
</div>
