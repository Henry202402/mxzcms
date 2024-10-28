<?php

namespace Modules\Main\Http\Controllers\Admin;

use Modules\Main\Models\Member;
use Modules\ModulesController;

class UserController extends ModulesController {

    //我的信息
    function myInfo() {
        $admin_info = session(\Modules\System\Http\Controllers\Common\SessionKey::AdminInfo);
        if ($this->request->ajax()) {
            $all = $this->request->all();
            if ($all['uid'] != $admin_info['uid']) return returnArr(0, '身份错误');
            $all['moduleName'] = 'System';
            $res = hook('UpdateUserInfo', $all)[0];
            return $res;
        }

        $user = Member::query()->find($admin_info['uid']);
        if (!$user) return back()->with('errormsg', 'no data');
        return view("admin/user/userinfo", ['member' => $user]);
    }

}

