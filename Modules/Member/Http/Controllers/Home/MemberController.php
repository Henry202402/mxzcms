<?php

namespace Modules\Member\Http\Controllers\Home;

use Modules\Member\Helper\Func;
use Modules\Member\Http\Controllers\Api\AuthController;
use Modules\Member\Http\Controllers\Common\CommonController;
use Modules\Member\Models\Auth;
use Modules\System\Http\Controllers\Common\SessionKey;

class MemberController extends CommonController {


    public function index() {
        $tig = [
            'active' => 'index',
        ];
        $list = hook("GetMemberEntry",[]);
        $list = array_filter($list);
        return $this->homeView('index.index', [
            'tig' => $tig,
            'list' => $list,
        ]);
    }


    public function mine() {
        if ($this->request->isMethod("post")) {
            $request = \Request();
            $request->offsetSet('home_user_info', session(SessionKey::HomeInfo));
            $request->offsetSet('home_key', SessionKey::HomeInfo);
            $api = new \Modules\Main\Http\Controllers\Api\MemberController();
            return $api->mine($request);
        }
        $user = session(SessionKey::HomeInfo);
        return $this->homeView('index.mine', [
            'user' => $user
        ]);
    }

    public function updatePassword() {
        if ($this->request->isMethod("post")) {
            $request = \Request();
            $request->offsetSet('home_user_info', session(SessionKey::HomeInfo));
            $request->offsetSet('home_key', SessionKey::HomeInfo);
            $request->offsetSet('old_password_encryption', session(SessionKey::HomeInfo)['password']);
            $api = new \Modules\Main\Http\Controllers\Api\MemberController();
            return $api->updatePassword($request);
        }
        $tig = [
            'active' => 'index',
            'nav' => '会员中心',
            'nav_url' => url("member"),
            'title' => '个人资料',
            'subtitle' => '修改密码',
        ];
//        return HomeView('member.updatePassword');
        return $this->homeView('member.updatePassword', [
            'tig' => $tig,
        ]);
    }

    //修改邮箱
    public function updateEmail() {
        if ($this->request->isMethod("post")) {
            $request = \Request();
            $request->offsetSet('home_user_info', session(SessionKey::HomeInfo));
            $request->offsetSet('home_key', SessionKey::HomeInfo);
            $api = new \Modules\Main\Http\Controllers\Api\MemberController();
            return $api->updateEmail($request);
        }
        return HomeView('member.updateEmail');
    }

    //修改手机
    public function updatePhone() {
        if ($this->request->isMethod("post")) {
            $request = \Request();
            $request->offsetSet('home_user_info', session(SessionKey::HomeInfo));
            $request->offsetSet('home_key', SessionKey::HomeInfo);
            $api = new \Modules\Main\Http\Controllers\Api\MemberController();
            return $api->updatePhone($request);
        }
        return HomeView('member.updatePhone');
    }

    //我的会员
    public function myMembers() {
        $request = \Request();
        $request->offsetSet('home_user_info', session(SessionKey::HomeInfo));
        $request->offsetSet('home_key', SessionKey::HomeInfo);
        $api = new \Modules\Main\Http\Controllers\Api\MemberController();

        $tig = [
            'active' => 'myMembers',
            'nav' => '我的会员',
            'nav_url' => url("member/myMembers"),
            'title' => '会员列表',
            'subtitle' => '会员列表',
        ];
        $data = $api->myMembers($request)['data'];
        return $this->homeView('member.myMembers', [
            'tig' => $tig,
            'data' => $data,
        ]);
    }

