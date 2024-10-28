<?php

namespace Modules\System\Http\Middleware;

//use App\Events\CheckVersionUpdate;
use Closure;
use Modules\System\Http\Controllers\Common\SessionKey;

class CheckModuleAdmin {
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
        if (!$userInfo['uid']) {
            /**
             * 不同的模块，返回自己的登陆界面
             */
            //获取当前路径，登录后依然回到该路径
            session()->put("previous", url()->current());
            return redirect('/');
        }

        $moduleName = strtolower(getURIByRoute($request)['moduleName']);

        session([
            'now_module' => $moduleName,
            'userInfo' => $userInfo,
        ]);

        view()->share(array(
            'userInfo' => $userInfo,
            'moduleName' => $moduleName,
        ));

//        dd($menu_data);
        return $next($request);
    }
}




