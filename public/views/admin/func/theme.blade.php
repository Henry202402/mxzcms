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

    .package-meta {
        display: inline-block;
        padding: 2px 8px;
        margin-right: 6px;
        margin-bottom: 6px;
        border-radius: 12px;
        background: #f3f5f7;
        color: #59636e;
        font-size: 12px;
    }

    .package-tip {
        font-size: 12px;
        color: #6c757d;
        line-height: 1.6;
    }

    .action-disabled {
        color: #adb5bd;
        cursor: not-allowed;
    }

    .page-header {
        margin-bottom: 20px;
    }

    .container-fluid {
        padding-left: 28px !important;
        padding-right: 28px !important;
    }

    .col-12.bg-light {
        background: transparent !important;
    }

    .card-header.card-default {
        padding: 20px 24px;
        border: 0;
        border-bottom: 1px solid #edf2f7;
        background: #fff;
    }

    .card-body {
        padding: 24px;
        background: #fff;
    }

    .package-toolbar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px 14px;
        font-size: 14px;
        line-height: 1.7;
    }

    .package-toolbar .small {
        font-size: 13px !important;
    }

    .package-toolbar .onlineModuleList,
    .package-toolbar .float-right {
        margin-left: auto;
    }

    .box {
        height: 100%;
        padding: 18px;
        border: 1px solid #e7edf5;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 12px 28px rgba(15, 23, 42, .06);
    }

    .box img {
        width: 100%;
        height: 210px;
        object-fit: cover;
        border-radius: 12px;
    }

    .mt-20 {
        margin-top: 16px !important;
    }

    .bottomtitle h5,
    .box h5 {
        margin: 0;
        color: #1f2937;
        font-size: 16px;
        font-weight: 600;
        line-height: 1.5;
        word-break: break-word;
    }

    .bottomtitle p,
    .box p.text-secondary.small {
        margin: 0;
        color: #64748b !important;
        font-size: 13px !important;
        line-height: 1.6;
    }

    .bottomtitle {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 12px;
    }

    .package-head {
        width: 100%;
    }

    .package-head-title {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 10px;
    }

    .package-head-title h5 {
        flex: 1;
        min-width: 0;
    }

    .package-head-status {
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        margin-top: 1px;
        font-size: 12px;
        white-space: nowrap;
    }

    .package-head-meta {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px 10px;
    }

    .package-head-meta p,
    .package-head-meta .mb-2 {
        display: inline-flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 6px 10px;
        margin: 0 !important;
    }

    .package-tip {
        display: inline-flex;
        align-items: center;
        max-width: 100%;
        padding: 4px 10px;
        margin: 8px 10px 0 0;
        border-radius: 999px;
        background: #f8fafc;
        font-size: 12px;
        color: #64748b;
        line-height: 1.6;
        vertical-align: top;
        word-break: break-word;
    }

    .package-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px 16px;
        margin-top: 14px;
    }

    .package-actions .d-inline-block,
    .package-actions a,
    .package-actions span,
    .package-actions div {
        font-size: 13px;
        line-height: 1.6;
    }

    .scroll-container {
        height: 88px !important;
        padding-right: 4px;
    }

    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 16px !important;
            padding-right: 16px !important;
        }

        .card-header.card-default,
        .card-body {
            padding: 18px;
        }
    }

</style>
<div class="row page-header">
    <div class="col-lg-6 align-self-center ">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url("admin/index")}}">{{getTranslateByKey("common_home_page")}}</a>
            </li>
            <li class="breadcrumb-item"><a href="{{url("admin/theme")}}">{{getTranslateByKey("theme_configuration")}}</a></li>
            <li class="breadcrumb-item active">{{getTranslateByKey("theme_list")}}</li>
        </ol>
    </div>
</div>


