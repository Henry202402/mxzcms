<?php

namespace Modules\Auth\Listeners;

use Illuminate\Support\Facades\Cache;
use Modules\Auth\Http\Controllers\Common\CacheKey;

class SetPermissionGroupInfoList {

    public function handle(\App\Events\SetPermissionGroupInfoList $event) {
        //事件逻辑 ...
        $data = $event->data;
        $id = $data['id'];
        $array = is_array($data['array'] ?? null) ? $data['array'] : [];
        $cache = hook('GetPermissionGroupInfoList', ['pageData' => ['type' => 1]])[0]['list'];
        if (!empty($array['remove'])) {
            unset($cache[$id]);
            Cache::put(CacheKey::GroupListInfo, $cache);
            return;
        }

        if (!isset($cache[$id])) {
            $cache[$id] = [];
        }

        if (array_key_exists('type', $array)) $cache[$id]['type'] = $array['type'];
        if (array_key_exists('group_name', $array)) $cache[$id]['group_name'] = $array['group_name'];
        if (array_key_exists('role_array', $array)) $cache[$id]['role_array'] = $array['role_array'];
        Cache::put(CacheKey::GroupListInfo, $cache);
    }

}
