<?php

namespace Modules\Auth\Listeners;

use Illuminate\Support\Facades\Cache;
use Modules\Auth\Http\Controllers\Common\CacheKey;

class SetPermissionGroupInfoList {

    public function handle(\App\Events\SetPermissionGroupInfoList $event) {
        //事件逻辑 ...
        $data = $event->data;
        $id = $data['id'];
        $array = $data['array'];
        $cache = hook('GetPermissionGroupInfoList', ['pageData' => ['type' => 1]])[0]['list'];
        if ($array['type']) $cache[$id]['type'] = $array['type'];
        if ($array['group_name']) $cache[$id]['group_name'] = $array['group_name'];
        if ($array['role_array']) $cache[$id]['role_array'] = $array['role_array'];
        Cache::put(CacheKey::GroupListInfo, $cache);
    }

}
