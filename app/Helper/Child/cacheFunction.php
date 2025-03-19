<?php

//获取全局settings缓存

use Illuminate\Support\Facades\Cache;
use Modules\System\Models\Setting;

function cacheGlobalSettings($type = 1) {

    if ($type == 1 && Cache::has('settings')) {
        return Cache::get('settings');
    }

    $settings = Setting::all();

    if ($settings) {
        $settings = $settings->toArray();
        foreach ($settings as $key => $value) {
            $all_setting[$value['module']][$value['key']] = $value;
        }
        Cache::put('settings', $all_setting);
    }

    return Cache::get('settings');


}

//获取全局settings缓存通过key
function cacheGlobalSettingsByKey($key,$module="Main", $field = "value") {
    return cacheGlobalSettings(1)[$module][$key][$field];
}

//获取全局settings缓存通过key
function __E($key,$module="Main", $field = "value") {
    $cache = Cache::get('settings')[$module][$key];
    return empty($cache[$field]) ? '' : $cache[$field];
}

/******************************** 模块信息 ***********************************/

//获取缓存模块信息
function getCacheModuleInfo($module, $key = '') {
    $array = Cache::get($module);
    return $key ? $array[$key] : $array;
}

//缓存模块信息
function cacheModuleInfo($module, $key, $data = []) {
    $array = getCacheModuleInfo($module);
    $array[$key] = $data;
    Cache::put($module, $array, 60 * 24 * 7);
}

//获取模块列表
function getModuleList() {
    $arr = Cache::get('moduleList');
    if (!$arr) return updateModuleList();
    return $arr;
}

function updateModuleList() {
    $list = \Modules\Main\Services\ServiceModel::getModuleList();
    $arr = [];
    foreach ($list as $l) {
        $arr[$l] = true;
    }
    Cache::put('moduleList', $arr, 60 * 24 * 7);
    return $arr;
}

//获取模块语言包
function getModuleLang($module, $lang_name, $key = '', $reset = 1) {
    $lang = getCacheModuleInfo($module, $lang_name);
    if (!$lang || $reset == 2) {
        $lang = [];
        $array = scandir(module_path($module) . '/Resources/lang');
        foreach ($array as $file) {
            if ($file != '.' && $file != '..' && strpos($file, '.php') !== false) {
                $local = explode('.', $file)[0];
                $lang[$local] = include (module_path($module) . '/Resources/lang/' . $file) ?: [];
            }
        }
        return $key ? $lang[$lang_name][$key] : $lang;
    } else {
        return $lang[$lang_name][$key];
    }
}

//判断权限
function permissions($path) {
    $route = explode('/', ltrim($_SERVER['REQUEST_URI'], '/'));
    $moduleName = $route[1];
    $config = include module_path($moduleName, 'Config/config.php');
    if ($config['auth'] != 'y') return '';
    $url = "$route[0]/$route[1]/$path";
    $roleArray = session(\Modules\System\Http\Controllers\Common\SessionKey::CurrentUserPermissionGroupInfo);
    $roleModule = $roleArray['role_array'][ucfirst($moduleName)];
    if ($roleArray['type'] != 'admin' && !in_array($url, $roleModule)) {
        return 'hide';
    }
    return '';
}



