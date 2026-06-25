@php
    $detailData = $detailData ?? [];
    $rawValue = $detailData[$f['identification']] ?? $f['value'] ?? '';
    $isColumn = !empty($f['showtype']) && $f['showtype'] === 'column';
    $columnWidth = max(1, min(12, intval($f['width'] ?? 6) ?: 6));
    $wrapperClass = $isColumn ? 'col-md-' . $columnWidth : 'form-group row';
    $labelClass = $isColumn ? '' : 'col-lg-1 control-label';
    $contentClass = $isColumn ? '' : 'col-lg-11';
    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    $flattenOptions = function ($options) use (&$flattenOptions) {
        $items = [];
        foreach ((array) $options as $option) {
            if (!is_array($option)) {
                continue;
            }
            if (array_key_exists('value', $option)) {
                $items[(string) $option['value']] = $option['name'] ?? $option['value'];
            }
            foreach (($option['children'] ?? $option['child'] ?? []) as $child) {
                $items = array_merge($items, $flattenOptions([$child]));
            }
        }
        return $items;
    };

    $normalizeArrayValues = function ($value) {
        if (is_array($value)) {
            $values = $value;
        } else {
            $stringValue = trim((string) $value);
            if ($stringValue === '') {
                return [];
            }

            $decoded = json_decode($stringValue, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $values = $decoded;
            } else {
                $values = preg_split('/[\r\n,]+/', $stringValue);
            }
        }

        $normalized = [];
        foreach ((array) $values as $item) {
            if (is_array($item)) {
                $item = $item['value'] ?? $item['path'] ?? $item['url'] ?? '';
            }
            $item = trim((string) $item);
            if ($item !== '') {
                $normalized[] = $item;
            }
        }

        return array_values(array_unique($normalized));
    };

    $buildFileItems = function ($value) use ($normalizeArrayValues, $imageExtensions) {
        $items = [];
        foreach ($normalizeArrayValues($value) as $path) {
            $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            $items[] = [
                'path' => $path,
                'url' => GetUrlByPath($path),
                'name' => basename($path),
                'isImage' => in_array($extension, $imageExtensions, true),
            ];
        }
        return $items;
    };

    $buildLinkItems = function ($datas) {
        $items = [];
        foreach ((array) $datas as $item) {
            if (!is_array($item) || empty($item['name']) || empty($item['href'])) {
                continue;
            }

            $href = (string) $item['href'];
            if (strpos($href, 'http') !== 0) {
                $href = url(ltrim($href, '/'));
            }

            $items[] = [
                'name' => $item['name'],
                'href' => $href,
            ];
        }
        return $items;
    };

    $displayValue = function () use ($f, $rawValue, $flattenOptions, $normalizeArrayValues) {
        if (in_array($f['formtype'], ['section', 'legend'], true)) {
            return '';
        }

        if ($f['formtype'] === 'switch') {
            $options = $f['datas'] ?: [
                ['name' => '开启', 'value' => '1'],
                ['name' => '关闭', 'value' => '0'],
            ];
            $on = $options[0] ?? ['name' => '开启', 'value' => '1'];
            $off = $options[1] ?? ['name' => '关闭', 'value' => '0'];
            return (string) $rawValue === (string) $on['value'] ? $on['name'] : $off['name'];
        }

        if (in_array($f['formtype'], ['checkbox', 'checkboxList'], true)) {
            $map = $flattenOptions($f['datas'] ?? []);
            $values = $normalizeArrayValues($rawValue);
            $labels = [];
            foreach ($values as $value) {
                $value = trim((string) $value);
                if ($value === '') {
                    continue;
                }
                $labels[] = $map[$value] ?? $value;
            }
            return $labels ? implode(' , ', array_unique($labels)) : '';
        }

        if (in_array($f['formtype'], ['selectMore', 'multipleSelect'], true) && !empty($f['datas'])) {
            $map = $flattenOptions($f['datas']);
            $values = $normalizeArrayValues($rawValue);
            $labels = [];
            foreach ($values as $value) {
                $labels[] = $map[(string) $value] ?? $value;
            }
            return $labels ? implode(' , ', array_unique($labels)) : '';
        }

        if (in_array($f['formtype'], ['radio', 'select'], true) && !empty($f['datas'])) {
            $map = $flattenOptions($f['datas']);
            return $map[(string) $rawValue] ?? $rawValue;
        }

        if (in_array($f['formtype'], ['json', 'code'], true)) {
            return trim((string) $rawValue);
        }

        if ($f['formtype'] === 'tags') {
            $items = $normalizeArrayValues($rawValue);
            return implode(', ', array_unique($items));
        }

        return is_array($rawValue) ? json_encode($rawValue, JSON_UNESCAPED_UNICODE) : (string) $rawValue;
    };

    $display = $displayValue();
    $fileItems = in_array($f['formtype'], ['upload', 'uploadAjax', 'image', 'imageAjax'], true) ? $buildFileItems($rawValue) : [];
    $linkItems = $f['formtype'] === 'links' ? $buildLinkItems($f['datas'] ?? []) : [];
@endphp

@if(in_array($f['formtype'], ['section', 'legend'], true))
    @include('formtools::admin.formtooltemp.field', compact('f'))