    //签到
    public function signIn() {
        $request = \Request();
        $all = $request->all();
        $user = session(SessionKey::HomeInfo);
        $request->offsetSet('uid', $user['uid']);
        $request->offsetSet('no_login_string', $this->getNoLoginStr());

        if ($request->ajax() && $request->operate_type == 'signIn') {
            $api = new \Modules\Member\Http\Controllers\Api\SignInController($request);
            return $api->signIn($request);
        }
        if ($all['type'] && $all['prevMonth']) {
            $all['month'] = date('Y-m', strtotime(($all['type'] == 1 ? '-' : '+') . '1month', strtotime($all['prevMonth'])));
        }

        $all['month'] = $all['month'] ?: date('Y-m');
        $request->offsetSet('pagesize', 100);
        $request->offsetSet('month', $all['month']);
        $api = new \Modules\Member\Http\Controllers\Api\SignInController($request);
        $data = $api->getSignInList($request)['data']['list'];
        $all['sign_in_rules'] = $api->getSignInInfo($request)['data']['sign_in_rules'];

        $request->offsetSet('now_day', getDay(2));
        $all['can_sign_in'] = $api->checkSignIn($request)['data']['can_sign_in'];

        $tig = [
            'active' => 'signIn',
            'nav' => '签到',
            'nav_url' => url("member/signIn"),
            'title' => '签到',
            'subtitle' => '签到',
        ];
        return $this->homeView('member.signIn', [
            'tig' => $tig,
            'data' => $data,
            'all' => $all,
        ]);
    }

    public function signinRecord() {
        $tig = [
            'active' => 'signIn',
            'nav' => '签到',
            'nav_url' => url("member/signinRecord"),
            'title' => '签到',
            'subtitle' => '签到明细',
        ];
        $request = \Request();
        $all = $request->all();
        $user = session(SessionKey::HomeInfo);
        $request->offsetSet('home_user_info', $user);
        $request->offsetSet('home_key', SessionKey::HomeInfo);
        $request->offsetSet('data_type', 'signin');
//        $request->offsetSet('pagesize', 1);
        $api = new \Modules\Main\Http\Controllers\Api\MemberController();
        $data = $api->myBill($request)['data'];
        return $this->homeView('member.signinRecord', [
            'tig' => $tig,
            'all' => $all,
            'data' => $data,
        ]);
    }

    //我的VIP
    public function myVip() {
        $request = \Request();
        $user = session(SessionKey::HomeInfo);
        $request->offsetSet('home_user_info', $user);
        $request->offsetSet('home_key', SessionKey::HomeInfo);
        $api = new \Modules\Main\Http\Controllers\Api\MemberController();
        $data = $api->getVipList($request)['data'];
        $wallet = $api->getWallet($request)['data'];
        $auth = $api->myRealName($request)['data']['auth'];
        $config = Func::getBaseConfig('vipConfig');

        $tig = [
            'active' => 'myVip',
            'nav' => '我的VIP',
            'nav_url' => url("member/myVip"),
            'title' => 'VIP列表',
            'subtitle' => 'VIP',
        ];
        return $this->homeView('member.myVip', [
            'tig' => $tig,
            'data' => $data,
            'wallet' => $wallet,
            'auth' => $auth,
            'config' => $config,
        ]);
    }

    public function vipPay() {
        $request = \Request();
        $request->offsetSet('uid', session(SessionKey::HomeInfo)['uid']);
        $request->offsetSet('no_login_string', $this->getNoLoginStr());
        $api = new \Modules\Member\Http\Controllers\Api\OrderController($request);
        return $api->buyVip($request);
    }

    public function checkVipPayStatus() {
        $request = \Request();
        $request->offsetSet('uid', session(SessionKey::HomeInfo)['uid']);
        $request->offsetSet('no_login_string', $this->getNoLoginStr());
        $api = new \Modules\Member\Http\Controllers\Api\OrderController($request);
        return $api->checkVipPayStatus($request);
    }

    public function vipRecord() {
        $tig = [
            'active' => 'myVip',
            'nav' => 'VIP',
            'nav_url' => url("member/vipRecord"),
            'title' => 'VIP',
            'subtitle' => 'VIP订单明细',
        ];
        $request = \Request();
        $all = $request->all();
        $user = session(SessionKey::HomeInfo);
        $request->offsetSet('home_user_info', $user);
        $request->offsetSet('home_key', SessionKey::HomeInfo);
        $request->offsetSet('data_type', 'vip');
//        $request->offsetSet('pagesize', 1);
        $api = new \Modules\Main\Http\Controllers\Api\MemberController();
        $data = $api->myBill($request)['data'];
        return $this->homeView('member.vipRecord', [
            'tig' => $tig,
            'all' => $all,
            'data' => $data,
        ]);
    }


