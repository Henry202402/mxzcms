@include("admin.public.header")

<body class="horizontal">

<!-- ============================================================== -->
<!--                        Topbar Start                            -->
<!-- ============================================================== -->
@include("admin.public.topbar")
<!-- ============================================================== -->
<!--                        Topbar End                              -->
<!-- ============================================================== -->

<!-- ============================================================== -->
<!--                        Navigation Start                        -->
<!-- ============================================================== -->

@include("admin.public.nav")

<!-- ============================================================== -->
<!--                        Navigation End                          -->
<!-- ============================================================== -->


<!-- ============================================================== -->
<!--                        Content Start                           -->
<!-- ============================================================== -->
<div class="row page-header">
    <div class="col-lg-6 align-self-center ">

    </div>
</div>
<section class="main-content">
    <div class="row w-no-padding margin-b-30">
        <div class="col-md-3">
            <div class="widget  bg-light">
                <div class="row row-table ">
                    <div class="margin-b-30">
                        <h2 class="margin-b-5">{{getTranslateByKey('total_number_of_members')}}</h2>
                        <p class="text-muted">{{getTranslateByKey('total_number_of_members2')}}</p>
                        <span class="float-right text-primary widget-r-m">{{$total_member}}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="widget  bg-light">
                <div class="row row-table ">
                    <div class="margin-b-30">
                        <h2 class="margin-b-5">模块总数</h2>
                        <p class="text-muted">已安装</p>
                        <span class="float-right text-indigo widget-r-m">{{$total_modules_count}}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="widget  bg-light">
                <div class="row row-table ">
                    <div class="margin-b-30">
                        <h2 class="margin-b-5">插件总数</h2>
                        <p class="text-muted">已安装</p>
                        <span class="float-right text-success widget-r-m">{{$total_plugin_count}}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="widget  bg-light">
                <div class="row row-table ">
                    <div class="margin-b-30">
                        <h2 class="margin-b-5">主题总数</h2>
                        <p class="text-muted">已安装</p>
                        <span class="float-right text-warning widget-r-m">{{$total_theme_count}}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <table class="table">
                        <thead>
                        <tr>
                            <th colspan="7"><h4>{{getTranslateByKey("index_system_info")}}</h4></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td align="right" colspan="2" >{{getTranslateByKey("index_system_name")}}：</td>
                            <td align="left" >{{getenv("APP_NAME")}}</td>
                            <td align="right" >{{getTranslateByKey("index_system_version")}}：</td>
                            <td align="left" >{{config("app.app_version")}}
                                &nbsp;&nbsp;&nbsp;
                                {!! hook("CmsUpdateVersion",['version'=>env("APP_VERSION"),"moduleName"=>"System"])[0] !!}
                            </td>
                            <td align="right" >{{getTranslateByKey("index_system_language_framework")}}：</td>
                            <td align="left" >Laravel Framework {{app()::VERSION}}</td>
                        </tr>

                        <tr>
                            <td align="right" colspan="2">{{getTranslateByKey("index_system_time")}}：</td>
                            <td align="left" >{{date("Y-m-d H:i:s")}}</td>
                            <td align="right" >{{getTranslateByKey("index_system_server_os")}}：</td>
                            <td align="left" >{{PHP_OS}}</td>
                            <td align="right" >{{getTranslateByKey("index_mysql_version")}}：</td>
                            <td align="left" >{{$version}}</td>
                        </tr>

                        <tr>
                            <td align="right" colspan="2">{{getTranslateByKey("index_php_version")}}：</td>
                            <td align="left" >{{PHP_VERSION}}</td>
                            <td align="right" >{{getTranslateByKey("index_gd_version")}}：</td>
                            <td align="left" >{{$gdinfo}}</td>
                            <td align="right" >FreeType：</td>
                            <td align="left" >{{$freetype}}</td>
                        </tr>

                        <tr>
                            <td align="right" colspan="2">{{getTranslateByKey("index_allow_curl")}}：</td>
                            <td align="left" >{{$allowurl}}</td>
                            <td align="right" >{{getTranslateByKey("index_max_upload_limit")}}：</td>
                            <td align="left" >{{$max_upload}}</td>
                            <td align="right" >{{getTranslateByKey("index_max_run_time")}}：</td>
                            <td align="left" >{{$max_ex_time}}</td>
                        </tr>

                        <tr>
                            <td align="right" colspan="2">{{getTranslateByKey("index_max_run_memory")}}：</td>
                            <td align="left" >{{$memory_limit}}</td>
                            <td align="right" >ZIP扩展：</td>
                            <td align="left" >{{$zip}}</td>
                            <td align="right" >Composer：</td>
                            <td align="left" >{{$composer}}</td>
                        </tr>
                        <tr>
                            <td align="right" colspan="2">其他参考：</td>
                            <td colspan="5" align="left"  style="max-width: 100px;white-space: normal;word-wrap: break-word;overflow-wrap: break-word;" >
                                需要安装的扩展：
                                fileinfo,
                                mbstring,
                                openssl,
                                pdo_mysql,
                                tokenizer,
                                xml,
                                xmlwriter,
                                zip,
                                exif,
                                imagick,
                                redis,
                                json,
                                curl
                                <br/>
                                需要启用的函数：
                                fopen
                                mkdir
                                rmdir
                                unlink
                                copy
                                exec
                                passthru
                                shell_exec
                                system
                                popen
                                proc_open
                                pcntl_signal
                                <br />
                                已安装扩展：{!! $loadedExtensions !!}
                                <br />
                                被禁用函数：{!! $disableFunctions !!}
                            </td>
                        </tr>




                        </tbody>
                    </table>


                </div>
            </div>
        </div>
    </div>

    <div class="row" id="" >
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <table class="table">
                        <thead>
                        <tr>
                            <th colspan="7"><h4>第三方SDK清单</h4></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td align="left" id="sdks" colspan="7" style="max-width: 100px;white-space: normal;word-wrap: break-word;overflow-wrap: break-word;">

                            </td>
                        </tr>

                        </tbody>
                    </table>


                </div>
            </div>
        </div>
    </div>

