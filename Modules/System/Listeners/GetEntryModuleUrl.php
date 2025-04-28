<?php

namespace Modules\System\Listeners;

use Modules\Main\Models\Modules;
use Modules\System\Http\Controllers\Common\SessionKey;

class GetEntryModuleUrl {

    //获取进入模块首个url入口
    public function handle(\App\Events\GetEntryModuleUrl $event) {
        $userInfo = session(SessionKey::AdminInfo);
        //事件逻辑 ...
        $pageData = $event->data;
        $moduleName = $pageData['moduleName'];
        $roleArray = session(SessionKey::CurrentUserPermissionGroupInfo);
        if (!$roleArray) {
            $getPermissionGroupInfoList = hook('GetPermissionGroupInfoList', ['uid' => $userInfo['uid']])[0];
            $groupListInfo = $getPermissionGroupInfoList['list'];
            $group = $getPermissionGroupInfoList['group'];
            $roleArray = $groupListInfo[$group['group_id']];
        }
        $url = '';
        if ($roleArray['type'] == 'admin') {
            //管理员直接获取菜单文件
            $config = include module_path($moduleName, "Config/menus.php");
            foreach ($config as $cValue) {
                if ($cValue['url'] && $cValue['url'] != '#') {
                    $url = $cValue['url'];
                    break;
                }
                foreach ($cValue['submenu'] as $submenu) {
                    if ($submenu['url'] && $submenu['url'] != '#') {
                        $url = $submenu['url'];
                        break 2;
                    }
                }
            }
        } else {
            //用户获取模块授权的路径
            foreach ($roleArray['role_array'][$moduleName] as $cValue) {
                if (strpos($cValue, strtolower($moduleName)) !== false) {
                    $url = $cValue;
                    break;
                }
            }
        }

        Modules::query()
        ->where("identification",'=',$moduleName)
        ->where('cloud_type','=','module')
        ->update(['updated_at'=>date('Y-m-d H:i:s')]);

        return url($url);
    }

}
