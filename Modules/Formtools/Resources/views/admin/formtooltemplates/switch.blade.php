@php
    $switchOptions = $f['datas'] ?: [
        ['name' => '开启', 'value' => '1'],
        ['name' => '关闭', 'value' => '0'],
    ];
    $switchOn = $switchOptions[0] ?? ['name' => '开启', 'value' => '1'];
    $switchOff = $switchOptions[1] ?? ['name' => '关闭', 'value' => '0'];
    $checked = (string) $f['value'] === (string) $switchOn['value'];
@endphp
@if($f['formtype']=='switch')
    @include('formtools::admin.formtooltemplates.fieldWrapperStart', compact('f'))
    <div>
        <input type="hidden" name="{{$f['identification']}}" value="{{$switchOff['value']}}">
        <label class="formtool-switch">
            <input type="checkbox"
                   class="formtool-switch-input"
                   id="{{$f['identification']}}"
                   @if($checked) checked @endif
                   {{$f['disabled']}}
                   data-target-name="{{$f['identification']}}"
                   data-on-value="{{$switchOn['value']}}"
                   data-off-value="{{$switchOff['value']}}"
                   data-on-label="{{$switchOn['name']}}"
                   data-off-label="{{$switchOff['name']}}"
            >
            <span class="formtool-switch-slider"></span>
        </label>
        <span class="formtool-switch-label">{{$checked ? $switchOn['name'] : $switchOff['name']}}</span>
    </div>
    @include('formtools::admin.formtooltemplates.fieldWrapperEnd', compact('f'))
@endif
