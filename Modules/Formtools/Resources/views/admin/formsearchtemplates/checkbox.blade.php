@if($f['formtype']=='checkbox')

    @foreach($f['datas'] as $r)
        <label class="checkbox-inline">
            <input type="checkbox"
                   name="{{$f['identification']}}"
                   id="{{$f['identification']}}"
                   value="{{$r['value']}}"
                   {{$f['disabled']}}
                   @if(in_array($r['value'],$f['value'])) checked @endif
            >{{$r['name']}}
        </label>
    @endforeach

@endif
