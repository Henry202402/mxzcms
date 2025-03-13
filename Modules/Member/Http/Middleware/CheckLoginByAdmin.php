<?php

namespace Modules\Member\Http\Middleware;
use Closure;
use function dump;

class CheckLoginByAdmin {
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct() {
        //
    }


    public function handle($request, Closure $next) {
        //模块自己的判断登录
        $userInfo = session(\Modules\System\Http\Controllers\Common\SessionKey::AdminInfo);
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


        return $next($request);
    }
}