    public function myRealName() {
        if ($this->request->isMethod("post")) {
            $request = \Request();
            $request->offsetSet('home_user_info', session(SessionKey::HomeInfo));
            $request->offsetSet('home_key', SessionKey::HomeInfo);
            $request->offsetSet('old_password_encryption', session(SessionKey::HomeInfo)['password']);
            $api = new \Modules\Main\Http\Controllers\Api\MemberController();
            return $api->updatePassword($request);
        }
        $tig = [
            'active' => 'myRealName',
            'nav' => '我的实名',
            'nav_url' => url("member/myRealName"),
            'title' => '实名认证',
            'subtitle' => '实名信息',
        ];

        $request = \Request();
        $user = session(SessionKey::HomeInfo);
        $request->offsetSet('home_user_info', $user);
        $request->offsetSet('home_key', SessionKey::HomeInfo);
        $api = new \Modules\Main\Http\Controllers\Api\MemberController();
        $data = $api->myRealName($request)['data'];
        $auth = $data['auth'] ?: $data['authRecord'];
        return $this->homeView('member.myRealName', [
            'tig' => $tig,
            'data' => $data,
            'auth' => $auth,
        ]);
    }

    public function addRealName() {
        $request = \Request();
        if ($this->request->isMethod("post")) {
            $request->offsetSet('uid', session(SessionKey::HomeInfo)['uid']);
            $request->offsetSet('admin_str', $this->getNoLoginStr());
            $api = new AuthController($request);
            return $api->userAuthAdd($request);
        }
        $tig = [
            'active' => 'myRealName',
            'nav' => '我的实名',
            'nav_url' => url("member/myRealName"),
            'title' => '实名认证',
            'subtitle' => '实名认证',
        ];
        if ($this->request->change == 1) {
            $request->offsetSet('home_user_info', session(SessionKey::HomeInfo));
            $request->offsetSet('home_key', SessionKey::HomeInfo);
            $api = new \Modules\Main\Http\Controllers\Api\MemberController();
            $auth = $api->myRealName($request)['data']['authRecord'];
        }
        return $this->homeView('member.addRealName', [
            'tig' => $tig,
            'auth' => $auth,
        ]);
    }

    public function editRealName() {
        $request = \Request();
        $request->offsetSet('uid', session(SessionKey::HomeInfo)['uid']);
        $request->offsetSet('admin_str', $this->getNoLoginStr());
        $api = new AuthController($request);
        if ($this->request->isMethod("post")) {
            return $api->userAuthEdit($request);
        }
        $tig = [
            'active' => 'myRealName',
            'nav' => '我的实名',
            'nav_url' => url("member/myRealName"),
            'title' => '实名认证',
            'subtitle' => '实名认证',
        ];

        $data = $api->getUserAuth($request)['data'];
        return $this->homeView('member.editRealName', [
            'tig' => $tig,
            'data' => $data,
        ]);
    }

    public function myWallet() {
        $tig = [
            'active' => 'myWallet',
            'nav' => '我的钱包',
            'nav_url' => url("member/myWallet"),
            'title' => '钱包',
            'subtitle' => '钱包',
        ];
        $request = \Request();
        $user = session(SessionKey::HomeInfo);
        $request->offsetSet('home_user_info', $user);
        $request->offsetSet('home_key', SessionKey::HomeInfo);
        $api = new \Modules\Main\Http\Controllers\Api\MemberController();
        $wallet = $api->getWallet($request)['data'];

        $config = Func::getBaseConfig('signInConfig');

        return $this->homeView('member.myWallet', [
            'tig' => $tig,
            'wallet' => $wallet,
            'config' => $config,
        ]);
    }

    public function myBill() {
        $tig = [
            'active' => 'myBill',
            'nav' => '我的账单',
            'nav_url' => url("member/myBill"),
            'title' => '账单',
            'subtitle' => '账单',
        ];
        $request = \Request();
        $all = $request->all();
        $user = session(SessionKey::HomeInfo);
        $request->offsetSet('home_user_info', $user);
        $request->offsetSet('home_key', SessionKey::HomeInfo);
        $request->offsetSet('data_type', 'wallet_record');
//        $request->offsetSet('pagesize', 10);
        $api = new \Modules\Main\Http\Controllers\Api\MemberController();
        $data = $api->myBill($request)['data'];

        return $this->homeView('member.myBill', [
            'tig' => $tig,
            'all' => $all,
            'data' => $data,
        ]);
    }
}
