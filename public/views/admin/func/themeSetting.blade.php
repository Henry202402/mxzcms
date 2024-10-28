@include("admin.public.header")
<body class="horizontal">

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
            <li>
                <a href="{{url("admin/theme")}}"> <i class="fa fa-arrow-circle-left"></i>返回主题列表</a>
            </li>

            <li>
                <a href="{{url("admin/theme/themeMenuList?m=".$_GET['m'])}}"><i class="fa fa-pencil-square"></i> 菜单管理</a>
            </li>

            <li>
                <a href="{{url("admin/theme/diy?m=".$_GET['m'])}}"><i class="fa fa-gears"></i> 页面配置</a>
            </li>

        </ul>
    </nav>
</div>

<div class="row page-header" style="margin-bottom: 0px;">
    <div class="col-lg-6 align-self-center ">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url("admin/index")}}">{{getTranslateByKey("common_home_page")}}</a></li>
            <li class="breadcrumb-item"><a href="{{url("admin/theme")}}">主题配置</a></li>
            <li class="breadcrumb-item active">主题预览</li>
        </ol>
    </div>
</div>

<section class="container-fluid pl-5 pr-5 ">

    <div class="row pb-5">
        <div class="col-12 bg-light p-2" style="min-height: calc(100vh - 160px)">
            <iframe src="{{url("admin/theme/preview?m=".$_GET["m"]?:"default")}}" class="" style="width: 100%;height: 100%;border: none;"></iframe>
        </div>
    </div>
    @include('admin.public.footer')
</section>
@include('admin.public.js',['load'=> ["custom"]])
</body>
</html>
