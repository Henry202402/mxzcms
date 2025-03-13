<?php

namespace Modules\Member\Helper;

use Illuminate\Support\Facades\Cache;
use Modules\Member\Models\BaseConfiguration;

trait Func {
    public static function getBaseConfig($name, $type = 1) {
        $info = Cache::get($name);
        if ($type == 2|| !$info) {
            $config = BaseConfiguration::getOne(['name' => $name]);
            $info = json_decode($config['json_str'], true);
            Cache::set($name, $info);
        }
        return $info;
    }

    //处理一维转二维，下拉，checkbox，radio
    public static function dealArrayToTwoArray($data) {
        $list = [];
        foreach ($data as $key => $value) {
            $list[] = [
                'value' => $key,
                'name' => $value,
            ];
        }
        return $list;
    }

    //处理一维转二维，下拉，checkbox，radio
    public static function dealArrayToTwoArray2($data, $valueKey = 'id', $nameKey = 'name') {
        $list = [];
        foreach ($data as $value) {
            $list[] = [
                'value' => $value[$valueKey],
                'name' => $value[$nameKey],
            ];
        }
        return $list;
    }

    public static function isAdmin() {
        $userInfo = session(\Modules\System\Http\Controllers\Common\SessionKey::AdminInfo);
        if ($userInfo['type'] == 'admin') return true;
        return false;
    }


    public static function deleteImage(...$args) {
        foreach ($args as $arg) {
            if ($arg) unlink(public_path('uploads/' . $arg));
        }
    }

    //数字保留几位
    public static function numberFormat($price, $decimal = 2) {
        return number_format($price, intval($decimal), '.', '');
    }

    // $table=哪个表 / $filed=订单号的字段
    static function getOrderNum($table, $filed) {
        $order_num = date('ymdHis') . rand(1000, 9999) . rand(1000, 9999);
        for ($i = 1; $i <= 3; $i++) {
            $find = \Illuminate\Support\Facades\DB::table($table)->where($filed, $order_num)->first();
            if (!$find) {
                return $order_num;
                break;
            }
        }
        return false;
    }
}