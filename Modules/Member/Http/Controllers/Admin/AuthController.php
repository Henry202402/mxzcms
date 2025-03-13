<?php

namespace Modules\Member\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Formtools\Http\Controllers\Admin\FormTool;
use Modules\Main\Models\Member;
use Modules\Main\Services\ServiceModel;
use Modules\Member\Helper\Func;
use Modules\Member\Http\Controllers\Common\CommonController;
use Modules\Member\Models\Auth;
use Modules\Member\Models\AuthRecord;

class AuthController extends CommonController {
    public $controller = 'User';
    public $action = 'user/userAuthList';

    public function userAuthList(Request $request) {
        $all = \Request()->all();
        $pageData = getURIByRoute($this->request);
        $pageData['controller'] = $this->controller;
        $pageData['action'] = $this->action;
        if ($all['rang']) {
            $rang = explode(' - ', $all['rang']);
            $all['rang_arr'] = [$rang[0] . ' 00:00:00', $rang[1] . ' 23:59:59'];
        }
        $data = \Modules\Member\Services\ServiceModel::getUserAuthList($all);
        foreach ($data as &$d) {
            if ($d['status'] != 0) $real_audit_arr[] = $d['id'];
            if ($d['status'] == 1) {
                $delete_btn_arr[] = $d['id'];
                $d['statusCssClass'] = FormTool::label_success;
            } elseif ($d['status'] == 2) {
                $d['statusCssClass'] = FormTool::label_danger;
            } else {
                $d['statusCssClass'] = FormTool::label_info;
            }
            $d['typeCssClass'] = $d['type'] == 1 ? FormTool::label_success : FormTool::label_danger;
            $d['user_name'] = $d['user_data']['username'];
            $d['user_phone'] = $d['user_data']['phone'];
            $d['user_avatar'] = $d['user_data']['avatar'];
            if ($d['type'] == 2) {
                $d['real_name'] = $d['id_card'] = $d['id_card_positive_img'] = $d['id_card_back_img'] = $d['id_card_hand_img'] = '';
            }

            unset($d['user_data']);
        }

        $statusList = Func::dealArrayToTwoArray(AuthRecord::status());
        $typeList = Func::dealArrayToTwoArray(AuthRecord::type());
        $noAuditNum = AuthRecord::getNoAuditNum();
        return FormTool::create()
            ->field("id", "ID")
            ->field("type", ['name' => '类型', 'datas' => AuthRecord::type()])
            ->field("user_name,user_phone", "申请人信息")
            ->field("real_name,id_card", "实名信息")
            ->field("id_card_positive_img", ['name' => "人像面", 'formtype' => 'image'])
            ->field("id_card_back_img", ['name' => "国徽面", 'formtype' => 'image'])
            ->field("id_card_hand_img", ['name' => "手持", 'formtype' => 'image'])
            ->field("company_name,unified_social_credit_code", ['name' => "企业信息",])
            ->field("legal_person,legal_id_card", ['name' => '法人信息',])
            ->field("business_license_img", ['name' => "营业执照", 'formtype' => 'image'])
            ->field("status", ['name' => '状态', 'datas' => AuthRecord::status()])
            ->field("created_at", "时间")
            ->pageTitle("账号管理")
            ->formTitle("认证列表")
            ->searchField("real_name", "真实名称", $all['real_name'])
            ->searchField("id_card", "身份证号", $all['id_card'])
            ->searchField("type", "类型", $all['type'], 'select', $typeList)
            ->searchField("status", "用户状态", $all['status'], 'select', $statusList)
            ->searchField("rang", "时间", $all['rang'], 'dateRange')
            ->listAction([
                ['actionName' => "认证审核（{$noAuditNum}）", 'actionUrl' => moduleAdminJump($this->moduleName, 'user/userAuthRecordList'), 'cssClass' => "bg-info btn-xs"],
            ])
            ->linkAppend($all)
            ->listView($pageData, $data);
    }

