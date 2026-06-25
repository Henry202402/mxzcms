<?php

namespace Modules\Main\Http\Middleware;

//use App\Events\CheckVersionUpdate;
use Closure;

class CheckModule {
    protected function resolveMaintenanceStatus(string $key, string $legacyKey = 'website_status', int $default = 1): int {
        $value = cacheGlobalSettingsByKey($key);
        if ($value !== null && $value !== '') {
            return (int) $value;
        }

        $legacyValue = cacheGlobalSettingsByKey($legacyKey);
        if ($legacyValue !== null && $legacyValue !== '') {
            return (int) $legacyValue;
        }

        return $default;
    }

    protected function resolveMaintenanceMessage(string $key, string $legacyKey = 'website_status_when'): string {
        $value = cacheGlobalSettingsByKey($key);
        if ($value !== null && $value !== '') {
            return (string) $value;
        }

        return (string) (cacheGlobalSettingsByKey($legacyKey) ?: '网站维护中');
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if ($this->resolveMaintenanceStatus('website_api_status') !== 1) {
            return response()->json([
                'status' => 0,
                'msg' => $this->resolveMaintenanceMessage('website_api_status_when'),
            ], 503, [], JSON_UNESCAPED_UNICODE);
        }
        return $next($request);
    }
}




