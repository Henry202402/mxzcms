@if($f['formtype']=='radio')
    @if(!$f['showtype'] || $f['showtype']=='row')
        <div class="form-group row">
            <label class="control-label col-lg-1">
                @if($f['required'])<span style="color:red;">*</span> @endif{{$f['name']}}
            </label>
            <div class="col-lg-11">
                @foreach($f['datas'] as $r)
                    <label class="radio-inline">
                        <input type="radio" class="styled"
                               name="{{$f['identification']}}"
                               id="{{$f['identification']}}"
                               value="{{$r['value']}}"
                               {{$f['disabled']}}
                               @if($r['value']==$f['value']) checked @endif
                        > {{$r['name']}}
                    </label>
                @endforeach
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
            <br>
            @foreach($f['datas'] as $kr=>$r)
                <label class="radio-inline" for="{{$f['identification']}}{{$kr}}">
                    <input type="radio" class="styled"
                           name="{{$f['identification']}}"
                           id="{{$f['identification']}}{{$kr}}"
                           value="{{$r['value']}}"
                           {{$f['disabled']}}
                           @if($r['value']==$f['value']) checked @endif
                    > {{$r['name']}}
                </label>
            @endforeach
            @if($f['notes'])
                <span class="help-block">{{ $f['notes'] }}</span>
            @endif
        </div>
    @endif
@endif
