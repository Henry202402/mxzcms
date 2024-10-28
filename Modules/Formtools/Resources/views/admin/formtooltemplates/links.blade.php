@if($f['formtype']=='links')
    @if(!$f['showtype'] || $f['showtype']=='row')
        <div class="form-group row">
            <label class="control-label col-lg-1 col-md-1">@if($f['required'])<span style="color:red;">*</span> @endif{{$f['name']}}</label>
            <div class="col-lg-11 col-md-11">
                @foreach($f['datas'] as $r)
                    <label class="radio-inline">
                        <a href="{{strpos($r['href'], 'http') === 0?$r['href']:url($r['href'])}}" target="_blank">{{$r['name']}}</a>
                    </label>
                @endforeach
                @if($f['notes'])
                    <div>
                        <span class="help-block small">{{ $f['notes'] }}</span>
                    </div>
                @endif
            </div>
        </div>
    @elseif($f['showtype']=='column')
        <div class="col-md-6" style="margin-bottom: 15px;">
            <label>
                @if($f['required'])<span style="color:red;">*</span> @endif{{$f['name']}}
            </label>
            @foreach($f['datas'] as $r)
                <label class="radio-inline">
                    <a href="{{strpos($r['href'], 'http') === 0?$r['href']:url($r['href'])}}" target="_blank">{{$r['name']}}</a>
                </label>
            @endforeach
            @if($f['notes'])
                <div>
                    <span class="help-block small">{{ $f['notes'] }}</span>
                </div>
            @endif
        </div>
    @endif
@endif
