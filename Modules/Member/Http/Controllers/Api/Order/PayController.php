<?php

namespace Modules\Member\Http\Controllers\Api\Order;

use Illuminate\Http\Request;
use Modules\Main\Services\ServiceModel;
use Modules\Member\Http\Controllers\Api\JWTController;
use Illuminate\Support\Facades\DB;
use Modules\Member\Models\VipOrder;
use Modules\Member\Models\Wallet;
use Modules\Member\Models\WalletRecord;


class PayController extends JWTController {

    public function __construct(Request $request) {
        parent::__construct($request);
    }

    //购买Vip回调接口
    public function orderBuyVipCallback($data) {
        if ($data['order_info']) {
            $order = $data['order_info'];
        } else {
            $order = VipOrder::query()->where('order_num', $data['out_trade_no'])->first();
        }
        if (!$order) return returnArr(0, '订单不存在');
        if ($order['pay_status'] != 0) return returnArr(0, '状态已改');

        DB::beginTransaction();
        $up = [
            'payment_id' => $data['payment_id'],
            'pay_status' => $data['status'],
            'pay_at' => $data['status'] == 1 ? getDay() : '',
            'callback_msg' => json_encode($data, JSON_UNESCAPED_UNICODE),
        ];
        //更新订单
        $upOrder = ServiceModel::whereUpdate(VipOrder::TABLE_NAME, ['order_id' => $order['order_id']], $up);

        //更新用户vip过期时间
        $find_wallet = ServiceModel::apiGetOneArray(Wallet::TABLE_NAME, ['uid' => $order['uid']]);
        if ($find_wallet['vip_time'] > getDay(2)) {
            $time = strtotime($find_wallet['vip_time']);
        } else {
            $time = time();
        }
        if ($order['type'] == 1) {
            $unit = 'year';
        } elseif ($order['type'] == 2) {
            $unit = 'month';
        } elseif ($order['type'] == 3) {
            $unit = 'day';
        }
        $up_wallet['vip_time'] = date("Y-m-d", strtotime("+{$order['number']}{$unit}", $time));
        $upWallet = ServiceModel::whereUpdate(Wallet::TABLE_NAME, ['uid' => $order['uid']], $up_wallet);

        //添加记录
        $addRecord = hook('AddWalletRecord', [
            'moduleName' => 'Member',
            'order_num' => $order['order_num'],
            'module' => $this->moduleName,
            'uid' => $order['uid'],
            'type' => WalletRecord::subtract,
            'amount_type' => "购买VIP",
            'amount' => $order['price'],
            'unit' => '元',
            'remark' => "购买VIP",
            'extra' => json_encode([
                'order_id' => $order['order_id'],
                'order_num' => $order['order_num'],
                'uid' => $order['uid'],
                'price' => $order['price'],
                'vip_id' => $order['vip_id'],
                'type' => $order['type'],
                'number' => $order['number'],
            ], JSON_UNESCAPED_UNICODE),
        ])[0]['status'];

        if ($upOrder && $upWallet && $addRecord) {
            DB::commit();

            hook('UpdateUserMessage', [
                'moduleName' => 'System',
                'operate_type' => 6,
                'module' => $this->moduleName,
                'uid' => $order['uid'],
                'title' => '购买VIP',
                'content' => "恭喜您，成功开通VIP。",
            ]);

            if ($data['api_return']) return returnArr(200, '支付成功');
        } else {
            DB::rollBack();
            if ($data['api_return']) return returnArr(0, '支付失败');
        }
    }
}
