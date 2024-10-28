<?php

namespace Modules\Auth\Http\Middleware;

use Closure;
use Modules\Auth\Models\GroupUser;
use Modules\System\Http\Controllers\Common\SessionKey;

class CheckPermission {

    public function handle($request, Closure $next) {

        //模块自己的判断登录
        $userInfo = session(\Modules\System\Http\Controllers\Common\SessionKey::AdminInfo);
        //检查是否登录，权限检查
        if (!$userInfo['uid']) {
            session()->put("previous", url()->current());
            return redirect('/');
        }

        $route = getURIByRoute($request);
        $moduleName = $route['moduleName'];
        $getPermissionGroupInfoList = hook('GetPermissionGroupInfoList', ['uid' => $userInfo['uid']])[0];
        $groupListInfo = $getPermissionGroupInfoList['list'];
        $group = $getPermissionGroupInfoList['group'];
        $roleArray = $groupListInfo[$group['group_id']];
        $roleModule = $groupListInfo[$group['group_id']]['role_array'][$moduleName] ?: [];

        $config = include module_path($moduleName, 'Config/config.php');//模块是否需要权限
        if ($config['auth'] == 'y' && $roleArray['type'] != 'admin' && !in_array($route['uri'], $roleModule)) {
            return back()->with('pageDataMsg', '没权限')->with('pageDataStatus', '0');
        }
        session([SessionKey::CurrentUserPermissionGroupInfo => $roleArray]);
        $userInfo['type'] = $roleArray['type'];
        $userInfo['group_id'] = $group['group_id'];
        session([SessionKey::AdminInfo => $userInfo]);

        return $next($request);
    }
}
