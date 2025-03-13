<?php

namespace Modules\Member\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Main\Services\ServiceModel;
use Modules\Member\Models\AuthRecord;

class AuthController extends JWTController {
    public $user;

    public function __construct(Request $request) {
        parent::__construct($request);
        $this->user = $this->current_user_or();
    }

    //提交
    public function userAuthAdd(Request $request) {
        $all = $request->all();
        $uid = intval($this->user['uid']);
        if ($uid <= 0) return returnArr(0, '用户错误');

        $findAuth = ServiceModel::apiGetOne(AuthRecord::TABLE_NAME, ['uid' => $uid, 'status' => 0]);
        if ($findAuth) return returnArr(0, '认证记录已提交');

        switch ($all['type']) {
            case 1:
                if ($findAuth['status'] == 1) return returnArr(0, '认证记录已审核');
                if ($if = ifCondition([
                    'real_name' => '真实姓名不能为空',
                    'id_card' => '身份证号不能为空',
                ])) return $if;

                if ($_FILES['id_card_positive_img']['size'] <= 0) return returnArr(0, '身份证人像面不能为空');
                if ($_FILES['id_card_back_img']['size'] <= 0) return returnArr(0, '身份证国徽面不能为空');

                $add = [
                    'uid' => $uid,
                    'real_name' => trim($all['real_name']),
                    'id_card' => trim($all['id_card']),
                    'type' => intval($all['type']),
                ];

                try {
                    $add['id_card_positive_img'] = UploadFile($this->request, "id_card_positive_img", "auth/" . date("Y/m/d/") . uniqid(), ALLOWEXT, __E("upload_driver"));
//                (new \Modules\System\Http\Controllers\Common\CommonController($this->request))->resizeImg($add['id_card_back_img'], 1024, 300, 300);
                } catch (\Exception $exception) {
                    return returnArr(0, $exception->getMessage());
                }

                try {
                    $add['id_card_back_img'] = UploadFile($this->request, "id_card_back_img", "auth/" . date("Y/m/d/") . uniqid(), ALLOWEXT, __E("upload_driver"));
//                (new \Modules\System\Http\Controllers\Common\CommonController($this->request))->resizeImg($add['id_card_back_img'], 1024, 300, 300);
                } catch (\Exception $exception) {
                    return returnArr(0, $exception->getMessage());
                }

                if ($_FILES['id_card_hand_img']['size'] > 0) {
                    try {
                        $add['id_card_hand_img'] = UploadFile($this->request, "id_card_hand_img", "auth/" . date("Y/m/d/") . uniqid(), ALLOWEXT, __E("upload_driver"));
//                (new \Modules\System\Http\Controllers\Common\CommonController($this->request))->resizeImg($add['id_card_back_img'], 1024, 300, 300);
                    } catch (\Exception $exception) {
                        return returnArr(0, $exception->getMessage());
                    }
                }

                break;
            case 2:
                if ($findAuth['type'] == 2 && $findAuth['status'] == 1) return returnArr(0, '认证记录已审核');
                if ($if = ifCondition([
                    'company_name' => '企业名称不能为空',
                    'unified_social_credit_code' => '统一社会信用代码不能为空',
                    'legal_person' => '法人名称不能为空',
                    'legal_id_card' => '法人名称身份证号不能为空',
                ])) return $if;

                if ($_FILES['business_license_img']['size'] <= 0) return returnArr(0, '营业执照照片不能为空');
                $add = [
                    'uid' => $uid,
                    'company_name' => trim($all['company_name']),
                    'unified_social_credit_code' => trim($all['unified_social_credit_code']),
                    'legal_person' => trim($all['legal_person']),
                    'legal_id_card' => trim($all['legal_id_card']),
                    'type' => intval($all['type']),
                ];

                try {
                    $add['business_license_img'] = UploadFile($this->request, "business_license_img", "auth/" . date("Y/m/d/") . uniqid(), ALLOWEXT, __E("upload_driver"));
//                (new \Modules\System\Http\Controllers\Common\CommonController($this->request))->resizeImg($add['id_card_back_img'], 1024, 300, 300);
                } catch (\Exception $exception) {
                    return returnArr(0, $exception->getMessage());
                }
                break;
            default:
                return returnArr(0, '类型错误');
        }

        $res = ServiceModel::add(AuthRecord::TABLE_NAME, $add);
        if ($res) {
            return returnArr(200, '提交成功');
        } else {
            return returnArr(0, '提交失败');
        }
    }

