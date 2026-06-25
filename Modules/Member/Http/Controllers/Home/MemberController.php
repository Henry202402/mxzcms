<?php

namespace Modules\Member\Http\Controllers\Home;

use Modules\Main\Models\Member as MainMember;
use Modules\Main\Services\ServiceModel;
use Modules\Member\Helper\Func;
use Modules\Member\Http\Controllers\Api\AuthController;
use Modules\Member\Http\Controllers\Common\CommonController;
use Modules\Member\Models\Auth;
use Modules\Member\Models\AuthRecord;
use Modules\Member\Models\SignIn;
use Modules\Member\Models\Wallet;
use Modules\System\Http\Controllers\Common\SessionKey;

class MemberController extends CommonController {

    protected function makeHomeRequest()
    {
        $request = \Request();
        $request->offsetSet('home_user_info', session(SessionKey::HomeInfo));
        $request->offsetSet('home_key', SessionKey::HomeInfo);
        return $request;
    }

    protected function normalizeHomeUser($user): array
    {
        if (is_array($user)) {
            return $user;
        }

        if (is_object($user) && method_exists($user, 'toArray')) {
            return $user->toArray();
        }

        return (array) $user;
    }

    protected function buildOverviewData($user, array $list = []): array
    {
        $user = $this->normalizeHomeUser($user);
        $request = $this->makeHomeRequest();
        $api = new \Modules\Main\Http\Controllers\Api\MemberController();

        $wallet = $api->getWallet($request)['data'] ?? [];
        $authData = $api->myRealName($request)['data'] ?? [];
        $unreadData = $api->getUserNoReadMessage($request)['data'] ?? [];

        $fieldChecks = [
            !empty($user['avatar']),
            !empty($user['nickname']),
            !empty($user['email']),
            !empty($user['phone']),
            !empty($user['signature']),
        ];
        $profileCompletion = (int) round(array_sum($fieldChecks) / count($fieldChecks) * 100);

        $auth = $authData['auth'] ?? null;
        $authRecord = $authData['authRecord'] ?? null;
        $authStatusMap = Auth::status();
        $authRecordStatusMap = AuthRecord::status();
        $authStatus = '未认证';
        $authTone = 'muted';
        if ($auth) {
            $authStatus = $authStatusMap[$auth['status']] ?? '已认证';
            $authTone = ((int) ($auth['status'] ?? 0) === 1) ? 'success' : 'warning';
        } elseif ($authRecord) {
            $authStatus = $authRecordStatusMap[$authRecord['status']] ?? '审核中';
            $authTone = ((int) ($authRecord['status'] ?? 0) === 2) ? 'danger' : 'warning';
        }

        $vipTime = trim((string) ($wallet['vip_time'] ?? ''));
        $hasVip = $vipTime !== '' && $vipTime !== '0000-00-00 00:00:00';
        $memberCount = MainMember::query()->where('pid', $user['uid'])->count();
        $monthSignCount = SignIn::query()
            ->where('uid', $user['uid'])
            ->where('created_at', 'like', date('Y-m') . '%')
            ->count();

        return [
            'wallet' => $wallet,
            'auth' => $auth,
            'auth_record' => $authRecord,
            'auth_status' => $authStatus,
            'auth_tone' => $authTone,
            'profile_completion' => $profileCompletion,
            'member_count' => $memberCount,
            'month_sign_count' => $monthSignCount,
            'unread_message_num' => (int) ($unreadData['no_read_num'] ?? 0),
            'has_vip' => $hasVip,
            'vip_time_text' => $hasVip ? $vipTime : '未开通',
            'stats' => [
                [
                    'label' => '账户余额',
                    'value' => number_format((float) ($wallet['balance'] ?? 0), 2),
                    'suffix' => '元',
                    'icon' => 'mdi mdi-wallet-outline',
                    'tone' => 'primary',
                    'url' => url('member/myWallet'),
                ],
                [
                    'label' => '可提现余额',
                    'value' => number_format((float) ($wallet['withdrawable'] ?? 0), 2),
                    'suffix' => '元',
                    'icon' => 'mdi mdi-cash-multiple',
                    'tone' => 'success',
                    'url' => url('member/myWallet'),
                ],
                [
                    'label' => Func::getBaseConfig('signInConfig')['integral_alias'] ?: '积分',
                    'value' => (string) ((int) ($wallet['integral'] ?? 0)),
                    'suffix' => '分',
                    'icon' => 'mdi mdi-star-circle-outline',
                    'tone' => 'warning',
                    'url' => url('member/signinRecord'),
                ],
                [
                    'label' => '未读消息',
                    'value' => (string) ((int) ($unreadData['no_read_num'] ?? 0)),
                    'suffix' => '条',
                    'icon' => 'mdi mdi-bell-badge-outline',
                    'tone' => 'danger',
                    'url' => url('member/message'),
                ],
            ],
            'quick_links' => [
                ['title' => '完善资料', 'desc' => '更新昵称、邮箱、签名和头像', 'icon' => 'mdi mdi-account-edit-outline', 'url' => url('member/mine')],
                ['title' => '账号安全', 'desc' => '修改登录密码并检查安全状态', 'icon' => 'mdi mdi-shield-lock-outline', 'url' => url('member/password')],
                ['title' => '实名认证', 'desc' => '提交或查看实名审核状态', 'icon' => 'mdi mdi-card-account-details-outline', 'url' => url('member/myRealName')],
                ['title' => '钱包账单', 'desc' => '查看余额、积分和账单流水', 'icon' => 'mdi mdi-credit-card-outline', 'url' => url('member/myBill')],
            ],
            'service_cards' => [
                ['title' => '我的会员', 'desc' => '查看邀请或归属的会员数量', 'meta' => $memberCount . ' 位会员', 'icon' => 'mdi mdi-account-multiple-outline', 'url' => url('member/myMembers')],
                ['title' => '签到中心', 'desc' => '保持活跃，累计签到奖励', 'meta' => '本月 ' . $monthSignCount . ' 次签到', 'icon' => 'mdi mdi-calendar-check-outline', 'url' => url('member/signIn')],
                ['title' => 'VIP 服务', 'desc' => $hasVip ? '当前有效期至 ' . $vipTime : '开通会员解锁更多权益', 'meta' => $hasVip ? '会员有效中' : '立即开通', 'icon' => 'mdi mdi-crown-outline', 'url' => url('member/myVip')],
                ['title' => '扩展入口', 'desc' => !empty($list) ? '已接入 ' . count($list) . ' 个模块能力' : '当前暂无扩展模块入口', 'meta' => !empty($list) ? '查看快捷入口' : '后续可继续扩展', 'icon' => 'mdi mdi-apps', 'url' => url('member')],
            ],
        ];
    }

