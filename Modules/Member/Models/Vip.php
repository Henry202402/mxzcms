<?php

namespace Modules\Member\Models;

use Illuminate\Database\Eloquent\Model;

class Vip extends Model {
    //设置表名
    const TABLE_NAME = 'module_member_vip';
    public $table = self::TABLE_NAME;
    public $primaryKey = 'id';
    public $timestamps = false;
    public $guarded = [];

    public static function type() {
        return [
            1 => '年',
            2 => '月',
            3 => '日',
        ];
    }

    public static function status() {
        return [
            1 => '启用',
            2 => '禁用',
        ];
    }

    public static function is_only_buy_one() {
        return [
            1 => '是',
            2 => '否',
        ];
    }
}