<section class="container-fluid pl-5 pr-5 ">

    <div class="row pb-5">
        <div class="col-12 bg-light" style="min-height: calc(100vh - 250px)">
            <div class="">
                <div class="card-header card-default">
                    <div class="row">
                        <div class="col-md-12 col-12 package-toolbar">
                            {{getTranslateByKey('theme_installed')}}
                            <a href="#" onclick="clearCache();" class="small">{{getTranslateByKey('clear_cache')}}</a>
                            @if(cacheGlobalSettingsByKey('use_of_cloud')==1)
                                <a href="{{url('admin/cloud?cloud_type='.\Modules\Main\Models\Modules::Theme)}}"
                                   class="text-right float-right onlineModuleList">
                                    {{getTranslateByKey('online_theme')}} <i class="fa fa-arrow-circle-o-right"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <div class="row">
                        @if($themes_install_datas)
                            @foreach($themes_install_datas as $install_data)
                                <div class="col-lg-2 col-md-3 col-sm-4 mb-4" style="min-width: 260px">
                                    <div class="box">
                                        <div class="">
                                            <div class="bottomtitle mt-3">
                                                <div class="thumbnail">
                                                    <img class="img-thumbnail img-fluid"
                                                         src="{{asset("views/themes/".$install_data->identification.'/'.$install_data->preview)}}"
                                                         alt="">
                                                </div>
                                                <div class="mt-20">
                                                    <div class="package-head">
                                                        <div class="package-head-title">
                                                            <h5>{{$install_data->name}}</h5>
                                                            @if($install_data->status==1)
                                                                <span class="package-head-status text-success">{{getTranslateByKey('theme_in_use')}}</span>
                                                            @else
                                                                <a href="{{url('admin/theme/changeStatus?m='.$install_data->identification)}}"
                                                                   class="package-head-status text-grey small">
                                                                    {{getTranslateByKey('click_to_enable')}}
                                                                </a>
                                                            @endif
                                                        </div>
                                                        <div class="package-head-meta">
                                                            <p class="text-secondary small">
                                                                <span>{{$install_data->author}}</span>
                                                                <span>V{{$install_data->version}}</span>
                                                            </p>
                                                            <div class="mb-2">
                                                                <span class="package-meta">{{$install_data->level_label}}</span>
                                                                @if($install_data->compatibility_summary)
                                                                    <span class="package-meta">{{$install_data->compatibility_summary}}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="clearfix scroll-container" style="height: 80px;overflow: auto">
                                                <p class="text-secondary">
                                                    {{$install_data->description}}
                                                </p>
                                            </div>
                                            @if($install_data->dependency_names)
                                                <div class="package-tip mb-2">
                                                    {{getTranslateByKey('dependency_label')}}：{{implode('、', $install_data->dependency_names)}}
                                                </div>
                                            @endif

                                            <div class="package-actions">
                                                <div class="d-inline-block">
                                                    <a target="_blank"
                                                       href="{{url('admin/theme/setting?m='.$install_data->identification)}}">
                                                        <span class="fa fa-cog"></span> {{getTranslateByKey('enter_configuration')}}
                                                    </a>
                                                </div>
                                                @if(intval($install_data->order ?? 0) >= 9999)
                                                    <div class="d-inline-block">
                                                        <div class="text-danger cursor"
                                                             onclick="changeTopQuestion(0,'{{url('admin/theme/changeTop?m='.$install_data->identification.'&top=0')}}')">
                                                            <span class="fa fa-thumb-tack"></span>
                                                            取消置顶
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="d-inline-block">
                                                        <div class="cursor text-primary"
                                                             onclick="changeTopQuestion(1,'{{url('admin/theme/changeTop?m='.$install_data->identification.'&top=1')}}')">
                                                            <span class="fa fa-thumb-tack"></span>
                                                            置顶
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="d-inline-block module-update-{{$install_data->identification}} text-danger"
                                                     style="cursor: pointer;visibility: hidden;"
                                                     onclick="updateVersion('{{$install_data->identification}}','theme','{{session()->get('versionLimit')["theme_".$install_data->identification]}}')">
                                                    {{getTranslateByKey('update_version')}}
                                                </div>
                                                @if($install_data->uninstall_allowed)
                                                    <div class="d-inline-block text-secondary cursor"
                                                         onclick="uninstallQuestion('{{url('admin/theme/uninstall?m='.$install_data->identification)}}')">
                                                        <span class=" fa fa-trash-o"></span> {{getTranslateByKey('common_uninstall')}}
                                                    </div>
                                                @else
                                                    <div class="d-inline-block action-disabled"
                                                         title="{{$install_data->uninstall_reason}}">
                                                        <span class=" fa fa-trash-o"></span> {{getTranslateByKey('common_uninstall')}}
                                                    </div>
                                                @endif
                                            </div>
                                            @if($install_data->uninstall_reason)
                                                <div class="package-tip mt-2 text-danger">
                                                    {{getTranslateByKey('uninstall_limit')}}：{{$install_data->uninstall_reason}}
                                                </div>
                                            @endif

                                        </div>
                                    </div>

                                </div>
                                <script>
                                    $(function () {
                                        checkModuleVersion('{{$install_data->identification}}', 'theme', '{{$install_data->version}}');
                                    });
                                </script>
                            @endforeach

                        @endif


                    </div>

                </div>

                @if($themes_not_install_datas)
                    <div class="card-header card-default">
                        <div class="row">
                            <div class="col-md-6">
                                {{getTranslateByKey('theme_not_installed')}}
                            </div>
                        </div>
                    </div>

                    <div class="card-body">

                        <div class="row">
                            @foreach($themes_not_install_datas as $not_install_data)

                                <div class="col-md-2 mb-3 " style="min-width: 260px">
                                    <div class="box">
                                        <div class="thumbnail">
                                            <img class="img-thumbnail img-fluid"
                                                 src="{{asset("views/themes/".$not_install_data->identification.'/'.$not_install_data->preview)}}"
                                                 alt="">
                                        </div>
                                        <div class="mt-20">
                                            <div class="package-head">
                                                <div class="package-head-title">
                                                    <h5>{{$not_install_data->name}}</h5>
                                                </div>
                                                <div class="package-head-meta">
                                                    <p class="text-secondary small">
                                                        <span>{{$not_install_data->author}}</span>
                                                        <span>V{{$not_install_data->version}}</span>
                                                    </p>
                                                    <div class="mb-2">
                                                        <span class="package-meta">{{$not_install_data->level_label}}</span>
                                                        @if($not_install_data->compatibility_summary)
                                                            <span class="package-meta">{{$not_install_data->compatibility_summary}}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="">
                                                @if($not_install_data->dependency_names)
                                                    <div class="package-tip mt-2">
                                                        {{getTranslateByKey('dependency_label')}}：{{implode('、', $not_install_data->dependency_names)}}
                                                    </div>
                                                @endif
                                                <div class="package-actions">
                                                        <span class="glyphicon glyphicon-cog">
                                                               @if($not_install_data->install_allowed)
                                                                   <a href="{{url('admin/theme/install?m='
                                                                        .$not_install_data->identification
                                                                        .'&form=local')}}"
                                                                      class="">
                                                                       {{getTranslateByKey('install')}}
                                                                   </a>
                                                               @else
                                                                   <span class="action-disabled"
                                                                         title="{{$not_install_data->install_reason}}">
                                                                       {{getTranslateByKey('install')}}
                                                                   </span>
                                                               @endif
                                                        </span>

                                                        <span class="glyphicon glyphicon-cog ">
                                                               <span onclick="DelQuestion('{{url('admin/module/delete?m='
                                                                    .$not_install_data->identification
                                                                    .'&form=local'
                                                                    .'&cloud_type='.\Modules\Main\Models\Modules::Theme)}}')"
                                                                     class="text-danger" style="cursor: pointer;">
                                                                   {{getTranslateByKey('common_delete')}}
                                                               </span>
                                                        </span>
                                                </div>
                                                @if($not_install_data->install_reason)
                                                    <div class="package-tip mt-2 text-danger">
                                                        {{getTranslateByKey('install_limit')}}：{{$not_install_data->install_reason}}
                                                    </div>
                                                @endif
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
<!-- ============================================================== -->
<!-- 						Content End		 						-->
<!-- ============================================================== -->


