<?php

namespace Modules\Main\Http\Controllers\Home;

use Modules\Main\Models\Member;
use Modules\Main\Services\ServiceModel;
use Modules\ModulesController;
use Modules\System\Http\Controllers\Common\SessionKey;

class LoginController extends ModulesController {

    protected function getLoginRegisterConfig(): array
    {
        return hook('GetHomeBasicConfig', ['moduleName' => 'System'])[0]['login_register'];
    }

    protected function canSendCode(array $all, array $loginRegister): array
    {
        $objectType = trim((string) ($all['object_type'] ?? ''));
        $codeType = (int) ($all['code_type'] ?? 0);

        if ($objectType === 'captcha') {
            return $loginRegister['open_login']
                ? returnArr(200, '允许发送')
                : returnArr(0, '未开放登录');
        }

        if (in_array($objectType, ['email', 'phone'], true)) {
            if ($codeType === 2 && !$loginRegister['open_register']) {
                return returnArr(0, '未开放注册');
            }

            if ($codeType === 3) {
                if (!$loginRegister['open_login']) {
                    return returnArr(0, '未开放登录');
                }

                if ($objectType === 'email' && empty($loginRegister['open_email_verify'])) {
                    return returnArr(0, '未开启邮箱找回');
                }

                if ($objectType === 'phone' && empty($loginRegister['open_phone_verify'])) {
                    return returnArr(0, '未开启手机找回');
                }
            }
        }

        return returnArr(200, '允许发送');
    }

    //登录
    public function login() {
        if ($this->request->isMethod("post")) {
            //用户登录
            $api = new \Modules\Main\Http\Controllers\Api\LoginController();
            $res = $api->login();
            if ($res['status'] != 200) return back()->with(["errormsg" => $res['msg'], "status" => 500]);
            return redirect(url("member"));
        }
        $loginRegister = $this->getLoginRegisterConfig();
        if (!$loginRegister['open_login']) return back();
        return HomeView('index.login', $loginRegister);
    }

    //注册
    public function register() {
        if ($this->request->isMethod("post")) {
            //用户登录
            $api = new \Modules\Main\Http\Controllers\Api\LoginController();
            $res = $api->register();
            if ($res['status'] != 200) return returnArr(0, $res['msg']);
            return returnArr(200, '注册成功', ['url' => url("login")]);
        }
        $loginRegister = $this->getLoginRegisterConfig();
        if (!$loginRegister['open_register']) return back();
        return HomeView('index.register', $loginRegister);
    }

    //忘记密码
    public function forgot() {
        if ($this->request->isMethod("post")) {
            $api = new \Modules\Main\Http\Controllers\Api\LoginController();
            return $api->forgot();
        }
        $loginRegister = $this->getLoginRegisterConfig();
        if (!$loginRegister['open_login']) return back();
        if (empty($loginRegister['open_email_verify']) && empty($loginRegister['open_phone_verify'])) return back();
        return HomeView('index.forgot', $loginRegister);
    }

    //退出
    public function logout() {
        session()->forget(SessionKey::HomeInfo);
        return redirect(url("login"));
    }

    //发送验证码
    public function sendCode() {
        $request = \Request();
        $loginRegister = $this->getLoginRegisterConfig();
        $allow = $this->canSendCode($request->all(), $loginRegister);
        if ($allow['status'] != 200) {
            return $allow;
        }
        $request->offsetSet('uid', session(SessionKey::HomeInfo)['uid'] * 1);
        $api = new \Modules\Main\Http\Controllers\Api\LoginController();
        return $api->sendCode($request);
    }
}
