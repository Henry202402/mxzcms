<?php

namespace Modules\System\Listeners;

use Modules\Main\Models\Member;
use Modules\Main\Services\ServiceModel;
use Modules\System\Http\Controllers\Common\SessionKey;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserLogin {

    public function handle(\Modules\System\Events\UserLogin $event) {
        //事件逻辑 ...

        $moduleName = ucfirst($event->data['moduleName']);
        //参数
        $all = $event->data['all'];
        //登录注册配置
        $loginRegister = $event->data['loginRegister'];
        if (!$loginRegister['open_login']) return returnArr(0, "未开放登录");

        if (!$event->data['api_login']) {
            $verify = hook('GetSendCode', ['moduleName' => 'System', 'object_type' => 'captcha', 'operate_type' => 'verify', 'captcha' => $all['captcha']])[0];
            if ($verify['status'] != 200) return $verify;
        }
        //用户登录
        $check = Member::query()
            ->where("status", 1)
            ->where(function ($q)use($all){
                $q->where("email", $all['name'])->orWhere("phone", $all['name'])->orWhere("username", $all['name']);
            })
            ->first();
        if (!$check) return returnArr(0, "用户不存在");
        $password = ServiceModel::getPassword($all['password']);
        if ($password != $check->password) return returnArr(0, "密码不正确");
        $data = $check->toArray();
        $data['avatar'] = GetUrlByPath($data['avatar']);

        /*if ($event->data['api_login']) {
            $token = JWTAuth::fromUser($data);
            $data['token'] = $token;
        }*/

        session([SessionKey::HomeInfo => $data]);
        session()->save();
        return returnArr(200, '登录成功', $data);
    }

}