    //编辑
    public function userAuthEdit(Request $request) {
        $all = $request->all();
        $uid = intval($this->user['uid']);
        if ($uid <= 0) return returnArr(0, '用户错误');

        $findAuth = ServiceModel::apiGetOne(AuthRecord::TABLE_NAME, ['uid' => $all['uid'], 'id' => $all['id']]);
        if (!$findAuth) return returnArr(0, '记录不存在');
        if ($findAuth['status'] != 2) return returnArr(0, '记录状态错误');

        switch ($all['type']) {
            case 1:
                if ($if = ifCondition([
                    'real_name' => '真实姓名不能为空',
                    'id_card' => '身份证号不能为空',
                ], $all)) return $if;

                $add = [
                    'uid' => $uid,
                    'real_name' => trim($all['real_name']),
                    'id_card' => trim($all['id_card']),
                    'type' => intval($all['type']),
                ];
                if ($_FILES['id_card_positive_img']['size'] > 0) {
                    try {
                        $add['id_card_positive_img'] = UploadFile($this->request, "id_card_positive_img", "auth/" . date("Y/m/d/") . uniqid(), ALLOWEXT, __E("upload_driver"));
//                (new \Modules\System\Http\Controllers\Common\CommonController($this->request))->resizeImg($add['id_card_back_img'], 1024, 300, 300);
                    } catch (\Exception $exception) {
                        return returnArr(0, $exception->getMessage());
                    }
                }

                if ($_FILES['id_card_back_img']['size'] > 0) {
                    try {
                        $add['id_card_back_img'] = UploadFile($this->request, "id_card_back_img", "auth/" . date("Y/m/d/") . uniqid(), ALLOWEXT, __E("upload_driver"));
//                (new \Modules\System\Http\Controllers\Common\CommonController($this->request))->resizeImg($add['id_card_back_img'], 1024, 300, 300);
                    } catch (\Exception $exception) {
                        return returnArr(0, $exception->getMessage());
                    }
                }

                if ($_FILES['id_card_hand_img']['size'] > 0) {
                    try {
                        $add['id_card_hand_img'] = UploadFile($this->request, "id_card_hand_img", "auth/" . date("Y/m/d/") . uniqid(), ALLOWEXT, __E("upload_driver"));
//                (new \Modules\System\Http\Controllers\Common\CommonController($this->request))->resizeImg($add['id_card_back_img'], 1024, 300, 300);
                    } catch (\Exception $exception) {
                        return returnArr(0, $exception->getMessage());
                    }
                }


                break;
            case 2:
                if ($findAuth['type'] == 2 && $findAuth['status'] == 1) return returnArr(0, '认证记录已审核');
                if ($if = ifCondition([
                    'company_name' => '企业名称不能为空',
                    'unified_social_credit_code' => '统一社会信用代码不能为空',
                    'legal_person' => '法人名称不能为空',
                    'legal_id_card' => '法人名称身份证号不能为空',
                ], $all)) return $if;
                $add = [
                    'uid' => $uid,
                    'company_name' => trim($all['company_name']),
                    'unified_social_credit_code' => trim($all['unified_social_credit_code']),
                    'legal_person' => trim($all['legal_person']),
                    'legal_id_card' => trim($all['legal_id_card']),
                    'type' => intval($all['type']),
                ];

                if ($_FILES['business_license_img']['size'] > 0) {
                    try {
                        $add['business_license_img'] = UploadFile($this->request, "business_license_img", "auth/" . date("Y/m/d/") . uniqid(), ALLOWEXT, __E("upload_driver"));
//                (new \Modules\System\Http\Controllers\Common\CommonController($this->request))->resizeImg($add['id_card_back_img'], 1024, 300, 300);
                    } catch (\Exception $exception) {
                        return returnArr(0, $exception->getMessage());
                    }
                }

                break;
            default:
                return returnArr(0, '类型错误');
        }
        $add['status'] = 0;
        $add['remark'] = '';
        $res = ServiceModel::whereUpdate(AuthRecord::TABLE_NAME, ['id' => $all['id']], $add);
        if ($res) {
            return returnArr(200, '提交成功');
        } else {
            return returnArr(0, '提交失败');
        }
    }

    //获取认证
    public function getUserAuth(Request $request) {
        $all = $request->all();
        $uid = intval($this->user['uid']);
        if ($uid <= 0) return returnArr(0, '用户错误');

        $findAuth = AuthRecord::query()
            ->where('uid', $uid)
            ->latest()
            ->first();
        if (!$findAuth) return returnArr(0, '记录不存在');

        $findAuth['id_card_positive_img_url'] = GetUrlByPath($findAuth['id_card_positive_img']);
        $findAuth['id_card_back_img_url'] = GetUrlByPath($findAuth['id_card_back_img']);
        $findAuth['id_card_hand_img_url'] = GetUrlByPath($findAuth['id_card_hand_img']);
        $findAuth['business_license_img_url'] = GetUrlByPath($findAuth['business_license_img']);

        return returnArr(200, '获取成功', $findAuth);
    }

    //删除
    public function userAuthDelete(Request $request) {
        $id = $request->id;
        $uid = intval($this->user['uid']);
        if ($uid <= 0) return returnArr(0, '用户错误');

        $findAuth = ServiceModel::apiGetOne(AuthRecord::TABLE_NAME, ['uid' => $uid, 'id' => $id]);
        if (!$findAuth) return returnArr(0, '记录不存在');

        $res = AuthRecord::destroy($id);
        if ($res) {
            return returnArr(200, '删除成功');
        } else {
            return returnArr(0, '删除失败');
        }
    }
}
