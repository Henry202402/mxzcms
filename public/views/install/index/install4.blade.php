<!doctype html>
<html>
<head>
    @include("install.head")
    <script type="text/javascript">
        //全局变量
        var GV = {
            ROOT: "/",
            WEB_ROOT: "/",
            JS_ROOT: "/"
        };
    </script>
    <style type="text/css">
        .display-none {
            display: none;
        }
    </style>
</head>
<body>

@include("install.header")
<section class="container">
    @php
        $installAdmin = session('install.admin') ?: [];
        $installEnv = session('install.env') ?: [];
        $installSettings = session('install.settings') ?: [];
        $installDefaults = session('install_form_defaults') ?: [];
        $adminLoginEntrance = $installDefaults['admin_login_entrance'] ?? '';
        $adminLoginUrl = $adminLoginEntrance ? url('/admin/login/' . $adminLoginEntrance) : url('/admin/login');
        $websiteName = '';
        foreach ($installSettings as $setting) {
            if (($setting['key'] ?? '') === 'website_name') {
                $websiteName = (string) ($setting['value'] ?? '');
                break;
            }
        }
    @endphp
    <div class="install-step-nav mt-3">
        <div class="install-step-nav__item">
            <div><span class="install-step-nav__step">1</span><span class="install-step-nav__label">环境检测</span></div>
            <div class="install-step-nav__desc">确认服务器环境可用。</div>
        </div>
        <div class="install-step-nav__item">
            <div><span class="install-step-nav__step">2</span><span class="install-step-nav__label">创建数据</span></div>
            <div class="install-step-nav__desc">生成数据库与站点初始化数据。</div>
        </div>
        <div class="install-step-nav__item is-active">
            <div><span class="install-step-nav__step">3</span><span class="install-step-nav__label">完成安装</span></div>
            <div class="install-step-nav__desc">执行安装并再次确认管理信息。</div>
        </div>
    </div>
    <div class="install-panel mt-4">
        <div class="install-panel__body">
            <div class="alert alert-info install-alert">
                <div class="font-weight-bold">正在执行安装初始化</div>
                <div class="small mt-2">此阶段会完成数据库迁移、核心模块安装、站点信息写入与后台入口生成，请勿中途关闭页面。</div>
            </div>
            <div id="log" class="install-log">
                <ul id="install-msg-panel" class="pl-0 mb-0 small list-unstyled text-muted"></ul>
            </div>

            <div class="text-center mt-4">
                <a class="install-load btn btn-light border" href="javascript:;"><i class="fa fa-refresh fa-spin"></i>&nbsp;正在安装...</a>
            </div>

            <div class="install-info-card display-none install-ok mt-4">
                <h6>安装信息汇总</h6>
                <p class="install-section-desc">以下信息仅在安装结束时集中展示一次，请立即保存，尤其是隐藏后台入口与创始人密码。</p>
                <div class="install-summary-grid">
                    <div class="install-summary-card">
                        <h6>站点信息</h6>
                        <ul class="install-meta-list">
                            <li><span class="install-meta-list__label">站点名称</span><span class="install-meta-list__value">{{$websiteName ?: $cms_name}}</span></li>
                            <li><span class="install-meta-list__label">站点首页</span><span class="install-meta-list__value"><code id="js-site-home-url">{{url('/')}}</code></span></li>
                            <li><span class="install-meta-list__label">隐藏后台入口</span><span class="install-meta-list__value"><code id="js-hidden-admin-url">{{$adminLoginUrl}}</code></span></li>
                        </ul>
                    </div>
                    <div class="install-summary-card">
                        <h6>管理员信息</h6>
                        <ul class="install-meta-list">
                            <li><span class="install-meta-list__label">管理员账号</span><span class="install-meta-list__value"><code>{{$installAdmin['username'] ?? 'admin'}}</code></span></li>
                            <li><span class="install-meta-list__label">初始密码</span><span class="install-meta-list__value"><code id="js-admin-password">{{$installAdmin['password'] ?? ''}}</code></span></li>
                            <li><span class="install-meta-list__label">提示</span><span class="install-meta-list__value">首次登录后请立即修改密码</span></li>
                        </ul>
                    </div>
                    <div class="install-summary-card">
                        <h6>数据库信息</h6>
                        <ul class="install-meta-list">
                            <li><span class="install-meta-list__label">主机</span><span class="install-meta-list__value">{{$installEnv['DB_HOST'] ?? env('DB_HOST')}}</span></li>
                            <li><span class="install-meta-list__label">端口</span><span class="install-meta-list__value">{{$installEnv['DB_PORT'] ?? env('DB_PORT', 3306)}}</span></li>
                            <li><span class="install-meta-list__label">数据库名</span><span class="install-meta-list__value">{{$installEnv['DB_DATABASE'] ?? env('DB_DATABASE')}}</span></li>
                            <li><span class="install-meta-list__label">表前缀</span><span class="install-meta-list__value">{{$installEnv['DB_PREFIX'] ?? env('DB_PREFIX')}}</span></li>
                        </ul>
                    </div>
                </div>
                <div class="text-danger mt-3">请妥善保存以上信息，并保留 `public/install.lock` 以阻止重复安装覆盖现有数据。</div>
                <div class="install-result-actions mt-3">
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="copyValueById('js-hidden-admin-url', '隐藏后台入口已复制，请妥善保存。')">复制隐藏后台入口</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="copyValueById('js-admin-password', '管理员初始密码已复制，请妥善保存。')">复制管理员密码</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="copyValueById('js-site-home-url', '站点首页地址已复制。')">复制站点首页</button>
                </div>
                <div class="install-result-actions mt-3">
                    <a class="btn btn-success install-ok display-none" href="{{url('/')}}">进入前台</a>
                    <a class="btn btn-primary install-ok display-none" href="{{$adminLoginUrl}}">进入后台</a>
                </div>
            </div>
        </div>
    </div>

