<?php

namespace Modules\Main\Http\Middleware;

use Closure;
use Mxzcms\Modules\session\SessionKey;

class AccessLog {

    public function handle($request, Closure $next) {
        $userInfo = session(SessionKey::HomeInfo);
        $routeArr = getURIByRoute($request);
        hook("Loger", [
            'module' => $routeArr['moduleName'],
            'type' => 1,
            'two_type' => 1,
            'params' => [],
            'remark' => "访问页面",
            'unique_id' => $userInfo['uid'] ?: '',
            'requestid' => $request->requestid
        ]);
        return $next($request);
    }
}
