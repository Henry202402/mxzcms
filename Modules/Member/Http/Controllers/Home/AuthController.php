<?php

namespace Modules\Member\Http\Controllers\Home;

use Modules\Member\Http\Controllers\Common\CommonController;
use Modules\System\Http\Controllers\Common\SessionKey;

class AuthController extends CommonController {

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
        return $this->homeView('auth.myRealName', [
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
            $api = new \Modules\Member\Http\Controllers\Api\AuthController($request);
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
        return $this->homeView('auth.addRealName', [
            'tig' => $tig,
            'auth' => $auth,
        ]);
    }

    public function editRealName() {
        $request = \Request();
        $request->offsetSet('uid', session(SessionKey::HomeInfo)['uid']);
        $request->offsetSet('admin_str', $this->getNoLoginStr());
        $api = new \Modules\Member\Http\Controllers\Api\AuthController($request);
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
        return $this->homeView('auth.editRealName', [
            'tig' => $tig,
            'data' => $data,
        ]);
    }
}
