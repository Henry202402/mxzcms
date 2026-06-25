<div id="content">
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">发展历程</div>
            <div class="panel-body">
                @foreach($data as $item)
                    <div style="padding: 18px 0; border-bottom: 1px solid #eee;">
                        <h4 style="margin-top: 0;">{{$item['title'] ?? $item['name'] ?? ('#'.$item['id'])}}</h4>
                        @if(!empty($item['date']))
                            <p class="text-muted">{{$item['date']}}</p>
                        @elseif(!empty($item['created_at']))
                            <p class="text-muted">{{$item['created_at']}}</p>
                        @endif
                        <div style="line-height: 1.8;">{!! $item['content'] ?? '' !!}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
