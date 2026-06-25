@include(moduleAdminTemplate($moduleName)."public.header")
@php($sessionCookie = env('SESSION_COOKIE', env('COOKIE_NAME')))
@php($sessionDriver = env('SESSION_DRIVER', 'file'))
@php($sessionDomain = env('SESSION_DOMAIN'))
@php($sessionLifetime = env('SESSION_LIFETIME'))
@php($sessionEncrypt = filter_var(env('SESSION_ENCRYPT', false), FILTER_VALIDATE_BOOLEAN))
@php($adminLoginEntrance = (string) cacheGlobalSettingsByKey('admin_login_entrance'))
@php($adminLoginCode = (int) cacheGlobalSettingsByKey('admin_login_code'))
@php($homeSubmitCode = (int) cacheGlobalSettingsByKey('home_submit_code'))
@php($blacklistIp = (string) cacheGlobalSettingsByKey('blacklist_ip'))
@php($filterStrings = (string) cacheGlobalSettingsByKey('filter_strings'))
@php($blacklistItems = array_filter(array_map('trim', explode(',', $blacklistIp))))
@php($reservedConfigCount = 3)
@php($effectiveConfigCount = 6)
<style>
    .secure-config-hero {
        margin-bottom: 20px;
        padding: 22px 24px;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.06);
    }

    .secure-config-hero__title {
        margin: 0;
        font-size: 22px;
        font-weight: 700;
        color: #111827;
    }

    .secure-config-hero__desc {
        margin-top: 8px;
        max-width: 880px;
        color: #6b7280;
        line-height: 1.8;
    }

    .secure-config-overview {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
        margin-top: 18px;
    }

    .secure-config-card,
    .secure-config-sidecard {
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 10px 32px rgba(15, 23, 42, 0.05);
    }

    .secure-config-overview__item {
        padding: 16px 18px;
    }

    .secure-config-overview__name {
        font-size: 13px;
        color: #6b7280;
    }

    .secure-config-overview__value {
        margin-top: 10px;
        font-size: 24px;
        line-height: 1.2;
        font-weight: 700;
        color: #111827;
        word-break: break-word;
    }

    .secure-config-overview__desc {
        margin-top: 10px;
        min-height: 42px;
        color: #6b7280;
        line-height: 1.6;
    }

    .secure-config-layout {
        display: grid;
        grid-template-columns: minmax(0, 2fr) minmax(320px, 1fr);
        gap: 20px;
        align-items: start;
    }

    .secure-config-card {
        padding: 20px;
    }

    .secure-config-section + .secure-config-section {
        margin-top: 18px;
    }

    .secure-config-section {
        padding: 18px;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #fff;
    }

    .secure-config-section__title {
        margin: 0 0 6px;
        font-size: 16px;
        font-weight: 700;
        color: #111827;
    }

    .secure-config-section__desc {
        margin-bottom: 16px;
        color: #6b7280;
        line-height: 1.7;
    }

    .secure-config-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .secure-config-field--full {
        grid-column: 1 / -1;
    }

    .secure-config-field label {
        display: block;
        margin-bottom: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
    }

    .secure-config-field input,
    .secure-config-field textarea,
    .secure-config-field select {
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        background: #fff;
    }

    .secure-config-field input,
    .secure-config-field select {
        height: 42px;
        padding: 8px 12px;
    }

    .secure-config-field textarea {
        min-height: 120px;
        padding: 10px 12px;
        resize: vertical;
    }

    .secure-config-help {
        margin-top: 6px;
        color: #6b7280;
        line-height: 1.6;
    }

    .secure-config-help.is-warning {
        color: #b45309;
    }

    .secure-config-inline {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .secure-config-inline__prefix,
    .secure-config-inline__suffix {
        color: #6b7280;
        white-space: nowrap;
    }

    .secure-config-inline__field {
        min-width: 180px;
        flex: 1;
    }

    .secure-config-actions {
        margin-top: 20px;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .secure-config-sidecard {
        padding: 18px;
    }

    .secure-config-sidecard + .secure-config-sidecard {
        margin-top: 18px;
    }

    .secure-config-sidecard__title {
        margin: 0 0 8px;
        font-size: 15px;
        font-weight: 700;
        color: #111827;
    }

    .secure-config-sidecard__desc,
    .secure-config-sidecard__meta {
        color: #6b7280;
        line-height: 1.7;
        word-break: break-word;
    }

    .secure-config-sidecard__meta strong {
        color: #111827;
    }

    .secure-config-radio-group {
        display: flex;
        align-items: center;
        gap: 22px;
        flex-wrap: wrap;
        min-height: 48px;
    }

    .secure-config-radio-group label {
        margin: 0;
        font-weight: 400;
    }

    .secure-config-radio-group .radio-inline {
        display: inline-flex;
        align-items: center;
        padding-left: 0;
        margin-right: 6px;
        font-size: 15px;
        line-height: 1.4;
        overflow: visible;
    }

    .secure-config-radio-group .styled,
    .secure-config-radio-group input[type="radio"] {
        width: 14px;
        height: 14px;
        margin: 0 12px 0 0;
        vertical-align: middle;
        transform: scale(1.2);
        transform-origin: center;
    }

    .secure-config-radio-group .h-span-val {
        display: inline-block;
        font-size: 15px;
        line-height: 1.4;
        white-space: nowrap;
        margin-left: 20px;
    }

    .secure-config-chip {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
    }

    .secure-config-chip.is-active {
        color: #166534;
        background: #dcfce7;
    }

    .secure-config-chip.is-pending {
        color: #b45309;
        background: #fef3c7;
    }

    @media (max-width: 1200px) {
        .secure-config-overview,
        .secure-config-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .secure-config-layout {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .secure-config-overview,
        .secure-config-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
<body>

@include(moduleAdminTemplate($moduleName)."public.nav")

<div class="page-container">
    <div class="page-content">
        @include(moduleAdminTemplate($moduleName)."public.left")

        <div class="content-wrapper">
            <div class="page-header">
                @include(moduleAdminTemplate($moduleName)."public.page", ['breadcrumb'=>[$pageData['title'],$pageData['subtitle']]])
            </div>

            <div class="content" style="margin-top: 1rem;">
                <div class="secure-config-hero">
                    <h2 class="secure-config-hero__title">安全配置工作台</h2>
                    <div class="secure-config-hero__desc">
                        这页负责后台入口、会话参数、验证码和基础访问控制。已接入运行时的配置会直接影响全局行为，预留项会明确标注，避免“页面能改但系统没吃到”。
                    </div>

                    <div class="secure-config-overview">
                        <div class="secure-config-card secure-config-overview__item">
                            <div class="secure-config-overview__name">Session 驱动</div>
                            <div class="secure-config-overview__value">{{ strtoupper($sessionDriver) }}</div>
                            <div class="secure-config-overview__desc">当前 Laravel 会话层实际使用的存储驱动。</div>
                        </div>
                        <div class="secure-config-card secure-config-overview__item">
                            <div class="secure-config-overview__name">后台安全入口</div>
                            <div class="secure-config-overview__value">{{ $adminLoginEntrance ?: '未设置' }}</div>
                            <div class="secure-config-overview__desc">未设置时使用默认后台登录地址，设置后需通过专用入口访问。</div>
                        </div>
                        <div class="secure-config-card secure-config-overview__item">
                            <div class="secure-config-overview__name">IP 黑名单</div>
                            <div class="secure-config-overview__value">{{ count($blacklistItems) }} 条</div>
                            <div class="secure-config-overview__desc">该配置已接入中间件，命中后会被统一拦截。</div>
                        </div>
                        <div class="secure-config-card secure-config-overview__item">
                            <div class="secure-config-overview__name">后台登录验证码</div>
                            <div class="secure-config-overview__value">{{ $adminLoginCode === 1 ? '开启' : '关闭' }}</div>
                            <div class="secure-config-overview__desc">影响后台登录页的验证码显示与校验流程。</div>
                        </div>
                        <div class="secure-config-card secure-config-overview__item">
                            <div class="secure-config-overview__name">已接入项</div>
                            <div class="secure-config-overview__value">{{ $effectiveConfigCount }} 项</div>
                            <div class="secure-config-overview__desc">当前已确认真实影响运行时行为的安全配置数量。</div>
                        </div>
                        <div class="secure-config-card secure-config-overview__item">
                            <div class="secure-config-overview__name">预留项</div>
                            <div class="secure-config-overview__value">{{ $reservedConfigCount }} 项</div>
                            <div class="secure-config-overview__desc">仍保留在页面中，但尚未完整接入统一策略链路。</div>
                        </div>
                    </div>
                </div>

                <div class="secure-config-layout">
                    <div class="secure-config-card">
                        <form id="myForm" method="post" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <input type="hidden" name="form" value="safe">

                            <div class="secure-config-section">
                                <h3 class="secure-config-section__title">会话与登录入口</h3>
                                <div class="secure-config-section__desc">这组配置决定会话 Cookie、会话驱动和后台登录入口，是优先级最高的安全基础项。</div>

                                <div class="secure-config-grid">
                                    <div class="secure-config-field">
                                        <label>Session Cookie</label>
                                        <input type="text" name="SESSION_COOKIE" value="{{$sessionCookie}}" class="form-control">
                                        <div class="secure-config-help">Laravel 实际读取的是 `SESSION_COOKIE`，这里控制前后台 Session Cookie 名称。</div>
                                    </div>

                                    <div class="secure-config-field">
                                        <label>Session 域名</label>
                                        <input type="text" name="SESSION_DOMAIN" value="{{$sessionDomain}}" class="form-control" placeholder=".example.com">
                                        <div class="secure-config-help">跨子域共享登录状态时可配置统一域名，否则通常留空。</div>
                                    </div>

                                    <div class="secure-config-field">
                                        <label>Session 驱动</label>
                                        <select name="SESSION_DRIVER" class="select-search">
                                            <option value="file" @if($sessionDriver=='file') selected @endif>File</option>
                                            <option value="redis" @if($sessionDriver=='redis') selected @endif>Redis</option>
                                            <option value="memcached" @if($sessionDriver=='memcached') selected @endif>Memcached</option>
                                            <option value="database" @if($sessionDriver=='database') selected @endif>Database</option>
                                            <option value="apc" @if($sessionDriver=='apc') selected @endif>Apc</option>
                                            <option value="array" @if($sessionDriver=='array') selected @endif>Array</option>
                                        </select>
                                        <div class="secure-config-help">建议与缓存驱动和部署环境保持一致，避免会话漂移。</div>
                                    </div>

                                    <div class="secure-config-field">
                                        <label>Session 时长</label>
                                        <div class="secure-config-inline">
                                            <input type="number" name="SESSION_LIFETIME" value="{{$sessionLifetime}}" class="form-control secure-config-inline__field">
                                            <span class="secure-config-inline__suffix">分钟</span>
                                        </div>
                                        <div class="secure-config-help">超时后用户需要重新登录，后台建议按管理场景设置合适时长。</div>
                                    </div>

                                    <div class="secure-config-field secure-config-field--full">
                                        <label>Session 加密</label>
                                        <div class="secure-config-radio-group">
                                            <label class="radio-inline">
                                                <input type="radio" name="SESSION_ENCRYPT" class="styled h-radio" value="false" @if(!$sessionEncrypt) checked @endif>
                                                <span class="h-span-val">不加密</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="SESSION_ENCRYPT" class="styled h-radio" value="true" @if($sessionEncrypt) checked @endif>
                                                <span class="h-span-val">加密</span>
                                            </label>
                                        </div>
                                        <div class="secure-config-help">现在已真正同步到 Laravel Session 配置，修改后建议清理配置缓存。</div>
                                    </div>

                                    <div class="secure-config-field secure-config-field--full">
                                        <label>后台登录入口</label>
                                        <div class="secure-config-inline">
                                            <span class="secure-config-inline__prefix">{{ url('admin/login') }}/</span>
                                            <input type="text" id="adminLoginEntrance" name="admin_login_entrance" value="{{$adminLoginEntrance}}" class="form-control secure-config-inline__field" placeholder="字母、数字、短横线">
                                            <button type="button" class="btn btn-default" id="generateAdminLoginEntrance">随机生成</button>
                                        </div>
                                        <div class="secure-config-help">设置后只能通过该专用入口进入后台，建议使用易记但不直白的路径。</div>
                                        <div class="secure-config-help">当前预览：<span id="adminLoginEntrancePreview">{{ $adminLoginEntrance ? url('admin/login/' . $adminLoginEntrance) : url('admin/login') }}</span></div>
                                    </div>

                                    <div class="secure-config-field secure-config-field--full">
                                        <label>密码加密 KEY</label>
                                        <input type="text" name="password_key" value="{{cacheGlobalSettingsByKey('password_key')}}" class="form-control">
                                        <div class="secure-config-help">当前加密规则：`md5(key + md5(password))`。修改后会影响密码校验一致性，建议谨慎操作。</div>
                                    </div>
                                </div>
                            </div>

                            <div class="secure-config-section">
                                <h3 class="secure-config-section__title">访问控制与过滤</h3>
                                <div class="secure-config-section__desc">这里包含已接入的 IP 黑名单，以及尚未完整接入的账号锁定和过滤词预留策略。</div>

                                <div class="secure-config-grid">
                                    <div class="secure-config-field secure-config-field--full">
                                        <label>IP 黑名单</label>
                                        <textarea placeholder="127.0.0.1,192.168.1.1" name="blacklist_ip" rows="4">{{$blacklistIp}}</textarea>
                                        <div class="secure-config-help">多个 IP 用英文逗号隔开。该配置已接入 IP 黑名单中间件。</div>
                                    </div>

                                    <div class="secure-config-field">
                                        <label>账号锁定阈值</label>
                                        <div class="secure-config-inline">
                                            <span class="secure-config-inline__prefix">连续失败</span>
                                            <input type="number" name="limit_count" value="{{cacheGlobalSettingsByKey('limit_count')}}" class="form-control secure-config-inline__field">
                                            <span class="secure-config-inline__suffix">次</span>
                                        </div>
                                    </div>

                                    <div class="secure-config-field">
                                        <label>锁定时长</label>
                                        <div class="secure-config-inline">
                                            <span class="secure-config-inline__prefix">锁定</span>
                                            <input type="number" name="limit_time" value="{{cacheGlobalSettingsByKey('limit_time')}}" class="form-control secure-config-inline__field">
                                            <span class="secure-config-inline__suffix">分钟</span>
                                        </div>
                                    </div>

                                    <div class="secure-config-field secure-config-field--full">
                                        <div class="secure-config-help is-warning">账号锁定仍属于预留策略项，当前版本尚未完整接入后台登录失败计数逻辑。</div>
                                    </div>

                                    <div class="secure-config-field secure-config-field--full">
                                        <label>敏感字符过滤</label>
                                        <textarea placeholder="admin|test|demo" name="filter_strings" rows="4">{{$filterStrings}}</textarea>
                                        <div class="secure-config-help is-warning">多个词用 `|` 隔开。当前仍是预留配置，尚未统一接入全站内容提交过滤链路。</div>
                                    </div>
                                </div>
                            </div>

                            <div class="secure-config-section">
                                <h3 class="secure-config-section__title">验证码与校验</h3>
                                <div class="secure-config-section__desc">后台验证码已接入登录链路，前台提交验证码当前仍以基础设置中的 `open_captcha` 为实际入口。</div>

                                <div class="secure-config-grid">
                                    <div class="secure-config-field">
                                        <label>后台登录验证码</label>
                                        <div class="secure-config-radio-group">
                                            <label class="radio-inline">
                                                <input type="radio" name="admin_login_code" class="styled h-radio" value="1" @if($adminLoginCode==1) checked @endif>
                                                <span class="h-span-val">开启</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="admin_login_code" class="styled h-radio" value="0" @if($adminLoginCode==0) checked @endif>
                                                <span class="h-span-val">关闭</span>
                                            </label>
                                        </div>
                                        <div class="secure-config-help">该项已接入后台登录控制器。</div>
                                    </div>

                                    <div class="secure-config-field">
                                        <label>前台提交验证码</label>
                                        <div class="secure-config-radio-group">
                                            <label class="radio-inline">
                                                <input type="radio" name="home_submit_code" class="styled h-radio" value="1" @if($homeSubmitCode==1) checked @endif>
                                                <span class="h-span-val">开启</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="home_submit_code" class="styled h-radio" value="0" @if($homeSubmitCode==0) checked @endif>
                                                <span class="h-span-val">关闭</span>
                                            </label>
                                        </div>
                                        <div class="secure-config-help is-warning">当前前台登录/注册验证码实际由基本配置中的 `open_captcha` 控制，这里仍属于预留开关。</div>
                                    </div>
                                </div>
                            </div>

                            <div class="secure-config-actions">
                                <button type="button" class="btn btn-info h-sub">保存安全配置</button>
                                <a href="{{moduleAdminJump($moduleName,'base/baseConfig')}}" class="btn btn-default">查看基础设置</a>
                            </div>
                        </form>
                    </div>

                    <div>
                        <div class="secure-config-sidecard">
                            <h4 class="secure-config-sidecard__title">生效范围</h4>
                            <div class="secure-config-sidecard__meta"><strong>后台安全：</strong><span class="secure-config-chip is-active">已接入</span> 登录入口、后台验证码、密码加密 KEY</div>
                            <div class="secure-config-sidecard__meta"><strong>会话层：</strong><span class="secure-config-chip is-active">已接入</span> Cookie、Session 驱动、时长、加密</div>
                            <div class="secure-config-sidecard__meta"><strong>访问控制：</strong><span class="secure-config-chip is-active">已接入</span> IP 黑名单</div>
                            <div class="secure-config-sidecard__meta"><strong>策略预留：</strong><span class="secure-config-chip is-pending">待接入</span> 账号锁定、过滤词、前台提交验证码</div>
                        </div>

                        <div class="secure-config-sidecard">
                            <h4 class="secure-config-sidecard__title">当前状态</h4>
                            <div class="secure-config-sidecard__meta"><strong>Session Cookie：</strong>{{$sessionCookie ?: '未设置'}}</div>
                            <div class="secure-config-sidecard__meta"><strong>Session 域名：</strong>{{$sessionDomain ?: '留空'}}</div>
                            <div class="secure-config-sidecard__meta"><strong>Session 加密：</strong>{{$sessionEncrypt ? '已开启' : '未开启'}}</div>
                            <div class="secure-config-sidecard__meta"><strong>后台入口预览：</strong>{{ $adminLoginEntrance ? url('admin/login/' . $adminLoginEntrance) : url('admin/login') }}</div>
                        </div>

                        <div class="secure-config-sidecard">
                            <h4 class="secure-config-sidecard__title">维护建议</h4>
                            <div class="secure-config-sidecard__desc">优先保证会话和登录入口配置准确，再处理黑名单。预留项目前建议只作为策略草稿，不要误以为已经自动生效。</div>
                        </div>
                    </div>
                </div>

                @include(moduleAdminTemplate($moduleName)."public.footer")
            </div>
        </div>
    </div>
</div>

@include(moduleAdminTemplate($moduleName)."public.js")
<script>
    function generateAdminLoginEntranceValue() {
        var chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
        var value = 'adm-';
        for (var i = 0; i < 8; i++) {
            value += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return value;
    }

    function syncAdminLoginEntrancePreview() {
        var raw = $('#adminLoginEntrance').val() || '';
        var normalized = raw.replace(/[^a-zA-Z0-9\-_]/g, '');
        if (raw !== normalized) {
            $('#adminLoginEntrance').val(normalized);
        }

        var baseUrl = @json(url('admin/login'));
        $('#adminLoginEntrancePreview').text(normalized ? (baseUrl + '/' + normalized) : baseUrl);
    }

    $('#adminLoginEntrance').on('input', syncAdminLoginEntrancePreview);

    $('#generateAdminLoginEntrance').click(function () {
        $('#adminLoginEntrance').val(generateAdminLoginEntranceValue());
        syncAdminLoginEntrancePreview();
    });

    $('.h-sub').click(function () {
        layer.load(1);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            method: 'post',
            url: "{{moduleAdminJump($moduleName,'secure/toolSubmit')}}",
            data: new FormData($('#myForm')[0]),
            dataType: 'json',
            cache: false,
            processData: false,
            contentType: false,
            success: function (res) {
                layer.closeAll();
                if (res.status == 200) {
                    layer.msg(res.msg, {icon: 1, time: 1000}, function () {
                        window.location.reload();
                    });
                } else {
                    layer.msg(res.msg, {icon: 2});
                }
            },
            error: function () {
                layer.closeAll();
                layer.msg("系统错误，请稍后重试", {icon: 5});
            }
        });
    });

    $(function () {
        syncAdminLoginEntrancePreview();
    });
</script>
<script type="text/javascript" src="{{asset("assets/module")}}/js/plugins/forms/selects/select2.min.js"></script>
<script type="text/javascript" src="{{asset("assets/module")}}/js/pages/form_select2.js"></script>
</body>
</html>
