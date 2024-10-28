<?php

namespace Modules\System\Listeners;

use Illuminate\Support\Facades\Cache;
use Mxzcms\Modules\cache\CacheKey;

class GetAdminViewNav {
    //获取后台顶部导航
    public function handle(\Modules\System\Events\GetAdminViewNav $event) {
        //事件逻辑 ...
        $moduleName = $event->data['data']['moduleName'];
        $userInfo = $event->data['data']['userInfo'];
        $groupListInfo = hook('GetPermissionGroupInfoList', ['moduleName' => 'auth'])[0]['list'];
        $roleArray = $groupListInfo[$userInfo['group_id']];
        $moduleList = array_column(Cache::get(CacheKey::ModulesActive), 'name', 'identification');

        $moduleArray = [];
        foreach ($roleArray['role_array'] as $key => $value) {
            $moduleArray[] = [
                'identification' => $key,
                'name' => $moduleList[$key],
            ];
        }
        return view('system::admin.public.navTemplate', compact('moduleName', 'userInfo', 'moduleArray'));
    }

}
