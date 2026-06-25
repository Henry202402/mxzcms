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
        $filters = [
            'username' => trim((string) ($all['username'] ?? '')),
            'uid' => trim((string) ($all['uid'] ?? '')),
            'status' => array_key_exists('status', $all) ? (string) $all['status'] : '',
            'timeRang' => trim((string) ($all['timeRang'] ?? '')),
            'page' => (int) ($all['page'] ?? 1),
        ];
        $pageData = getURIByRoute($this->request);
        $pageData['controller'] = 'User';
        $pageData['action'] = 'user/userList';

        $query = [
            'username' => $filters['username'],
            'uid' => (int) $filters['uid'],
            'timeRang' => [],
            'module' => $this->moduleName,
        ];

        if ($filters['timeRang'] !== '') {
            $query['timeRang'] = dealTimeRang($filters['timeRang']);
        }

        if ($filters['status'] !== '') {
            $query['status'] = (int) $filters['status'];
        }

        $data = \Modules\Member\Services\ServiceModel::getAdminUserList($query, ['member']);
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
            ->field("phone", "手机号")
            ->field("email", "邮箱")
            ->field("pid_name", "上级")
            ->field("group_name", "身份权限")
            ->field("status", ['name' => "状态", 'datas' => status()])
            ->field("created_at", ['name' => "时间"])
            ->rightAction([
                ['actionName' => '编辑', 'actionUrl' => moduleAdminJump($this->moduleName, 'user/userDetail'), 'cssClass' => FormTool::btn_info, 'param' => ['page' => $filters['page']]],
                ['actionName' => '个人认证', 'actionUrl' => moduleAdminJump($this->moduleName, 'user/userAuthAdd'), 'cssClass' => FormTool::btn_primary, 'param' => ['type' => 1], 'notIdArray' => $personArr],
                ['actionName' => '企业认证', 'actionUrl' => moduleAdminJump($this->moduleName, 'user/userAuthAdd'), 'cssClass' => FormTool::btn_primary, 'param' => ['type' => 2], 'notIdArray' => $companyArr],
            ], "uid")
            ->pageTitle("账号管理")
            ->formTitle("用户列表")
            ->searchField("username", "手机号/用户名称/昵称", $filters['username'])
            ->searchField("uid", "用户ID", $filters['uid'])
            ->searchField("status", "状态", $filters['status'], 'select', $statusList)
            ->searchField("timeRang", "时间", $filters['timeRang'], 'dateRange')
            ->listAction([
                ['actionName' => '添加', 'actionUrl' => moduleAdminJump($this->moduleName, 'user/userAdd'), 'cssClass' => "bg-info btn-xs"],
            ])
            ->linkAppend([
                'username' => $filters['username'],
                'uid' => $filters['uid'],
                'status' => $filters['status'],
                'timeRang' => $filters['timeRang'],
            ])
            ->listView($pageData, $data);
    }

    //用户添加
    public function userAdd(Request $request) {
        $all = $this->request->all();

        if ($request->isMethod("post")) {
            $member = New ServiceModel();
            //文件上传
            if ($request->hasFile('avatar')) {
                try {
                    $avatar = UploadFile($this->request, "avatar", "avatar/" . date("Y/m/d/") . uniqid(), ALLOWEXT, __E("upload_driver"));
                    $all['avatar'] = $avatar;
                    $this->resizeImg($all['avatar'], 50, 100, 100);
                } catch (\Exception $exception) {
                    return returnArr(0, $exception->getMessage());
                }
            }

            $res = $member->InsertArr($all);
            if (($res['status'] ?? 0) == 200) {
                $res['data']['jumpUrl'] = $all['jumpUrl'] ?: moduleAdminJump($this->moduleName, 'user/userList');
            }
            return $res;
        }

        $pageData = getURIByRoute($this->request);
        $pageData['controller'] = 'User';
        $pageData['action'] = 'user/userAdd';
        $phoneCodeList = Func::dealArrayToTwoArray(getPhoneCode());
        $maleList = Func::dealArrayToTwoArray([
            '男' => '男',
            '女' => '女',
        ]);

        return FormTool::create()
            ->field("username", ['name' => '用户名', 'required' => 'required'])
            ->field("nickname", ['name' => '昵称', 'required' => 'required'])
            ->field("phone_code", ['name' => '区号', 'formtype' => 'select', 'datas' => $phoneCodeList, 'value' => '86'])
            ->field("phone", ['name' => '手机号码', 'required' => 'required'])
            ->field("password", ['name' => '密码', 'formtype' => 'password', 'required' => 'required'])
            ->field("confirm_password", ['name' => '确认密码', 'formtype' => 'password', 'required' => 'required'])
            ->field("male", ['name' => '性别', 'formtype' => 'radio', 'datas' => $maleList, 'value' => '男'])
            ->field("avatar", ['name' => '头像', 'formtype' => 'image'])
            ->csrf_field()
            ->pageTitle("账号管理")
            ->formTitle("添加用户")
            ->formAction(moduleAdminJump($this->moduleName, 'user/userAdd'))
            ->actionType('ajax')
            ->jumpPrevUrl()
            ->formView($pageData);
    }

    //详情
    public function userDetail(Request $request) {
        $all = $request->all();
        if ($request->isMethod('POST')) {
            $findUser = Member::query()->find($all['uid']);
            if (!$findUser) return returnArr(0, 'id错误');
            $all['moduleName'] = 'System';
            $res = hook('UpdateUserInfo', $all)[0];
            if (($res['status'] ?? 0) == 200) {
                return returnArr(200, '更新成功', [
                    'jumpUrl' => $all['jumpUrl'] ?: moduleAdminJump($this->moduleName, 'user/userList'),
                ]);
            }

            return returnArr(0, $res['msg'] ?: '更新失败');

        } else {
            $data = Member::query()->find($all['uid']);
            if (!$data) return oneFlash([0, '参数错误']);

            $pageData = getURIByRoute($this->request);
            $pageData['controller'] = 'User';
            $pageData['action'] = 'user/userDetail';
            $phoneCodeList = Func::dealArrayToTwoArray(getPhoneCode());
            $maleList = Func::dealArrayToTwoArray([
                '男' => '男',
                '女' => '女',
                '保密' => '保密',
            ]);
            $statusList = Func::dealArrayToTwoArray(status());

            return FormTool::create()
                ->field("c_code", ['name' => '区号', 'formtype' => 'select', 'datas' => $phoneCodeList, 'value' => $data['c_code'] ?: '86'])
                ->field("phone", ['name' => '手机号', 'value' => $data['phone']])
                ->field("username", ['name' => '用户名', 'value' => $data['username'], 'required' => 'required'])
                ->field("nickname", ['name' => '昵称', 'value' => $data['nickname']])
                ->field("password", ['name' => '密码', 'formtype' => 'password', 'placeholder' => '不填则不修改'])
                ->field("birthday", ['name' => '生日', 'formtype' => 'date', 'value' => $data['birthday']])
                ->field("avatar", ['name' => '头像', 'formtype' => 'image', 'value' => $data['avatar']])
                ->field("male", ['name' => '性别', 'formtype' => 'radio', 'datas' => $maleList, 'value' => $data['male'] ?: '男'])
                ->field("status", ['name' => '状态', 'formtype' => 'radio', 'datas' => $statusList, 'value' => (string) $data['status']])
                ->field("uid", ['formtype' => 'hidden', 'value' => $data['uid']])
                ->csrf_field()
                ->pageTitle("账号管理")
                ->formTitle("用户详情")
                ->formAction(moduleAdminJump($this->moduleName, 'user/userDetail'))
                ->actionType('ajax')
                ->jumpPrevUrl()
                ->formView($pageData);
        }
    }

}

