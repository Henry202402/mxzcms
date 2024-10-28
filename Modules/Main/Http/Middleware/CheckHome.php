<?php

namespace Modules\Main\Http\Middleware;

use Closure;
use Mxzcms\Modules\session\SessionKey;


class CheckHome {
    public function handle($request, Closure $next) {
        $topMenu = hook('GetHomeMenu', ['moduleName' => 'System', 'position' => 'top'])[0];
        $bottomMenu = hook('GetHomeMenu', ['moduleName' => 'System', 'position' => 'bottom'])[0];
        $footerMenu = hook('GetHomeMenu', ['moduleName' => 'System', 'position' => 'footer'])[0];
        $userInfo = session(SessionKey::HomeInfo);
        view()->share([
            'homeMenu' => [
                'topMenu' => $topMenu,
                'bottomMenu' => $bottomMenu,
                'footerMenu' => $footerMenu,
            ],
            'userInfo' => $userInfo
        ]);
        return $next($request);
    }
}




