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
            <li class="breadcrumb-item"><a href="{{url("admin/index")}}">{{getTranslateByKey("common_home_page")}}</a>
            </li>
            <li class="breadcrumb-item"><a
                        href="{{url("admin/module")}}">{{getTranslateByKey("functional_module")}}</a></li>
            <li class="breadcrumb-item active">{{getTranslateByKey("functional_module_list")}}</li>
        </ol>
    </div>
</div>

<section class="container-fluid pl-5 pr-5 ">

    <div class="row pb-5">
        <div class="col-12 bg-light" style="min-height: calc(100vh - 250px)">
            <div class="">
                <div class="card-header card-default">
                    <div class="row">
                        <div class="col-md-12 col-12">
                            {{getTranslateByKey('module_installed')}} &nbsp;
                            <a href="{{url('admin/system/setting/moduleBindDomain')}}" class="small">模块域名设置</a>
                            <a href="#" onclick="clearCache();" class="small">清空缓存</a>
                            @if(cacheGlobalSettingsByKey('use_of_cloud')==1)
                                <a href="{{url('admin/cloud?cloud_type='.\Modules\Main\Models\Modules::Module)}}"
                                   class="text-right float-right onlineModuleList">
                                    {{getTranslateByKey('online_module')}} <i class="fa fa-arrow-circle-o-right"></i>
                                </a>
                            @endif
                        </div>

                    </div>
                </div>
                <div class="card-body">
                    <form class="">
                        <div class="row mb-3 pb-3">
                            <div class="col-md-12">
                                <a href="{{url("admin/module")}}"
                                   class="btn btn-sm btn-light mr-3 @if(!$_GET['type']) btn-primary active @endif ">全部模块</a>
                                <a href="{{url("admin/module?type=system")}}"
                                   class="btn btn-sm btn-light mr-3 @if($_GET['type']=='system') btn-primary active @endif  ">内置模块</a>
                                <a href="{{url("admin/module?type=function")}}"
                                   class="btn btn-sm btn-light mr-3 @if($_GET['type']=="function") btn-primary active @endif  ">功能模块</a>
                            </div>
                        </div>
                        <div class="row mb-3 pb-3">
                            <div class="col-md-12 form-inline">
                                <div class="form-group">
                                    <label for="input-email" class="sr-only">模块名称</label>
                                    <input  type="text" name="keyword" value="{{$_GET['keyword']}}" placeholder="输入名称关键词" class="form-control">
                                    <input type="hidden" name="type" value="{{$_GET['type']}}">
                                </div>
                                <button type="submit" class="btn btn-primary margin-l-5 mx-sm-3">搜索</button>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        @if($modules_install_datas)
                            @foreach($modules_install_datas as $modules_install_data)
                                <div class="col-lg-2 col-md-3 col-sm-4 mb-4" style="min-width: 260px">
                                    <div class="box">
                                        <div class="">
                                            <div class="bottomtitle clearfix mt-3">
                                                <h5>
                                                    {{$modules_install_data['name']}}
                                                    @if($modules_install_data['status'] == 0)
                                                        <span class="float-right small text-danger"
                                                              style="font-size: 12px">
                                                            禁用中
                                                        </span>
                                                    @else
                                                        <span class="float-right small text-success"
                                                              style="font-size: 12px">
                                                            启用中
                                                        </span>
                                                    @endif

                                                </h5>
                                                <p class="text-secondary small">
                                                    {{$modules_install_data['author']}} &nbsp;
                                                    V{{$modules_install_data['version']}}

                                                    <span class="float-right">
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

                                            <div class="d-inline-block">
                                                <a href="{{url('admin/entryModule?m='.$modules_install_data['identification'])}}"
                                                   target="_blank">
                                                    {{--<a href="{{url('admin/'.strtolower($modules_install_data['identification']).'/index')}}" target="_blank">--}}
                                                    <span class="fa fa-cog"></span> {{getTranslateByKey('enter_management')}}
                                                </a>
                                            </div>
                                            <div class="d-inline-block module-update-{{$modules_install_data['identification']}} text-danger"
                                                 style="cursor: pointer;visibility: hidden;"
                                                 onclick="updateVersion('{{$modules_install_data['identification']}}','module')"
                                            >
                                                &nbsp;&nbsp;
                                                更新版本
                                            </div>
                                            <br/>
                                            @if($modules_install_data['domain']=="y")

                                                @if($modules_install_data['is_index'] == 0)
                                                    <div class="d-inline-block">
                                                        <div class="cursor text-primary" onclick="changeStatusHomePage(1,'{{url('admin/module/changeIndex?m='.$modules_install_data['identification'].'&is_index=1')}}')">
                                                            <span class="fa fa-home"></span>
                                                            {{getTranslateByKey('set_as_front_desk_home_page')}}
                                                        </div>
                                                    </div>
                                                @else

                                                    <div class="d-inline-block">
                                                        <div class="text-danger cursor" onclick="changeStatusHomePage(0,'{{url('admin/module/changeIndex?m='.$modules_install_data['identification'].'&is_index=0')}}')"
                                                           >
                                                            <span class="fa fa-home"></span>
                                                            {{getTranslateByKey('unset_as_front_desk_home_page')}}
                                                        </div>
                                                    </div>
                                                @endif
                                                <br/>
                                            @else
                                                <div class="d-inline-block text-secondary">
                                                    <span class="fa fa-home"></span>
                                                    不可设前台首页
                                                </div>
                                                <br/>
                                            @endif

                                            @if($modules_install_data['is_backend'] == 0)
                                                <div class="d-inline-block">
                                                    <div class="cursor text-primary" onclick="changeStatusBackPage(1,'{{url('admin/module/changeBack?m='.$modules_install_data['identification'].'&is_backend=1')}}')">
                                                        <span class="fa fa-flag"></span>
                                                        设为后台入口
                                                    </div>
                                                </div>
                                            @else

                                                <div class="d-inline-block">
                                                    <div class="text-danger cursor" onclick="changeStatusBackPage(0,'{{url('admin/module/changeBack?m='.$modules_install_data['identification'].'&is_backend=0')}}')"
                                                    >
                                                        <span class="fa fa-flag"></span>
                                                        取消后台入口
                                                    </div>
                                                </div>
                                            @endif

                                            @if($modules_install_data['type'] == "function")
                                                &nbsp;
                                                <div class="d-inline-block text-secondary cursor"
                                                     onclick="uninstallQuestion('{{url('admin/module/uninstall?m='.$modules_install_data['identification'].'&cloud_type='.\Modules\Main\Models\Modules::Module)}}')">
                                                    <span class=" fa fa-trash-o"></span> 卸载
                                                </div>
                                                &nbsp;
                                                @if($modules_install_data['status'] == 0)
                                                    <div class="d-inline-block cursor"
                                                         onclick="changeStatusQuestion(1, '{{url('admin/module/changeStatus?m='.$modules_install_data['identification'].'&status=1&cloud_type='.\Modules\Main\Models\Modules::Module)}}')">
                                                        <span class="fa fa-circle-o-notch"></span>
                                                        {{getTranslateByKey('common_enable')}}
                                                    </div>

                                                @else
                                                    <div class="d-inline-block text-danger cursor"
                                                         onclick="changeStatusQuestion(0, '{{url('admin/module/changeStatus?m='.$modules_install_data['identification'].'&status=0&cloud_type='.\Modules\Main\Models\Modules::Module)}}')">
                                                        <span class="fa fa-ban"></span>
                                                        {{getTranslateByKey('common_disable')}}
                                                    </div>
                                                @endif

                                            @else
                                                <div class="d-inline-block text-secondary cursor">
                                                    &nbsp;
                                                </div>
                                            @endif





                                        </div>
                                    </div>

                                </div>

                                <script>
                                    $(function () {
                                        checkModuleVersion('{{$modules_install_data['identification']}}', 'module', '{{$modules_install_data['version']}}');
                                    });
                                </script>
                            @endforeach
                        @else
                            <div class="col-lg-12 col-md-12 col-sm-12 mb-4 text-center mt-4" style="min-width: 260px">
                                <div>尚未安装任何功能模块，<a href="{{url("admin/cloud?cloud_type=module")}}">去安装</a></div>
                            </div>
                        @endif


                    </div>

                </div>

                @if($modules_not_install_datas)
                    <div class="card-header card-default">
                        <div class="row">
                            <div class="col-md-6">
                                {{--未安装模块--}}
                                {{getTranslateByKey('module_not_installed')}}
                            </div>
                        </div>
                    </div>

                    <div class="card-body">

                        <div class="row">
                            @foreach($modules_not_install_datas as $modules_not_install_data)

                                <div class="col-md-2 mb-3 " style="min-width: 260px">
                                    <div class="box">
                                        <div class="">
                                            <h5>
                                                {{$modules_not_install_data['name']}}
                                                <span class="float-right small text-secondary" style="font-size: 12px">
                                                    {{$modules_not_install_data['author']}}
                                                </span>
                                            </h5>
                                            <div class="">
                                                <span class="glyphicon glyphicon-download"></span>{{$modules_not_install_data['version']}}
                                                <div class="d-inline-block float-right">
                                                        <span class="glyphicon glyphicon-cog">
                                                               <a href="{{url('admin/module/install?m='
                                                                    .$modules_not_install_data['identification']
                                                                    .'&form=local'
                                                                    .'&cloud_type='.\Modules\Main\Models\Modules::Module)}}"
                                                                  class="">
                                                                   {{--安装--}}
                                                                   {{getTranslateByKey('install')}}
                                                               </a>
                                                        </span>

                                                        <span class="glyphicon glyphicon-cog ">
                                                               <span onclick="DelQuestion('{{url('admin/module/delete?m='
                                                                    .$modules_not_install_data['identification']
                                                                    .'&form=local'
                                                                    .'&cloud_type='.\Modules\Main\Models\Modules::Module)}}')"
                                                                  class="text-danger" style="cursor: pointer;">
                                                                   删除
                                                               </span>
                                                        </span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            @endforeach

                        </div>

                    </div>
                @endif


            </div>
        </div>
    </div>


    @include('admin.public.footer')