</section>


<!-- 模态 -->
<div class="modal" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- 模态标题 -->
            <div class="modal-header">
                <h4 class="modal-title">温馨提示</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- 模态主体 -->
            <div class="modal-body"></div>

            <!-- 模态页脚 -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>


@include("install.footer")

<script type="text/html" id="exec-success-msg-tpl">
    <li>
        <i class="fa fa-check correct"></i>
        {message}<br>
        <!--<pre>{sql}</pre>-->
    </li>
</script>
<script type="text/html" id="exec-fail-msg-tpl">
    <li>
        <i class="fa fa-remove error"></i>
        {message}<br>
        <pre>{sql}</pre>
        <!--<pre>{exception}</pre>-->
    </li>
</script>
<script type="text/javascript">
    function copyValueById(elementId, successMessage) {
        var text = $('#' + elementId).text().trim();
        if (!text) {
            $('#myModal .modal-body').html('没有可复制的内容');
            $('#myModal').modal('show');
            return;
        }

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(function () {
                $('#myModal .modal-body').html(successMessage);
                $('#myModal').modal('show');
            }).catch(function () {
                fallbackCopyValue(text, successMessage);
            });
            return;
        }

        fallbackCopyValue(text, successMessage);
    }

    function fallbackCopyValue(text, successMessage) {
        var tempInput = $('<input>');
        $('body').append(tempInput);
        tempInput.val(text).trigger('select');
        document.execCommand('copy');
        tempInput.remove();
        $('#myModal .modal-body').html(successMessage);
        $('#myModal').modal('show');
    }

    $(function () {
        $installMsgPanel.append('请稍后，需要花费几分钟时间，数据录入中……');
        install(0);
    });

    var $installMsgPanel = $('#install-msg-panel');
    var $log = $("#log");
    var execSuccessTpl = $('#exec-success-msg-tpl').html();
    var execFailTpl = $('#exec-fail-msg-tpl').html();
    var sqlExecResult;

    function install(sqlIndex) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            url: "{{url('install/start')}}",
            data: {sql_index: sqlIndex},
            dataType: 'json',
            type: 'post',
            success: function (data) {
                console.log(data);
                var line = sqlIndex + 1;
                if (data.status == 200) {

                    if (!(data.data && data.data.done)) {
                        var tpl = execSuccessTpl;
                        tpl = tpl.replace(/\{message\}/g, line + '.' + data.msg);
                        // tpl     = tpl.replace(/\{sql\}/g, data.data.sql);
                        $installMsgPanel.append(tpl);

                    } else {
                        $installMsgPanel.append('<li><i class="fa fa-check correct"></i>数据库安装完成!</li>');

                        sqlExecResult = data.data;

                        if (data.data.error) {
                            $("#myModal .modal-body").html("安装过程,共" + data.data.error + "个SQL执行错误,可能您在此数据库下已经安装过,请查看问题后重新安装,或者<br>");
                            $('#myModal').modal('show')
                        } else {
                            stepAction(0)
                        }
                    }

                } else if (data.code == 0) {

                    var tpl = execFailTpl;
                    tpl = tpl.replace(/\{message\}/g, line + '.' + data.msg);
                    tpl = tpl.replace(/\{sql\}/g, data.data.sql);
                    tpl = tpl.replace(/\{exception\}/g, data.data.exception);
                    $installMsgPanel.append(tpl);
                }

                $log.scrollTop(1000000000);

                if (!(data.data && data.data.done)) {
                    sqlIndex++;
                    install(sqlIndex);
                }


            },
            error: function () {

            },
            complete: function () {

            }
        });
    }

    var stepUrls = [
        "{{url('install/setDbConfig')}}",
        "{{url('install/installModule?m=System')}}",
        "{{url('install/installModule?m=Auth')}}",
        "{{url('install/installModule?m=Formtools')}}",
        "{{url('install/installModule?m=Member')}}",
        "{{url('install/setSite')}}",
    ];

    function stepAction(index) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            url: stepUrls[index],
            dataType: 'json',
            data: {_hithinkcmf: 1},
            type: 'post',
            success: function (data) {
                if (data.status == 200) {
                    $installMsgPanel.append('<li><i class="fa fa-check correct"></i>' + data.msg + '</li>');
                    $log.scrollTop(1000000000);
                    if (index + 1 == stepUrls.length) {
                        $('a.install-load').html('<i class="fa fa-check correct"></i>安装完成！');
                        $('.install-ok').show();
                    } else {
                        index++;
                        stepAction(index);
                    }
                } else {
                    $installMsgPanel.append('<li><i class="fa fa-remove error"></i>' + data.msg + '</li>');
                    $log.scrollTop(1000000000);
                    $("#myModal .modal-body").html(data.msg);
                    $('#myModal').modal('show')
                }

            },
            error: function () {

            }
        });
    }
</script>
</body>
</html>
