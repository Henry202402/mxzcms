<div class="main-horizontal-nav">
    <nav>
        <!-- Menu Toggle btn-->
        <div class="menu-toggle">
            <h3>{{getTranslateByKey("menu_navigation")}}</h3>
            <button type="button" id="menu-btn">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <ul id="respMenu" class="ace-responsive-menu" data-menu-style="horizontal">
            <li @if(request()->route()->uri=="admin/index") class="menu-active" @endif>
                <a href="{{url("admin/index")}}"> <i class="fa fa-home"></i>{{getTranslateByKey("common_home_page")}}</a>
            </li>
            <li @if(request()->route()->uri=="admin/module") class="menu-active" @endif>
                <a href="{{url("admin/module")}}"><i class="fa fa-puzzle-piece"></i> {{getTranslateByKey("functional_module")}}</a>
            </li>
            <li @if(request()->route()->uri=="admin/plugin") class="menu-active" @endif>
                <a href="{{url("admin/plugin")}}"><i class="fa fa-plug"></i> 插件管理</a>
            </li>
            <li @if(request()->route()->uri=="admin/theme") class="menu-active" @endif>
                <a href="{{url("admin/theme")}}"><i class="fa fa-pencil-square"></i> 主题配置</a>
            </li>

            {{--@if(cacheGlobalSettingsByKey('Useofcloud') =='true')
                <li>
                    <a href="{{url('admin/cloud')}}"><i class="fa fa-cloud"></i> 云应用</a>
                </li>
            @endif--}}
        </ul>
    </nav>
</div>
