<?php

namespace Modules\System\Listeners;

class Loger
{

    public function handle( \App\Events\Loger $event) {
        //事件逻辑 ...
        $pageData = $event->data['pageData'];//获取事件数据

    }

}
