@if(hook('GetEditor',compact('f'))[0])
    {{hook('GetEditor',compact('f'))[0]}}
@else
    @php $f['formtype']="textarea" @endphp
    @include(moduleAdminTemplate("formtools")."formtooltemplates.".$f['formtype'],compact( 'f'))
@endif

