<?php

namespace Modules\Member\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Main\Models\Member;

class Auth extends Model {
    //设置表名
    const TABLE_NAME = 'module_member_auth';
    public $table = self::TABLE_NAME;
    public $primaryKey = 'id';
    public $timestamps = false;
    public $guarded = [];

    public static function type() {
        return [
            1 => '个人认证',
            2 => '企业认证',
        ];
    }

    public static function status() {
        return [
            0 => '待审核',
            1 => '审核通过',
            2 => '审核失败',
        ];
    }


    public function user_data() {
        return $this->hasOne(Member::class, 'uid', 'uid');
    }
}
