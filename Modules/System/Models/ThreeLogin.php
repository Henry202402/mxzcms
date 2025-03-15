<?php

namespace Modules\System\Models;

use Illuminate\Database\Eloquent\Model;


class ThreeLogin extends Model {
    //设置表名
    const TABLE_NAME = "module_system_three_login";
    const ID = "id";
    public $table = self::TABLE_NAME;
    public $primaryKey = self::ID;
    public $timestamps = false;

    //获取openid
    public static function getUserOpenid($uid, $field = '', $openid = '') {
        if (trim($openid)) return trim($openid);
        if (in_array($field, ['wx_app', 'wx_public', 'wx_small', 'apple', 'qq', 'wb'])) {
            return self::query()->where('uid', $uid)->value($field . '_openid') ?: '';
        } elseif ($field == 'all') {
            $res = self::query()->where('uid', $uid)->first();
            return $res ? $res->toArray() : [];
        } else {
            return '';
        }
    }
}
