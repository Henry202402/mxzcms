<?php

namespace Modules\Member\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model {
    //设置表名
    const TABLE_NAME = 'module_member_wallet';
    public $table = self::TABLE_NAME;
    public $primaryKey = 'wallet_id';
    public $timestamps = false;
    public $guarded = [];

    //添加钱包记录
    public static function checkWallet($uid) {
        if ($wallet = self::apiGetOne(['uid'=> $uid])) {
            return $wallet;
        } else {
            return self::add(['uid' => $uid]);
        }
    }

    public static function add($add) {
        $add['created_at'] = getDay();
        $add['updated_at'] = getDay();
        return self::query()->insertGetId($add);
    }

    public static function apiGetOne($w) {
        return self::query()->where($w)->first();
    }

    public static function getWallet($uid) {
        $w = ['uid' => $uid];
        $wallet = self::apiGetOne($w);
        if (!$wallet) {
            self::add($w);
            $wallet = self::apiGetOne($w);
        }
        return $wallet;
    }
}
