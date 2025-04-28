<?php

namespace Modules\Main\Helper;

use Illuminate\Support\Facades\Cache;
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

    public static function theme_lang_pack($theme,$langkey,$lang = "zh") {
        /**
         内置多语言
         模板自定义多语言
        ***/
        if(request()->get("lang") && request()->get("lang") != session()->get('homelang')){
            session()->put('homelang',request()->get("lang"));
            Cache::put("homelangList",null);
        }
        if(session()->get('homelang')){
            $lang = session()->get('homelang');
        }
        if(Cache::get("homelangList")){
            $langList = Cache::get("homelangList");
        }else{
            $langList = include public_path('views/themes/'.$theme.'/lang/'.$lang.'/lang.php');
            Cache::put("homelangList",$langList);
        }
        return isset($langList[$langkey]) ? $langList[$langkey] : $langkey;
    }
}
