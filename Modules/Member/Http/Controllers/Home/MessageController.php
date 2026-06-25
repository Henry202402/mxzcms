<?php

namespace Modules\Member\Http\Controllers\Home;

use Modules\Member\Http\Controllers\Common\CommonController;
use Modules\System\Http\Controllers\Common\SessionKey;

class MessageController extends CommonController {

    /************************************ 站内信 ***************************************/
    //站内信列表
    public function messageList() {
        $all = $this->request->all();
        $homeUser = session(SessionKey::HomeInfo);
        if (is_object($homeUser) && method_exists($homeUser, 'toArray')) {
            $homeUser = $homeUser->toArray();
        }
        $all['moduleName'] = 'System';
        $all['operate_type'] = 4;
        $all['uid'] = $homeUser['uid'] ?? 0;
        $res = hook('UpdateUserMessage', $all)[0];

        $data['param'] = $all;
        $data['data'] = $res['data'] ?? [];
        $data['tig'] = [
            'active' => 'systemMessage',
            'nav' => '站内信',
            'nav_url' => url("member/message"),
            'title' => '站内信',
            'subtitle' => '列表',
            'description' => '查看系统通知、业务提醒，并批量处理未读消息。',
        ];
        return $this->homeView('setting.messageList', $data);
    }

    //站内信详情
    public function messageDetail() {
        $request = \Request();
        $request->offsetSet('home_user_info', session(SessionKey::HomeInfo));
        $api = new \Modules\Main\Http\Controllers\Api\MemberController();
        $res = $api->messageDetail($request);

        $data['data'] = $res['data'];
        return HomeView('setting.messageDetail', $data);
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

    //获取站内信未读数量
    public function getUserNoReadMessage() {
        $request = \Request();
        $request->offsetSet('home_user_info', session(SessionKey::HomeInfo));
        $api = new \Modules\Main\Http\Controllers\Api\MemberController();
        return $api->getUserNoReadMessage($request);
    }
}
