<!doctype html>
<html>
<head>
    @include("install.head")
</head>
<body>

@include("install.header")
@php
    $defaults = $installDefaults ?? [];
    $defaultManagerPassword = old('manager_pwd', $defaults['manager_pwd'] ?? '');
    $adminLoginEntrance = $defaults['admin_login_entrance'] ?? '';
    $adminLoginUrl = $adminLoginEntrance ? url('/admin/login/' . $adminLoginEntrance) : url('/admin/login');
    $dbPort = old('dbport', env('DB_PORT', 3306));
@endphp

<section class="container install-shell">
    <div class="install-step-nav">
        <div class="install-step-nav__item">
            <div><span class="install-step-nav__step">1</span><span class="install-step-nav__label">环境检测</span></div>
            <div class="install-step-nav__desc">确认运行环境、扩展与目录权限。</div>
        </div>
        <div class="install-step-nav__item is-active">
            <div><span class="install-step-nav__step">2</span><span class="install-step-nav__label">创建数据</span></div>
            <div class="install-step-nav__desc">填写数据库、站点信息与创始人账户。</div>
        </div>
        <div class="install-step-nav__item">
            <div><span class="install-step-nav__step">3</span><span class="install-step-nav__label">完成安装</span></div>
            <div class="install-step-nav__desc">执行安装并再次确认后台路径与密码。</div>
        </div>
    </div>

    <div class="install-panel mt-4">
        <div class="install-panel__body">
            <div class="install-form-grid">
                <div>
                    <form id="js-install-form" action="{{url('/install?install=4')}}" method="post">
                        {{csrf_field()}}
                        <input type="hidden" name="force" value="0"/>
                        <div class="alert alert-info install-alert">
                            <div class="font-weight-bold">安装信息预填已就绪</div>
                            <div class="small mt-2">系统已为创始人账号生成随机密码，并预生成一条隐藏后台入口。你可以直接使用，也可以修改后再开始初始化。</div>
                        </div>
                        <div class="server">
                            <table class="table small install-table">
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
                            <td class="text-left">数据库端口：</td>
                            <td colspan="2">
                                <input type="text" name="dbport" id="dbport" value="{{$dbPort}}" class="form-control col-md-6 col-sm-9">
                                <div id="js-install-tip-dbport">
                                    <span class="text-muted">默认 MySQL 端口一般为 3306，若你的服务器使用了自定义端口请在这里填写。</span>
                                </div>
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
                                <input type="text" name="website_name" value="{{old('website_name', $cms_name)}}" class="form-control col-md-6 col-sm-9">
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
                                <textarea class="form-control col-md-6 col-sm-9 "  rows="10" name="website_desc">{{$cms_name}} 是一款基于 Laravel 框架开发的模块化内容管理系统，提供直观易用的界面，支持插件、主题和模块扩展，适用于个人博客、企业官网、知识付费、电商平台等多种网站类型，轻松构建灵活、高效的内容管理平台，助您轻松打造个性化网站。</textarea>
                                <div id="js-install-tip-website_desc" class="text-muted"></div>
                            </td>

                        </tr>

                        <tr>
                            <td class="td1" colspan="3">创始人信息</td>
                        </tr>
                        <tr>
                            <td class="text-left">管理员帐号：</td>
                            <td colspan="2">
                                <input type="text" name="manager" value="{{old('manager', $defaults['manager'] ?? 'admin')}}" class="form-control col-md-6 col-sm-9">
                                <div id="js-install-tip-manager" class="text-muted"></div>
                            </td>

                        </tr>
                        <tr>
                            <td class="text-left">登录密码：</td>
                            <td colspan="2">
                                <div class="input-group col-md-6 col-sm-9 p-0">
                                    <input type="password" name="manager_pwd" id="js-manager-pwd" class="form-control"
                                           value="{{$defaultManagerPassword}}" autoComplete="off">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" onclick="generateManagerPassword()">重新生成</button>
                                        <button class="btn btn-outline-secondary" type="button" onclick="toggleManagerPassword()">显示</button>
                                    </div>
                                </div>
                                <div class="small text-muted mt-2">
                                    当前随机密码：<code class="js-generated-password">{{$defaultManagerPassword}}</code>
                                </div>
                                <div id="js-install-tip-manager_pwd" class="text-muted">
                                 <span class="text-muted">
                                    密码长度不低于6位,不高于32位。
                                 </span>
                                </div>
                            </td>

                        </tr>
                        <tr>
                            <td class="text-left">重复密码：</td>
                            <td colspan="2">
                                <input type="password" name="manager_ckpwd" id="js-manager-ckpwd" value="{{old('manager_ckpwd', $defaultManagerPassword)}}" class="form-control col-md-6 col-sm-9" autoComplete="off">
                                <div id="js-install-tip-manager_ckpwd" class="text-muted"></div>
                            </td>

                        </tr>
                        <tr>
                            <td class="text-left">Email：</td>
                            <td colspan="2">
                                <input type="text" name="manager_email" class="form-control col-md-6 col-sm-9" value="{{old('manager_email', $defaults['manager_email'] ?? '')}}">
                                <div id="js-install-tip-manager_email" class="text-muted"></div>
                            </td>

                        </tr>
                            </table>
                            <div id="js-response-tips" style="display: none;"></div>
                        </div>
                        <div class="install-bottom-actions justify-content-start">
                            <a href="{{url('/install?install=2')}}" class="btn btn-outline-primary">返回环境检测</a>
                            <button type="button" class="btn btn-outline-secondary" onclick="saveDBInfo()">保存数据库信息</button>
                            <button type="submit" class="btn btn-primary">开始创建数据</button>
                        </div>
                    </form>
                </div>

                <div>
                    <div class="install-side-card">
                        <h6>安装前确认</h6>
                        <ul class="install-meta-list">
                            <li>
                                <span class="install-meta-list__label">隐藏后台入口</span>
                                <span class="install-meta-list__value"><code id="js-hidden-admin-url">{{$adminLoginUrl}}</code></span>
                            </li>
                            <li>
                                <span class="install-meta-list__label">创始人账号</span>
                                <span class="install-meta-list__value">{{old('manager', $defaults['manager'] ?? 'admin')}}</span>
                            </li>
                            <li>
                                <span class="install-meta-list__label">初始密码</span>
                                <span class="install-meta-list__value"><code class="js-generated-password">{{$defaultManagerPassword}}</code></span>
                            </li>
                            <li>
                                <span class="install-meta-list__label">数据库编码</span>
                                <span class="install-meta-list__value"><span id="js-current-charset">utf8mb4</span></span>
                            </li>
                        </ul>
                        <div class="mt-3">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="copyTextById('js-hidden-admin-url', '已复制隐藏后台入口，请妥善保存。')">复制隐藏后台入口</button>
                        </div>
                    </div>

                    <div class="install-side-card mt-3">
                        <h6>交互建议</h6>
                        <ul class="small pl-3 mb-0 install-muted">
                            <li class="mb-2">先点击“保存数据库信息”，确认数据库账号可连通。</li>
                            <li class="mb-2">创始人密码建议保留随机值，首次登录后再立即修改。</li>
                            <li class="mb-2">隐藏后台入口只会在安装结束页再次集中展示，请务必保存。</li>
                            <li>如使用远程数据库或非默认端口，请同步确认主机、端口和防火墙策略。</li>
                        </ul>
                    </div>
                </div>
            </div>
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
    function randomString(length) {
        var chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789';
        var result = '';
        for (var i = 0; i < length; i++) {
            result += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return result;
    }

    function generateManagerPassword() {
        var password = randomString(12);
        $('#js-manager-pwd').val(password);
        $('#js-manager-ckpwd').val(password);
        $('.js-generated-password').text(password);
        $('#js-install-tip-manager_pwd').html('<span class="text-success">已生成新的随机密码，请妥善保存。</span>');
        $('#js-install-tip-manager_ckpwd').html('<span class="text-success">重复密码已自动同步。</span>');
    }

    function toggleManagerPassword() {
        var currentType = $('#js-manager-pwd').attr('type');
        var nextType = currentType === 'password' ? 'text' : 'password';
        $('#js-manager-pwd').attr('type', nextType);
        $('#js-manager-ckpwd').attr('type', nextType);
    }

    function copyTextById(elementId, successMessage) {
        var text = $('#' + elementId).text().trim();
        if (!text) {
            $('.toast-body').html('没有可复制的内容');
            $('.toast').toast('show');
            return;
        }

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(function () {
                $('.toast-body').html(successMessage);
                $('.toast').toast('show');
            }).catch(function () {
                fallbackCopyText(text, successMessage);
            });
            return;
        }

        fallbackCopyText(text, successMessage);
    }

    function fallbackCopyText(text, successMessage) {
        var tempInput = $('<input>');
        $('body').append(tempInput);
        tempInput.val(text).trigger('select');
        document.execCommand('copy');
        tempInput.remove();
        $('.toast-body').html(successMessage);
        $('.toast').toast('show');
    }

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
        $('#js-current-charset').text($('#dbcharset').val());
        $('#dbcharset').on('change', function () {
            $('#js-current-charset').text($(this).val());
        });

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
                $('.toast-body').html(error.text());
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
