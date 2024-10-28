<?php

namespace Modules\Main\Http\Controllers\Admin;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Modules\Main\Models\Member;
use Modules\Main\Services\ServiceModel;
use Modules\ModulesController;
use Modules\System\Http\Controllers\Common\SessionKey;
use function back;
use function cacheGlobalSettingsByKey;
use function getTranslateByKey;
use function redirect;
use function session;
use function view;

class LoginController extends ModulesController {
    //登录
    public function login(Request $request) {

        if (session(\Modules\System\Http\Controllers\Common\SessionKey::AdminInfo)) {
            //返回上一层，带上已登录提示
            return redirect("/admin")->with("errormsg", getTranslateByKey('already_logged_in'));
        }
        $entrance = cacheGlobalSettingsByKey('admin_login_entrance');
        $arr = explode('/', $request->getPathInfo()) ?: [];
        if ($arr[3] != $entrance) {
            abort(404, '页面不存在');
        }

        return view("admin/login/login");
    }

    public function handle() {
        if ($this->request->isMethod("POST")) {
            $post = $this->request->all();
            //记住登录
            session()->put("admin_remember",
                [
                    "username" => $post["username"],
                    "is_remember" => $post["is_remember"],
                    "password" => session('admin_remember')["password"]?:ServiceModel::getPassword($post["password"])
                ]
            );

            if (cacheGlobalSettingsByKey("admin_login_code") == 1) {
                try {
                    //验证码验证码
                    $this->validate($this->request, [
                        'captcha' => 'required|captcha'
                    ], [
                        'captcha.required' => getTranslateByKey('please_fill_in_the_verification_code'),
                        'captcha.captcha' => getTranslateByKey('verification_code_error'),
                    ]);
                } catch (\Exception $e) {
                    return back()->withErrors([getTranslateByKey('verification_code_error')])->withInput();
                }
            }

            if (!isset($post["password"]) || !isset($post["username"])) return back()->withErrors([getTranslateByKey('user_name_or_password_required')])->withInput();

            //判断空值处理
            $first = ServiceModel::loginFindUserV2(Member::TABLE_NAME, $post);
            if (!$first) return back()->withErrors([getTranslateByKey('incorrect_username')])->withInput();
            if ($first["password"] != ServiceModel::getPassword($post["password"])) {
                if ($first["password"] != session("admin_remember")["password"]){
                    //登录后台的事件
                    hook("Loger", [
                        'module' => 'Main',
                        'type' => 3,
                        'two_type' => 3,
                        'params' => [
                            'uid' => $first['uid'],
                            'username' => $first['username'],
                        ],
                        'remark' => '登录后台-密码错误',
                        'unique_id' => $first['uid'],
                    ]);
                    session()->put("admin_remember",
                        array_merge(session('admin_remember')?:[], ["password" => null])
                    );
                    return back()->withErrors([getTranslateByKey('incorrect_password')])->withInput();
                }
            }else{
                session()->put("admin_remember",
                    array_merge(session('admin_remember')?:[], ["password" => ServiceModel::getPassword($post["password"])])
                );
            }

            //存放登录记录
            session()->put(SessionKey::AdminInfo, $first);
            //登录后台的事件
            hook("Loger", [
                'module' => 'Main',
                'type' => 3,
                'two_type' => 1,
                'params' => [
                    'uid' => $first['uid'],
                    'username' => $first['username'],
                ],
                'remark' => '登录后台成功',
                'unique_id' => $first['uid'],
            ]);

            if ($post["is_remember"] != "on") {
                session()->forget("admin_remember");
            }

            //返回上一级
            if (session("admin_previous")) return redirect(session("admin_previous"));
            return redirect("/admin/index");

        }
    }

    //退出
    public function logout(Request $request) {
        session(['userInfo' => NULL]);
        session([SessionKey::AdminInfo => NULL]);
        session([SessionKey::HomeInfo => NULL]);
        session([SessionKey::CurrentUserPermissionGroupInfo => NULL]);
        session()->save();
        return redirect('admin/login/' . cacheGlobalSettingsByKey('admin_login_entrance'));
    }
}
