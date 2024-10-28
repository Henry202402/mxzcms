<div class="header @if(!$model['home_page_title'] && !$model['home_page_describe']) background-2 @else header-over large @endif "
     style="background: {{cacheGlobalSettingsByKey("nav_bgcolor")}} ; ">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-5">
                <!-- Logo Image -->
                <a href="{{url('/')}}" class="logo-image {{cacheGlobalSettingsByKey("logo_animated")}}">
                    <img src="{{GetLocalFileByPath(cacheGlobalSettingsByKey('weblogo'))}}" alt="logo">
                </a>
                <!-- End of Logo Image -->
            </div>
            <div class="col-md-9 col-sm-6 col-xs-7">
                <!-- Menu -->
                <nav class="right helper">
                    <ul class="menu sf-menu js-menu">
                        @foreach($homeMenu['topMenu'] as $menu)
                            <li>
                                <a href="{{$menu['url']}}"
                                   style="color: {{cacheGlobalSettingsByKey("nav_color")}} !important;">{{$menu['name']}}
                                    @if($menu['icon'])
                                        <span class="{{$menu['icon']}}">{{$menu['icon_character']}}</span>
                                    @endif
                                </a>
                                @if($menu['child'])
                                    <ul>
                                        @foreach($menu['child'] as $child)
                                            <li>
                                                <a href="{{$child['url']}}"
                                                   style="color: {{cacheGlobalSettingsByKey("nav_color")}} !important;">{{$child['name']}}</a>
                                                @if($child['child'])
                                                    <ul>
                                                        @foreach($child['child'] as $c)
                                                            <li><a href="{{$c['url']}}"
                                                                   style="color: {{cacheGlobalSettingsByKey("nav_color")}} !important;">{{$c['name']}}</a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                        @if($userInfo)
                            <li>
                                <a href="{{url("member")}}"
                                   style="color: {{cacheGlobalSettingsByKey("nav_color")}} !important;">
                                    {{$userInfo['nickname']?:$userInfo['username']}}
                                </a>
                            </li>
                        @else
                            <li>
                                <a href="{{url("login")}}"
                                   style="color: {{cacheGlobalSettingsByKey("nav_color")}} !important;">
                                    登录 | 注册
                                </a>
                            </li>
                        @endif
                    </ul>
                </nav>
                <!-- End of Menu -->
            </div>
        </div>
    </div>
</div>
