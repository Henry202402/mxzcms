@if($f['formtype']=='hidden')
    <input type="hidden" id="{{$f['identification']}}"
           name="{{$f['identification']}}"
           class="form-control"
           value="{{$f['value']}}"
           @if($f['required']) required @endif
    >
@endif
