<?php

namespace Modules\System\Listeners;

class GetEditorList
{

    public function handle( \App\Events\GetEditorList $event) {
        //事件逻辑 ...
        $pageData = $event->data['pageData'];//获取事件数据

    }

}
