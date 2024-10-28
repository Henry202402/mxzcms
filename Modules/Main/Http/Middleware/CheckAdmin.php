<?php

namespace Modules\Main\Http\Middleware;

use Closure;
use Modules\System\Http\Controllers\Common\SessionKey;

class CheckAdmin {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $userInfo = session(SessionKey::AdminInfo);
        //检查是否登录，权限检查
        $path = $request->path();
        if (explode('/', $path)[0] != 'admin') {
            return $next($request);
        }
        if (empty($userInfo)) {
            //获取当前路径，登录后依然回到改路径
            //session()->put("admin_previous",url()->current());
            return redirect("admin/login");
        }

        if ($userInfo['uid'] != 1 && session(SessionKey::CurrentUserPermissionGroupInfo)['type'] != 'admin') {
            if (session(SessionKey::NoAuthNum) > 5 || !session(SessionKey::CurrentUserPermissionGroupInfo)) {
                session([SessionKey::NoAuthNum => 0]);
                $authModuleName = array_keys(session(SessionKey::CurrentUserPermissionGroupInfo)['role_array'] ?: [])[0];
                if ($authModuleName) return redirect(moduleAdminJump(strtolower($authModuleName), 'logout'));
                return redirect("admin/logout");
            }
            session([SessionKey::NoAuthNum => session(SessionKey::NoAuthNum) + 1]);
            return back()->with('pageDataMsg', '没权限')->with('pageDataStatus', '0');
        }

        session([
            'now_module' => 'main',
        ]);
//        event(new CheckVersionUpdate($request));
        cacheGlobalSettings();
        return $next($request);
    }
}




