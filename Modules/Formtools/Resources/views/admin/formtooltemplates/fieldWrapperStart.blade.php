@php($isColumn = !empty($f['showtype']) && $f['showtype'] === 'column')
@php($columnWidth = max(1, min(12, intval($f['width'] ?? 6) ?: 6)))
<div class="{{$isColumn ? 'col-md-' . $columnWidth : 'form-group row'}}" @if($isColumn) style="margin-bottom: 15px;" @endif>
    <label class="{{$isColumn ? '' : 'col-lg-1 control-label'}}">
        @if($f['required'])<span style="color:red;">*</span> @endif{{$f['name']}}
    </label>
    @if(!$isColumn)
        <div class="col-lg-11">
    @endif
