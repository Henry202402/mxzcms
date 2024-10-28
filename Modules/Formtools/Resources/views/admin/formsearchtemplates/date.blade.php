@if($f['formtype']=='date')
    <input type="text" id="{{$f['identification']}}"
           name="{{$f['identification']}}"
           class="form-control cursor-pointer"
           placeholder="{{$f['placeholder']}}"
           value="{{$f['value']}}" readonly
           {{$f['disabled']}}
           @if($f['required']) required @endif
           style="background: #fff;"
    >
    <script>
        $(function () {
            //申请日期范围选择
            laydate.render({
                elem: "#{{$f['identification']}}",
                type: 'date',
                trigger: 'click',
            });
        })
    </script>
@endif
