<?php

namespace Modules\System\Listeners;

class GetPluginUploadConfig
{

    public function handle( \App\Events\GetPluginUploadConfig $event) {
        //事件逻辑 ...
        $pageData = $event->data['pageData'];//获取事件数据

    }

}
