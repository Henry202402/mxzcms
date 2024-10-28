@if($f['formtype']=='multipleSelect')
    @if(!$f['showtype'] || $f['showtype']=='row')
        <div class="form-group row">
            <label class="control-label col-lg-1">
                @if($f['required'])<span style="color:red;">*</span> @endif{{$f['name']}}
            </label>
            <div class="col-lg-11">
                <select class="select-search" {{$f['disabled']}} name="{{$f['identification']}}[]"
                        id="{{$f['identification']}}" multiple @if($f['required']) required @endif>
                    <option value="">请选择</option>
                    @foreach($f['datas'] as $s)
                        <option value="{{$s['value']}}"
                                @if(in_array($s['value'],explode(",",$f['value']))) selected @endif>{!! $s['name'] !!}</option>
                        @foreach($s['child'] as $ss)
                            <option value="{{$ss['value']}}"
                                    @if(in_array($ss['value'],explode(",",$f['value']))) selected @endif>
                                　ㄴ {!! $ss['name'] !!}</option>
                        @endforeach
                    @endforeach
                </select>
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
            <select class="select-search" {{$f['disabled']}} name="{{$f['identification']}}[]"
                    id="{{$f['identification']}}" multiple @if($f['required']) required @endif>
                <option value="">请选择</option>
                @foreach($f['datas'] as $s)
                    <option value="{{$s['value']}}"
                            @if(in_array($s['value'],explode(",",$f['value']))) selected @endif>{!! $s['name'] !!}</option>
                    @foreach($s['child'] as $ss)
                        <option value="{{$ss['value']}}"
                                @if(in_array($ss['value'],explode(",",$f['value']))) selected @endif>
                            　ㄴ {!! $ss['name'] !!}</option>
                    @endforeach
                @endforeach
            </select>
            @if($f['notes'])
                <span class="help-block">{{ $f['notes'] }}</span>
            @endif
        </div>
    @endif
@endif