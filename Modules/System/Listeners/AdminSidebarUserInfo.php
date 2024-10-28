<?php

namespace Modules\System\Listeners;

class AdminSidebarUserInfo
{

    public function handle( \App\Events\AdminSidebarUserInfo $event) {
        //事件逻辑 ...
        $pageData = $event->data['pageData'];
        return view('system::admin.public.sidebaruserinfo', compact('pageData'));
    }

}
