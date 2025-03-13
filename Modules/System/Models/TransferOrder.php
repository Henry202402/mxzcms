<?php

namespace Modules\System\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class TransferOrder extends Model {
    //设置表名
    const TABLE_NAME = 'module_system_transfer_order';
    public $table = self::TABLE_NAME;
    public $primaryKey = 'id';
    public $timestamps = false;
    public $guarded = [];

    //添加数据
    static function InsertArr($params) {
        if (!is_array($params)) return ["status" => 0, "msg" => "失败"];

        $validator = Validator::make($params, [
            'order_num' => 'required',
            'module' => 'required',
            'action' => 'required',
            'pay_method' => 'required',
        ], [
            'order_num.required' => '订单号不能为空',
            'module.required' => '回调模块不能为空',
            'action.required' => '回调函数不能为空',
            'pay_method.required' => '支付方式不能为空',
        ]);
        if ($validator->fails()) return ["status" => 0, "msg" => $validator->errors()->first()];

        $find = self::getOrder($params['order_num']);
        if ($find) {
            if ($find['module'] == $params['module'] && $find['action'] == $params['action'] && $find['pay_method'] == $params['pay_method']) {
                return ["status" => 200, "msg" => "成功"];
            } else {
                return ["status" => 0, "msg" => "订单号已存在"];
            }
        }

        $add['order_num'] = $params['order_num'];
        $add['module'] = $params['module'];
        $add['action'] = $params['action'];
        $add['pay_method'] = $params['pay_method'];
        $add['create_at'] = date("Y-m-d H:i:s");
        if (self::query()->insertGetId($add))
            return ["status" => 200, "msg" => "成功"];
        else
            return ["status" => 0, "msg" => "失败"];
    }

    //获取数据
    static function getOrder($order_num) {
        $res = self::query()->where('order_num', $order_num)->latest('create_at')->first();
        return $res ? $res->toArray() : [];
    }
}
