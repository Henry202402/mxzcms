<?php

namespace Modules\System\Listeners;

class CmsUpdateVersion
{

    public function handle( \Modules\System\Events\CmsUpdateVersion $event) {
        //事件逻辑 ...
        $pageData = $event->data;//获取事件数据

        $return = '<a style="cursor: pointer;"  class="hiden text-danger '.$pageData["cssClass"].' " onclick="cmsUpdateVersion()">有新版本</a>';

        return $return;
    }

}
