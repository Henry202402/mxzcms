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

                            <form action="{{url("login")}}" method="post" class="login-form">
                                {{csrf_field()}}
                                <div class="login-inputs">
                                    @if(session("errormsg"))
                                        <span class="help-block form-error h-center"
                                              style="color: #ff3625;">{{session("errormsg")}}</span>
                                    @endif
                                    <input type="text" class="form-control" name="name" placeholder="邮箱/手机号/用户名"
                                           autocomplete="off" required>
                                    <input type="password" class="form-control" name="password" placeholder="密码"
                                           autocomplete="off" required>
                                    @if($open_code_verify)
                                        <div class="helper display-flex">
                                            <input type="text" class="form-control" name="captcha"
                                                   placeholder="验证码" required
                                                   autocomplete="off">
                                            {!! hook('GetSendCode', ['moduleName' => 'System', 'object_type' => 'captcha', 'operate_type' => 'send'])[0] !!}
                                        </div>
                                    @endif
                                </div>
                                <button type="submit" class="button green full">登录</button>
                            </form>
                            @if($open_register)
                                <ul class="login-helpers">
                                    <li class="login-helper-item">还没有账号? <a href="{{url("register")}}">注册</a></li>
                                    <li class="login-helper-item"><a href="{{url("forgot")}}">忘记密码</a></li>
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
</body>
</html>
