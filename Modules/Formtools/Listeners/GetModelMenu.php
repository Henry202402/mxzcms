<?php

namespace Modules\Formtools\Listeners;

use Modules\Formtools\Models\FormModel;

class GetModelMenu {

    public function handle(\Modules\Formtools\Events\GetModelMenu $event) {
        //事件逻辑 ...
        $data = $event->data;
        $list = FormModel::query()->get(['name', 'access_identification'])->toArray();
        foreach ($list as &$l) {
            if(!$l['access_identification']){
                unset($l);
                continue;
            }
            $l['url'] = 'list/' . $l['access_identification'];
        }
        return ['identification' => 'Formtools', 'menuList' => $list];
    }

}
