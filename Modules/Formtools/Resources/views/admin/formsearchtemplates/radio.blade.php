@if($f['formtype']=='radio')

    @foreach($f['datas'] as $r)
        <label class="radio-inline">
            <input type="radio"
                   name="{{$f['identification']}}"
                   id="{{$f['identification']}}"
                   value="{{$r['value']}}"
                   {{$f['disabled']}}
                   @if($r['value']==$f['value']) checked @endif
            > {{$r['name']}}
        </label>
    @endforeach

@endif
