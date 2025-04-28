@include(moduleAdminTemplate($moduleName)."public.header")
<style>
    .fileinput-preview {
        border: 1px #ccc solid;
        margin-bottom: .2rem;
    }

    .h-word-deal {
        position: relative;
        top: 10px;
    }

    legend {
        font-size: 18px;
    }
</style>
<body>

@include(moduleAdminTemplate($moduleName)."public.nav")

<div class="page-container">
    <!-- Page content -->
    <div class="page-content">

    @include(moduleAdminTemplate($moduleName)."public.left")

        <div class="content-wrapper">

            <div class="page-header">
                @include(moduleAdminTemplate($moduleName)."public.page", ['breadcrumb'=>[$pageData['title'],$pageData['subtitle']]])
            </div>

            <div class="content" style="margin-top: 1rem;">

                <div class="panel panel-flat">
                    <div class="panel-heading">

                        <form id="myForm" class="form-horizontal" method="post" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <fieldset class="content-group">
                                <legend class="text-bold mt-20">常规性设置</legend>
                                <div class="form-group ">
                                    <label class="control-label col-lg-1">cookie 名字</label>
                                    <div class="col-lg-11">
                                        <input type="text" name="COOKIE_NAME" value="{{env('COOKIE_NAME')}}"
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label class="control-label col-lg-1">session 域名</label>
                                    <div class="col-lg-11">
                                        <input type="text" name="SESSION_DOMAIN" value="{{env('SESSION_DOMAIN')}}"
                                               class="form-control">
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label class="control-label col-lg-1">session 驱动</label>
                                    <div class="col-lg-11">
                                        <select name="SESSION_DRIVER" class="select-search">
                                            <option value="file" @if(env('SESSION_DRIVER')=='file') selected @endif>
                                                File
                                            </option>
                                            <option value="redis" @if(env('SESSION_DRIVER')=='redis') selected @endif>
                                                Redis
                                            </option>
                                            <option value="memcached"
                                                    @if(env('SESSION_DRIVER')=='memcached') selected @endif>Memcached
                                            </option>
                                            <option value="database"
                                                    @if(env('SESSION_DRIVER')=='database') selected @endif>Database
                                            </option>
                                            <option value="apc" @if(env('SESSION_DRIVER')=='apc') selected @endif>Apc
                                            </option>
                                            <option value="array" @if(env('SESSION_DRIVER')=='array') selected @endif>
                                                Array
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label class="control-label col-lg-1">session 时长</label>
                                    <div class="col-lg-10">
                                        <input type="number" name="SESSION_LIFETIME" value="{{env('SESSION_LIFETIME')}}"
                                               class="form-control">
                                    </div>
                                    <div class="col-lg-1 h-word-deal">
                                        分钟
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-lg-1">session 加密</label>
                                    <div class="col-lg-11">

                                        <label class="radio-inline">
                                            <input type="radio" name="SESSION_ENCRYPT" class="styled h-radio"
                                                   value="false"
                                                   @if(env('SESSION_ENCRYPT')==false) checked @endif>
                                            <span class="h-span-val">不加密</span>
                                        </label>

                                        <label class="radio-inline">
                                            <input type="radio" name="SESSION_ENCRYPT" class="styled h-radio"
                                                   value="true"
                                                   @if(env('SESSION_ENCRYPT')==true) checked @endif>
                                            <span class="h-span-val">加密</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label class="control-label col-lg-1">账号锁定</label>
                                    <div class="col-lg-1 h-word-deal">连续登录失败</div>
                                    <div class="col-lg-1">
                                        <input type="number" name="limit_count"
                                               value="{{cacheGlobalSettingsByKey('limit_count')}}"
                                               class="form-control">
                                    </div>
                                    <div class="col-lg-1 h-word-deal">次，</div>
                                    <div class="col-lg-1 h-word-deal">锁定账号</div>
                                    <div class="col-lg-1">
                                        <input type="number" name="limit_time"
                                               value="{{cacheGlobalSettingsByKey('limit_time')}}"
                                               class="form-control">
                                    </div>
                                    <div class="col-lg-1 h-word-deal">分钟</div>
                                </div>

                                <div class="form-group ">
                                    <label class="control-label col-lg-1">
                                        敏感字符过滤<br>
                                        (用 | 隔开)
                                    </label>
                                    <div class="col-lg-11">
                                        <textarea placeholder="admin" name="filter_strings" class="form-control"
                                                  rows="4">{{cacheGlobalSettingsByKey('filter_strings')}}</textarea>
                                    </div>
                                </div>


                                <div class="form-group ">
                                    <label class="control-label col-lg-1">
                                        IP黑名单<br>
                                        (用 , 隔开)
                                    </label>
                                    <div class="col-lg-11">
                                        <textarea placeholder="127.0.0.1" name="blacklist_ip" class="form-control"
                                                  rows="4">{{cacheGlobalSettingsByKey('blacklist_ip')}}</textarea>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label class="control-label col-lg-1">后台登录入口</label>
                                    <div class="col-lg-1 h-word-deal">
                                        /admin/login/
                                    </div>
                                    <div class="col-lg-3">
                                        <input type="text" name="admin_login_entrance"
                                               value="{{cacheGlobalSettingsByKey('admin_login_entrance')}}"
                                               class="form-control" placeholder="字母，数字">
                                    </div>
                                    <div class="col-lg-6 h-word-deal">
                                        后台登录入口，设置后只能通过指定安全入口登录，如:
                                        http://xx.com/admin/login/{{cacheGlobalSettingsByKey('admin_login_entrance')}}
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label class="control-label col-lg-1">密码加密KEY</label>
                                    <div class="col-lg-11">
                                        <input type="text" name="password_key" value="{{cacheGlobalSettingsByKey('password_key')}}"
                                               class="form-control">
                                        <span class="help-block">加密规则：md5(key+md5(password))</span>
                                    </div>
                                </div>


                                {{--<div class="h-clear-both"></div>--}}
                                {{--<br>--}}

                                <legend class="text-bold mt-20" style="text-transform: none;">验证码</legend>
                                <div class="form-group">
                                    <label class="control-label col-lg-1">后台登录验证码</label>
                                    <div class="col-lg-11">

                                        <label class="radio-inline">
                                            <input type="radio" name="admin_login_code" class="styled h-radio"
                                                   value="1"
                                                   @if(cacheGlobalSettingsByKey('admin_login_code')==1) checked @endif>
                                            <span class="h-span-val">开启</span>
                                        </label>

                                        <label class="radio-inline">
                                            <input type="radio" name="admin_login_code" class="styled h-radio"
                                                   value="0"
                                                   @if(cacheGlobalSettingsByKey('admin_login_code')==0) checked @endif>
                                            <span class="h-span-val">关闭</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-lg-1">前台提交验证码</label>
                                    <div class="col-lg-11">

                                        <label class="radio-inline">
                                            <input type="radio" name="home_submit_code" class="styled h-radio"
                                                   value="1"
                                                   @if(cacheGlobalSettingsByKey('home_submit_code')==1) checked @endif>
                                            <span class="h-span-val">开启</span>
                                        </label>

                                        <label class="radio-inline">
                                            <input type="radio" name="home_submit_code" class="styled h-radio"
                                                   value="0"
                                                   @if(cacheGlobalSettingsByKey('home_submit_code')==0) checked @endif>
                                            <span class="h-span-val">关闭</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <input type="hidden" name="form" value="safe">
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
        src="{{asset("assets/module")}}/js/plugins/forms/selects/select2.min.js"></script>
<script type="text/javascript" src="{{asset("assets/module")}}/js/pages/form_select2.js"></script>
</body>
</html>
