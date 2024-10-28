<?php

namespace Modules\System\Listeners;

use Modules\Main\Models\Member;
use Modules\Main\Services\ServiceModel;
use Modules\System\Http\Controllers\Common\SessionKey;

class UserRegister {

    public function handle(\Modules\System\Events\UserRegister $event) {
        //事件逻辑 ...

        $moduleName = ucfirst($event->data['moduleName']);
        //参数
        $all = $event->data['all'];
        //登录注册配置
        $loginRegister = $event->data['loginRegister'];
        if (!$loginRegister['open_register']) return returnArr(0, "未开放注册");

        if ($if = ifCondition([
            'username' => '用户名不能为空',
            'password' => '密码不能为空',
            'confirm_password' => '确认密码不能为空',
            'email' => '邮箱不能为空',
            'phone' => '手机号不能为空',
        ], $all)) return $if;

        if ($loginRegister['agreementList'] && !$all['agree']) return returnArr(0, "请勾选协议");

        //验证提交验证码
        if ($loginRegister['open_code_verify']) {
            $checkVerifyCode = hook('GetSendCode', ['moduleName' => 'System', 'object_type' => 'captcha', 'operate_type' => 'verify', 'captcha' => $all['captcha']])[0];
            if ($checkVerifyCode['status'] != 200) return returnArr(0, $checkVerifyCode['msg']);
        }

        //验证邮件验证码
        if ($loginRegister['open_email_verify']) {
            $emailArr = $all;
            $emailArr['moduleName'] = 'System';
            $emailArr['operate_type'] = 'verify';
            $emailArr['code_type'] = 2;
            $checkEmailCode = hook('GetSendEmail', $emailArr)[0];
            if ($checkEmailCode['status'] != 200) return returnArr(0, $checkEmailCode['msg']);
        }

        //验证手机验证码
        if ($loginRegister['open_phone_verify']) {
            $phoneArr = $all;
            $phoneArr['moduleName'] = 'System';
            $phoneArr['operate_type'] = 'verify';
            $phoneArr['code_type'] = 2;
            $checkPhoneCode = hook('GetSendPhone', $phoneArr)[0];
            if ($checkPhoneCode['status'] != 200) return returnArr(0, $checkPhoneCode['msg']);
        }

        //用户注册
        $add = ServiceModel::InsertArr($all);
        if ($add['status'] != 200) return returnArr(0, $add['msg']);
        return returnArr(200, '注册成功');
    }

}
