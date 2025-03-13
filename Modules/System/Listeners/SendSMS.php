<?php

namespace Modules\System\Listeners;

class SendSMS
{

    public function handle( \App\Events\SendSMS $event) {
        //事件逻辑 ...
        $pageData = $event->data;//获取事件数据

    }

}