    public function userAuthRecordList(Request $request) {
        $all = \Request()->all();
        $pageData = getURIByRoute($this->request);
        $pageData['controller'] = $this->controller;
        $pageData['action'] = $this->action;
        if ($all['rang']) {
            $rang = explode(' - ', $all['rang']);
            $all['rang_arr'] = [$rang[0] . ' 00:00:00', $rang[1] . ' 23:59:59'];
        }
        $data = \Modules\Member\Services\ServiceModel::getUserAuthRecordList($all);
        $real_audit_arr = [];
        $delete_btn_arr = [];
        foreach ($data as &$d) {
            if ($d['status'] != 0) $real_audit_arr[] = $d['id'];
            if ($d['status'] == 1) {
                $delete_btn_arr[] = $d['id'];
                $d['statusCssClass'] = FormTool::label_success;
            } elseif ($d['status'] == 2) {
                $d['statusCssClass'] = FormTool::label_danger;
            } else {
                $d['statusCssClass'] = FormTool::label_info;
            }
            $d['typeCssClass'] = $d['type'] == 1 ? FormTool::label_success : FormTool::label_danger;
            $d['user_name'] = $d['user_data']['username'];
            $d['user_phone'] = $d['user_data']['phone'];
            $d['user_avatar'] = $d['user_data']['avatar'];
            unset($d['user_data']);
        }

        $statusList = Func::dealArrayToTwoArray(AuthRecord::status());
        $typeList = Func::dealArrayToTwoArray(AuthRecord::type());

        return FormTool::create()
            ->field("id", "ID")
            ->field("type", ['name' => '类型', 'datas' => AuthRecord::type()])
            ->field("user_name,user_phone", "申请人信息")
            ->field("real_name,id_card", "实名信息")
            ->field("id_card_positive_img", ['name' => "人像面", 'formtype' => 'image'])
            ->field("id_card_back_img", ['name' => "国徽面", 'formtype' => 'image'])
            ->field("id_card_hand_img", ['name' => "手持", 'formtype' => 'image'])
            ->field("company_name,unified_social_credit_code", ['name' => "企业信息",])
            ->field("legal_person,legal_id_card", ['name' => '法人信息',])
            ->field("business_license_img", ['name' => "营业执照", 'formtype' => 'image'])
            ->field("status", ['name' => '状态', 'datas' => AuthRecord::status()])
            ->field("remark", ['name' => "备注",])
            ->field("created_at", "时间")
            ->pageTitle("账号管理")
            ->formTitle("认证列表")
            ->searchField("real_name", "真实名称", $all['real_name'])
            ->searchField("id_card", "身份证号", $all['id_card'])
            ->searchField("type", "类型", $all['type'], 'select', $typeList)
            ->searchField("status", "用户状态", $all['status'], 'select', $statusList)
            ->searchField("rang", "时间", $all['rang'], 'dateRange')
            ->listAction([
                ['actionName' => "返回", 'actionUrl' => moduleAdminJump($this->moduleName, 'user/userAuthList'), 'cssClass' => "bg-danger btn-xs"],
            ])
            ->rightAction([
                ['actionName' => '审核', 'actionUrl' => moduleAdminJump($this->moduleName, 'user/userAuthAudit'), 'cssClass' => "btn-info", 'notIdArray' => $real_audit_arr],
                ['actionName' => '删除', 'actionUrl' => moduleAdminJump($this->moduleName, 'user/userAuthDelete'), 'cssClass' => "btn-danger", "confirm" => true, 'notIdArray' => $delete_btn_arr
                ]], "id")
            ->linkAppend($all)
            ->listView($pageData, $data);

    }

