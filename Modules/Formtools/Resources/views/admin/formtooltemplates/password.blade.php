@if($f['formtype']=='password')
    @include('formtools::admin.formtooltemplates.fieldWrapperStart', compact('f'))
    <input type="password" id="{{$f['identification']}}"
           name="{{$f['identification']}}"
           class="form-control"
           placeholder="{{$f['placeholder']?:$f['name']}}"
           value="{{$f['value']}}"
           {{$f['disabled']}}
           @if($f['required']) required @endif
    >
    @include('formtools::admin.formtooltemplates.fieldWrapperEnd', compact('f'))
@endif

