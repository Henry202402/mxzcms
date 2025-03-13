<?php

namespace Modules\Member\Models;

use Illuminate\Database\Eloquent\Model;

class VipOrder extends Model {
    //设置表名
    const TABLE_NAME = 'module_member_vip_order';
    public $table = self::TABLE_NAME;
    public $primaryKey = 'order_id';
    public $timestamps = false;
    public $guarded = [];

    public static function type() {
        return [
            1 => '年',
            2 => '月',
            3 => '日',
        ];
    }

    public static function buyVipNum($uid) {
        return self::query()->where('uid', $uid)->where('pay_status', 1)->count('order_id');
    }

    public static function getBill($all) {
        return self::query()
            ->where('uid', $all['uid'])
            ->where('pay_status', 1)
            ->latest()
            ->paginate(getLen($all));
    }
}
