<?php

namespace Modules\Main\Http\Middleware;

use Closure;
use Mxzcms\Modules\session\SessionKey;


class CheckHome {
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

    protected function isApiRequest($request): bool {
        return $request->is('api/*') || $request->expectsJson();
    }

    public function handle($request, Closure $next) {
        if ($this->isApiRequest($request)) {
            if ($this->resolveMaintenanceStatus('website_api_status') !== 1) {
                return response()->json([
                    'status' => 0,
                    'msg' => $this->resolveMaintenanceMessage('website_api_status_when'),
                ], 503, [], JSON_UNESCAPED_UNICODE);
            }

            return $next($request);
        }

        if ($this->resolveMaintenanceStatus('website_pc_status') !== 1) {
            return response()->view('error.repair', [
                'maintenanceMessage' => $this->resolveMaintenanceMessage('website_pc_status_when'),
            ], 503);
        }
        $menuLang = currentHomeLang();
        $topMenu = hook('GetHomeMenu', ['moduleName' => 'System', 'position' => 'top', 'lang' => $menuLang])[0];
        $bottomMenu = hook('GetHomeMenu', ['moduleName' => 'System', 'position' => 'bottom', 'lang' => $menuLang])[0];
        $footerMenu = hook('GetHomeMenu', ['moduleName' => 'System', 'position' => 'footer', 'lang' => $menuLang])[0];
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




