<?php

namespace Modules\System\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Main\Models\Member;
use Modules\Main\Services\ServiceModel;
use Modules\System\Http\Controllers\Common\CommonController;
use Modules\System\Http\Controllers\Common\SessionKey;

class IndexController extends CommonController {
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index() {
        $pageData = [
            'title' => '后台—首页',
            'controller' => 'Index'
        ];
        $uid = session(SessionKey::HomeInfo)['uid'];
        if (session('login_type') == 'admin') {
            $data['user'] = [
                'all_user' => ServiceModel::getHomeUser(Member::TABLE_NAME, ['user_type' => 1]),
                'month_user' => ServiceModel::getHomeUser(Member::TABLE_NAME, ['user_type' => 1, 'month' => 1]),
                'day_user' => ServiceModel::getHomeUser(Member::TABLE_NAME, ['user_type' => 1, 'day' => 1]),
            ];
        }
        return $this->adminView('index.index', [
            'pageData' => $pageData
        ]);
    }

    //后台登录
    public function login(Request $request) {
        $all = $request->all();
        // 提交登录
        if ($request->isMethod('post')) {
            $loginFlag = $all['loginFlag'];
            $pass = $all['password'];

            if (empty($loginFlag)) return returnArr(201, '请输入登录账号');
            if (empty($pass)) return returnArr(202, '请输入登录密码');

            if (cacheGlobalSettingsByKey("admin_login_code") == 1) {
                //验证验证码
                try {
                    $this->validate($request, [
                        'captcha' => 'required|captcha'
                    ], [
                        'captcha.required' => '验证码不能为空',// 验证码不能为空,
                        'captcha.captcha' => '验证码错误',//验证码错误
                    ]);
                } catch (\Exception $e) {
                    return returnArr(0, '验证码错误');
                }
            }

            $res = Member::query()
                ->where('username', $loginFlag)
                ->orWhere('phone', $loginFlag)
                ->first();
            $password = ServiceModel::getPassword($pass);

            // 密码验证
            if (empty($res)) return returnArr(203, '账户不存在');
            if ($res['password'] != $password) return returnArr(204, '密码错误');

            // 权限判断
            // 用户类型  admin = 超级管理员, member=用户
            if ($res['status'] != 1) return returnArr(205, '账户已禁用');

            $request->session()->put([SessionKey::AdminInfo => $res]);

            return returnArr(200, '登录成功', ['url' => moduleAdminJump($this->moduleName, 'user/info')]);
        }
        return $this->adminView('index.login', [
            'title' => '后台—登录',
            'moduleName' => $this->moduleName,
        ]);
    }

    //后台退出
    public function logout() {
        session([SessionKey::AdminInfo => NULL]);
        return redirect(moduleAdminJump($this->moduleName, 'login'));
    }
}
