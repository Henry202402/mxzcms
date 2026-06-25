<div class="top-bar light-top-bar">
    <div class="container-fluid">
        <div class="">
            <nav class="navbar navbar-expand-lg navbar-light">
                {{--                <a class="navbar-brand" href="#">Navbar</a>--}}
                <a class="admin-logo" href="{{url('admin/index')}}" style="width: auto!important;">
                    <h1 style="color: #000;">
                        {{getTranslateByKey("admin_panel")}}
                    </h1>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">


                    <ul class="navbar-nav mr-auto">
                    </ul>
                    <ul class="navbar-nav navbar-padding ml-auto bg-light">
                        <li class="nav-item dropdown mr-2">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{session("admin_current_language")["name"]}}
                            </a>
                            <ul class="dropdown-menu top-dropdown" id="dropdown-menu">
                                @foreach(\Modules\Main\Services\ServiceModel::getLangList() as $kl=>$vl)
                                    <li>
                                        <a class="dropdown-item" href="{{url('admin/changeLang?lang='.$kl)}}">
                                            {{$vl}}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"
                               onclick="clearCache();">{{getTranslateByKey("clear_cache")}}</a>
                        </li>
                        <li class="nav-item updateCMS">
                            {!! hook("CmsUpdateVersion",['version'=>env("APP_VERSION"),"moduleName"=>"System","cssClass"=>"nav-link"])[0] !!}
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{url("/")}}"
                               target="_blank">{{getTranslateByKey("shopping_site")}}</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{session("admin_info")['username']}}({{getTranslateByKey("system_administrator")}})
                            </a>
                            <ul class="dropdown-menu top-dropdown" id="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{url('admin/myinfo')}}"><i class="icon-user"></i>
                                        {{getTranslateByKey("profile")}}</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{url('admin/logout')}}"><i class="icon-logout"></i>
                                        {{getTranslateByKey("logout")}}</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</div>