{{--    <div class="row">--}}
{{--        <div class="col-md-12">--}}
{{--            <div class="card">--}}
{{--                <div class="card-body">--}}

{{--                    <table class="table">--}}
{{--                        <thead>--}}
{{--                        <tr>--}}
{{--                            <th colspan="7"><h4>{{getTranslateByKey("index_system_team")}}</h4></th>--}}
{{--                        </tr>--}}
{{--                        </thead>--}}
{{--                        <tbody>--}}
{{--                        <tr>--}}
{{--                            <td align="center" colspan="2">{{getTranslateByKey("technical_director")}}：</td>--}}
{{--                            <td align="center" >Henry Huang</td>--}}
{{--                            <td align="center" >{{getTranslateByKey("project_leader")}}：</td>--}}
{{--                            <td align="center" >Henry Huang</td>--}}
{{--                            <td align="center" >{{getTranslateByKey("group_leader")}}：</td>--}}
{{--                            <td align="center" >Mr He</td>--}}
{{--                        </tr>--}}

{{--                        <tr>--}}
{{--                            <td align="center" colspan="2">{{getTranslateByKey("corporate_name")}}：</td>--}}
{{--                            <td align="center" >有限公司</td>--}}
{{--                            <td align="center" >{{getTranslateByKey("cms_official_website")}}：</td>--}}
{{--                            <td align="center" ><a href="http://www.2023-cms.cn">www.2023-cms.cn</a></td>--}}
{{--                            <td align="center" >{{getTranslateByKey("company_official_website")}}：</td>--}}
{{--                            <td align="center" ><a href="http://www.2023-cms.cn">www.2023-cms.cn</a></td>--}}
{{--                        </tr>--}}


{{--                        </tbody>--}}
{{--                    </table>--}}


{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}




    @include('admin.public.footer')




</section>
<!-- ============================================================== -->
<!--                        Content End                             -->
<!-- ============================================================== -->


<!-- Common Plugins -->
@include('admin.public.js',['load'=> ["custom"]])
<script>
    getsdks();
</script>
</body>
</html>
