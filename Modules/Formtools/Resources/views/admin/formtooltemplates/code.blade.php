@if($f['formtype']=='code')
    @if(!$f['showtype'] || $f['showtype']=='row')
        <div class="form-group row">
            <label class="col-lg-1 control-label">
                @if($f['required'])<span style="color:red;">*</span> @endif{{$f['name']}}
            </label>
            <div class="col-lg-11">
                <textarea
                        class="form-control formtool-code"
                        style="font-family: Consolas, 'Courier New', monospace; white-space: pre;"
                        name="{{$f['identification']}}"
                        id="{{$f['identification']}}"
                        placeholder="{{$f['placeholder']?:$f['name']}}"
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
                    class="form-control formtool-code"
                    style="font-family: Consolas, 'Courier New', monospace; white-space: pre;"
                    name="{{$f['identification']}}"
                    id="{{$f['identification']}}"
                    placeholder="{{$f['placeholder']?:$f['name']}}"
                    {{$f['disabled']}}
                    @if($f['required']) required @endif
                    rows="{{$f['rows']?:10}}">{{$f['value']}}</textarea>
            @if($f['notes'])
                <span class="help-block">{{ $f['notes'] }}</span>
            @endif
        </div>
    @endif
@endif
