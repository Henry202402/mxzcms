<?php

namespace Modules\Member\Listeners;


use Modules\Member\Helper\Func;
use Modules\Member\Models\WalletRecord;

class AddWalletRecord {

    public function handle(\Modules\Member\Events\AddWalletRecord $event) {
        $data = $event->data;
        //生成订单号
        $bill_order_num = Func::getOrderNum(WalletRecord::TABLE_NAME, 'bill_order_num');
        if (!$bill_order_num) return returnArr(0, '生成系统订单号失败');
        $addWalletRecord = WalletRecord::add(
            $bill_order_num,
            $data['order_num'] ?: $data->order_num,
            ucfirst($data['module'] ?: $data->module),
            $data['uid'] ?: $data->uid,
            $data['type'] ?: $data->type,
            $data['amount_type'] ?: $data->amount_type,
            $data['amount'] ?: $data->amount,
            $data['unit'] ?: ($data->unit ?: ''),
            $data['remark'] ?: ($data->remark ?: ''),
            $data['extra'] ?: ($data->extra ?: ''));
        if ($addWalletRecord) {
            return returnArr(200, '添加成功');
        } else {
            return returnArr(0, '添加失败');
        }
    }

}
