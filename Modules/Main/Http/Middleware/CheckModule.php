<?php

namespace Modules\Main\Http\Middleware;

//use App\Events\CheckVersionUpdate;
use Closure;

class CheckModule {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $website_status = cacheGlobalSettingsByKey('website_status');
        if ($website_status != 1) {
            die(json_encode(['status' => 0, 'msg' => cacheGlobalSettingsByKey('website_status_when')], JSON_UNESCAPED_UNICODE));
        }
        return $next($request);
    }
}




