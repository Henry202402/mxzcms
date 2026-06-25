@foreach($menus as $menu)
    @php
        $depth = $depth ?? 0;
        $prefix = $depth === 0 ? '' : str_repeat('├─ ', $depth);
        $selectedId = $selectedId ?? 0;
        $excludeId = $excludeId ?? 0;
        $langList = $langList ?? [];
        $menuLang = trim((string) ($menu['lang'] ?? ''));
        $langName = $menuLang === '' ? '全局共享' : ($langList[$menuLang] ?? $menuLang);
    @endphp
    @continue(($menu['id'] ?? 0) == $excludeId)
    <option value="{{$menu['id']}}" data-lang="{{$menuLang}}" data-position="{{$menu['position'] ?? 'top'}}" @if($selectedId == $menu['id']) selected @endif>
        {{$prefix}}[{{$langName}}] {{$menu['name']}}
    </option>
    @if(!empty($menu['child']))
        @include('admin.func.partials.themeMenuParentOptions', [
            'menus' => $menu['child'],
            'depth' => $depth + 1,
            'selectedId' => $selectedId,
            'excludeId' => $excludeId,
            'langList' => $langList,
        ])
    @endif
@endforeach
