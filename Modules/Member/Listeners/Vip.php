<?php

namespace Modules\Member\Listeners;


use Modules\Main\Services\ServiceModel;
use Modules\System\Helper\Func;

class Vip {

    public function handle(\Modules\Member\Events\Vip $event) {
        $all = $event->data;
        switch ($all['operate_type']) {
            case 'getList':
                return $this->getVipList($all);
                break;
        }
    }


    //获取vip列表
    public function getVipList($all) {

        $data = \Modules\Member\Models\Vip::query()
            ->where('status', 1)
            ->orderByDesc('sort')
            ->latest()
            ->get([
                'id',
                'name',
                'price',
                'discount_price',
                'tig',
                'type',
                'number',
                'describe',
                'is_only_buy_one',
            ])
            ->toArray();
        foreach ($data as &$d) {
            $d['price'] *= 1;
            $d['discount_price'] *= 1;
            if ($d['type'] == 1) {//年
                $unit = Func::numberFormat($d['price'] / ($d['number'] * 365), 3) * 1;

            } elseif ($d['type'] == 2) {//月
                $unit = Func::numberFormat($d['price'] / ($d['number'] * 30), 3) * 1;

            } elseif ($d['type'] == 3) {//日
                $unit = Func::numberFormat($d['price'] / $d['number'], 3) * 1;
            }
            $d['price_msg'] = "折合 ￥ {$unit}/每天";
            unset($d['type'], $d['number']);
        }
        return returnArr(20, '成功', $data);
    }

}
