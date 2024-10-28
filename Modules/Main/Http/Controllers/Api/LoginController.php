<?php

namespace Modules\Main\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Main\Models\Member;
use Modules\Main\Services\ServiceModel;
use Modules\ModulesController;
use Modules\System\Http\Controllers\Common\SessionKey;
use Tymon\JWTAuth\Facades\JWTAuth;


class LoginController extends ModulesController {

    //登录
    public function login() {
        $loginRegister = hook('GetHomeBasicConfig', ['moduleName' => 'System'])[0]['login_register'];
        if (!$loginRegister['open_login']) return returnArr(0, '未开放登录');
        $all = $this->request->all();
        //用户登录
        $check = hook('UserLogin', ['moduleName' => 'System', 'all' => $all, 'loginRegister' => $loginRegister, 'api_login' => $all['api_login']])[0];
        if ($check['status'] != 200) return returnArr(0, $check['msg']);
        return $check;
    }

    //注册
    public function register() {
        $loginRegister = hook('GetHomeBasicConfig', ['moduleName' => 'System'])[0]['login_register'];
        if (!$loginRegister['open_register']) return returnArr(0, '未开放注册');
        $all = $this->request->all();
        //用户注册
        $check = hook('UserRegister', ['moduleName' => 'System', 'all' => $all, 'loginRegister' => $loginRegister])[0];
        if ($check['status'] != 200) return returnArr(0, $check['msg']);
        return returnArr(200, '注册成功', ['url' => url("login")]);
    }

    //忘记密码
    public function forgot() {
        $all = $this->request->all();
        if (!$all['new_password']) return returnArr(0, '新密码不能为空');
        if (!$all['confirm_password']) return returnArr(0, '确认密码不能为空');
        if ($all['new_password'] !== $all['confirm_password']) return returnArr(0, '两次密码不一致');

        if ($all['verify_type'] == 'phone') {
            if (!$all['phone']) return returnArr(0, '手机不能为空');
            if (!$all['phone_captcha']) return returnArr(0, '手机验证码不能为空');
            //验证手机号和验证码
            $emailArr = $all;
            $emailArr['moduleName'] = 'System';
            $emailArr['operate_type'] = 'verify';
            $checkEmailCode = hook('GetSendPhone', $emailArr)[0];
            if ($checkEmailCode['status'] != 200) return returnArr(0, $checkEmailCode['msg']);
            $user = ServiceModel::apiGetOne(Member::TABLE_NAME, ['phone' => $all['phone']]);
        } elseif ($all['verify_type'] == 'email') {
            if (!$all['email']) return returnArr(0, '邮箱不能为空');
            if (!$all['email_captcha']) return returnArr(0, '邮箱验证码不能为空');

            //验证邮箱和验证码
            $emailArr = $all;
            $emailArr['moduleName'] = 'System';
            $emailArr['operate_type'] = 'verify';
            $checkEmailCode = hook('GetSendEmail', $emailArr)[0];
            if ($checkEmailCode['status'] != 200) return returnArr(0, $checkEmailCode['msg']);
            $user = ServiceModel::apiGetOne(Member::TABLE_NAME, ['email' => $all['email']]);
        } else {
            return returnArr(0, '验证类型不能为空');
        }
        if ($user['uid'] <= 0) return returnArr(0, '用户查询错误');
        //用户更新资料
        $row['password'] = $all['new_password'];
        $row['moduleName'] = 'System';
        $row['home_key'] = SessionKey::HomeInfo;
        $row['uid'] = $user['uid'];
        $res = hook('UpdateUserInfo', $row)[0];
        if ($res['status'] != 200) return returnArr(0, $res['msg']);
        return returnArr(200, '更新成功');
    }

    //退出
    public function logout() {
        if ($_SERVER['HTTP_AUTHORIZATION'] || $_GET['token']) JWTAuth::invalidate(JWTAuth::getToken());
        return returnArr(200, '退出成功');
    }

    //发送验证码
    public function sendCode(Request $request) {
        $all = $request->all();

        $all['moduleName'] = 'System';
        $all['operate_type'] = 'send';
        return hook('GetSendCode', $all)[0];
    }
}
