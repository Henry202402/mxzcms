<?php

namespace Modules\System\Listeners;

use Modules\System\Http\Controllers\Common\CronTaskController;

class GetCronJob
{
    public function handle(\App\Events\GetCronJob $event)
    {
        return ['System', [
            [CronTaskController::class, 'updateSitemap', '自动更新 sitemap.xml、sitemap.xml.gz 和 urllist.txt'],
            [CronTaskController::class, 'warmSystemSettingsCache', '预热系统设置缓存和多语言缓存'],
        ]];
    }
}

