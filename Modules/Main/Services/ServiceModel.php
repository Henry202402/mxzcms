<?php

namespace Modules\Main\Services;

use Illuminate\Support\Facades\DB;
use Modules\Main\Models\Common;
use Modules\Main\Models\HomeMenu;
use Modules\Main\Models\Member;
use Modules\Main\Models\Modules;
use Modules\Main\Models\SystemMessage;
use Modules\Member\Models\Vip;
use Modules\System\Models\Setting;

class ServiceModel {

    /**************************** Member ******************************/
    public static function getPassword($password) {
        return md5(cacheGlobalSettingsByKey('password_key') . md5($password));
    }


    public static function check($tableName, $w) {
        if ($find = self::apiGetOne($tableName, $w)) {
            return $find;
        } else {
            return self::add($tableName, $w);
        }
    }

    public static function add($tableName, $add) {
        $add['created_at'] = date('Y-m-d H:i:s');
        $add['updated_at'] = date('Y-m-d H:i:s');
        return Common::query()->from($tableName)->insertGetId($add);
    }

    public static function apiGetOne($tableName, $w, $notIdArr = []) {
        return Common::query()
            ->from($tableName)
            ->where($w)
            ->where(function ($q) use ($notIdArr) {
                if ($notIdArr && count($notIdArr) > 0) $q->whereNotIn('uid', $notIdArr);
            })
            ->first();
    }

    public static function apiGetOneArray($tableName, $w, $arr = []) {
        return Common::query()
            ->from($tableName)
            ->where($w)
            ->where(function ($q) use ($arr) {
                if ($arr['notIdKey'] && $arr['notIdList']) $q->whereNotIn($arr['notIdKey'], $arr['notIdList']);
            })
            ->first();
    }

    public static function whereUpdate($tableName, $w, $add) {
        $add['updated_at'] = date('Y-m-d H:i:s');
        return Common::query()->from($tableName)->where($w)->update($add);
    }

    public static function loginFindUserV2($tableName, $all) {
        return Common::query()
            ->from($tableName)
            ->where(function ($q) use ($all) {
                $q->where("username", "=", $all["username"])
                    ->orWhere("phone", "=", $all["username"]);
            })
            ->first();
    }

