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

                                    <input type="text" class="form-control" name="username" placeholder="用户名" required>
                                    <input type="password" class="form-control" name="password" placeholder="密码"
                                           required>
                                    <input type="password" class="form-control" name="confirm_password"
                                           placeholder="确认密码" required>

                                    <input type="text" class="form-control" name="email" placeholder="邮箱" required>
                                    @if($open_email_verify)
                                        <div class="helper display-flex">
                                            <input type="text" class="form-control" name="email_captcha"
                                                   placeholder="邮箱验证码" required
                                                   autocomplete="off">
                                            <button type="button" class="button blue-light helper email_captcha_btn">
                                                发送
                                            </button>
                                            <input type="hidden" id="email_code_type" value="2">
                                            <input type="hidden" name="email_key" value="">
                                        </div>
                                    @endif
                                    <input type="text" class="form-control" name="phone" placeholder="手机号" required>
                                    @if($open_phone_verify)
                                        <div class="helper display-flex">
                                            <input type="text" class="form-control" name="phone_captcha"
                                                   placeholder="手机验证码" required
                                                   autocomplete="off">
                                            <button type="button" class="button blue-light helper phone_captcha_btn">
                                                发送
                                            </button>
                                            <input type="hidden" id="phone_code_type" value="2">
                                            <input type="hidden" name="phone_key" value="">
                                        </div>
                                    @endif
                                    @if($open_code_verify)
                                        <div class="helper display-flex" style="cursor: pointer;">
                                            <input type="text" class="form-control" name="captcha"
                                                   placeholder="验证码" required
                                                   autocomplete="off">
                                            {!! hook('GetSendCode', ['moduleName' => 'System', 'object_type' => 'captcha', 'operate_type' => 'send'])[0] !!}

                                        </div>
                                    @endif
                                </div>
                                @if($agreementList)
                                    <input type="checkbox" class="register-checkbox" name="agree" value="1" required>
                                    <label for="terms">同意
                                        @foreach($agreementList as $agree)
                                            <a class="text-decoration-none" target="_blank"
                                               href="{{$agree['detail_url']}}">《{{$agree['name']}}》</a>
                                        @endforeach
                                    </label>
                                @endif


                                <button type="button" class="button green full registerBtn">注册</button>
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
</body>
</html>
