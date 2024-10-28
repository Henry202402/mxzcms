@if($f['formtype']=='password')
    @if(!$f['showtype'] || $f['showtype']=='row')
        <div class="form-group row">
            <label class="col-lg-1 control-label">
                @if($f['required'])<span style="color:red;">*</span> @endif{{$f['name']}}
            </label>
            <div class="col-lg-11">
                <input type="password" id="{{$f['identification']}}"
                       name="{{$f['identification']}}"
                       class="form-control"
                       placeholder="{{$f['placeholder']?:$f['name']}}"
                       value="{{$f['value']}}"
                       {{$f['disabled']}}
                       @if($f['required']) required @endif
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
            <input type="password" id="{{$f['identification']}}"
                   name="{{$f['identification']}}"
                   class="form-control"
                   placeholder="{{$f['placeholder']?:$f['name']}}"
                   value="{{$f['value']}}"
                   {{$f['disabled']}}
                   @if($f['required']) required @endif
            >
            @if($f['notes'])
                <span class="help-block">{{ $f['notes'] }}</span>
            @endif
        </div>
    @endif
@endif

