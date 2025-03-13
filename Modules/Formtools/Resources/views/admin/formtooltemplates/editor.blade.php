@if($editor = hook('GetEditor',compact('f'))[0])
    {{$editor}}
@else
    @php $f['formtype']="textarea" @endphp
    @include(moduleAdminTemplate("formtools")."formtooltemplates.".$f['formtype'],compact( 'f'))
@endif

