<?php

namespace Modules\System\Listeners;

use Intervention\Image\Facades\Image;
use Modules\Main\Services\ServiceModel;
use Modules\System\Http\Controllers\Common\SessionKey;
use Modules\Main\Models\Member;

class UpdateUserInfo {

    public function handle(\Modules\System\Events\UpdateUserInfo $event) {
        //事件逻辑 ...
        $all = $event->data;

        //文件上传
        if ($_FILES['avatar']['size'] > 0) {
            try {
                $all['avatar'] = UploadFile(\Request(), "avatar", "avatar/" . date("Y/m/d/") . uniqid(), ALLOWEXT, __E("upload_driver"));
                $this->resizeImg($all['avatar'], 50, 100, 100);
            } catch (\Exception $exception) {
                return returnArr(0, $exception->getMessage());
            }
        }

        $filedList = \Illuminate\Support\Facades\Schema::getColumnListing(Member::TABLE_NAME);
        foreach ($filedList as $fl) {
            if (isset($all[$fl])) $update[$fl] = $all[$fl];
        }

        if ($all['password']) {
            $update['password'] = ServiceModel::getPassword($update["password"]);
        } else {
            unset($update['password']);
        }
        unset($update['uid']);
        if ($update['username'] && ServiceModel::apiGetOne(Member::TABLE_NAME, ['username' => $update['username']], [$all['uid']])) return returnArr(0, getTranslateByKey('the_username_already_exists'));
        if ($update['phone'] && ServiceModel::apiGetOne(Member::TABLE_NAME, ['phone' => $update['phone']], [$all['uid']])) return returnArr(0, getTranslateByKey('phone_number_already_exists'));
        if ($update['email'] && ServiceModel::apiGetOne(Member::TABLE_NAME, ['email' => $update['email']], [$all['uid']])) return returnArr(0, getTranslateByKey('email_already_exists'));

        $res = ServiceModel::whereUpdate(Member::TABLE_NAME, ['uid' => $all['uid']], $update);

        if (!$res) return returnArr(0, 'error');

        if ($all['home_key']) {
            $user_key = \Modules\System\Http\Controllers\Common\SessionKey::HomeInfo;
        } else {
            $user_key = \Modules\System\Http\Controllers\Common\SessionKey::AdminInfo;
        }

        //更新登录者的session
        $admin_info = session($user_key);
        //更新信息
        $array = [];
        foreach ($update as $key => $info) {
            if ($admin_info[$key] != $all[$key]) $array[$key] = "{$admin_info[$key]}->{$all[$key]}";
        }

        if ($all['uid'] == $admin_info["uid"]) {
            if (!is_array($admin_info)) $admin_info = $admin_info->toArray();
            session([$user_key => array_merge($admin_info, $update)]);
            session()->save();
            $remark = "更新自己信息";
        } else {
            $remark = "更新用户{$admin_info['username']}信息";
        }

        hook("Loger", [
            'module' => $all['moduleName'],
            'type' => 3,
            'two_type' => 5,
            'params' => $array,
            'remark' => $remark,
            'unique_id' => $all['uid'],
            'requestid' => \request()->requestid
        ]);
        return returnArr(200, 'success', $update);
    }

    /**
     * 压缩图片
     * img_url  图片路径
     * max_size 这个大小就压缩，单位KB
     * width    宽
     * height   高
     */
    public function resizeImg($img_url, $max_size, $width, $height) {
        $img_url = str_replace(url('uploads') . '/', '', $img_url);
        $url = public_path('uploads/' . $img_url);
        $size = filesize($url) / 1024;
        if ($size > $max_size) {
            Image::make($url)->resize($width, $height)->save($url);
        }
    }

}
