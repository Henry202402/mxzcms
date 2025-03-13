<!-- Main navbar -->
<div class="navbar navbar-default header-highlight">
    <div class="navbar-header">
        <a class="navbar-brand" href="{{url('admin/module')}}" style="color: #fff;">
            {{--<img src="{{GetUrlByPath(cacheGlobalSettingsByKey('weblogo'))}}" alt="">--}}
            <span class="h-self-module">
                {{config()->get('modules')[$moduleName]['name']}}
            </span>
        </a>

        <ul class="nav navbar-nav visible-xs-block">
            <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
            <li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
        </ul>
    </div>

    <div class="navbar-collapse collapse" id="navbar-mobile">
        <ul class="nav navbar-nav">
            <li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-paragraph-justify3"></i></a>
            </li>
        </ul>

        {{--  <p class="navbar-text"><span class="label bg-success">Online</span></p>--}}

        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown dropdown-user">
                <a class="dropdown-toggle" data-toggle="dropdown">
                    <span>快捷入口</span>
                    <i class="caret"></i>
                </a>

                <ul class="dropdown-menu dropdown-menu-right">
                    @foreach($moduleArray as $value)
                        <li>
                            <a href="{{url('admin/entryModule?m='.$value['identification'])}}">
                                <i class="icon-vector2"></i>
                                {{$value['name']}}
                            </a>
                        </li>
                    @endforeach
                    @if($moduleArray)
                        <li class="divider"></li>@endif
                    @if($userInfo['type']=='admin')
                        <li><a href="{{url('/admin')}}"><i class="icon-switch2"></i> 返回主站</a></li>
                    @else
                        <li><a href="{{moduleAdminJump(strtolower($value['identification']),'logout')}}"><i class="icon-switch2"></i> 退出</a>
                        </li>
                    @endif
                </ul>
            </li>
        </ul>
    </div>
</div>
<!-- /main navbar -->
