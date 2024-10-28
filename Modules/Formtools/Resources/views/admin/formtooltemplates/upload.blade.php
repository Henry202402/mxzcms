@if($f['formtype']=='upload')
    @if(!$f['showtype'] || $f['showtype']=='row')
        <div class="form-group row">
            <label class="control-label col-lg-1">
                @if($f['required'])<span style="color:red;">*</span> @endif{{$f['name']}}
            </label>
            <div class="col-lg-11">
                <input type="file" name="{{$f['identification']}}" {{$f['disabled']}} class="file-styled-primary">
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
            <input type="file" name="{{$f['identification']}}" {{$f['disabled']}} class="file-styled-primary">
            @if($f['notes'])
                <span class="help-block">{{ $f['notes'] }}</span>
            @endif
        </div>
    @endif
    <script>
        $(function () {
            $(".file-styled-primary").uniform({
                wrapperClass: 'bg-warning',
                fileButtonHtml: '<i class="icon-plus2"></i>'
            });
        })
    </script>
@endif
