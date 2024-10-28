@if($f['formtype']=='select')

    <select class="form-control" {{$f['disabled']}} name="{{$f['identification']}}" id="{{$f['identification']}}">
        <option @if($f['value']==="") selected @endif value="">请选择</option>
        @foreach($f['datas'] as $s)
            <option value="{{$s['value']}}"
                    @if($f['value'] !="" && $s['value']==$f['value']) selected @endif
            >{!! $s['name'] !!}</option>
        @endforeach
    </select>

@endif