    public function userAuthAdd(Request $request) {
        $all = \Request()->all();
        $user = ServiceModel::apiGetOne(Member::TABLE_NAME, ['uid' => $all['uid']]);
        if (!$user) return oneFlash([0, '用户不存在']);

        if ($request->ajax()) {
            $request->offsetSet('uid', $user['uid']);
            $request->offsetSet('admin_str', $this->getNoLoginStr());
            return (new \Modules\Member\Http\Controllers\Api\AuthController($request))->userAuthAdd($request);
        }

        $findAuth = ServiceModel::apiGetOne(Auth::TABLE_NAME, ['uid' => $all['uid']]);
        if ($findAuth && $findAuth['status'] == 0) return oneFlash([0, '认证记录待审核']);
        if ($findAuth && $_GET['type'] == 1) return oneFlash([0, '认证记录已提交']);
        if ($findAuth['type'] == 2 && $findAuth['status'] == 1) return oneFlash([0, '认证记录已审核']);

        $pageData = getURIByRoute($this->request);
        $pageData['controller'] = $this->controller;
        $pageData['action'] = $this->action;
        $res = FormTool::create()
            ->csrf_field()
            ->field("username", ['name' => '账号', 'value' => $user['username'], 'formtype' => "text", 'disabled' => 'disabled'])
            ->field("uid", ['value' => $user['uid'], 'formtype' => "hidden"]);

        if ($all['type'] == 1) {
            $res
                ->field("type", ['value' => $all['type'], 'formtype' => "hidden"])
                ->field("real_name", ['name' => '真实姓名', 'formtype' => "text", 'required' => "required"])
                ->field("id_card", ['name' => '身份证号', 'formtype' => "text", 'required' => "required"])
                ->field("id_card_positive_img", ['name' => '身份证人像面', 'formtype' => "image", 'required' => "required"])
                ->field("id_card_back_img", ['name' => '身份证国徽面', 'formtype' => "image", 'required' => "required"])
                ->field("id_card_back_img", ['name' => '身份证国徽面', 'formtype' => "image", 'required' => "required"])
                ->field("id_card_hand_img", ['name' => '手持身份证', 'formtype' => "image"]);
        } else {
            $res
                ->field("type", ['value' => $all['type'], 'formtype' => "hidden"])
                ->field("company_name", ['name' => '企业名称', 'formtype' => "text", 'required' => "required"])
                ->field("unified_social_credit_code", ['name' => '社会信用代码', 'placeholder' => '统一社会信用代码', 'formtype' => "text", 'required' => "required"])
                ->field("legal_person", ['name' => '法人名称', 'formtype' => "text", 'required' => "required"])
                ->field("legal_id_card", ['name' => '法人身份证号码', 'formtype' => "text", 'required' => "required"])
                ->field("business_license_img", ['name' => '营业执照', 'formtype' => "image", 'required' => "required"]);
        }

        return $res
            ->pageTitle("账号管理")
            ->formTitle("添加认证")
            ->formAction(moduleAdminJump($this->moduleName, 'user/userAuthAdd'))
            ->actionType('ajax')
            ->formView($pageData);
    }

