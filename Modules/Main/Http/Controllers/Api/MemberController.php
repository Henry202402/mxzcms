<?php

namespace Modules\Main\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\Main\Helper\Func;
use Modules\Main\Services\ServiceModel;
use Modules\Member\Models\Auth;
use Modules\Member\Models\AuthRecord;
use Modules\Member\Models\SignIn;
use Modules\Member\Models\Vip;
use Modules\Member\Models\VipOrder;
use Modules\Member\Models\Wallet;
use Modules\Member\Models\WalletRecord;
use Modules\ModulesController;
use Mxzcms\Modules\cache\CacheKey;

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
        $row['email'] = $all['email'];
        $row['signature'] = $all['signature'];
        $row['avatar'] = $all['avatar'];
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

    //获取我的会员列表
    public function myMembers(Request $request) {
        $all = $this->request->all();
        $all['pid'] = $this->user['uid'];
        $data = ServiceModel::getMyMembers($all);
        return returnArr(200, '成功', $data);
    }

    //获取我的会员列表
    public function getVipList(Request $request) {
        $all = $this->request->all();
        $all['pid'] = $this->user['uid'];
        $data = ServiceModel::getVipList($all);
        return returnArr(200, '成功', $data);
    }

    //获取我的会员列表
    public function getWallet(Request $request) {
        $all = $this->request->all();
        $wallet = Wallet::getWallet($this->user['uid']);
        return returnArr(200, '成功', $wallet);
    }

    public function myRealName(Request $request) {
        $all = $this->request->all();
        $data['auth'] = Auth::query()
            ->where('uid', $this->user['uid'])
            ->where('status', 1)
            ->latest()
            ->first();
        $data['authRecord'] = AuthRecord::query()
            ->where('uid', $this->user['uid'])
            ->latest()
            ->first();
        return returnArr(200, '成功', $data);
    }


    public function myBill(Request $request) {
        $all = $request->all();
        $all['uid'] = $this->user['uid'];
        if ($all['data_type'] == 'vip') {
            $data = VipOrder::getBill($all);
        } elseif ($all['data_type'] == 'signin') {
            $data = SignIn::getBill($all);
        } elseif ($all['data_type'] == 'wallet_record') {
            $data = WalletRecord::getBill($all);
            foreach (Cache::get(CacheKey::ModulesActive) ?: [] as $m) {
                $moduleList[$m['identification']] = $m['name'];
            }
            foreach ($data as &$d) {
                $d['module_name'] = $moduleList[$d['module']];
                $d['type_msg'] = WalletRecord::type()[$d['type']];
            }
        }

        return returnArr(200, '成功', $data);
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

    //获取站内信未读数量
    public function getUserNoReadMessage(Request $request) {
        $all = $request->all();
        $all['moduleName'] = 'System';
        $all['operate_type'] = 9;
        $all['uid'] = $this->user['uid'];
        $data['no_read_num'] = hook('UpdateUserMessage', $all)[0]['data'];
        return returnArr(200, '成功', $data);
    }
}
