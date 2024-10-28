@if($f['formtype']=='dateRange')
    @if(!$f['showtype'] || $f['showtype']=='row')
        <div class="form-group row">
            <label class="col-lg-1 control-label">
                @if($f['required'])<span style="color:red;">*</span> @endif{{$f['name']}}
            </label>
            <div class="col-lg-11">
                <input type="text" id="{{$f['identification']}}"
                       name="{{$f['identification']}}"
                       class="form-control cursor-pointer"
                       placeholder="{{$f['placeholder']?:$f['name']}}"
                       value="{{$f['value']}}" readonly
                       {{$f['disabled']}}
                       @if($f['required']) required @endif
                       style="background: #fff;"
                >
                @if($f['notes'])
                    <span class="help-block">{{ $f['notes'] }}</span>
                @endif
            </div>
        </div>
    @elseif($f['showtype']=='column')
        <div class="col-md-6" style="margin-bottom: 15px;">
            <label>
                @if($f['required'])<span style="color:red;">*</span> @endif{{$f['name']}}
            </label>
            <input type="text" id="{{$f['identification']}}"
                   name="{{$f['identification']}}"
                   class="form-control cursor-pointer"
                   placeholder="{{$f['placeholder']?:$f['name']}}"
                   value="{{$f['value']}}" readonly
                   {{$f['disabled']}}
                   @if($f['required']) required @endif
                   style="background: #fff;"
            >
            @if($f['notes'])
                <span class="help-block">{{ $f['notes'] }}</span>
            @endif
        </div>
    @endif
    <script>
        $(function () {
            //申请日期范围选择
            laydate.render({
                elem: "#{{$f['identification']}}",
                type: 'date',
                trigger: 'click',
                range: true,
            });
        })
    </script>
@endif
