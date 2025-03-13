<?php

namespace Modules\Member\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Main\Models\Member;

class AuthRecord extends Model {
    //设置表名
    const TABLE_NAME = 'module_member_auth_record';
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
            1 => '已审核',
            2 => '审核失败',
        ];
    }

    public function user_data() {
        return $this->hasOne(Member::class, 'uid', 'uid');
    }

    public static function getNoAuditNum() {
        return self::query()->where('status', 0)->count('id');
    }

    public static function getAddRecordGetType($uid) {
        return self::query()
            ->where('uid', $uid)
            ->whereIn('status', [0, 1])
            ->get()->pluck('type')->toArray();
    }
}
