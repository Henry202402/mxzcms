@php($formtype = !empty($f['formtype']) ? $f['formtype'] : 'text')
@php($templateView = !empty($f['template']) ? $f['template'] : moduleAdminTemplate("formtools")."formtooltemplates.".$formtype)
@php($templateView = view()->exists($templateView) ? $templateView : moduleAdminTemplate("formtools")."formtooltemplates.text")
@include($templateView, compact('f'))
