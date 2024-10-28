@if($f['formtype']=='image')
    @if(!$f['showtype'] || $f['showtype']=='row')
        <div class="form-group row">
            <label class="col-lg-1 control-label">
                @if($f['required'])<span style="color:red;">*</span> @endif{{$f['name']}}
            </label>
            <div class="col-lg-11">
                <div class="media no-margin-top">
                    @if($f['value'])
                        <div class="media-left">
                            <a href="#">
                                <img src="{{$f['value']?GetUrlByPath($f['value']):moduleAdminResource($moduleName).'/images/placeholder.jpg'}}"
                                     style="width: 58px; height: 58px;" class="img-rounded" alt=""
                                     onclick="clickImage('{{$f['value']?GetUrlByPath($f['value']):moduleAdminResource($moduleName).'/images/placeholder.jpg'}}')">
                            </a>
                        </div>
                    @endif
                    @if(!$f['disabled'])
                        <div class="media-body">
                            <input type="file" name="{{$f['identification']}}" {{$f['disabled']}} class="file-styled"
                                   accept="image/*">
                            {{--                    <span class="help-block">支持格式: gif, png, jpg，jpeg. 最大文件 2Mb</span>--}}
                            @if($f['notes'])
                                <span class="help-block">{{ $f['notes'] }}</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @elseif($f['showtype']=='column')
        <div class="col-md-6" style="margin-bottom: 15px;">
            <label>
                @if($f['required'])<span style="color:red;">*</span> @endif{{$f['name']}}
            </label>
            <div class="media no-margin-top">
                @if($f['value'])
                    <div class="media-left">
                        <a href="#">
                            <img src="{{$f['value']?GetUrlByPath($f['value']):moduleAdminResource($moduleName).'/images/placeholder.jpg'}}"
                                 style="width: 35px; height: 35px;" class="img-rounded" alt=""
                                 onclick="clickImage('{{$f['value']?GetUrlByPath($f['value']):moduleAdminResource($moduleName).'/images/placeholder.jpg'}}')">
                        </a>
                    </div>
                @endif
                @if(!$f['disabled'])
                    <div class="media-body">
                        <input type="file" name="{{$f['identification']}}" {{$f['disabled']}} class="file-styled"
                               accept="image/*">
                        {{--                    <span class="help-block">支持格式: gif, png, jpg，jpeg. 最大文件 2Mb</span>--}}
                        @if($f['notes'])
                            <span class="help-block">{{ $f['notes'] }}</span>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    @endif
    <script>
        $(function () {
            // Primary file input
            $(".file-styled").uniform({
                wrapperClass: 'bg-warning',
                fileButtonHtml: '<i class="icon-googleplus5"></i>'
            });
        })
    </script>
@endif



