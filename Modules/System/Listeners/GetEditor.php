<?php

namespace Modules\System\Listeners;

class GetEditor {

    public function handle(\App\Events\GetEditor $event) {
        $data = $event->data;
        $data['moduleName'] = __E("editor_driver");
        $data['cloudType'] = "plugin";
        return hook("SetEditor",$data)[0];
    }
}
