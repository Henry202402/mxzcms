<?php

namespace Modules\Main\Http\Controllers\Home;

use Modules\Main\Models\Member;
use Modules\Main\Services\ServiceModel;
use Modules\ModulesController;
use Modules\System\Http\Controllers\Common\SessionKey;

class LoginController extends ModulesController {

    //登录
    public function login() {
        if ($this->request->isMethod("post")) {
            //用户登录
            $api = new \Modules\Main\Http\Controllers\Api\LoginController();
            $res = $api->login();
            if ($res['status'] != 200) return back()->with(["errormsg" => $res['msg'], "status" => 500]);
            return redirect(url("member"));
        }
        $loginRegister = hook('GetHomeBasicConfig', ['moduleName' => 'System'])[0]['login_register'];
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
        $loginRegister = hook('GetHomeBasicConfig', ['moduleName' => 'System'])[0]['login_register'];
        if (!$loginRegister['open_register']) return back();
        return HomeView('index.register', $loginRegister);
    }

    //忘记密码
    public function forgot() {
        if ($this->request->isMethod("post")) {
            $api = new \Modules\Main\Http\Controllers\Api\LoginController();
            return $api->forgot();
        }
        $loginRegister = hook('GetHomeBasicConfig', ['moduleName' => 'System'])[0]['login_register'];
        if (!$loginRegister['open_register']) return back();
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
        $request->offsetSet('uid', session(SessionKey::HomeInfo)['uid'] * 1);
        $api = new \Modules\Main\Http\Controllers\Api\LoginController();
        return $api->sendCode($request);
    }
}
