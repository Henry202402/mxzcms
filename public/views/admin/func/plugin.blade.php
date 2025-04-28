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
            <li class="breadcrumb-item"><a href="{{url("admin/plugin")}}">插件管理</a></li>
            <li class="breadcrumb-item active">插件列表</li>
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
                            已安装插件
                            <a href="#" onclick="clearCache();" class="small">清空缓存</a>
                            @if(cacheGlobalSettingsByKey('use_of_cloud')==1)
                                <a href="{{url('admin/cloud?cloud_type='.\Modules\Main\Models\Modules::Plugin)}}"
                                   class="text-right float-right onlineModuleList">
                                    在线插件 <i class="fa fa-arrow-circle-o-right"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <div class="row">
                        @if($plugin_install_datas)
                            @foreach($plugin_install_datas as $install_data)
                                <div class="col-lg-2 col-md-3 col-sm-4 mb-4" style="min-width: 260px">
                                    <div class="box">
                                        <div class="">
                                            <div class="bottomtitle clearfix mt-3">
                                                <h5>
                                                    {{$install_data['name']}}
                                                    @if($install_data['status']==1)
                                                        <span class="float-right text-success small">
                                                            启用中
                                                        </span>
                                                    @else
                                                        <span class="float-right text-danger small">
                                                            禁用中
                                                        </span>
                                                    @endif
                                                </h5>
                                                <p class="text-secondary small">
                                                    V{{$install_data['version']}}

                                                    <span class="float-right text-secondary">
                                                            {{$install_data['author']}}
                                                    </span>

                                                </p>
                                            </div>

                                            <div class="clearfix scroll-container" style="height: 80px;overflow: auto">
                                                <p class="text-secondary">
                                                    {{$install_data['description']}}
                                                </p>
                                            </div>
                                            @if($install_data['config'])
                                                <div class="d-inline-block">
                                                    <a href="{{url('admin/plugin/config?identification='.$install_data['identification'])}}">
                                                        <span class="fa fa-cog"></span> 设置
                                                    </a>
                                                </div>
                                            @else
                                                <div class="d-inline-block text-secondary">
                                                    <span class="fa fa-cog"></span> 设置
                                                </div>
                                            @endif

                                            <div class="d-inline-block module-update-{{$install_data['identification']}} text-danger"
                                                 style="cursor: pointer;visibility: hidden;"
                                                 onclick="updateVersion('{{$install_data['identification']}}','plugin')"
                                            >
                                                &nbsp;&nbsp;
                                                更新版本
                                            </div>

                                            <br/>

                                            @if($install_data['status'] == 0)
                                                <div class="d-inline-block cursor text-success"
                                                     onclick="changeStatusQuestion(1, '{{url('admin/module/changeStatus?m='.$install_data['identification'].'&status=1&cloud_type='.\Modules\Main\Models\Modules::Plugin)}}')">
                                                    <span class="fa fa-circle-o-notch"></span>
                                                    {{getTranslateByKey('common_enable')}}
                                                </div>

                                            @else
                                                <div class="d-inline-block text-danger cursor"
                                                     onclick="changeStatusQuestion(0, '{{url('admin/module/changeStatus?m='.$install_data['identification'].'&status=0&cloud_type='.\Modules\Main\Models\Modules::Plugin)}}')">
                                                    <span class="fa fa-ban"></span>
                                                    {{getTranslateByKey('common_disable')}}
                                                </div>
                                            @endif

                                            <div class="d-inline-block text-secondary cursor"
                                                 onclick="uninstallQuestion('{{url('admin/module/uninstall?m='.$install_data['identification'].'&cloud_type='.\Modules\Main\Models\Modules::Plugin)}}')">
                                                <span class=" fa fa-trash-o"></span> 卸载
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <script>
                                    $(function () {
                                        checkModuleVersion('{{$install_data['identification']}}', 'plugin', '{{$install_data['version']}}');
                                    });
                                </script>
                            @endforeach
                        @else
                            <div class="col-lg-12 col-md-12 col-sm-12 mb-4 text-center mt-4" style="min-width: 260px">
                                <div>尚未安装任何插件，<a href="{{url("admin/cloud?cloud_type=plugin")}}">去安装</a></div>
                            </div>
                        @endif


                    </div>

                </div>

                @if($plugin_not_install_datas)
                    <div class="card-header card-default">
                        <div class="row">
                            <div class="col-md-6">
                                未安装插件
                            </div>
                        </div>
                    </div>

                    <div class="card-body">

                        <div class="row">
                            @foreach($plugin_not_install_datas as $not_install_data)

                                <div class="col-md-2 mb-3 " style="min-width: 260px">
                                    <div class="box">
                                        <div class="">
                                            <h5>
                                                {{$not_install_data['name']}}
                                                <span class="float-right text-secondary small" style="font-size: 12px">
                                                    {{$not_install_data['author']}}
                                                </span>
                                            </h5>
                                            <div class="">
                                                <span class="glyphicon glyphicon-download"></span>{{$not_install_data['version']}}
                                                <div class="d-inline-block float-right">
                                                        <span class="glyphicon glyphicon-cog">
                                                               <a href="{{url('admin/module/install?m='
                                                                    .$not_install_data['identification']
                                                                    .'&form=local'
                                                                    .'&cloud_type='.\Modules\Main\Models\Modules::Plugin)}}"
                                                                  class="">
                                                                   {{getTranslateByKey('install')}}
                                                               </a>
                                                        </span>

                                                        <span class="glyphicon glyphicon-cog ">
                                                               <span onclick="DelQuestion('{{url('admin/module/delete?m='
                                                                    .$not_install_data['identification']
                                                                    .'&form=local'
                                                                    .'&cloud_type='.\Modules\Main\Models\Modules::Plugin)}}')"
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
            title: '<?php echo e(getTranslateByKey("common_tip")); ?>',
            content: '确定要删除吗!',
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