<!-- Common Plugins -->
@include('admin.public.js',['load'=> ["custom"]])

<script type="text/javascript">

    // Online package list
    function onlineModuleList() {
        window.location.href = "{{url('admin/cloud?cloud_type='.\Modules\Main\Models\Modules::Module)}}";
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

    function changeTopQuestion(status, url) {
        $.confirm({
            title: '{{getTranslateByKey("common_tip")}}',
            content: status == 1 ? '确定置顶该主题吗？' : '确定取消置顶该主题吗？',
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
            content: '{{getTranslateByKey("common_delete_confirm")}}',
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
    /* Scrollbar size */
    .scroll-container {
        scrollbar-width: thin; /* Scrollbar width */
        scrollbar-color: #888 #f1f1f1; /* Thumb and track colors */
    }

    /* Scrollbar size */
    .scroll-container::-webkit-scrollbar {
        width: 4px;
        height: 12px;
    }

    /* Track color */
    .scroll-container::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    /* Thumb color */
    .scroll-container::-webkit-scrollbar-thumb {
        background: #888;
    }

    /* Thumb color on hover */
    .scroll-container::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    /* Track color on hover */
    .scroll-container:hover {
        scrollbar-color: #555 #f1f1f1; /* Hover colors */
    }
</style>
</body>
</html>
