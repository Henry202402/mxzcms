@if($f['formtype']=='upload')
    @include('formtools::admin.formtooltemplates.fieldWrapperStart', compact('f'))
    <input type="file" name="{{$f['identification']}}" {{$f['disabled']}} class="file-styled-primary">
    @include('formtools::admin.formtooltemplates.fieldWrapperEnd', compact('f'))
    <script>
        $(function () {
            $(".file-styled-primary").uniform({
                wrapperClass: 'bg-warning',
                fileButtonHtml: '<i class="icon-plus2"></i>'
            });
        })
    </script>
@endif
