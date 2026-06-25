<div id="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">{{$data['title'] ?? $data['name'] ?? '联系我们'}}</div>
                    <div class="panel-body">
                        @foreach(($model['frontend_schema']['detail'] ?? $model['fields'] ?? []) as $field)
                            @php($value = $data[$field['identification']] ?? '')
                            @continue($value === '' || $value === null)
                            <div style="margin-bottom: 12px; line-height: 1.8;">
                                <strong>{{$field['name']}}：</strong>
                                @if(($field['formtype'] ?? '') === 'image')
                                    <img src="{{GetUrlByPath($value)}}" alt="{{$field['name']}}" style="max-width: 180px; vertical-align: middle;">
                                @else
                                    <span>{{$value}}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
