<?php

namespace Modules\Formtools\Listeners;

use Illuminate\Support\Facades\Cache;

class GetFormToolsModules
{

    public function handle(\Modules\Formtools\Events\GetFormToolsModules $event) {
        $modules = Cache::get(\Mxzcms\Modules\cache\CacheKey::ModulesActive);
        foreach ($modules as $value) {
            $temp = config()->get("modules.".strtolower($value['identification']));
            $value['addmodel'] = $temp['addmodel'];
            if($value['addmodel'] && $value['addmodel']=="y"){
                $event->modules[] = $value;
            }
        }
        return $event->modules;
    }
}