@elseif(in_array($f['formtype'], ['upload', 'uploadAjax', 'image', 'imageAjax'], true))
    <div class="{{$wrapperClass}}" @if($isColumn) style="margin-bottom: 15px;" @endif>
        <label class="{{$labelClass}}">{{$f['name']}}</label>
        <div class="{{$contentClass}}">
            @if($fileItems)
                @foreach($fileItems as $fileItem)
                    @if($fileItem['isImage'])
                        <img src="{{$fileItem['url']}}" class="cursor-pointer img-rounded" style="max-width: 120px; max-height: 120px; margin-right: 10px; margin-bottom: 10px;" onclick="clickImage('{{$fileItem['url']}}', 500)">
                    @else
                        <div style="margin-bottom: 8px;">
                            <a href="javascript:void(0);" onclick="fileDownload('{{$fileItem['url']}}')">{{$fileItem['name']}}</a>
                        </div>
                    @endif
                @endforeach
            @else
                <span class="text-muted">-</span>
            @endif
            @if($f['notes'])
                <span class="help-block">{{ $f['notes'] }}</span>
            @endif
        </div>
    </div>
@elseif(in_array($f['formtype'], ['json', 'code'], true))
    <div class="{{$wrapperClass}}" @if($isColumn) style="margin-bottom: 15px;" @endif>
        <label class="{{$labelClass}}">{{$f['name']}}</label>
        <div class="{{$contentClass}}">
            <pre class="form-control" style="height: auto; min-height: 120px; white-space: pre-wrap; font-family: Consolas, 'Courier New', monospace;">{{$display ?: '-'}}</pre>
            @if($f['notes'])
                <span class="help-block">{{ $f['notes'] }}</span>
            @endif
        </div>
    </div>
@elseif($f['formtype'] === 'editor')
    <div class="{{$wrapperClass}}" @if($isColumn) style="margin-bottom: 15px;" @endif>
        <label class="{{$labelClass}}">{{$f['name']}}</label>
        <div class="{{$contentClass}}">
            @if(trim(strip_tags($display)) !== '' || trim($display) !== '')
                <div class="form-control" style="height: auto; min-height: 120px; overflow: auto;">{!! $display !!}</div>
            @else
                <div class="form-control" style="height: auto; min-height: 36px;">-</div>
            @endif
            @if($f['notes'])
                <span class="help-block">{{ $f['notes'] }}</span>
            @endif
        </div>
    </div>
@elseif($f['formtype'] === 'tags')
    <div class="{{$wrapperClass}}" @if($isColumn) style="margin-bottom: 15px;" @endif>
        <label class="{{$labelClass}}">{{$f['name']}}</label>
        <div class="{{$contentClass}}">
            @if($display)
                @foreach(explode(', ', $display) as $tag)
                    <span class="label label-default" style="margin-right: 6px;">{{$tag}}</span>
                @endforeach
            @else
                <span class="text-muted">-</span>
            @endif
            @if($f['notes'])
                <span class="help-block">{{ $f['notes'] }}</span>
            @endif
        </div>
    </div>
@elseif($f['formtype'] === 'links')
    <div class="{{$wrapperClass}}" @if($isColumn) style="margin-bottom: 15px;" @endif>
        <label class="{{$labelClass}}">{{$f['name']}}</label>
        <div class="{{$contentClass}}">
            @if($linkItems)
                @foreach($linkItems as $linkItem)
                    <div style="margin-bottom: 8px;">
                        <a href="{{$linkItem['href']}}" target="_blank">{{$linkItem['name']}}</a>
                    </div>
                @endforeach
            @else
                <span class="text-muted">-</span>
            @endif
            @if($f['notes'])
                <span class="help-block">{{ $f['notes'] }}</span>
            @endif
        </div>
    </div>
@elseif($f['formtype'] === 'color')
    <div class="{{$wrapperClass}}" @if($isColumn) style="margin-bottom: 15px;" @endif>
        <label class="{{$labelClass}}">{{$f['name']}}</label>
        <div class="{{$contentClass}}">
            @if($display)
                <span class="label label-default" style="display: inline-block; width: 22px; height: 22px; vertical-align: middle; background: {{$display}}; border: 1px solid #ddd;"></span>
                <span style="margin-left: 8px; vertical-align: middle;">{{$display}}</span>
            @else
                <span class="text-muted">-</span>
            @endif
            @if($f['notes'])
                <span class="help-block">{{ $f['notes'] }}</span>
            @endif
        </div>
    </div>
@elseif($f['formtype'] === 'switch')
    <div class="{{$wrapperClass}}" @if($isColumn) style="margin-bottom: 15px;" @endif>
        <label class="{{$labelClass}}">{{$f['name']}}</label>
        <div class="{{$contentClass}}">
            <span class="label {{$display === (($f['datas'][0]['name'] ?? '开启')) ? 'label-success' : 'label-default'}}">{{$display ?: '-'}}</span>
            @if($f['notes'])
                <span class="help-block">{{ $f['notes'] }}</span>
            @endif
        </div>
    </div>
@else
    <div class="{{$wrapperClass}}" @if($isColumn) style="margin-bottom: 15px;" @endif>
        <label class="{{$labelClass}}">{{$f['name']}}</label>
        <div class="{{$contentClass}}">
            <div class="form-control" style="height: auto; min-height: 36px;">{{$display !== '' ? $display : '-'}}</div>
            @if($f['notes'])
                <span class="help-block">{{ $f['notes'] }}</span>
            @endif
        </div>
    </div>
@endif
