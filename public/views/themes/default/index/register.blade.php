@include("themes.default.public.head")
@include("themes.default.public.preloader")

<div class="page js-page mx-auth-page">
    <div class="mx-auth-shell">
        <div class="mx-auth-card mx-auth-card--compact mx-auth-card--register">
            <a href="{{url('/')}}" class="mx-auth-brand mx-auth-brand--dark">
                <img src="{{GetLocalFileByPath(cacheGlobalSettingsByKey('weblogo'))}}" alt="logo">
                <span>{{ cacheGlobalSettingsByKey('website_name') ?: 'MXZCMS' }}</span>
            </a>

            <div class="mx-auth-card__header">
                <h2>{{ themeTrans('register.submit') }}</h2>
                <p>填写基础资料，快速创建一个新账户。</p>
            </div>

            <form id="myForm" method="POST" class="mx-auth-form">
                {{csrf_field()}}
                @if(session("errormsg"))
                    <div class="mx-auth-alert mx-auth-alert--error">{{session("errormsg")}}{{ themeTrans('common.password_incorrect') }}</div>
                @endif

                <div class="mx-auth-grid">
                    <div class="mx-auth-field">
                        <label>{{ themeTrans('register.username_placeholder') }}</label>
                        <input type="text" class="form-control" name="username" placeholder="{{ themeTrans('register.username_placeholder') }}" required>
                    </div>
                    @if($show_nickname)
                        <div class="mx-auth-field">
                            <label>{{ themeTrans('register.nickname_placeholder') }}</label>
                            <input type="text" class="form-control" name="nickname" placeholder="{{ themeTrans('register.nickname_placeholder') }}" @if($required_nickname) required @endif>
                        </div>
                    @endif
                    @if($show_email)
                        <div class="mx-auth-field">
                            <label>{{ themeTrans('register.email_placeholder') }}</label>
                            <input type="text" class="form-control" name="email" placeholder="{{ themeTrans('register.email_placeholder') }}" @if($required_email) required @endif>
                        </div>
                    @endif
                    <div class="mx-auth-field">
                        <label>{{ themeTrans('register.password_placeholder') }}</label>
                        <input type="password" class="form-control" name="password" placeholder="{{ themeTrans('register.password_placeholder') }}" required>
                    </div>
                    <div class="mx-auth-field">
                        <label>{{ themeTrans('register.confirm_password_placeholder') }}</label>
                        <input type="password" class="form-control" name="confirm_password" placeholder="{{ themeTrans('register.confirm_password_placeholder') }}" required>
                    </div>
                    @if($show_phone)
                        <div class="mx-auth-field mx-auth-field--full">
                            <label>{{ themeTrans('register.phone_placeholder') }}</label>
                            <input type="text" class="form-control" name="phone" placeholder="{{ themeTrans('register.phone_placeholder') }}" @if($required_phone) required @endif>
                        </div>
                    @endif
                </div>

                @if($open_email_verify)
                    <div class="mx-auth-field">
                        <label>{{ themeTrans('register.email_captcha_placeholder') }}</label>
                        <div class="mx-auth-inline-field">
                            <input type="text" class="form-control" name="email_captcha" placeholder="{{ themeTrans('register.email_captcha_placeholder') }}" required autocomplete="off">
                            <button type="button" class="button blue-light helper email_captcha_btn">{{ themeTrans('common.send') }}</button>
                            <input type="hidden" id="email_code_type" value="2">
                            <input type="hidden" name="email_key" value="">
                        </div>
                    </div>
                @endif

                @if($open_phone_verify)
                    <div class="mx-auth-field">
                        <label>{{ themeTrans('register.phone_captcha_placeholder') }}</label>
                        <div class="mx-auth-inline-field">
                            <input type="text" class="form-control" name="phone_captcha" placeholder="{{ themeTrans('register.phone_captcha_placeholder') }}" required autocomplete="off">
                            <button type="button" class="button blue-light helper phone_captcha_btn">{{ themeTrans('common.send') }}</button>
                            <input type="hidden" id="phone_code_type" value="2">
                            <input type="hidden" name="phone_key" value="">
                        </div>
                    </div>
                @endif

                @if($open_code_verify)
                    <div class="mx-auth-field">
                        <label>{{ themeTrans('register.captcha_placeholder') }}</label>
                        <div class="mx-auth-inline-field">
                            <input type="text" class="form-control" name="captcha" placeholder="{{ themeTrans('register.captcha_placeholder') }}" required autocomplete="off">
                            <div class="mx-auth-inline-field__addon">
                                {!! hook('GetSendCode', ['moduleName' => 'System', 'object_type' => 'captcha', 'operate_type' => 'send'])[0] !!}
                            </div>
                        </div>
                    </div>
                @endif

                @if($agreementList)
                    <label class="mx-auth-check">
                        <input type="checkbox" class="register-checkbox" name="agree" value="1" required>
                        <span>{{ themeTrans('register.agree_prefix') }}
                            @foreach($agreementList as $agree)
                                <a class="text-decoration-none" target="_blank" href="{{$agree['detail_url']}}">《{{$agree['name']}}》</a>
                            @endforeach
                        </span>
                    </label>
                @endif

                <button type="button" class="button green full registerBtn">{{ themeTrans('register.submit') }}</button>
            </form>

            @if($open_login)
                <div class="mx-auth-links">
                    <a href="{{url('login')}}">{{ themeTrans('register.has_account') }} {{ themeTrans('login.submit') }}</a>
                </div>
            @endif
        </div>
    </div>
</div>
@include("themes.default.public.js")
<script src="{{HOME_ASSET}}default/assets/js/common.js"></script>
<script src="{{HOME_ASSET}}default/assets/js/loginRegister.js"></script>
</body>
</html>
