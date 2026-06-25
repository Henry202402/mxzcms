@if($f['formtype']=='time')
    @include('formtools::admin.formtooltemplates.fieldWrapperStart', compact('f'))
    <input type="text" id="{{$f['identification']}}"
           name="{{$f['identification']}}"
           class="form-control cursor-pointer"
           placeholder="{{$f['placeholder']?:$f['name']}}"
           value="{{$f['value']}}" readonly
           {{$f['disabled']}}
           @if($f['required']) required @endif
           style="background: #fff;"
    >
    @include('formtools::admin.formtooltemplates.fieldWrapperEnd', compact('f'))
    <script>
        $(function () {
            //申请日期范围选择
            laydate.render({
                elem: "#{{$f['identification']}}",
                type: 'time',
                trigger: 'click',
            });
        })
    </script>
@endif

