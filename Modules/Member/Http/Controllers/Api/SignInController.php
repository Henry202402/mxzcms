<?php

namespace Modules\Member\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Main\Services\ServiceModel;
use Modules\Member\Helper\Func;
use Modules\Member\Models\SignIn;
use Modules\Member\Models\Wallet;
use Modules\Member\Models\WalletRecord;

class SignInController extends JWTController {

    //签到记录
    public function getSignInList(Request $request) {
        $user = $this->current_user();
        $all = $request->all();
        $all['uid'] = $user['uid'];
        $data = SignIn::getSignInList($all);
        $data = dealPage($data);
        $tig = SignIn::getLastTig($user['uid']);
        $data['continuous'] = SignIn::getUserContinuousSignAllDay(['uid' => $user['uid'], 'tig' => $tig ?: '']);
        //$data['all_sign_in_num'] = SignIn::findSignInCount($user['uid']);
        return returnArr(200, '成功', $data);
    }

    //签到
    public function signIn(Request $request) {
        $user = $this->current_user();
        $uid = $user['uid'];
        $all = $request->all();

        $wallet = Wallet::getWallet($uid);
        if (!$wallet) return returnArr(0, '获取账号失败，请重试');

        //今天日期
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));

        //查询最新签到
        $find_sign_in = SignIn::findNewSignIn($uid);
        //判断当天是否已经有记录
        if ($find_sign_in['day'] == $today) return returnArr(0, '今天已经签到了');

        //判断 昨天是否 打卡 如果打卡 持续打卡打卡天数 继续增加
        if ($find_sign_in['day'] == $yesterday) {
            $tig = $find_sign_in['tig'];//连签标识
            //获取用户之前的连签天数
            $count = SignIn::getContinuousSignInDay(['uid' => $uid, 'tig' => $tig, 'day' => $today]);
            $continuous = intval($count) + 1;   //连续天数加1

        } else {
            $tig = date('ymdHis') . rand(100000, 999999) . rand(100000, 999999);
            //标识重复
            if (ServiceModel::apiGetOneArray(SignIn::TABLE_NAME, ['tig' => $tig])) return returnArr(0, '生成失败，请重试');
            //从头开始
            $continuous = 1;
        }


        //获取用户积分
        $signIn = Func::getBaseConfig('signInConfig');
        $day_int = $signIn['day_int'];
        //升序排序
        $last_names = array_column($day_int, 'key');
        array_multisort($last_names, SORT_ASC, $day_int);

        if (!$day_int) {
            $point = 1;
        } else {
            foreach ($day_int as $item) {
                if ($continuous <= $item['key']) {
                    $point = $item['value'];
                    break;
                }
            }
            if ($point == 0) $point = $item['value'];
        }


        //开启事务
        DB::beginTransaction();
        //添加签到记录
        $remark = '签到';
        //生成订单号
        $order_num = Func::getOrderNum(SignIn::TABLE_NAME, 'order_num');
        if (!$order_num) return returnArr(0, '生成订单号失败');
        $res1 = ServiceModel::add(SignIn::TABLE_NAME, [
            'order_num' => $order_num,
            'uid' => $uid,
            'day' => $today,
            'tig' => $tig,
            'point' => $point,
            'remark' => $remark,
        ]);

        //添加签到积分记录
        $integral = ($wallet['integral'] + $point) * 1;//用户当前积分

        $res2 = hook('AddWalletRecord', [
            'moduleName' => 'Member',
            'order_num' => $order_num,
            'module' => $this->moduleName,
            'uid' => $uid,
            'type' => WalletRecord::add,
            'amount_type' => $remark,
            'amount' => $point,
            'unit' => '积分',
            'remark' => $remark,
            'extra' => json_encode([
                'uid' => $uid,
                'balance' => $point,
                'all_balance' => $integral,
            ], JSON_UNESCAPED_UNICODE),
        ])[0]['status'];

        //同时更新用户积分
        $res3 = Wallet::query()->where('uid', $uid)->increment('integral', $point);

        if ($res1 && $res2 && $res3) {
            DB::commit();  //提交

            hook('UpdateUserMessage', [
                'moduleName' => 'System',
                'operate_type' => 6,
                'module' => $this->moduleName,
                'uid' => $uid,
                'title' => '签到成功',
                'content' => "恭喜您，签到成功",
            ]);

            return returnArr(200, '签到成功', [
                'now_integral' => $integral,
                'add_integral' => intval($point),
                'continuous' => intval($continuous),
                'all_sign_in_num' => SignIn::findSignInCount($uid),
            ]);
        } else {
            DB::rollback();  //回滚
            return returnArr(0, '签到失败');
        }
    }

    //获取签到信息
    public function getSignInInfo(Request $request) {
        //获取用户积分
        $signIn = Func::getBaseConfig('signInConfig');
        $data['sign_in_rules'] = $signIn['sign_in_rules'];
        return returnArr(200, '成功', $data);
    }

    //是否已签到
    public function checkSignIn(Request $request) {
        $user = $this->current_user();
        $uid = $user['uid'];
        $all = $request->all();
        //查询最新签到
        $find_sign_in = SignIn::findNewSignIn($uid);

        $can_sign_in = $find_sign_in['day'] != $all['now_day'] ? true : false;

        return returnArr(200, 'ok', ['can_sign_in' => $can_sign_in]);
    }
}