</section>



@include('admin.public.js',['load'=> ["custom"]])


<script type="text/javascript">
    function changeStatusBackPage(status, url) {
        $.confirm({
            title: '<?php echo e(getTranslateByKey("common_tip")); ?>',
            content: status == 1 ? '<?php echo '设为默认后台入口吗？'; ?>' : '<?php echo '取消默认后台入口吗？'; ?>',
            type: 'default',
            buttons: {
                ok: {
                    text: "<?php echo e(getTranslateByKey('common_ensure')); ?>",
                    btnClass: 'btn-primary',
                    keys: ['enter'],
                    action: function () {
                        location.href = url
                    }
                },
                cancel: {
                    text: "<?php echo e(getTranslateByKey('common_cancel')); ?>"
                }
            }
        });
    }
    function changeStatusHomePage(status, url) {
        $.confirm({
            title: '{{getTranslateByKey("common_tip")}}',
            content: status == 1 ? '{{getTranslateByKey("set_as_front_desk_home_page")}}' : '{{getTranslateByKey("unset_as_front_desk_home_page")}}',
            type: 'default',
            buttons: {
                ok: {
                    text: "{{getTranslateByKey('common_ensure')}}",
                    btnClass: 'btn-primary',
                    keys: ['enter'],
                    action: function () {
                        location.href = url
                    }
                },
                cancel: {
                    text: "{{getTranslateByKey('common_cancel')}}"
                }
            }
        });
    }
    function changeStatusQuestion(status, url) {
        $.confirm({
            title: '{{getTranslateByKey("common_tip")}}',
            content: status == 1 ? '{{getTranslateByKey("common_sure_to_enabling")}}' : '{{getTranslateByKey("common_sure_to_forbidden")}}',
            type: 'default',
            buttons: {
                ok: {
                    text: "{{getTranslateByKey('common_ensure')}}",
                    btnClass: 'btn-primary',
                    keys: ['enter'],
                    action: function () {
                        location.href = url
                    }
                },
                cancel: {
                    text: "{{getTranslateByKey('common_cancel')}}"
                }
            }
        });
    }

    function uninstallQuestion(url) {
        $.confirm({
            title: '{{getTranslateByKey("common_tip")}}',
            content: '{{getTranslateByKey("common_sure_to_uninstall")}}',
            type: 'default',
            buttons: {
                ok: {
                    text: "{{getTranslateByKey('common_ensure')}}",
                    btnClass: 'btn-primary',
                    keys: ['enter'],
                    action: function () {
                        location.href = url
                    }
                },
                cancel: {
                    text: "{{getTranslateByKey('common_cancel')}}"
                }
            }
        });
    }

    function DelQuestion(url) {
        $.confirm({
            title: '{{getTranslateByKey("common_tip")}}',
            content: '确定要删除吗!',
            type: 'default',
            buttons: {
                ok: {
                    text: "{{getTranslateByKey('common_ensure')}}",
                    btnClass: 'btn-primary',
                    keys: ['enter'],
                    action: function () {
                        location.href = url
                    }
                },
                cancel: {
                    text: "{{getTranslateByKey('common_cancel')}}"
                }
            }
        });
    }

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