    //获取所有用户
    public static function getHomeUser($tableName, $arr) {
        return Common::query()
            ->from($tableName)
            ->where(function ($q) use ($arr) {
                if ($arr['month'] == 1) $q->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), date('Y-m'));
                if ($arr['day'] == 1) $q->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), date('Y-m-d'));
            })->count();
    }

    //注册插入
    public static function InsertArr($arr) {
        if (!is_array($arr)) return returnArr(0, '用户数据不全');

        //密码是否一致
        if ($arr['password'] && $arr['password'] != $arr['confirm_password']) return returnArr(0, '两次密码不一致');

        //判断用户名是否重复
        if (!$arr['username']) return returnArr(0, '用户名不能为空');
        $res = ServiceModel::apiGetOne(Member::TABLE_NAME, ['username' => $arr['username']]);
        if ($res) return returnArr(0, '用户名已存在');

        //判断手机是否重复
        if (isset($arr['phone'])) {
            $row = ServiceModel::apiGetOne(Member::TABLE_NAME, ['phone' => $arr['phone']]);
            if ($row) return returnArr(0, '手机号已存在');
            $res1['c_code'] = $arr['phone_code'] ?: '86';
            $res1['phone'] = $arr['phone'];
            $res1['phone_active'] = 1;
        }

        //判断邮箱是否重复
        if (isset($arr['email'])) {
            $row = ServiceModel::apiGetOne(Member::TABLE_NAME, ['email' => $arr['email']]);
            if ($row) return returnArr(0, '邮箱已存在');
            $res1['email'] = $arr['email'];
            $res1['email_active'] = 1;
        }

        //用户表
        $res1['avatar'] = $arr['avatar'] ?: 'avatar/avatar.jpg';
        $res1['username'] = $arr['username'] ?: '';
        $res1['nickname'] = $arr['nickname'] ?: $arr['username'];
        $res1['password'] = $arr['password'] ? ServiceModel::getPassword($arr['password']) : '';
        $res1['email'] = $arr['email'] ?: '';
        $res1['status'] = 1;
        $res1['userid'] = self::getUserId();
        $res1['pid'] = $arr['pid'] ?: 1;
        $res1['pid_path'] = $arr['pid_path'] ?: 1;
        $res1['male'] = in_array(trim($arr['male']), ['男', '女']) ? trim($arr['male']) : '';
        $uid = ServiceModel::add(Member::TABLE_NAME, $res1);
        if (!$uid) return returnArr(0, '注册失败');
        return returnArr(200, '注册成功');

    }

    public static function getUserId() {
        return md5(self::getRandUsername() . rand(100000, 999999) . microtime(true) . rand(100000, 999999) . uniqid() . rand(100000, 999999));
    }

    //获取随机用户名
    public static function getRandUsername($len = 13) {
        $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
        $randStr = str_shuffle(str_shuffle($str));//打乱字符串
        $rands = substr($randStr, 0, $len);//substr(string,start,length);返回字符串的一部分
        return $rands;
    }

    /**************************** Language ******************************/
    public static function getLangList() {
        return [
            'zh-CN' => '简体中文',
            'zh-TW' => '繁體中文',
            'en' => 'English',
        ];
    }


    /**************************** Modules ******************************/
    //获取模块前台
    public static function getModuleIndex() {
        return Modules::query()
            ->where('cloud_type', Modules::Module)
            ->where('is_index', 1)
            ->where('status', 1)
            ->first();
    }

    //获取模块启用列表
    public static function getModuleList() {
        return Modules::query()
            ->where('cloud_type', Modules::Module)
            ->where('status', 1)
            ->get()
            ->pluck('identification')
            ->toArray();
    }

    /**************************** HomeMenu ******************************/
    const homeMenuSort = "FIELD(position,'top','bottom','footer')";

    public static function getThemeMenuList() {
        return HomeMenu::query()
            ->orderByRaw(self::homeMenuSort)
            ->orderByDesc('sort')
            ->get()->toArray();
    }

    //获取导航菜单上级
    public static function getHomeMenu() {
        return HomeMenu::query()
            ->where('pid', 0)
            ->with(['child'])
//            ->orderByDesc('position')
            ->orderByRaw(self::homeMenuSort)
            ->get()
            ->toArray();
    }

    //获取启用的导航菜单列表
    public static function getHomeMenuList($all) {
        return HomeMenu::query()
            ->where('status', 1)
            ->where('pid', 0)
            ->where('position', $all['position'])
            ->with(['child' => function ($q) {
                $q->with(['child' => function ($qq) {
                    $qq->where('status', 1)
                        ->orderByDesc('sort')
                        ->select(['id', 'pid', 'name', 'url', 'icon', 'icon_character']);
                }])->where('status', 1)
                    ->orderByDesc('sort')
                    ->select(['id', 'pid', 'name', 'url', 'icon', 'icon_character']);
            }])
            ->orderByRaw(self::homeMenuSort)
            ->orderByDesc('sort')
            ->get(['id', 'pid', 'name', 'url', 'icon', 'icon_character'])
            ->toArray();
    }

    public static function SettingInsertOrUpdate($module, $type, $key, $value) {
        $data = [
            "module" => $module,
            "type" => $type,
            "key" => $key,
            "value" => $value
        ];
        $check = Setting::query()
            ->where("module", $module)
            ->where("type", $type)
            ->where("key", $key)
            ->first();
        if ($check) {
            Setting::query()
                ->where("module", $module)
                ->where("type", $type)
                ->where("key", $key)
                ->update($data);
        } else {
            Setting::query()
                ->insert($data);
        }
    }

    //获取未读数量
    public static function getNoReadMessageNum($uid) {
        return SystemMessage::query()->where(['receive_uid' => $uid, 'status' => 0])->count(SystemMessage::primaryKey);
    }

    //获取我的会员列表
    public static function getMyMembers($all) {
        return Member::query()
            ->where('pid', $all['pid'])
            ->paginate(getLen($all));
    }

    //获取我的VIP列表
    public static function getVipList($all) {
        return Vip::query()
            ->where('status', 1)
            ->latest('sort')
            ->latest('created_at')
            ->get()->toArray();
    }
}
