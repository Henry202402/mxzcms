<?php

namespace Modules\System\Helper;

trait Func {
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
    public static function dealArrayToTwoArrayV2($data, $valueKey = 'id', $nameKey = 'name') {
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

    //处理距离
    public static function dealDistance($distance) {
        if ($distance < 1000) {
            $str = "{$distance}m";
        } else {
            $distance = $distance / 100;
            $distance = self::numberFormat($distance, 1);
            $distance = $distance / 10;
            $str = "{$distance}km";
        }
        return $str;
    }

    public static function twoArraySort($data, $field, $arg = SORT_DESC) {
        $last_names = array_column($data, $field);
        array_multisort($last_names, $arg, $data);
        return $data;
    }

    public static function getPluginsConfig($pluginName) {
        $configArray = include PLUGIN_PATH . "/{$pluginName}/Config/config.php";
        $config = [];
        foreach ($configArray['config'] as $key => $item) {
            $config[$key] = $item['value'];
        }
        return $config;
    }
}
