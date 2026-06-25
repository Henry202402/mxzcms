<?php

namespace Modules\System\Listeners;

class GetMemberEntry
{

    public function handle( \App\Events\GetMemberEntry $event) {
        //事件逻辑 ...
        $pageData = $event->data['pageData'];//获取事件数据
    }

}
