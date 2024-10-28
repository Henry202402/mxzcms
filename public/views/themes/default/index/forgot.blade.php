@include("themes.default.public.head")
@include("themes.default.public.preloader")

<div class="page js-page login-page">

    <div class="login">
        <div class="login-content">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="login-wrapper">
                            <div class="helper center">
                                <a href="{{url("/")}}" class="logo-image animated">
                                    <img src="{{GetLocalFileByPath(cacheGlobalSettingsByKey('weblogo'))}}" alt="logo">
                                </a>
                            </div>
                            <form id="myForm" method="POST" class="register-form">
                                {{csrf_field()}}
                                <div class="register-inputs">

                                    @if(session("errormsg"))
                                        <span class="help-block form-error h-center"
                                              style="color: #ff3625;">{{session("errormsg")}}密码有误</span>
                                    @endif
                                    <select name="verify_type" class="form-control" style="height: 50px;">
                                        @if($open_email_verify)
                                            <option value="email">邮箱</option>@endif
                                        @if($open_phone_verify)
                                            <option value="phone">手机</option>@endif
                                    </select>
                                    @if($open_email_verify)
                                        <div class="h-email-top" style="display:none;">
                                            <input type="text" class="form-control" name="email" placeholder="邮箱"
                                                   required>
                                            <div class="helper display-flex">
                                                <input type="text" class="form-control" name="email_captcha"
                                                       placeholder="邮箱验证码" required
                                                       autocomplete="off">
                                                <button type="button"
                                                        class="button blue-light helper email_captcha_btn">
                                                    发送
                                                </button>
                                                <input type="hidden" id="email_code_type" value="3">
                                                <input type="hidden" name="email_key" value="">
                                            </div>
                                        </div>
                                    @endif
                                    @if($open_phone_verify)
                                        <div class="h-phone-top" style="display:none;">
                                            <input type="text" class="form-control" name="phone" placeholder="手机号"
                                                   required>
                                            <div class="helper display-flex">
                                                <input type="text" class="form-control" name="phone_captcha"
                                                       placeholder="手机验证码" required
                                                       autocomplete="off">
                                                <button type="button"
                                                        class="button blue-light helper phone_captcha_btn">
                                                    发送
                                                </button>
                                                <input type="hidden" id="phone_code_type" value="3">
                                                <input type="hidden" name="phone_key" value="">
                                            </div>
                                        </div>
                                    @endif
                                    <input type="password" class="form-control" name="new_password" placeholder="密码"
                                           required>
                                    <input type="password" class="form-control" name="confirm_password"
                                           placeholder="确认密码" required>
                                </div>


                                <button type="button" class="button green full forgotBtn">提交</button>
                            </form>
                            @if($open_login)
                                <ul class="register-helpers">
                                    <li class="register-helper-item">已有账号? <a href="{{url("login")}}">登录</a></li>
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@include("themes.default.public.js")
<script src="{{HOME_ASSET}}default/assets/js/common.js"></script>
<script src="{{HOME_ASSET}}default/assets/js/loginRegister.js"></script>
<script>
    @if($open_email_verify)
    $('.h-email-top').show();
    @elseif($open_phone_verify)
    $('.h-phone-top').show();
    @endif
    $('select[name="verify_type"]').change(function () {
        if($(this).val()=='phone'){
            $('.h-email-top').hide();
            $('.h-phone-top').show();
        }else{
            $('.h-phone-top').hide();
            $('.h-email-top').show();
        }
    });
</script>
</body>
</html>
