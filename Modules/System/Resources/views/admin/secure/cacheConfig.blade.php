@include(moduleAdminTemplate($moduleName)."public.header")
<style>
    .fileinput-preview {
        border: 1px #ccc solid;
        margin-bottom: .2rem;
    }

    legend {
        font-size: 18px;
    }
</style>
<body>

<!--                        Topbar End                              -->
<!-- ============================================================== -->


<!-- ============================================================== -->
<!-- 						Navigation Start 						-->
<!-- ============================================================== -->

@include(moduleAdminTemplate($moduleName)."public.nav")
<!-- ============================================================== -->
<!-- 						Navigation End	 						-->
<!-- ============================================================== -->

<!-- Page container -->
<div class="page-container">

    <!-- Page content -->
    <div class="page-content">


    @include(moduleAdminTemplate($moduleName)."public.left")


    <!-- Main content -->
        <div class="content-wrapper">

            <!-- Page header -->
            <div class="page-header">
                @include(moduleAdminTemplate($moduleName)."public.page",
         ['breadcrumb'=>[$pageData['title'],$pageData['subtitle']]])
            </div>
            <!-- /page header -->


            <!-- Content area -->
            <div class="content" style="margin-top: 1rem;">


                <div class="panel panel-flat">
                    <div class="panel-heading">

                        <form id="myForm" class="form-horizontal" method="post" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <fieldset class="content-group">
                                <legend class="text-bold mt-20"></legend>
                                <div class="form-group ">
                                    <label class="control-label col-lg-1">缓存前缀</label>
                                    <div class="col-lg-11">
                                        <input type="text" name="CACHE_PREFIX" value="{{env('CACHE_PREFIX')}}"
                                               class="form-control">
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label class="control-label col-lg-1">缓存方式</label>
                                    <div class="col-lg-11">
                                        <select name="CACHE_DRIVER" class="select-search">
                                            <option value="file" @if(env('CACHE_DRIVER')=='file') selected @endif>File
                                            </option>
                                            <option value="redis" @if(env('CACHE_DRIVER')=='redis') selected @endif>
                                                Redis
                                            </option>
                                            <option value="memcached"
                                                    @if(env('CACHE_DRIVER')=='memcached') selected @endif>Memcached
                                            </option>
                                            <option value="database"
                                                    @if(env('CACHE_DRIVER')=='database') selected @endif>Database
                                            </option>
                                            <option value="apc" @if(env('CACHE_DRIVER')=='apc') selected @endif>Apc
                                            </option>
                                            <option value="array" @if(env('CACHE_DRIVER')=='array') selected @endif>
                                                Array
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                {{--<div class="h-clear-both"></div>--}}
                                {{--<br>--}}

                                <legend class="text-bold mt-20" style="text-transform: none;">Redis设置</legend>
                                <div class="form-group ">
                                    <label class="control-label col-lg-1">Redis 主机</label>
                                    <div class="col-lg-11">
                                        <input type="text" name="REDIS_HOST" value="{{env('REDIS_HOST')}}"
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label class="control-label col-lg-1">Redis 密码</label>
                                    <div class="col-lg-11">
                                        <input type="text" name="REDIS_PASSWORD" value="{{env('REDIS_PASSWORD')}}"
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label class="control-label col-lg-1">REDIS 端口</label>
                                    <div class="col-lg-11">
                                        <input type="text" name="REDIS_PORT" value="{{env('REDIS_PORT')}}"
                                               class="form-control">
                                    </div>
                                </div>

                                <legend class="text-bold mt-20" style="text-transform: none;">Memcache设置</legend>


                                <div class="form-group ">
                                    <label class="control-label col-lg-1">Memcache 主机</label>
                                    <div class="col-lg-11">
                                        <input type="text" name="MEMCACHED_HOST" value="{{env('MEMCACHED_HOST')}}"
                                               class="form-control">
                                    </div>
                                </div>


                                <div class="form-group ">
                                    <label class="control-label col-lg-1">Memcached 用户名</label>
                                    <div class="col-lg-11">
                                        <input type="text" name="MEMCACHED_USERNAME"
                                               value="{{env('MEMCACHED_USERNAME')}}" class="form-control">
                                    </div>
                                </div>


                                <div class="form-group ">
                                    <label class="control-label col-lg-1">Memcache 密码</label>
                                    <div class="col-lg-11">
                                        <input type="text" name="MEMCACHED_PASSWORD"
                                               value="{{env('MEMCACHED_PASSWORD')}}" class="form-control">
                                    </div>
                                </div>


                                <div class="form-group ">
                                    <label class="control-label col-lg-1">Memcache 端口</label>
                                    <div class="col-lg-11">
                                        <input type="text" name="MEMCACHED_PORT" value="{{env('MEMCACHED_PORT')}}"
                                               class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <input type="hidden" name="form" value="cache">
                                    <label class="col-lg-1 control-label"></label>
                                    <div class="col-lg-11">
                                        <button type="button" class="btn btn-sm btn-info h-sub">
                                            提交
                                        </button>
                                    </div>
                                </div>
                            </fieldset>

                        </form>
                    </div>
                </div>

                <!-- Footer -->
            @include(moduleAdminTemplate($moduleName)."public.footer")
            <!-- /footer -->

            </div>
            <!-- /content area -->

        </div>
        <!-- /main content -->

    </div>
    <!-- /page content -->

</div>

<!-- 						Content End		 						-->
<!-- ============================================================== -->
@include(moduleAdminTemplate($moduleName)."public.js")
<script>
    $('.h-sub').click(function () {
        layer.load(1);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            "method": "post",
            "url": "{{moduleAdminJump($moduleName,'secure/toolSubmit')}}",
            "data": new FormData($('#myForm')[0]),
            "dataType": 'json',
            "cache": false,
            "processData": false,
            "contentType": false,
            "success": function (res) {
                layer.closeAll();
                if (res.status == 200) {
                    layer.msg(res.msg, {icon: 1, time: 1000}, function () {
                        window.location.reload();
                    });
                } else {
                    layer.msg(res.msg, {icon: 2})
                }
            },
            "error": function (res) {
                layer.closeAll();
                layer.msg("系统错误，请稍后重试", {icon: 5})
            }
        });
    });
</script>
<script type="text/javascript"
        src="{{moduleAdminResource($moduleName)}}/js/plugins/forms/selects/select2.min.js"></script>
<script type="text/javascript" src="{{moduleAdminResource($moduleName)}}/js/pages/form_select2.js"></script>
</body>
</html>
