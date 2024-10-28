<?php

namespace Modules\System\Http\Controllers\Admin;

use Modules\Main\Services\ServiceModel;
use Modules\System\Http\Controllers\Common\CommonController;
use Illuminate\Http\Request;
use Modules\Main\Models\Member;
class UserController extends CommonController {

    /*********************  用户列表 ************************/

    //用户列表
    public function userList(Request $request) {
        $post = $request->all();
        $pageData = [
            'title' => '用户列表',
            'controller' => 'User',
            'action' => 'user/userList',
        ];
        //时间范围
        if ($request->timeRang) {
            $post['timeRang'] = dealTimeRang($request->timeRang);
        }
        $post['module'] = $this->moduleName;
        $data = \Modules\System\Services\ServiceModel::getAdminUserList($post, ['member']);
        return $this->adminView('user.userList', [
            'pageData' => $pageData,
            'data' => $data,
        ]);
    }

    //用户添加
    public function userAdd(Request $request) {
        $all = $this->request->all();

        if ($request->isMethod("post")) {
            $member = New ServiceModel();
            //文件上传
            if ($_FILES['avatar']['size'] > 0) {
                try {
                    $avatar = UploadFile($this->request, "avatar", "avatar/" . date("Y/m/d/") . uniqid(), ALLOWEXT, __E("upload_driver"));
                    $all['avatar'] = $avatar;
                    $this->resizeImg($all['avatar'], 50, 100, 100);
                } catch (\Exception $exception) {
                    return returnArr(0, $exception->getMessage());
                }
            }

            return $member->InsertArr($all);
        }

        $pageData = [
            'title' => '用户添加',
            'controller' => 'User',
            'action' => 'user/userAdd',
        ];
        return $this->adminView('user.userAdd', [
            'pageData' => $pageData,
            'data' => [],
        ]);
    }

    //详情
    public function userDetail(Request $request) {
        $all = $request->all();
        if ($request->isMethod('POST')) {
            $findUser = Member::query()->find($all['uid']);
            if (!$findUser) return oneFlash([0, 'id错误']);
            $all['moduleName'] = 'System';
            $res = hook('UpdateUserInfo', $all)[0];
            if ($res['status'] == 200) {
                return oneFlash([200, '更新成功']);
            } else {
                return oneFlash([0, '更新失败']);
            }

        } else {
            $pageData = [
                'title' => '用户详情',
                'controller' => 'User',
                'action' => 'user/userList',
            ];

            $data = Member::query()->find($all['uid']);
            if (!$data) return oneFlash([0, '参数错误']);
            session(['tmp_user' => $data]);
            return $this->adminView('user.userDetail', [
                'pageData' => $pageData,
                'data' => $data,
            ]);
        }
    }

}

