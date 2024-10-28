<?php

namespace Modules\Main\Helper;

use Tymon\JWTAuth\Facades\JWTAuth;

trait Func {
    public static function admin_api_current_user() {
        $request = \Request();
        if ($request->isMethod('OPTIONS')) exit();
        if ($request->home_user_info) {
            return $request->home_user_info;
        } else {
            try {
                $user = JWTAuth::toUser(JWTAuth::getToken());     //获取用户名
                if (!$user) die(json_encode(['status' => 0, 'msg' => '获取用户信息错误']));
            } catch (\Exception $exception) {
                die(json_encode(['status' => 0, 'msg' => '获取用户信息错误']));
            }
            return $user;
        }
    }
}