<?php

namespace Modules\Member\Models;

use Illuminate\Database\Eloquent\Model;

class SignIn extends Model {

    const TABLE_NAME = "module_member_signin";
    public $table = self::TABLE_NAME;
    public $primaryKey = "id";
    public $timestamps = false;

    //查询最新签到
    public static function findNewSignIn($uid) {
        if ($uid <= 0) return false;
        return self::query()
            ->where('uid', $uid)
            ->orderByDesc('day')
            ->first();
    }

    //查询签到累计天数
    public static function findSignInCount($uid) {
        if ($uid <= 0) return 0;
        return self::query()->where('uid', $uid)->count();
    }

    //查询用户已连签多少天
    public static function getContinuousSignInDay($all) {
        return self::query()
            ->where('uid', $all['uid'])
            ->where('tig', $all['tig'])
            ->where('day', '<', $all['day'])
            ->count('id');
    }

    //查询用户连签总天数
    public static function getUserContinuousSignAllDay($all) {
        return self::query()
            ->where('uid', $all['uid'])
            ->where('tig', $all['tig'])
            ->count('id');
    }

    //当月签到列表
    public static function getMonthSignInList($uid) {
        return self::query()
            ->where('uid', $uid)
            ->where("day", "LIKE", date('Y-m') . "%")
            ->orderBy('id')
            ->get([
                'day', 'point'
            ])
            ->toArray();
    }

    public static function getSignInList($all) {
        return self::query()
            ->where('uid', $all['uid'])
            ->where(function ($q) use ($all) {
                if ($all['month']) $q->where("day", "LIKE", $all['month'] . "%");
            })
            ->latest('day')
            ->select([
                'id', 'day', 'point', 'created_at'
            ])
            ->paginate(getLen($all))->toArray();
    }

    public static function getLastTig($uid) {
        $today = getDay(2);
        $yesterday = date('Y-m-d', strtotime('-1day'));
        return self::query()
            ->where('uid', $uid)
            ->where(function ($q) use ($today, $yesterday) {
                $q->where('day', $today)
                    ->orWhere('day', $yesterday);
            })
            ->latest('day')
            ->value('tig');
    }


    public static function getBill($all) {
        return self::query()
            ->where('uid', $all['uid'])
            ->latest()
            ->paginate(getLen($all));
    }
}