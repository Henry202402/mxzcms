<div class="sidebar-category sidebar-category-visible">
    <div class="category-content no-padding">
        <ul class="navigation navigation-main navigation-accordion">
            @foreach($menus as $menu)
            @if($menu['url']!='#'&& $menu['action']!='#')
                    <li @if($pageData['controller']==$menu['controller']) class="active" @endif>
                        <a href="{{url($menu['url'])}}" @if($menu['target']) target="{{$menu['target']}}" @endif >
                            <i class="{{$menu['icon']}}"></i>
                            <span>{{$menu['title']}}</span>
                        </a>
                    </li>
                @elseif($menu['url']=='#'&& $menu['action']=='#')
                    <li @if($pageData['controller']==$menu['controller']) class="active" @endif >
                        <a href="#">
                            <i class="{{$menu['icon']}}"></i>
                            <span>{{$menu['title']}}</span>
                        </a>
                        <ul>
                            @foreach($menu['submenu'] as $two)
                                    <li @if($pageData['action']==$two['action'] && $pageData['controller']==$two['controller']) class="active" @endif>
                                        <a href="{{url($two['url'])}}" @if($two['target']) target="{{$two['target']}}" @endif >
                                            <i class="{{$two['icon']}}"></i>
                                            {{$two['title']}}
                                        </a>

                                        @if($two['submenu'])
                                            <ul>
                                                @foreach($two['submenu'] as $tree)
                                                    <li @if($pageData['action']==$tree['action'] && $pageData['controller']==$tree['controller']) class="active" @endif>
                                                        <a href="{{url($tree['url'])}}" @if($tree['target']) target="{{$tree['target']}}" @endif >
                                                            <i class="{{$tree['icon']}}"></i>
                                                            {{$tree['title']}}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                            @endforeach
                        </ul>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
</div>
