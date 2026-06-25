<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2023/6/25
 * Time: 16:54
 */

namespace Modules\System\Http\Requests;


trait verifyFunction {
    protected static function normalizeRequestHost($request): string {
        $host = '';
        if (is_object($request) && method_exists($request, 'getHost')) {
            $host = (string) $request->getHost();
        }

        if ($host === '') {
            $host = (string) ($request->server('HTTP_HOST') ?: $request->server('SERVER_NAME'));
        }

        $host = strtolower(trim($host));
        $host = explode(':', $host)[0] ?? $host;

        return trim($host, '.');
    }

    //获取域名绑定模块
    public static function domainGetBindModule($request) {
        $host = self::normalizeRequestHost($request);
        if ($host === '') {
            return false;
        }

        $record = \Modules\System\Models\ModuleBindDomain::query()
            ->with(['module_data' => function ($query) {
                $query->where('status', 1);
            }])
            ->where('num', '>', 0)
            ->get();

        foreach ($record as $item) {
            $domains = array_filter(array_map('trim', explode(',', (string) $item->domain)));
            $domains = array_map(function ($domain) {
                return strtolower(trim($domain, '.'));
            }, $domains);

            if (in_array($host, $domains, true) && $item->module_data) {
                return $item->module_data;
            }
        }

        return false;
    }
}
