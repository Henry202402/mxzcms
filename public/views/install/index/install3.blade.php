<!doctype html>
<html>
<head>
    @include("install.head")
</head>
<body>

@include("install.header")

<section class="container pb-5">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="step mt-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><em>1</em> 检测环境</li>
                        <li class="breadcrumb-item active"><em>2</em> 创建数据</li>
                        <li class="breadcrumb-item" aria-current="page"><em>3</em> 完成安装</li>
                    </ol>
                </nav>
            </div>
            <form id="js-install-form" action="{{url('/install?install=4')}}" method="post">
                {{csrf_field()}}
                <input type="hidden" name="force" value="0"/>
                <div class="server">
                    <table class="table small">
                        <tr>
                            <td class="td1" colspan="3" >数据库信息</td>
                        </tr>
                        <tr>
                            <td class="text-left">数据库服务器：</td>
                            <td colspan="2">
                                <input type="text" name="dbhost" id="dbhost" value="{{env('DB_HOST')}}" class="form-control col-md-6 col-sm-9">
                                <div id="js-install-tip-dbhost">
                                    <span class="text-muted">数据库服务器地址，一般为127.0.0.1或localhost</span>
                                </div>
                            </td>

                        </tr>
                        <tr>
                            <td class="text-left">数据库用户名：</td>
                            <td colspan="2">
                                <input type="text" name="dbuser" id="dbuser" value="{{env('DB_USERNAME')}}" class="form-control col-md-6 col-sm-9">
                                <div id="js-install-tip-dbuser" class="text-muted"></div>
                            </td>

                        </tr>
                        <tr>
                            <td class="text-left">数据库密码：</td>
                            <td colspan="2">
                                <input type="text" name="dbpw" id="dbpw" value="{{env('DB_PASSWORD')}}" class="form-control col-md-6 col-sm-9" autoComplete="off"
                                       onblur="checkDbPwd()">
                                <div id="js-install-tip-dbpw" class="text-muted"></div>
                            </td>

                        </tr>
                        <tr>
                            <td class="text-left">数据库名：</td>
                            <td colspan="2">
                                <input type="text" name="dbname" id="dbname" value="{{env('DB_DATABASE')}}" class="form-control col-md-6 col-sm-9"
                                       onblur="checkDbPwd()">
                                <div id="js-install-tip-dbname">
                                    <span class="text-muted">最好小写字母</span>
                                </div>
                            </td>

                        </tr>
                        <tr>
                            <td class="text-left">数据库表前缀：</td>
                            <td colspan="2"><input type="text" name="dbprefix" id="dbprefix" placeholder="{{env('DB_PREFIX')}}" value="mxz_"
                                       class="form-control col-md-6 col-sm-9">
                                <div id="js-install-tip-dbprefix">
                                    <span class="text-muted">建议使用默认，同一数据库安装多个{{$cms_name}}时需修改</span>
                                </div>
                            </td>

                        </tr>
                        <tr>
                            <td class="text-left">数据库编码：</td>
                            <td colspan="2">
                                <select type="text" name="dbcharset" id="dbcharset" value="" class="form-control col-md-6 col-sm-9">
                                    <option value="utf8mb4">utf8mb4</option>
                                    <option value="utf8">utf8</option>
                                </select>
                                <div id="js-install-tip-dbcharset">
                                    <span class="text-muted">如果您的服务器是虚拟空间不支持uft8mb4,请选择 utf8</span>
                                </div>
                            </td>

                        </tr>

                        <tr>
                            <td class="td1" colspan="3">网站配置</td>
                        </tr>
                        <tr>
                            <td class="text-left">网站名称：</td>
                            <td colspan="2">
                                <input type="text" name="website_name" value="{{$cms_name}}" class="form-control col-md-6 col-sm-9">
                                <div id="js-install-tip-website_name" class="text-muted"></div>
                            </td>

                        </tr>
                        <tr>
                            <td class="text-left">网站关键词：</td>
                            <td colspan="2">
                                <input type="text" name="website_keys" value="{{$cms_name}},免费cms,开源cms"
                                       class="form-control col-md-6 col-sm-9" autoComplete="off">
                                <div id="js-install-tip-website_keys" class="text-muted"></div>
                            </td>

                        </tr>
                        <tr>
                            <td class="text-left">网站描述：</td>
                            <td colspan="2">
                                <textarea class="form-control col-md-6 col-sm-9 "  rows="10" name="website_desc">{{$cms_name}}是基于PHP laravel框架的内容管理系统，采用低耦合、模块化设计思想，适用各行各业使用。感谢广大企业、个人、开发者的支持。</textarea>
                                <div id="js-install-tip-website_desc" class="text-muted"></div>
                            </td>

                        </tr>

                        <tr>
                            <td class="td1" colspan="3">创始人信息</td>
                        </tr>
                        <tr>
                            <td class="text-left">管理员帐号：</td>
                            <td colspan="2">
                                <input type="text" name="manager" value="admin" class="form-control col-md-6 col-sm-9">
                                <div id="js-install-tip-manager" class="text-muted"></div>
                            </td>

                        </tr>
                        <tr>
                            <td class="text-left">登录密码：</td>
                            <td colspan="2">
                                <input type="password" name="manager_pwd" id="js-manager-pwd" class="form-control col-md-6 col-sm-9"
                                       autoComplete="off">
                                <div id="js-install-tip-manager_pwd " class="text-muted">
                                 <span class="text-muted">
                                    密码长度不低于6位,不高于32位。
                                 </span>
                                </div>
                            </td>

                        </tr>
                        <tr>
                            <td class="text-left">重复密码：</td>
                            <td colspan="2">
                                <input type="password" name="manager_ckpwd" class="form-control col-md-6 col-sm-9" autoComplete="off">
                                <div id="js-install-tip-manager_ckpwd" class="text-muted"></div>
                            </td>

                        </tr>
                        <tr>
                            <td class="text-left">Email：</td>
                            <td colspan="2">
                                <input type="text" name="manager_email" class="form-control col-md-6 col-sm-9" value="">
                                <div id="js-install-tip-manager_email" class="text-muted"></div>
                            </td>

                        </tr>
                    </table>
                    <div id="js-response-tips" style="display: none;"></div>
                </div>
                <div class="bottom text-center">
                    <a href="{{url('/install?install=2')}}" class="btn btn-primary">上一步</a>
                    <button type="button" class="btn btn-primary" onclick="saveDBInfo()">保存数据库信息</button>
                    <button type="submit" class="btn btn-primary">创建数据</button>
                </div>
            </form>

        </div>
    </div>
