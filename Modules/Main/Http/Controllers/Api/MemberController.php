<?php

namespace Modules\Main\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Main\Helper\Func;
use Modules\Main\Services\ServiceModel;
use Modules\ModulesController;

class MemberController extends ModulesController {
    public $user;

    public function __construct() {
        parent::__construct();
        $this->user = Func::admin_api_current_user();
    }


    public function mine(Request $request) {
        $all = $this->request->all();
        //用户更新资料
        $row['moduleName'] = 'System';
        $row['username'] = $all['username'];
        $row['nickname'] = $all['nickname'];
        $row['home_key'] = $all['home_key'];
        $row['uid'] = $this->user['uid'];
        $res = hook('UpdateUserInfo', $row)[0];
        if ($res['status'] != 200) return returnArr(0, $res['msg']);
        return returnArr(200, '更新成功');
    }

    public function updatePassword(Request $request) {

        $all = $this->request->all();
        if (!$all['old_password']) return returnArr(0, '原密码不能为空');
        if (!$all['new_password']) return returnArr(0, '新密码不能为空');
        if (!$all['confirm_password']) return returnArr(0, '确认密码不能为空');
        if ($all['new_password'] !== $all['confirm_password']) return returnArr(0, '两次密码不一致');

        if ($all['old_password_encryption'] != ServiceModel::getPassword($all["old_password"])) return returnArr(0, '原密码不正确');

        //用户更新资料
        $row['password'] = $all['new_password'];
        $row['moduleName'] = 'System';
        $row['home_key'] = $all['home_key'];
        $row['uid'] = $this->user['uid'];
        $res = hook('UpdateUserInfo', $row)[0];
        if ($res['status'] != 200) return returnArr(0, $res['msg']);
        return returnArr(200, '更新成功');

    }

    //修改邮箱
    public function updateEmail(Request $request) {

        $all = $this->request->all();
        if (!$all['email']) return returnArr(0, '新邮箱不能为空');
        if (!$all['email_captcha']) return returnArr(0, '新邮箱验证码不能为空');


        //验证邮箱和验证码
        $emailArr = $all;
        $emailArr['moduleName'] = 'System';
        $emailArr['operate_type'] = 'verify';
        $checkEmailCode = hook('GetSendEmail', $emailArr)[0];
        if ($checkEmailCode['status'] != 200) return returnArr(0, $checkEmailCode['msg']);


        //用户更新资料
        $row['email'] = $all['email'];
        $row['email_active'] = 1;
        $row['moduleName'] = 'System';
        $row['home_key'] = $all['home_key'];
        $row['uid'] = $this->user['uid'];
        $res = hook('UpdateUserInfo', $row)[0];
        if ($res['status'] != 200) return returnArr(0, $res['msg']);
        return returnArr(200, '更新成功');

    }

    //修改手机
    public function updatePhone(Request $request) {

        $all = $this->request->all();
        if (!$all['phone']) return returnArr(0, '新手机不能为空');
        if (!$all['phone_captcha']) return returnArr(0, '新手机验证码不能为空');

        //验证手机号和验证码
        $emailArr = $all;
        $emailArr['moduleName'] = 'System';
        $emailArr['operate_type'] = 'verify';
        $checkEmailCode = hook('GetSendPhone', $emailArr)[0];
        if ($checkEmailCode['status'] != 200) return returnArr(0, $checkEmailCode['msg']);


        //用户更新资料
        $row['phone'] = $all['phone'];
        $row['phone_active'] = 1;
        $row['moduleName'] = 'System';
        $row['home_key'] = $all['home_key'];
        $row['uid'] = $this->user['uid'];
        $res = hook('UpdateUserInfo', $row)[0];
        if ($res['status'] != 200) return returnArr(0, $res['msg']);
        return returnArr(200, '更新成功');

    }

    /************************************ 站内信 ***************************************/
    //站内信列表
    public function messageList(Request $request) {
        $all = $request->all();

        $all['moduleName'] = 'System';
        $all['operate_type'] = 4;
        $all['uid'] = $this->user['uid'];
        $res = hook('UpdateUserMessage', $all)[0];
        $data['param'] = $all;
        $data['data'] = $res['data'];

        return HomeView('member.messageList', $data);
    }

    //站内信详情
    public function messageDetail(Request $request) {
        $all = $request->all();
        $all['moduleName'] = 'System';
        $all['operate_type'] = 5;
        $all['uid'] = $this->user['uid'];
        return hook('UpdateUserMessage', $all)[0];
    }

    //站内信已读
    public function messageRead(Request $request) {
        $all = $request->all();
        $all['moduleName'] = 'System';
        $all['uid'] = $this->user['uid'];
        return hook('UpdateUserMessage', $all)[0];
    }

    //站内信删除
    public function messageDelete(Request $request) {
        $all = $request->all();
        $all['moduleName'] = 'System';
        $all['operate_type'] = 3;
        $all['uid'] = $this->user['uid'];
        return hook('UpdateUserMessage', $all)[0];
    }
}
