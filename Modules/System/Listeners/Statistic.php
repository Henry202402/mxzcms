<?php

namespace Modules\System\Listeners;

use Modules\Main\Libs\UPDATECMS;

class Statistic
{

    public function handle( \Modules\System\Events\Statistic $event) {
        call_user_func([new UPDATECMS(),"statistic"],$event->data);
    }

}
