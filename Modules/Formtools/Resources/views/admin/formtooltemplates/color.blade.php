@php
    $colorValue = trim((string) ($f['value'] ?? ''));
    if (!preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $colorValue)) {
        $colorValue = '#ffffff';
    }
    $pickerId = 'picker_' . md5($f['identification'] . '_' . $f['showtype']);
@endphp

@if($f['formtype']=='color')
    @include('formtools::admin.formtooltemplates.fieldWrapperStart', compact('f'))
    <div class="input-group formtool-color-group" data-picker="#{{$pickerId}}">
        <span class="input-group-addon" style="padding: 2px 6px;">
            <input type="color" id="{{$pickerId}}" class="formtool-color-picker" value="{{$colorValue}}" {{$f['disabled']}}>
        </span>
        <input type="text"
               id="{{$f['identification']}}"
               name="{{$f['identification']}}"
               class="form-control formtool-color-text"
               placeholder="{{$f['placeholder']?:'#ffffff'}}"
               value="{{$f['value']}}"
               {{$f['disabled']}}
               @if($f['required']) required @endif>
    </div>
    @include('formtools::admin.formtooltemplates.fieldWrapperEnd', compact('f'))
    <script>
        (function ($) {
            function normalizeHexColor(value) {
                value = $.trim(value || '');
                if (!value) {
                    return '';
                }
                if (value.charAt(0) !== '#') {
                    value = '#' + value;
                }
                if (/^#([0-9a-fA-F]{3}){1,2}$/.test(value)) {
                    return value.toLowerCase();
                }
                return '';
            }

            $('.formtool-color-group').each(function () {
                var $group = $(this);
                if ($group.data('color-initialized')) {
                    return;
                }
                $group.data('color-initialized', true);

                var $picker = $group.find('.formtool-color-picker').first();
                var $input = $group.find('.formtool-color-text').first();

                function syncFromInput() {
                    var normalized = normalizeHexColor($input.val());
                    if (normalized) {
                        $picker.val(normalized);
                        $group.removeClass('has-error');
                    } else if ($.trim($input.val()) === '') {
                        $group.removeClass('has-error');
                    } else {
                        $group.addClass('has-error');
                    }
                }

                $picker.on('input change', function () {
                    $input.val($(this).val());
                    $group.removeClass('has-error');
                });

                $input.on('blur input', syncFromInput);
                syncFromInput();
            });
        })(jQuery);
    </script>
@endif
