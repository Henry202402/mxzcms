@php($isColumn = !empty($f['showtype']) && $f['showtype'] === 'column')
@if($f['notes'])
    <span class="help-block">{{ $f['notes'] }}</span>
@endif
@if(!$isColumn)
    </div>
@endif
</div>
