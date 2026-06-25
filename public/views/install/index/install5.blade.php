<!doctype html>
<html>
<head>
    @include("install.head")
</head>
<body>
<div class="wrap install-shell">
    @php
        $installDefaults = session('install_form_defaults') ?: [];
        $installAdmin = session('install.admin') ?: [];
        $installEnv = session('install.env') ?: [];
        $installSettings = session('install.settings') ?: [];
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
    @include("install.header")
    <section class="container">
        <div class="install-step-nav mt-3">
            <div class="install-step-nav__item"><div><span class="install-step-nav__step">1</span><span class="install-step-nav__label">环境检测</span></div><div class="install-step-nav__desc">服务器预检已完成。</div></div>
            <div class="install-step-nav__item"><div><span class="install-step-nav__step">2</span><span class="install-step-nav__label">创建数据</span></div><div class="install-step-nav__desc">站点基础数据已写入。</div></div>
            <div class="install-step-nav__item is-active"><div><span class="install-step-nav__step">3</span><span class="install-step-nav__label">完成安装</span></div><div class="install-step-nav__desc">请保存后台入口与管理员密码。</div></div>
        </div>
        <div class="install-panel mt-4">
            <div class="install-panel__body">
                <div class="text-center">
                    <div class="install-section-title">恭喜您，安装完成！</div>
                    <div class="install-muted">请立即保存以下安装信息，并在首次登录后完成安全加固。</div>
                </div>
                <div class="alert alert-danger install-alert mt-4 text-left">
                    为了您的站点安全，请保留 `public/install.lock` 文件，它用于标识系统已完成安装并阻止重复安装覆盖现有数据；如文件丢失，可手动创建同名文件，同时建议及时备份数据库。
                </div>
                <div class="install-summary-grid mt-4">
                    <div class="install-summary-card">
                        <h6>站点信息</h6>
                        <ul class="install-meta-list">
                            <li><span class="install-meta-list__label">站点名称</span><span class="install-meta-list__value">{{$websiteName ?: $cms_name}}</span></li>
                            <li><span class="install-meta-list__label">首页地址</span><span class="install-meta-list__value"><code id="js-site-home-url">{{url('/')}}</code></span></li>
                            <li><span class="install-meta-list__label">隐藏后台入口</span><span class="install-meta-list__value"><code id="js-hidden-admin-url">{{$adminLoginUrl}}</code></span></li>
                        </ul>
                    </div>
                    <div class="install-summary-card">
                        <h6>管理员信息</h6>
                        <ul class="install-meta-list">
                            <li><span class="install-meta-list__label">管理员账号</span><span class="install-meta-list__value"><code>{{$installAdmin['username'] ?? 'admin'}}</code></span></li>
                            <li><span class="install-meta-list__label">初始密码</span><span class="install-meta-list__value"><code id="js-admin-password">{{$installAdmin['password'] ?? ''}}</code></span></li>
                            <li><span class="install-meta-list__label">安全建议</span><span class="install-meta-list__value">首次登录后立即改密</span></li>
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
                <div class="install-result-actions mt-4">
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="copyValueById('js-hidden-admin-url', '隐藏后台入口已复制，请妥善保存。')">复制隐藏后台入口</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="copyValueById('js-admin-password', '管理员初始密码已复制，请妥善保存。')">复制管理员密码</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="copyValueById('js-site-home-url', '站点首页已复制。')">复制首页地址</button>
                </div>
                <div class="install-result-actions mt-4">
                    <a class="btn btn-success" href="{{url('/')}}/">进入前台</a>
                    <a class="btn btn-primary" href="{{$adminLoginUrl}}">进入隐藏后台入口</a>
                </div>
            </div>
        </div>
    </section>
</div>

@include("install.footer")
<script>
    function copyValueById(elementId, successMessage) {
        var text = $('#' + elementId).text().trim();
        if (!text) {
            alert('没有可复制的内容');
            return;
        }

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(function () {
                alert(successMessage);
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
        alert(successMessage);
    }
</script>
</body>
</html>
