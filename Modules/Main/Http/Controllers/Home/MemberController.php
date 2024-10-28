<?php

namespace Modules\Main\Http\Controllers\Home;

use Modules\Main\Services\ServiceModel;
use Modules\ModulesController;
use Modules\System\Http\Controllers\Common\SessionKey;

class MemberController extends ModulesController {


    public function index() {
        return HomeView('member.index');
    }


    public function mine() {
        if ($this->request->isMethod("post")) {
            $request = \Request();
            $request->offsetSet('home_user_info', session(SessionKey::HomeInfo));
            $request->offsetSet('home_key', SessionKey::HomeInfo);
            $api = new \Modules\Main\Http\Controllers\Api\MemberController();
            return $api->mine($request);
        }
        return HomeView('member.mine');
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
        return HomeView('member.updatePassword');
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

    /************************************ 站内信 ***************************************/
    //站内信列表
    public function messageList() {
        $all = $this->request->all();

        $request = \Request();
        $request->offsetSet('home_user_info', session(SessionKey::HomeInfo));
        $api = new \Modules\Main\Http\Controllers\Api\MemberController();
        $res = $api->messageList($request);

        $data['param'] = $all;
        $data['data'] = $res['data'];

        return HomeView('member.messageList', $data);
    }

    //站内信详情
    public function messageDetail() {
        $request = \Request();
        $request->offsetSet('home_user_info', session(SessionKey::HomeInfo));
        $api = new \Modules\Main\Http\Controllers\Api\MemberController();
        $res = $api->messageDetail($request);

        $data['data'] = $res['data'];
        return HomeView('member.messageDetail', $data);
    }

    //站内信已读
    public function messageRead() {
        $request = \Request();
        $request->offsetSet('home_user_info', session(SessionKey::HomeInfo));
        $api = new \Modules\Main\Http\Controllers\Api\MemberController();
        return $api->messageRead($request);
    }

    //站内信删除
    public function messageDelete() {
        $request = \Request();
        $request->offsetSet('home_user_info', session(SessionKey::HomeInfo));
        $api = new \Modules\Main\Http\Controllers\Api\MemberController();
        return $api->messageDelete($request);
    }
}
