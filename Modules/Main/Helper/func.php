<?php

namespace Modules\Main\Helper;

use App\Support\I18n\ThemeTranslator;
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

    public static function theme_lang_pack($theme, $langkey, $lang = "zh") {
        return ThemeTranslator::translate($langkey, [], $theme, $lang);
    }


    //获取历史入口
    public static function getHistoricalEntry() {
        $key = 'MainHistoricalEntryList';
        $data = Cache::get($key) ?: [];
        array_multisort(array_column($data, 'timestamp'), SORT_DESC, $data);
        return $data;
    }

    //保存历史入口 1=单储存 2=整个储存
    public static function saveHistoricalEntry($type = 1, $array = []) {
        $key = 'MainHistoricalEntryList';
        if ($type == 2) {
            $list = $array;
        } else {
            $list = Cache::get($key) ?: [];
            $array['timestamp'] = time();
            $list[$array['module']] = $array;
        }
        Cache::put($key, $list, 60 * 24 * 365);
    }
}
