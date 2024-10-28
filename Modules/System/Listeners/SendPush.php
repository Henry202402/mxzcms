<?php

namespace Modules\System\Listeners;

class SendPush
{

    public function handle( \App\Events\SendPush $event) {
        //事件逻辑 ...
        $pageData = $event->data['pageData'];//获取事件数据

    }

}