    public function index() {
        $user = session(SessionKey::HomeInfo);
        $tig = [
            'active' => 'index',
            'nav' => '会员中心',
            'nav_url' => url("member"),
            'title' => '控制台',
            'subtitle' => '首页概览',
            'description' => '查看账户资料、安全状态、资产余额和常用入口。',
        ];
        $list = hook("GetMemberEntry",[]);
        $list = array_filter($list);
        return $this->homeView('index.index', [
            'tig' => $tig,
            'list' => $list,
            'user' => $user,
            'overview' => $this->buildOverviewData($user, $list),
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
        $tig = [
            'active' => 'mine',
            'nav' => '会员中心',
            'nav_url' => url("member"),
            'title' => '资料管理',
            'subtitle' => '个人资料',
            'description' => '完善基础资料、头像和联系方式，让账户信息更完整。',
        ];
        return $this->homeView('index.mine', [
            'tig' => $tig,
            'user' => $user,
            'overview' => $this->buildOverviewData($user),
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
            'active' => 'password',
            'nav' => '会员中心',
            'nav_url' => url("member"),
            'title' => '账号安全',
            'subtitle' => '修改密码',
            'description' => '定期更换高强度密码，保护账户登录安全。',
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
            'description' => '查看归属于你的会员用户和基础资料。',
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
            'description' => '保持连续签到，累积积分和成长奖励。',
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
            'description' => '查看历史签到记录和每次获得的积分。',
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
            'description' => '查看可购买的 VIP 套餐、权益说明和当前会员状态。',
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
            'description' => '查看历史 VIP 订单、支付方式和支付状态。',
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
            'description' => '提交、查看和维护你的个人或企业实名认证信息。',
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
            'description' => '按要求提交真实资料，完成认证后可解锁更多服务。',
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
            'description' => '更新已有实名资料并重新提交审核。',
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
            'description' => '集中查看余额、可提现金额、积分和 VIP 到期时间。',
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
            'description' => '查看资金与积分流水，掌握每一笔账户变动。',
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
