@include("themes.default.public.head")
@include("themes.default.public.preloader")

<div class="page js-page mx-auth-page">
    <div class="mx-auth-shell">
        <div class="mx-auth-card mx-auth-card--compact">
            <a href="{{url('/')}}" class="mx-auth-brand mx-auth-brand--dark">
                <img src="{{GetLocalFileByPath(cacheGlobalSettingsByKey('weblogo'))}}" alt="logo">
                <span>{{ cacheGlobalSettingsByKey('website_name') ?: 'MXZCMS' }}</span>
            </a>

            <div class="mx-auth-card__header">
                <h2>{{ themeTrans('login.submit') }}</h2>
                <p>欢迎回来，请先登录你的账户。</p>
            </div>

            <form action="{{url('login')}}" method="post" class="mx-auth-form">
                {{csrf_field()}}
                @if(session("errormsg"))
                    <div class="mx-auth-alert mx-auth-alert--error">{{session("errormsg")}}</div>
                @endif

                <div class="mx-auth-field">
                    <label>{{ themeTrans('login.identifier_placeholder') }}</label>
                    <input type="text" class="form-control" name="name" placeholder="{{ themeTrans('login.identifier_placeholder') }}" autocomplete="off" required>
                </div>

                <div class="mx-auth-field">
                    <label>{{ themeTrans('login.password_placeholder') }}</label>
                    <input type="password" class="form-control" name="password" placeholder="{{ themeTrans('login.password_placeholder') }}" autocomplete="off" required>
                </div>

                @if($open_code_verify)
                    <div class="mx-auth-field">
                        <label>{{ themeTrans('login.captcha_placeholder') }}</label>
                        <div class="mx-auth-inline-field">
                            <input type="text" class="form-control" name="captcha" placeholder="{{ themeTrans('login.captcha_placeholder') }}" required autocomplete="off">
                            <div class="mx-auth-inline-field__addon">
                                {!! hook('GetSendCode', ['moduleName' => 'System', 'object_type' => 'captcha', 'operate_type' => 'send'])[0] !!}
                            </div>
                        </div>
                    </div>
                @endif

                <button type="submit" class="button green full">{{ themeTrans('login.submit') }}</button>
            </form>

            <div class="mx-auth-links">
                @if($open_register)
                    <a href="{{url('register')}}">{{ themeTrans('login.no_account') }} {{ themeTrans('login.register') }}</a>
                @endif
                <a href="{{url('forgot')}}">{{ themeTrans('login.forgot_password') }}</a>
            </div>
        </div>
    </div>
</div>
@include("themes.default.public.js")
</body>
</html>
