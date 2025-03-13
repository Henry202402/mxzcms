<?php

namespace Modules\Member\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Main\Services\ServiceModel;
use Modules\Member\Helper\Func;
use Modules\Member\Models\Vip;
use Modules\Member\Models\VipOrder;
use Modules\Member\Models\ThreeLogin;
use Modules\Member\Models\Wallet;

class OrderController extends JWTController {
    public $user;

    public function __construct(Request $request) {
        parent::__construct($request);
        $this->user = $this->current_user();
    }

    //购买Vip
    public function buyVip(Request $request) {
        //支付事件调用
        $all = $request->all();
        $user = $this->user;
        $vip = Vip::query()->find($all['id']);
        if (!$vip) return returnArr(0, '记录不存在');

        if ($vip['is_only_buy_one'] == 1 && ServiceModel::apiGetOne(VipOrder::TABLE_NAME, ['uid' => $user['uid'], 'vip_id' => $vip['id'], 'pay_status' => 1])) return returnArr(0, '只能购买一次');

        if (!Wallet::checkWallet($user['uid'])) return returnArr(0, '钱包生成错误');

        //Vip价格
        $amount = $vip['discount_price'];
        if ($amount <= 0) return returnArr(0, 'Vip价格有误');

        $openid = $all['openid'] ?: '';
        $pay_method = $all['pay_method'] ?: 'WeChat';
        $pay_type = $all['pay_type'] ?: 'public';

        if (in_array($pay_type, ['public', 'small']) && !$openid) {
            $three_tig = returnArrGetThreePrefix()[$pay_method] . '_' . $pay_type;
            $openid = ThreeLogin::getUserOpenid($user['uid'], $three_tig, $openid);
            if (!$openid) return returnArr(0, 'openid有误');
        }

        //生成订单号
        $order_num = Func::getOrderNum(VipOrder::TABLE_NAME, 'order_num');
        if (!$order_num) return returnArr(0, '生成订单号失败');

        DB::beginTransaction();
        $add = [
            'order_num' => $order_num,
            'uid' => $user['uid'],

            'price' => $amount,
            'vip_id' => $vip['id'],
            'type' => $vip['type'],
            'number' => $vip['number'],

            'pay_method' => $all['pay_method'],
            'pay_type' => $all['pay_type'],
            'remark' => trim($all['remark']),
            'expire_at' => date('Y-m-d H:i:s', time() + (60 * 10)),
        ];
        $order_id = ServiceModel::add(VipOrder::TABLE_NAME, $add);
        if (!$order_id) {
            DB::rollBack();
            return returnArr(0, '生成订单失败');
        }

        $res = hook("Pay", ['moduleName' => __E("pay_driver"), 'cloudType' => "plugin", 'data' => [
            'request' => $request,
            'module' => $this->moduleName,
            'req_type' => 'pay',
            'action' => 'orderBuyVipCallback',
            'pay_method' => $pay_method,
            'pay_type' => $pay_type,
            'outTradeNo' => $order_num,
            'totalFee' => $amount,
            'openid' => $openid,
            'callback_data' => [],
        ]])[0];
        if ($res['status'] != 200) {
            DB::rollBack();
            return $res;
        }
        $data = $res['data'];
        $data['order_id'] = $order_id;
        DB::commit();
        return returnArr(200, '下单成功', $data);
    }

    //获取购买vip状态
    public function checkVipPayStatus(Request $request) {
        $uid = intval($this->user['uid']);
        if ($uid <= 0) return returnArr(0, '身份错误');
        $all = $this->request->all();
        //查询订单
        $findOrder = VipOrder::query()->where(['uid' => $uid, 'order_id' => $all['order_id']])->first();

        if (!$findOrder) return returnArr(0, '订单不存在');
        if ($findOrder['pay_status'] != 1) return returnArr(0, '待支付');
        return returnArr(200, '支付成功');
    }
}
