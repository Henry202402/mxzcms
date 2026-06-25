@if($f['formtype']=='select')
    @include('formtools::admin.formtooltemplates.fieldWrapperStart', compact('f'))
    <select class="select-search" {{$f['disabled']}}
            name="{{$f['identification']}}" id="{{$f['identification']}}"
            @if($f['required']) required @endif>
        <option value="">请选择</option>
        @foreach($f['datas'] as $s)
            <option value="{{$s['value']}}"
                    @if($s['value']==$f['value']) selected @endif
            >{!! $s['name'] !!}</option>
            @foreach($s['children']?:$s['child'] as $ss)
                <option value="{{$ss['value']}}"
                        @if($ss['value']==$f['value']) selected @endif
                >　ㄴ {!! $ss['name'] !!}</option>
            @endforeach
        @endforeach
    </select>
    @include('formtools::admin.formtooltemplates.fieldWrapperEnd', compact('f'))
@endif