    public function userAuthEdit(Request $request) {
        $all = \Request()->all();

        $findAuth = ServiceModel::apiGetOne(RealNameAuth::TABLE_NAME, ['real_id' => $all['real_id']]);
        if (!$findAuth) return oneFlash([0, '记录不存在']);
        //if ($findAuth['status'] == 1) return oneFlash([0, '实名记录已审核']);

        $data = ServiceModel::apiGetOne(Member::TABLE_NAME, ['uid' => $findAuth['uid']]);
        if (!$data) return oneFlash([0, '用户不存在']);

        if ($this->request->isMethod("post")) {
            if ($if = ifCondition([
                'real_id' => 'real_id不能为空',
                'real_name' => '真实姓名不能为空',
                'id_card' => '身份证号不能为空',
            ], $all)) return oneFlash([$if['status'], $if['msg']]);

            $add = [
                'real_name' => trim($all['real_name']),
                'id_card' => trim($all['id_card']),
                'status' => 0,
                'remark' => '',
            ];

            if ($_FILES['id_card_positive_img']['size'] > 0) {
                try {
                    $add['id_card_positive_img'] = UploadFile($this->request, "id_card_positive_img", "realname/" . date("Y/m/d/") . uniqid(), ALLOWEXT, __E("upload_driver"));
//                (new \Modules\System\Http\Controllers\Common\CommonController($this->request))->resizeImg($add['id_card_back_img'], 1024, 300, 300);
                } catch (\Exception $exception) {
                    return oneFlash([0, $exception->getMessage()]);
                }
            }

            if ($_FILES['id_card_back_img']['size'] > 0) {
                try {
                    $add['id_card_back_img'] = UploadFile($this->request, "id_card_back_img", "realname/" . date("Y/m/d/") . uniqid(), ALLOWEXT, __E("upload_driver"));
//                (new \Modules\System\Http\Controllers\Common\CommonController($this->request))->resizeImg($add['id_card_back_img'], 1024, 300, 300);
                } catch (\Exception $exception) {
                    return oneFlash([0, $exception->getMessage()]);
                }
            }

            $res = ServiceModel::whereUpdate(RealNameAuth::TABLE_NAME, ['real_id' => $findAuth['real_id']], $add);
            if ($res) {
                return oneFlash([200, '编辑成功', moduleAdminJump($this->moduleName, 'user/realNameList')]);
            } else {
                return oneFlash([0, '编辑失败']);
            }
        }


        $pageData = getURIByRoute($this->request);
        $pageData['controller'] = "User";
        $pageData['action'] = "user/realNameList";
        return FormTool::create()
            ->csrf_field()
            ->field("username", ['name' => '用户名称', 'value' => $data['username'], 'formtype' => "text", 'disabled' => 'disabled'])
            ->field("real_id", ['value' => $findAuth['real_id'], 'formtype' => "hidden"])
            ->field("real_name", ['name' => '真实姓名', 'placeholder' => "真实姓名", 'formtype' => "text", 'value' => $findAuth['real_name'],])
            ->field("id_card", ['name' => '身份证号', 'placeholder' => "身份证号", 'formtype' => "text", 'value' => $findAuth['id_card'],])
            ->field("id_card_positive_img", ['name' => '身份证人像面', 'formtype' => "image", 'value' => $findAuth['id_card_positive_img'],])
            ->field("id_card_back_img", ['name' => '身份证国徽面', 'formtype' => "image", 'value' => $findAuth['id_card_back_img'],])
            ->pageTitle("账号管理")
            ->formTitle("编辑实名")
            ->formAction(moduleAdminJump($this->moduleName, 'user/realNameEdit'))
            ->formView($pageData);
    }

    public function userAuthDelete(Request $request) {
        $find = AuthRecord::query()->find($request->id);
        $request->offsetSet('uid', $find['uid']);
        $request->offsetSet('admin_str', $this->getNoLoginStr());
        $res = (new \Modules\Member\Http\Controllers\Api\AuthController($request))->userAuthDelete($request);
        return oneFlash([$res['status'], $res['msg']]);
    }

