@if($f['formtype']=='hidden')

    <input type="hidden" id="{{$f['identification']}}"
           name="{{$f['identification']}}"
           class="form-control"
           placeholder="{{$f['placeholder']}}"
           value="{{$f['value']}}"
           {{$f['disabled']}}
           @if($f['required']) required @endif
    >

@endif

