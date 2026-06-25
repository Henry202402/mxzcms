@if($f['formtype']=='image')
    @php($isColumn = !empty($f['showtype']) && $f['showtype'] === 'column')
    @php($previewSize = $isColumn ? '35px' : '58px')
    @include('formtools::admin.formtooltemplates.fieldWrapperStart', compact('f'))
    <div class="media no-margin-top">
        @if($f['value'])
            <div class="media-left">
                <a href="#">
                    <img src="{{$f['value']?GetUrlByPath($f['value']):moduleAdminResource($moduleName).'/images/placeholder.jpg'}}"
                         style="width: {{$previewSize}}; height: {{$previewSize}};" class="img-rounded" alt=""
                         onclick="clickImage('{{$f['value']?GetUrlByPath($f['value']):moduleAdminResource($moduleName).'/images/placeholder.jpg'}}')">
                </a>
            </div>
        @endif
        @if(!$f['disabled'])
            <div class="media-body">
                <input type="file" name="{{$f['identification']}}" {{$f['disabled']}} class="file-styled"
                       accept="image/*">
            </div>
        @endif
    </div>
    @include('formtools::admin.formtooltemplates.fieldWrapperEnd', compact('f'))
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



