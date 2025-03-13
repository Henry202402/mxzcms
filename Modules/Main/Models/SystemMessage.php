<?php

namespace Modules\Main\Models;

use Illuminate\Database\Eloquent\Model;


class SystemMessage extends Model {
    //设置表名
    const TABLE_NAME = "system_message";
    const primaryKey = "id";
    protected $table = self::TABLE_NAME;
    protected $primaryKey = self::primaryKey;//主键
    public $timestamps = false;

    public static function status() {
        return [
            0 => '未读',
            1 => '已读'
        ];
    }

    public function user() {
        return $this->hasOne(Member::class, 'uid', 'receive_uid');
    }
}