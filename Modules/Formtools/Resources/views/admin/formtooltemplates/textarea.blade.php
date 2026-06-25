@if($f['formtype']=='textarea')
    @include('formtools::admin.formtooltemplates.fieldWrapperStart', compact('f'))
    <textarea
            class="form-control"
            name="{{$f['identification']}}"
            id="{{$f['identification']}}"
            placeholder="{{$f['placeholder']?:$f['name']}}"
            {{$f['disabled']}}
            @if($f['required']) required @endif
            rows="{{$f['rows']?:5}}"
            cols="5">{{$f['value']}}</textarea>
    @include('formtools::admin.formtooltemplates.fieldWrapperEnd', compact('f'))
@endif
