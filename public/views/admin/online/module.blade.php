@include("admin.public.header")
<body class="horizontal">
@include("admin.public.topbar")
@include("admin.public.nav")
<style type="text/css">
    .display-none {
        display: none;
    }

    .onlineModuleList {
        cursor: pointer;
    }

    .cursor {
        cursor: pointer;
    }

    .mokuai .row div.col-md-3 {
        height: 340px;
        margin: 2rem 0;
    }

    .box {
        box-shadow: 0 0 8px #ccc;
        border-radius: 15px;
    }

    .box img {
        width: 101%;
        height: 215px;
    }


    .clearfix:after {
        visibility: hidden;
        clear: both;
        display: block;
        content: ".";
        height: 0;
    }

    .clearfix {
        *zoom: 1;
    }

</style>
<div class="row page-header">
    <div class="col-lg-6 align-self-center ">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url("admin/index")}}">{{getTranslateByKey("common_home_page")}}</a></li>
            <li class="breadcrumb-item active">在线模块列表</li>
        </ol>
    </div>
</div>

<section class="container-fluid pl-5 pr-5 ">

    <div class="row pb-5">
        <div class="col-12 bg-light" style="min-height: calc(100vh - 250px)">
            <div class="">
                <div class="card-body">
                    <div class="row mb-3 pb-3">
                        <div class="col-md-12 mb-2">
                            <a href="{{url("admin/cloud?cloud_type=module")}}"
                               class="btn btn-sm btn-light mr-3 btn-sm @if($_GET['cloud_type']=="module") btn-primary active @endif ">在线模块</a>
                            <a href="{{url("admin/cloud?cloud_type=plugin")}}"
                               class="btn btn-sm btn-light mr-3 btn-sm @if($_GET['cloud_type']=='plugin') btn-primary active @endif  ">在线插件</a>
                            <a href="{{url("admin/cloud?cloud_type=theme")}}"
                               class="btn btn-sm btn-light mr-3 btn-sm @if($_GET['cloud_type']=="theme") btn-primary active @endif  ">在线模板</a>
                        </div>
                        <div class="col-md-12">
                            一级分类：
                            <a class="mr-3 @if(!$_GET['cate_pid']) text-primary  @else text-muted @endif " href="" >全部</a>
                            @foreach($listDatas['cates'] as $index=>$cate_data)
                                <a class="mr-3 @if($cate_data['id'] == $_GET['cate_pid']) text-primary @else text-muted @endif "
                                   href="{{url("admin/cloud?index=".$index."&cloud_type=".$_GET['cloud_type']."&cate_pid=".$cate_data['id'])}}">{{$cate_data['cate_name']}}</a>
                            @endforeach
                        </div>

                        @if($listDatas['cates'][$_GET['index']]['children'])
                            <div class="col-md-12">
                                二级分类：
                                <a class="mr-3 @if(!$_GET['cate_id']) text-primary  @else text-muted @endif " href="{{url("admin/cloud?".http_build_query($_GET)."&cate_id=")}}" >全部</a>
                                @foreach($listDatas['cates'][$_GET['index']]['children'] as $index=>$cate_data)
                                    <a class="mr-3 @if($cate_data['id'] == $_GET['cate_id']) text-primary @else text-muted @endif "
                                       href="{{url("admin/cloud?index=".$_GET['index']."&cloud_type=".$_GET['cloud_type']."&cate_pid=".$_GET['cate_pid']."&cate_id=".$cate_data['id'])}}">{{$cate_data['cate_name']}}</a>
                                @endforeach
                            </div>

                        @endif

                        <div class="col-md-12">
                            支持平台：
                            <a class="mr-3 @if(!$_GET['platform']) text-primary  @else text-muted @endif " href="{{url("admin/cloud?".http_build_query($_GET))}}">全部</a>
                            <a class="mr-3 @if($_GET['platform']=="pc") text-primary  @else text-muted @endif " href="{{url("admin/cloud?".http_build_query($_GET)."&platform=pc")}}">PC</a>
                            <a class="mr-3 @if($_GET['platform']=="moblic") text-primary  @else text-muted @endif " href="{{url("admin/cloud?".http_build_query($_GET)."&platform=moblic")}}">移动端</a>
                            <a class="mr-3 @if($_GET['platform']=="H5") text-primary  @else text-muted @endif " href="{{url("admin/cloud?".http_build_query($_GET)."&platform=H5")}}">H5</a>

                        </div>
                        <div class="col-md-12">
                            是否收费：
                            <a class="mr-3 @if(!$_GET['isfree']) text-primary  @else text-muted @endif " href="{{url("admin/cloud?".http_build_query($_GET))}}">全部</a>
                            <a class="mr-3 @if($_GET['isfree']=="n") text-primary  @else text-muted @endif " href="{{url("admin/cloud?".http_build_query($_GET)."&isfree=n")}}">收费</a>

                            <a class="mr-3 @if($_GET['isfree']=="y") text-primary  @else text-muted @endif " href="{{url("admin/cloud?".http_build_query($_GET)."&isfree=y")}}">免费</a>

                        </div>
                        <div class="col-md-12">
                            排序属性：
                            <a class="mr-3 @if(!$_GET['sort']) text-primary  @else text-muted @endif " href="{{url("admin/cloud?".http_build_query($_GET))}}">默认</a>
                            <a class="mr-3 @if($_GET['sort']=="download") text-primary  @else text-muted @endif " href="{{url("admin/cloud?".http_build_query($_GET)."&sort=download")}}">下载量</a>
                            <a class="mr-3 @if($_GET['sort']=="commont") text-primary  @else text-muted @endif " href="{{url("admin/cloud?".http_build_query($_GET)."&sort=commont")}}">评分</a>
                            <a class="mr-3 @if($_GET['sort']=="time") text-primary  @else text-muted @endif " href="{{url("admin/cloud?".http_build_query($_GET)."&sort=time")}}">发布时间</a>
                        </div>
                    </div>
                    <div class="row">
                        @if($listDatas['list']['data'])
                            @foreach($listDatas['list']['data'] as $modules_install_data)
                                <div class="col-lg-2 col-md-3 col-sm-4 mb-4" style="min-width: 260px">
                                    <div class="box">
                                        <div class="">
                                            <div class="thumbnail">
                                                <img
                                                    class="img-thumbnail img-fluid"
                                                    style="max-width: 300px;"
                                                    src="{{$modules_install_data['cover']}}" alt="">
                                            </div>
                                            <div class="bottomtitle clearfix mt-3">
                                                <h5>
                                                    {{$modules_install_data['name']}}
                                                    <span class="float-right small text-info"
                                                          style="font-size: 12px">
                                                            {{$modules_install_data['username']}}
                                                    </span>
                                                </h5>
                                                <p class="text-secondary small">
                                                    V{{$modules_install_data['version']}}

                                                    <span class="float-right">
                                                        <span class="text-primary">
                                                            {{$modules_install_data['cate_name']}}
                                                        </span>
                                                        @if($modules_install_data['type'] == "system")
                                                            内置模块
                                                        @else
                                                            功能模块
                                                        @endif
                                                    </span>
                                                </p>
                                            </div>

                                            <div class="clearfix scroll-container" style="height: 80px;overflow: auto">
                                                <p class="text-secondary">
                                                    {{$modules_install_data['description']}}
                                                </p>
                                            </div>

                                            <div class="d-inline-block text-danger cursor"
                                                 onclick="">
                                                {{$modules_install_data['price']>0?"￥".$modules_install_data['price']:"免费"}}
                                            </div>

                                            @if($local_data[$modules_install_data['identification']])
                                                @if($local_data[$modules_install_data['identification']]['version'] == $modules_install_data['version'])
                                                    <div class="d-inline-block text-muted cursor"
                                                         onclick="">
                                                        已安装最新版
                                                    </div>
                                                @else
                                                    <div class="d-inline-block text-success cursor"
                                                         onclick="updateVersion('{{$modules_install_data['identification']}}','module')">
                                                        <span class="fa fa-refresh"></span>
                                                        更新
                                                    </div>
                                                @endif
                                            @else
                                                <div class="d-inline-block cursor"
                                                     onclick="update('{{$modules_install_data['identification']}}','module')">
                                                    <span class="icon-cloud-download"></span>
                                                    安装
                                                </div>
                                            @endif

                                        </div>
                                    </div>

                                </div>

                            @endforeach
                        @else
                            <div class="col-lg-12 col-md-12 col-sm-12 mb-4">
                                <p>
                                    暂无数据
                                </p>
                            </div>
                        @endif


                    </div>

                    <div class="row">
                        <div class="col-md-12 text-center">
                            @include('admin.online.pagination',['links'=>$listDatas['list']])
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>


    @include('admin.public.footer')


</section>

@include('admin.public.js',['load'=> ["custom"]])

<script type="text/javascript">



</script>
<style>
    /* 设置滚动条的宽度和高度 */
    .scroll-container {
        scrollbar-width: thin; /* 滚动条宽度 */
        scrollbar-color: #888 #f1f1f1; /* 滑块颜色和轨道背景色 */
    }
    /* 设置滚动条的宽度和高度 */
    .scroll-container::-webkit-scrollbar {
        width: 4px;
        height: 12px;
    }

    /* 设置滚动条轨道的背景色 */
    .scroll-container::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    /* 设置滚动条滑块的颜色 */
    .scroll-container::-webkit-scrollbar-thumb {
        background: #888;
    }

    /* 设置鼠标悬停在滑块上时的颜色 */
    .scroll-container::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    /* 设置鼠标悬停在滑块上时的颜色 */
    .scroll-container:hover {
        scrollbar-color: #555 #f1f1f1; /* 悬停时的颜色 */
    }
</style>
</body>
</html>
