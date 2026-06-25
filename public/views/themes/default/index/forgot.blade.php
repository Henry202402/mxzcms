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
                <h2>{{ themeTrans('forgot.submit') }}</h2>
                <p>选择验证方式并设置一个新的密码。</p>
            </div>

            <form id="myForm" method="POST" class="mx-auth-form">
                {{csrf_field()}}
                @if(session("errormsg"))
                    <div class="mx-auth-alert mx-auth-alert--error">{{session("errormsg")}}{{ themeTrans('common.password_incorrect') }}</div>
                @endif

                <div class="mx-auth-field">
                    <label>验证方式</label>
                    <select name="verify_type" class="form-control">
                        @if($open_email_verify)
                            <option value="email">{{ themeTrans('forgot.verify_type_email') }}</option>
                        @endif
                        @if($open_phone_verify)
                            <option value="phone">{{ themeTrans('forgot.verify_type_phone') }}</option>
                        @endif
                    </select>
                </div>

                @if($open_email_verify)
                    <div class="h-email-top" data-verify-block="email">
                        <div class="mx-auth-field">
                            <label>{{ themeTrans('forgot.email_placeholder') }}</label>
                            <input type="text" class="form-control" name="email" placeholder="{{ themeTrans('forgot.email_placeholder') }}" data-verify-input="email">
                        </div>
                        <div class="mx-auth-field">
                            <label>{{ themeTrans('forgot.email_captcha_placeholder') }}</label>
                            <div class="mx-auth-inline-field">
                                <input type="text" class="form-control" name="email_captcha" placeholder="{{ themeTrans('forgot.email_captcha_placeholder') }}" data-verify-input="email" autocomplete="off">
                                <button type="button" class="button blue-light helper email_captcha_btn">{{ themeTrans('common.send') }}</button>
                                <input type="hidden" id="email_code_type" value="3">
                                <input type="hidden" name="email_key" value="">
                            </div>
                        </div>
                    </div>
                @endif

                @if($open_phone_verify)
                    <div class="h-phone-top" data-verify-block="phone">
                        <div class="mx-auth-field">
                            <label>{{ themeTrans('forgot.phone_placeholder') }}</label>
                            <input type="text" class="form-control" name="phone" placeholder="{{ themeTrans('forgot.phone_placeholder') }}" data-verify-input="phone">
                        </div>
                        <div class="mx-auth-field">
                            <label>{{ themeTrans('forgot.phone_captcha_placeholder') }}</label>
                            <div class="mx-auth-inline-field">
                                <input type="text" class="form-control" name="phone_captcha" placeholder="{{ themeTrans('forgot.phone_captcha_placeholder') }}" data-verify-input="phone" autocomplete="off">
                                <button type="button" class="button blue-light helper phone_captcha_btn">{{ themeTrans('common.send') }}</button>
                                <input type="hidden" id="phone_code_type" value="3">
                                <input type="hidden" name="phone_key" value="">
                            </div>
                        </div>
                    </div>
                @endif

                <div class="mx-auth-field">
                    <label>{{ themeTrans('forgot.new_password_placeholder') }}</label>
                    <input type="password" class="form-control" name="new_password" placeholder="{{ themeTrans('forgot.new_password_placeholder') }}" required>
                </div>

                <div class="mx-auth-field">
                    <label>{{ themeTrans('forgot.confirm_password_placeholder') }}</label>
                    <input type="password" class="form-control" name="confirm_password" placeholder="{{ themeTrans('forgot.confirm_password_placeholder') }}" required>
                </div>

                <button type="button" class="button green full forgotBtn">{{ themeTrans('forgot.submit') }}</button>
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
<script>
    function syncForgotVerifyFields(type) {
        $('[data-verify-block]').each(function () {
            var currentType = $(this).data('verify-block');
            var active = currentType === type;
            $(this).toggle(active);
            $(this).find('input').each(function () {
                var isHiddenField = $(this).attr('type') === 'hidden';
                $(this).prop('disabled', !active && !isHiddenField);
                if ($(this).data('verify-input')) {
                    $(this).prop('required', active);
                }
            });
            $(this).find('button').prop('disabled', !active);
        });
    }

    syncForgotVerifyFields($('select[name="verify_type"]').val());
    $('select[name="verify_type"]').change(function () {
        syncForgotVerifyFields($(this).val());
    });
</script>
</body>
</html>
