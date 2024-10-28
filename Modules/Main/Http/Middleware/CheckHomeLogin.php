<?php

namespace Modules\Main\Http\Middleware;

use Closure;
use Modules\Main\Services\ServiceModel;
use Mxzcms\Modules\session\SessionKey;


class CheckHomeLogin {
    public function handle($request, Closure $next) {
        $userInfo = session(SessionKey::HomeInfo);
        if ($userInfo['uid'] <= 0) return redirect(url('login'));

        $userInfo['messageNum'] = ServiceModel::getNoReadMessageNum($userInfo['uid']);
        view()->share([
            'userInfo' => $userInfo
        ]);
        return $next($request);
    }
}




