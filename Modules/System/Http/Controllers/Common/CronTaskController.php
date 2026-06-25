<?php

namespace Modules\System\Http\Controllers\Common;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\System\Services\SitemapService;

class CronTaskController extends CommonController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function updateSitemap()
    {
        $result = app(SitemapService::class)->generate();

        return returnArr(200, '更新成功，共生成 ' . $result['count'] . ' 条 URL');
    }

    public function warmSystemSettingsCache()
    {
        Cache::forget('settings');
        Cache::forget('homelangList');
        cacheGlobalSettings(2);

        return returnArr(200, '系统配置缓存预热成功');
    }
}