    public function userAuthAudit(Request $request) {
        $all = \Request()->all();

        $findAuth = ServiceModel::apiGetOne(AuthRecord::TABLE_NAME, ['id' => $all['id']]);

        if ($request->ajax()) {
            if (!$findAuth) return returnArr(0, '记录不存在');
            if ($findAuth['status'] != 0) return returnArr(0, '记录状态有误');
            if ($if = ifCondition([
                'id' => 'id不能为空',
                'status' => '审核状态不能为空',
            ], $all)) return $if;

            if ($all['status'] == 2 && !$all['remark']) return returnArr(0, '请填写备注');

            $add = [
                'status' => $all['status'] == 1 ? 1 : 2,
                'remark' => $all['remark'] ?: '',
            ];
            DB::beginTransaction();
            $res = ServiceModel::whereUpdate(AuthRecord::TABLE_NAME, ['id' => $findAuth['id']], $add);

            if ($add['status'] == 1) {
                $findAuthOld = ServiceModel::apiGetOne(Auth::TABLE_NAME, ['uid' => $findAuth['uid']]);
                if ($findAuthOld) {
                    $addRecord = ServiceModel::whereUpdate(Auth::TABLE_NAME, ['id' => $findAuthOld['id']], [
                        'type' => $findAuth['type'],
                        'company_name' => $findAuth['company_name'],
                        'unified_social_credit_code' => $findAuth['unified_social_credit_code'],
                        'business_license_img' => $findAuth['business_license_img'],
                        'legal_person' => $findAuth['legal_person'],
                        'legal_id_card' => $findAuth['legal_id_card'],
                    ]);
                } else {
                    $findAuth = $findAuth->toArray();
                    $findAuth['status'] = 1;
                    unset($findAuth['id'], $findAuth['created_at'], $findAuth['updated_at']);
                    $addRecord = ServiceModel::add(Auth::TABLE_NAME, $findAuth);
                }
            } else {
                $addRecord = true;
            }


            if ($res && $addRecord) {
                DB::commit();

                $all['moduleName'] = 'System';
                $all['operate_type'] = 6;
                $all['module'] = $this->moduleName;
                $all['uid'] = $findAuth['uid'];
                $typeName = Auth::type()[$findAuth['type']];
                if ($add['status'] == 1) {
                    $all['title'] = '认证通过';
                    $all['content'] = "恭喜您，您的{$typeName}已认证通过";
                } else {
                    $all['title'] = '认证失败';
                    $all['content'] = "对不起，您的{$typeName}认证不通过，原因是：{$add['remark']}";
                }
                hook('UpdateUserMessage', $all);

                return returnArr(200, '操作成功', ['jumpUrl' => moduleAdminJump($this->moduleName, 'user/userAuthRecordList')]);
            } else {
                DB::rollBack();
                return returnArr(0, '操作失败');
            }
        }

        if (!$findAuth) return oneFlash([0, '记录不存在']);
        if ($findAuth['status'] != 0) return oneFlash([0, '记录状态有误']);
        $pageData = getURIByRoute($this->request);
        $pageData['controller'] = $this->controller;
        $pageData['action'] = $this->action;
        $res = FormTool::create()
            ->csrf_field()
            ->field("legend", ['name' => '审核信息', 'formtype' => "legend"]);

        if ($findAuth['type'] == 1) {
            $res
                ->field("real_name", ['name' => '真实姓名', 'formtype' => "text", 'value' => $findAuth['real_name'], 'disabled' => 'disabled'])
                ->field("id_card", ['name' => '身份证号', 'formtype' => "text", 'value' => $findAuth['id_card'], 'disabled' => 'disabled'])
                ->field("id_card_positive_img", ['name' => '身份证人像面', 'formtype' => "image", 'value' => $findAuth['id_card_positive_img'], 'disabled' => 'disabled'])
                ->field("id_card_back_img", ['name' => '身份证国徽面', 'formtype' => "image", 'value' => $findAuth['id_card_back_img'], 'disabled' => 'disabled'])
                ->field("id_card_hand_img", ['name' => '手持身份证', 'formtype' => "image", 'value' => $findAuth['id_card_hand_img'], 'disabled' => 'disabled']);
        } else {
            $res
                ->field("company_name", ['name' => '企业名称', 'formtype' => "text", 'value' => $findAuth['company_name'], 'disabled' => 'disabled'])
                ->field("unified_social_credit_code", ['name' => '统一社会信用代码', 'formtype' => "text", 'value' => $findAuth['unified_social_credit_code'], 'disabled' => 'disabled'])
                ->field("legal_person", ['name' => '法人名称', 'formtype' => "text", 'value' => $findAuth['legal_person'], 'disabled' => 'disabled'])
                ->field("legal_id_card", ['name' => '法人身份证号码', 'formtype' => "text", 'value' => $findAuth['legal_id_card'], 'disabled' => 'disabled'])
                ->field("business_license_img", ['name' => '身份证人像面', 'formtype' => "image", 'value' => $findAuth['business_license_img'], 'disabled' => 'disabled']);
        }


        return $res
            ->field("id", ['value' => $findAuth['id'], 'formtype' => "hidden"])
            ->field("status", ['name' => '状态', 'formtype' => "radio", 'datas' => [
                ['value' => 1, 'name' => '审核通过'],
                ['value' => 2, 'name' => '审核不通过'],
            ]])
            ->field("remark", ['name' => '备注', 'formtype' => "textarea"])
            ->pageTitle("账号管理")
            ->formTitle("认证审核")
            ->formAction(moduleAdminJump($this->moduleName, 'user/userAuthAudit'))
            ->actionType('ajax')
            ->formView($pageData);
    }
}
