@if($f['formtype']=='json')
    @if(!$f['showtype'] || $f['showtype']=='row')
        <div class="form-group row">
            <label class="col-lg-1 control-label">
                @if($f['required'])<span style="color:red;">*</span> @endif{{$f['name']}}
            </label>
            <div class="col-lg-11">
                <textarea
                        class="form-control formtool-json"
                        name="{{$f['identification']}}"
                        id="{{$f['identification']}}"
                        placeholder="{{$f['placeholder']?:$f['name']}}"
                        data-json-error="{{$f['aInfo']?:'JSON 格式不正确'}}"
                        {{$f['disabled']}}
                        @if($f['required']) required @endif
                        rows="{{$f['rows']?:10}}">{{$f['value']}}</textarea>
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
            <textarea
                    class="form-control formtool-json"
                    name="{{$f['identification']}}"
                    id="{{$f['identification']}}"
                    placeholder="{{$f['placeholder']?:$f['name']}}"
                    data-json-error="{{$f['aInfo']?:'JSON 格式不正确'}}"
                    {{$f['disabled']}}
                    @if($f['required']) required @endif
                    rows="{{$f['rows']?:10}}">{{$f['value']}}</textarea>
            @if($f['notes'])
                <span class="help-block">{{ $f['notes'] }}</span>
            @endif
        </div>
    @endif
@endif
