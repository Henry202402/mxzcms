<?php

namespace Modules\Member\Http\Controllers\Admin;

use Modules\Formtools\Http\Controllers\Admin\FormTool;
use Modules\Main\Services\ServiceModel;
use Modules\Member\Helper\Func;
use Modules\Member\Http\Controllers\Common\CommonController;
use Illuminate\Http\Request;
use Modules\Main\Models\Member;
use Modules\Member\Models\AuthRecord;

class UserController extends CommonController {

    /*********************  用户列表 ************************/

    //用户列表
    public function userList(Request $request) {
        $all = $request->all();
        $pageData = getURIByRoute($this->request);
        $pageData['controller'] = 'User';
        $pageData['action'] = 'user/userList';
        //时间范围
        if ($request->timeRang) {
            $post['timeRang'] = dealTimeRang($request->timeRang);
        }
        $all['module'] = $this->moduleName;
        $data = \Modules\Member\Services\ServiceModel::getAdminUserList($all, ['member']);
        $personArr = $companyArr = [];
        foreach ($data as &$d) {
            $d['group_name'] = $d['group_name'] ?: '用户';
            $d['statusCssClass'] = $d['status'] == 1 ? FormTool::label_success : FormTool::label_info;
            $typeArr = AuthRecord::getAddRecordGetType($d['uid']);
            if (in_array(2, $typeArr)) {
                $personArr[] = $d['uid'];
                $companyArr[] = $d['uid'];
            }
            if (in_array(1, $typeArr)) $personArr[] = $d['uid'];
        }
        $statusList = Func::dealArrayToTwoArray(status());
        return FormTool::create()
            ->field("uid", "用户uid")
            ->field("avatar", ['name' => "头像", 'formtype' => 'image'])
            ->field("username", "名称")
            ->field("nickname", "昵称")
            ->field("email", "邮箱")
            ->field("pid_name", "上级")
            ->field("group_name", "身份权限")
            ->field("status", ['name' => "状态", 'datas' => status()])
            ->field("created_at", ['name' => "时间"])
            ->rightAction([
                ['actionName' => '编辑', 'actionUrl' => moduleAdminJump($this->moduleName, 'user/userDetail'), 'cssClass' => FormTool::btn_info, 'param' => ['page' => $_GET['page']]],
                ['actionName' => '个人认证', 'actionUrl' => moduleAdminJump($this->moduleName, 'user/userAuthAdd'), 'cssClass' => FormTool::btn_primary, 'param' => ['type' => 1], 'notIdArray' => $personArr],
                ['actionName' => '企业认证', 'actionUrl' => moduleAdminJump($this->moduleName, 'user/userAuthAdd'), 'cssClass' => FormTool::btn_primary, 'param' => ['type' => 2], 'notIdArray' => $companyArr],
            ], "uid")
            ->pageTitle("账号管理")
            ->formTitle("用户列表")
            ->searchField("keyword", "品牌名", $all['keyword'])
            ->searchField("status", "状态", $all['status'], 'select', $statusList)
            ->listAction([
                ['actionName' => '添加', 'actionUrl' => moduleAdminJump($this->moduleName, 'user/userAdd'), 'cssClass' => "bg-info btn-xs"],
            ])
            ->linkAppend($all)
            ->listView($pageData, $data);

        /*return $this->adminView('user.userList', [
            'pageData' => $pageData,
            'data' => $data,
        ]);*/
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

