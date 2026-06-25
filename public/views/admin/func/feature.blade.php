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

    .package-head {
        margin: 12px 0;
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
                        <div class="col-md-12 col-12 package-toolbar">
                            {{getTranslateByKey('module_installed')}} &nbsp;
                            <a href="{{url('admin/system/setting/moduleBindDomain')}}" class="small">{{getTranslateByKey('module_domain_setting')}}</a>
                            <a href="#" onclick="clearCache();" class="small">{{getTranslateByKey('clear_cache')}}</a>
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
                                   class="btn btn-sm btn-light mr-3 @if(!$_GET['type']) btn-primary active @endif ">{{getTranslateByKey('all_modules')}}</a>
                                <a href="{{url("admin/module?type=system")}}"
                                   class="btn btn-sm btn-light mr-3 @if($_GET['type']=='system') btn-primary active @endif  ">{{getTranslateByKey('built_in_modules')}}</a>
                                <a href="{{url("admin/module?type=function")}}"
                                   class="btn btn-sm btn-light mr-3 @if($_GET['type']=="function") btn-primary active @endif  ">{{getTranslateByKey('functional_modules')}}</a>
                            </div>
                        </div>
                        <div class="row mb-3 pb-3">
                            <div class="col-md-12 form-inline">
                                <div class="form-group">
                                    <label for="input-email" class="sr-only">{{getTranslateByKey('module_name')}}</label>
                                    <input  type="text" name="keyword" value="{{$_GET['keyword']}}" placeholder="{{getTranslateByKey('input_name_keyword')}}" class="form-control">
                                    <input type="hidden" name="type" value="{{$_GET['type']}}">
                                </div>
                                <button type="submit" class="btn btn-primary margin-l-5 mx-sm-3">{{getTranslateByKey('common_search')}}</button>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        @if($modules_install_datas)
                            @foreach($modules_install_datas as $modules_install_data)
                                <div class="col-lg-2 col-md-3 col-sm-4 mb-4" style="min-width: 260px">
                                    <div class="box">
                                        <div class="">
                                            <div class="package-head">
                                                <div class="package-head-title">
                                                    <h5>{{$modules_install_data['name']}}</h5>
                                                    @if($modules_install_data['status'] == 0)
                                                        <span class="package-head-status text-danger">{{getTranslateByKey('status_disabled')}}</span>
                                                    @else
                                                        <span class="package-head-status text-success">{{getTranslateByKey('status_enabled')}}</span>
                                                    @endif
                                                </div>
                                                <div class="package-head-meta">
                                                    <p class="text-secondary small">
                                                        <span>{{$modules_install_data['author']}}</span>
                                                        <span>V{{$modules_install_data['version']}}</span>
                                                        <span>{{$modules_install_data['package_type_label']}}</span>
                                                    </p>
                                                    <div class="mb-2">
                                                        <span class="package-meta">{{$modules_install_data['level_label']}}</span>
                                                        @if($modules_install_data['compatibility_summary'])
                                                            <span class="package-meta">{{$modules_install_data['compatibility_summary']}}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="clearfix scroll-container" style="height: 80px;overflow: auto">
                                                <p class="text-secondary">
                                                    {{$modules_install_data['description']}}
                                                </p>
                                            </div>
                                            @if($modules_install_data['dependency_names'])
                                                <div class="package-tip mb-2">
                                                    {{getTranslateByKey('dependency_label')}}：{{implode('、', $modules_install_data['dependency_names'])}}
                                                </div>
                                            @endif
                                            @if($modules_install_data['dependents'])
                                                <div class="package-tip mb-2 text-danger">
                                                    {{getTranslateByKey('dependent_label')}}：{{implode('、', $modules_install_data['dependents'])}}
                                                </div>
                                            @endif

                                            <div class="package-actions">
                                                <div class="d-inline-block">
                                                    <a href="{{url('admin/entryModule?m='.$modules_install_data['identification'])}}"
                                                       target="_blank">
                                                        <span class="fa fa-cog"></span> {{getTranslateByKey('enter_management')}}
                                                    </a>
                                                </div>
                                                @if(intval($modules_install_data['order'] ?? 0) >= 9999)
                                                    <div class="d-inline-block">
                                                        <div class="text-danger cursor" onclick="changeTopQuestion(0,'{{url('admin/module/changeTop?m='.$modules_install_data['identification'].'&top=0')}}')">
                                                            <span class="fa fa-thumb-tack"></span>
                                                            取消置顶
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="d-inline-block">
                                                        <div class="cursor text-primary" onclick="changeTopQuestion(1,'{{url('admin/module/changeTop?m='.$modules_install_data['identification'].'&top=1')}}')">
                                                            <span class="fa fa-thumb-tack"></span>
                                                            置顶
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="d-inline-block module-update-{{$modules_install_data['identification']}} text-danger"
                                                     style="cursor: pointer;visibility: hidden;"
                                                     onclick="updateVersion('{{$modules_install_data['identification']}}','module','{{session()->get('versionLimit')["module_".$modules_install_data["identification"]]}}')">
                                                    {{getTranslateByKey('update_version')}}
                                                </div>
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
                                                            <div class="text-danger cursor" onclick="changeStatusHomePage(0,'{{url('admin/module/changeIndex?m='.$modules_install_data['identification'].'&is_index=0')}}')">
                                                                <span class="fa fa-home"></span>
                                                                {{getTranslateByKey('unset_as_front_desk_home_page')}}
                                                            </div>
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="d-inline-block text-secondary">
                                                        <span class="fa fa-home"></span>
                                                        {{getTranslateByKey('cannot_set_front_home')}}
                                                    </div>
                                                @endif

                                                @if($modules_install_data['is_backend'] == 0)
                                                    <div class="d-inline-block">
                                                        <div class="cursor text-primary" onclick="changeStatusBackPage(1,'{{url('admin/module/changeBack?m='.$modules_install_data['identification'].'&is_backend=1')}}')">
                                                            <span class="fa fa-flag"></span>
                                                            {{getTranslateByKey('set_backend_entry')}}
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="d-inline-block">
                                                        <div class="text-danger cursor" onclick="changeStatusBackPage(0,'{{url('admin/module/changeBack?m='.$modules_install_data['identification'].'&is_backend=0')}}')">
                                                            <span class="fa fa-flag"></span>
                                                            {{getTranslateByKey('unset_backend_entry')}}
                                                        </div>
                                                    </div>
                                                @endif

                                                @if($modules_install_data['type'] == "function")
                                                    @if($modules_install_data['uninstall_allowed'])
                                                        <div class="d-inline-block text-secondary cursor"
                                                             onclick="uninstallQuestion('{{url('admin/module/uninstall?m='.$modules_install_data['identification'].'&cloud_type='.\Modules\Main\Models\Modules::Module)}}')">
                                                            <span class=" fa fa-trash-o"></span> {{getTranslateByKey('common_uninstall')}}
                                                        </div>
                                                    @else
                                                        <div class="d-inline-block action-disabled"
                                                             title="{{$modules_install_data['uninstall_reason']}}">
                                                            <span class=" fa fa-trash-o"></span> {{getTranslateByKey('common_uninstall')}}
                                                        </div>
                                                    @endif
                                                    @if($modules_install_data['status'] == 0)
                                                        <div class="d-inline-block cursor"
                                                             onclick="changeStatusQuestion(1, '{{url('admin/module/changeStatus?m='.$modules_install_data['identification'].'&status=1&cloud_type='.\Modules\Main\Models\Modules::Module)}}')">
                                                            <span class="fa fa-circle-o-notch"></span>
                                                            {{getTranslateByKey('common_enable')}}
                                                        </div>
                                                    @else
                                                        @if($modules_install_data['disable_allowed'])
                                                            <div class="d-inline-block text-danger cursor"
                                                                 onclick="changeStatusQuestion(0, '{{url('admin/module/changeStatus?m='.$modules_install_data['identification'].'&status=0&cloud_type='.\Modules\Main\Models\Modules::Module)}}')">
                                                                <span class="fa fa-ban"></span>
                                                                {{getTranslateByKey('common_disable')}}
                                                            </div>
                                                        @else
                                                            <div class="d-inline-block action-disabled"
                                                                 title="{{$modules_install_data['disable_reason']}}">
                                                                <span class="fa fa-ban"></span>
                                                                {{getTranslateByKey('common_disable')}}
                                                            </div>
                                                        @endif
                                                    @endif
                                                @endif
                                            </div>
                                            @if($modules_install_data['disable_reason'])
                                                <div class="package-tip mt-2 text-danger">
                                                    {{getTranslateByKey('disable_limit')}}：{{$modules_install_data['disable_reason']}}
                                                </div>
                                            @endif
                                            @if($modules_install_data['uninstall_reason'])
                                                <div class="package-tip mt-1 text-danger">
                                                    {{getTranslateByKey('uninstall_limit')}}：{{$modules_install_data['uninstall_reason']}}
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
                                <div>{{getTranslateByKey('no_installed_function_modules')}}，<a href="{{url("admin/cloud?cloud_type=module")}}">{{getTranslateByKey('go_install')}}</a></div>
                            </div>
                        @endif


                    </div>

                </div>

                @if($modules_not_install_datas)
                    <div class="card-header card-default">
                        <div class="row">
                            <div class="col-md-6">
                                {{-- Uninstalled modules --}}
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
                                            <div class="package-head">
                                                <div class="package-head-title">
                                                    <h5>{{$modules_not_install_data['name']}}</h5>
                                                </div>
                                                <div class="package-head-meta">
                                                    <p class="text-secondary small">
                                                        <span>{{$modules_not_install_data['author']}}</span>
                                                        <span>V{{$modules_not_install_data['version']}}</span>
                                                    </p>
                                                    <div class="mb-2">
                                                        <span class="package-meta">{{$modules_not_install_data['level_label']}}</span>
                                                        @if($modules_not_install_data['compatibility_summary'])
                                                            <span class="package-meta">{{$modules_not_install_data['compatibility_summary']}}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="">
                                                @if($modules_not_install_data['dependency_names'])
                                                    <div class="package-tip mt-2">
                                                        {{getTranslateByKey('dependency_label')}}：{{implode('、', $modules_not_install_data['dependency_names'])}}
                                                    </div>
                                                @endif
                                                <div class="package-actions">
                                                        <span class="glyphicon glyphicon-cog">
                                                               @if($modules_not_install_data['install_allowed'])
                                                                   <a href="{{url('admin/module/install?m='
                                                                        .$modules_not_install_data['identification']
                                                                        .'&form=local'
                                                                        .'&cloud_type='.\Modules\Main\Models\Modules::Module)}}"
                                                                      class="">
                                                                       {{getTranslateByKey('install')}}
                                                                   </a>
                                                               @else
                                                                   <span class="action-disabled"
                                                                         title="{{$modules_not_install_data['install_reason']}}">
                                                                       {{getTranslateByKey('install')}}
                                                                   </span>
                                                               @endif
                                                        </span>

                                                        <span class="glyphicon glyphicon-cog ">
                                                               <span onclick="DelQuestion('{{url('admin/module/delete?m='
                                                                    .$modules_not_install_data['identification']
                                                                    .'&form=local'
                                                                    .'&cloud_type='.\Modules\Main\Models\Modules::Module)}}')"
                                                                  class="text-danger" style="cursor: pointer;">
                                                                   {{getTranslateByKey('common_delete')}}
                                                               </span>
                                                        </span>
                                                </div>
                                                @if($modules_not_install_data['install_reason'])
                                                    <div class="package-tip mt-2 text-danger">
                                                        {{getTranslateByKey('install_limit')}}：{{$modules_not_install_data['install_reason']}}
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



@include('admin.public.js',['load'=> ["custom"]])


<script type="text/javascript">
    function changeStatusBackPage(status, url) {
        $.confirm({
            title: '<?php echo e(getTranslateByKey("common_tip")); ?>',
            content: status == 1 ? '{{getTranslateByKey("set_default_backend_entry_confirm")}}' : '{{getTranslateByKey("unset_default_backend_entry_confirm")}}',
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

    function changeTopQuestion(status, url) {
        $.confirm({
            title: '{{getTranslateByKey("common_tip")}}',
            content: status == 1 ? '确定置顶该模块吗？' : '确定取消置顶该模块吗？',
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
            content: '{{getTranslateByKey("common_delete_confirm")}}',
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
