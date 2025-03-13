<?php

namespace Modules\Member\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Main\Models\Member;

class WalletRecord extends Model {
    //设置表名
    const TABLE_NAME = 'module_member_wallet_record';
    public $table = self::TABLE_NAME;
    public $primaryKey = 'id';
    public $timestamps = false;
    public $guarded = [];

    const add = 1;
    const subtract = 2;

    const withdraw = 1;
    const balance = 2;
    const integrate = 3;
    const vip_time = 4;

    //类型
    public static function type() {
        return [
            self::add => '增加',
            self::subtract => '减少',
        ];
    }

    //操作对象类型
    public static function amount_type() {
        return [
            self::withdraw => '可提现余额',
            self::balance => '余额',
            self::integrate => '积分',
            self::vip_time => '在线支付',
        ];
    }

    public static function add($bill_order_num, $order_num, $module, $uid, $type, $amount_type, $amount, $unit, $remark = '', $extra = '') {
        return self::query()->insertGetId([
            'bill_order_num' => $bill_order_num,
            'order_num' => $order_num,
            'module' => $module,
            'uid' => $uid,
            'type' => $type,
            'amount_type' => $amount_type,
            'amount' => $amount,
            'unit' => $unit,
            'remark' => $remark,
            'extra' => $extra,
            'created_at' => getDay(),
            'updated_at' => getDay(),
        ]);
    }

    public function user() {
        return $this->hasOne(Member::class, 'uid', 'uid');
    }

    public static function getBill($all) {
        return self::query()
            ->where('uid', $all['uid'])
            ->latest()
            ->paginate(getLen($all));
    }
}