</section>


<div class="position-fixed bottom-0 right-0 p-3" style="z-index: 1111; right: 0; bottom: 0;">
    <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
        <div class="toast-header">
            <strong class="mr-auto">温馨提示</strong>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body"></div>
    </div>
</div>

@include("install.footer")

<script src="{{INSTALL_ASSET}}/assets/js/validate.js"></script>
<script src="{{INSTALL_ASSET}}/assets/js/ajaxForm.js"></script>
<script>
    function checkDbPwd() {
        var dbHost = $('#dbhost').val();
        var dbUser = $('#dbuser').val();
        var dbPwd = $('#dbpw').val();
        var dbName = $('#dbname').val();
        var dbPort = $('#dbport').val();
        data = {
            'hostname': dbHost,
            'username': dbUser,
            'password': dbPwd,
            'hostport': dbPort,
            'database': dbName,
        };
        var url = "{{url('install/checkDbPwd')}}";
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            type: "POST",
            url: url,
            dataType: 'JSON',
            data: data,
            beforeSend: function () {
            },
            success: function (data) {
                var tig = data.status == 200 ? 'tips-success' : 'tips-error';
                if (data.code) {

                } else {
                    //数据库链接配置失败
                    $('#js-install-tip-dbpw').html('<span for="dbname" generated="true" class="' + tig + '" style="">' + data.msg + '</span>');
                    $('.toast-body').html(data.msg);
                    $('.toast').toast('show')
                }
            },
            complete: function () {
            },
            error: function () {
                $('#js-install-tip-dbpw').html('<span for="dbname" generated="true" class="tips-error" style="">数据库链接配置失败</span>');
                $('#dbpw').val("");
                $('.toast-body').html('数据库链接配置失败');
                $('.toast').toast('show')
            }
        });
    }

    function saveDBInfo() {
        var dbHost = $('#dbhost').val();
        var dbUser = $('#dbuser').val();
        var dbPwd = $('#dbpw').val();
        var dbName = $('#dbname').val();
        var dbPort = $('#dbport').val();
        var dbprefix = $('#dbprefix').val();
        var dbcharset = $('#dbcharset').val();
        data = {
            'dbhost': dbHost,
            'dbuser': dbUser,
            'dbpw': dbPwd,
            'dbport': dbPort,
            'dbname': dbName,
            'dbprefix': dbprefix,
            'dbcharset': dbcharset,
        };
        var url = "{{url('install/saveDBInfo')}}";
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            type: "POST",
            url: url,
            dataType: 'JSON',
            data: data,
            beforeSend: function () {
            },
            success: function (data) {
                var tig = data.status == 200 ? 'tips-success' : 'tips-error';
                $('#js-install-tip-dbpw').html('<span for="dbname" generated="true" class="' + tig + '" style="">' + data.msg + '</span>');
                $('.toast-body').html(data.msg);
                $('.toast').toast('show')
            },
            complete: function () {
            },
            error: function () {
                $('#js-install-tip-dbpw').html('<span for="dbname" generated="true" class="tips-error" style="">' + data.msg + '</span>');
                $('.toast-body').html(data.msg);
                $('.toast').toast('show')
            }
        });
    }

    $(function () {
        //聚焦时默认提示
        var focus_tips = {
            dbhost: '数据库服务器地址，一般为127.0.0.1或localhost',
            dbport: '数据库服务器端口，一般为3306',
            dbuser: '',
            dbpw: '',
            dbname: '',
            dbprefix: '建议使用默认，同一数据库安装多个{{$cms_name}}时需修改',
            dbcharset: '如果您的服务器是虚拟空间不支持uft8mb4,请选择 utf8',
            manager: '创始人帐号，拥有站点后台所有管理权限',
            manager_pwd: '密码长度不低于6位,不高于32位',
            manager_ckpwd: '',
            sitename: '',
            siteurl: '请以“/”结尾',
            sitekeywords: '',
            siteinfo: '',
            manager_email: ''
        };

        var install_form = $("#js-install-form");

        //validate插件修改了remote ajax验证返回的response处理方式；增加密码强度提示 passwordRank
        install_form.validate({
            //debug : true,
            //onsubmit : false,
            errorPlacement: function (error, element) {
                //错误提示容器
                $('#js-install-tip-' + element[0].name).html(error);
                $('.toast-body').html(error);
                $('.toast').toast('show')
            },
            errorElement: 'span',
            //invalidHandler : , 未验证通过 回调
            //ignore : '.ignore' 忽略验证
            //onkeyup : true,
            errorClass: 'tips-error',
            validClass: 'tips-error',
            onkeyup: false,
            focusInvalid: false,
            rules: {
                dbhost: {required: true},
                dbport: {required: true},
                dbuser: {required: true},
                /* dbpw: {required  : true}, */
                dbname: {required: true},
                dbprefix: {required: true},
                manager: {required: true},
                manager_pwd: {required: true, minlength: 6, maxlength: 32},
                manager_ckpwd: {required: true, equalTo: '#js-manager-pwd'},
                manager_email: {required: true, email: true}
            },
            highlight: false,
            unhighlight: function (element, errorClass, validClass) {
                var tip_elem = $('#js-install-tip-' + element.name);
                tip_elem.html('<span class="' + validClass + '" data-text="text"><span>');
            },
            onfocusin: function (element) {
                // var name = element.name;
                // $('#js-install-tip-' + name).html('<span data-text="text">' + focus_tips[name] + '</span>');
                // $(element).parents('tr').addClass('current');
            },
            onfocusout: function (element) {
                // var _this = this;
                // $(element).parents('tr').removeClass('current');
                //
                // if (element.name === 'email') {
                //     //邮箱匹配点击后，延时处理
                //     setTimeout(function () {
                //         _this.element(element);
                //     }, 150);
                // } else {
                //     _this.element(element);
                // }

            },
            messages: {
                dbhost: {required: '数据库服务器地址不能为空'},
                dbport: {required: '数据库服务器端口不能为空'},
                dbuser: {required: '数据库用户名不能为空'},
                dbpw: {required: '数据库密码不能为空'},
                dbname: {required: '数据库名不能为空'},
                dbprefix: {required: '数据库表前缀不能为空'},
                manager: {required: '管理员帐号不能为空'},
                manager_pwd: {required: '密码不能为空', minlength: '密码长度不低于{0}位', maxlength: '密码长度不超过{0}位'},
                manager_ckpwd: {required: '重复密码不能为空', equalTo: '两次输入的密码不一致,请重新输入.'},
                manager_email: {required: 'Email不能为空', email: '请输入正确的电子邮箱地址'}
            },
            submitHandler: function (form) {
                form.submit();
                return true;
            }
        });
    });
</script>
</body>
</html>
