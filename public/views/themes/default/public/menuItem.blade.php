@php
    $menu = $menu ?? [];
    $hasChildren = !empty($menu['child']);
    $target = $menu['target'] ?? '_self';
    $depth = $depth ?? 0;
@endphp

<li class="{{ $hasChildren ? 'mx-has-children' : '' }}">
    <a href="{{ $menu['url'] ?? '#' }}"
       target="{{ $target }}"
       @if($target === '_blank') rel="noopener noreferrer" @endif>
        @if(!empty($menu['icon']))
            <span class="mx-nav-icon">
                <i class="{{ $menu['icon'] }}"></i>
                @if(!empty($menu['icon_character']))
                    <em>{{ $menu['icon_character'] }}</em>
                @endif
            </span>
        @endif
        <span>{{ $menu['name'] ?? '菜单' }}</span>
        @if($hasChildren)
            <i class="fa fa-angle-down mx-nav-caret"></i>
        @endif
    </a>
    @if($hasChildren)
        <ul>
            @foreach($menu['child'] as $child)
                @include('themes.default.public.menuItem', ['menu' => $child, 'depth' => $depth + 1])
            @endforeach
        </ul>
    @endif
</li>
