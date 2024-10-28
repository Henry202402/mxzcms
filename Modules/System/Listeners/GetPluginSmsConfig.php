<?php

namespace Modules\System\Listeners;

class GetPluginSmsConfig
{

    public function handle( \App\Events\GetPluginSmsConfig $event) {
        //事件逻辑 ...
        $pageData = $event->data['pageData'];//获取事件数据

    }

}
