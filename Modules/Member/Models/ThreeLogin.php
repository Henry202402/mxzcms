<?php

namespace Modules\Member\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Main\Models\Member;


class ThreeLogin extends Model {
    //设置表名
    const TABLE_NAME = "module_member_three_login";
    const ID = "id";
    public $table = self::TABLE_NAME;
    public $primaryKey = self::ID;
    public $timestamps = false;

    //添加记录
    public static function add($all) {
        $add = [
            'uid' => $all['uid'],

            'wx_unionid' => $all['wx_unionid'] ?: '',
            'wx_app_openid' => $all['wx_app_openid'] ?: '',
            'wx_public_openid' => $all['wx_public_openid'] ?: '',
            'wx_small_openid' => $all['wx_small_openid'] ?: '',

            'apple_openid' => $all['apple_openid'] ?: '',

            'qq_app_openid' => $all['qq_app_openid'] ?: '',
            'qq_public_openid' => $all['qq_public_openid'] ?: '',
            'qq_small_openid' => $all['qq_small_openid'] ?: '',

            'wb_app_openid' => $all['wb_app_openid'] ?: '',
            'wb_public_openid' => $all['wb_public_openid'] ?: '',

            'bd_app_openid' => $all['bd_app_openid'] ?: '',
            'bd_public_openid' => $all['bd_public_openid'] ?: '',
            'bd_small_openid' => $all['bd_small_openid'] ?: '',

            'created_at' => date('Y-m-d H:i:s'),
            'update_at' => time(),
        ];
        return self::query()->insertGetId($add);
    }

    //获取openid
    public static function getUserOpenid($uid = 0, $field = '', $openid = '') {
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

    //更新
    public static function updateThreeLogin($w, $up) {
        if (!$w || !$up) return false;
        $up['update_at'] = time();
        return self::query()->where($w)->limit(1)->update($up);
    }

    public static function openidGetUser($w) {
        return self::query()
            ->where($w)
            ->with(['user'])
            ->first();
    }

    public function user() {
        return $this->hasOne(Member::class, 'uid', 'uid');
    }
}
