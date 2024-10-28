<?php

namespace Modules\System\Listeners;

use Modules\Main\Services\ServiceModel;

class GetHomeMenu {

    public function handle(\Modules\System\Events\GetHomeMenu $event) {
        //事件逻辑 ...
        $data = $event->data;
        $list['child'] = ServiceModel::getHomeMenuList(['position' => $data['position']]);
        $list = self::dealUrl($list);
        return $list['child'];
    }

    public static function dealUrl($l) {
        if($l['child']){
            foreach ($l['child'] as &$child) {
                unset($child['pid']);
                if ($child['url'] && $child['url'] != '#' && strpos($child['url'], 'http') === false) {
                    $child['url'] = url($child['url']);
                }
                $child = self::dealUrl($child);
            }
        }
        return $l;
    }

}
