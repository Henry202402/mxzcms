<?php


namespace Modules\Member\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Member\Http\Controllers\Common\CommonController;
use Modules\Main\Models\Member;
use Modules\Member\Models\ThreeLogin;
use Modules\Member\Models\Wallet;
use Tymon\JWTAuth\Facades\JWTAuth;
use Modules\Main\Services\ServiceModel;

class JWTController extends CommonController {

    public function __construct(Request $request) {
        parent::__construct($request);
    }

    //登录，生成储存token
    public function login(Request $request) {

        if ($if = ifCondition([
            'username' => '账号不能为空',
            'password' => '密码不能为空',
        ])) return $if;
        $user = Member::query()
            ->where(['username' => $request->username])
            ->orWhere(['phone' => $request->username])
            ->select([
                'uid', 'userid', 'avatar', 'phone', 'email',
                'username', 'nickname', 'password', 'status', 'male', 'birthday',
                'signature', 'created_at',
            ])
            ->first();
        if (!$user) return returnArr(0, '账号不存在');
        if ($user['password'] != ServiceModel::getPassword($request->password)) return returnArr(0, '密码不正确');
        if ($user['status'] != 1) return returnArr(0, '账号已禁用');
        unset($user['password']);
        $user['token'] = JWTAuth::fromUser($user);
        return returnArr(200, '登录成功', $user);
    }

    //验证用户，获取用户信息
    public function getUserInfo(Request $request) {
        $user = $this->current_user();
        unset($user['c_code'], $user['password'], $user['pid'], $user['pid_path'], $user['phone_active'], $user['email_active'], $user['updated_at']);
        $user['token'] = $_SERVER['HTTP_AUTHORIZATION'] ?: $_GET['token'];
        $wallet = ServiceModel::apiGetOneArray(Wallet::TABLE_NAME, ['uid' => $user['uid']]);
        $user['vip_time'] = $wallet['vip_time'] ?: null;
        return returnArr(200, '获取成功', $user);
    }

    //刷新token，token过期，重新生成token
    public function refreshToken(Request $request) {
        try {
            $token = JWTAuth::refresh(JWTAuth::getToken());
        } catch (\Exception $exception) {
            return returnArr(0, $exception->getMessage());
        }

        return returnArr(200, '获取成功', ['token' => $token]);
    }

    //注册
    public function register(Request $request) {
        $all = $request->all();
        switch ($request->register_type) {
            case 1:
                if ($if = ifCondition([
                    'phone' => '手机号不能为空',
                    'code' => '验证码不能为空',
                ])) return $if;
                $phone = trim($request->phone);
                $password = ServiceModel::getPassword(trim($request->password));
                if ($all['login_method'] && $all['login_type']) $three_tig = returnArrGetThreePrefix()[$all['login_method']] . '_' . $all['login_type'] . '_openid';
                break;
            case 2:
                if ($if = ifCondition([
                    'openid' => 'openid不能为空',
                    'session_key' => 'session_key不能为空',
                    'iv' => 'iv不能为空',
                    'encryptedData' => 'encryptedData不能为空',
                ])) return $if;

                $dataParam = [
                    'request' => $this->request,
                    'req_type' => 'getPhone',
                    'login_method' => 'WeChat',
                    'login_type' => 'small',
                    'data' => $all,
                ];
                $dataArr = hook("Login", ['moduleName' => __E("login_driver"), 'cloudType' => "plugin", 'data' => $dataParam])[0];
                if ($dataArr['status'] != 200 || !$dataArr['data']) return $dataArr;
                $info = json_decode($dataArr['data'], true);
                $phone = $info['phoneNumber'];
                $password = '';
                $three_tig = returnArrGetThreePrefix()[$dataParam['login_method']] . '_' . $dataParam['login_type'] . '_openid';
                break;
            default:
                return returnArr(0, '类型错误');
        }

        $user = Member::query()
            ->where(['phone' => $phone])
            ->select([
                'uid', 'userid', 'avatar', 'phone', 'email',
                'username', 'nickname', 'password', 'status', 'male', 'birthday',
                'signature', 'created_at',
            ])
            ->first();

        if ($request->register_type == 1) {
            //验证验证码
            $array['moduleName'] = 'System';
            $array['object_type'] = 'phone';
            $array['data'] = [
                'operate_type' => 'verify',
                'module' => $this->moduleName,
                'uid' => $user['uid'],
                'phone' => $all['phone'],
                'code' => $all['code'],
                'code_type' => $user['uid'] ? 1 : 2,
            ];
            $verify = hook('GetSendCode', $array)[0];
            if ($verify['status'] != 200) return $verify;
        }
        $msg = '登录成功';
        if (!$user) {
            if ($request->register_type == 1 && !$request->password) return returnArr(0, '密码不能为空');
            DB::beginTransaction();
            $uid = Member::query()->insertGetId([
                'userid' => ServiceModel::getUserId(),
                'avatar' => $all['avatar'] ?: 'avatar/avatar.jpg',
                'phone' => $phone,
                'username' => $phone,
                'nickname' => $all['nickname'] ?: '微信用户',
                'password' => $password,
                'pid' => 1,
                'status' => 1,
                'pid_path' => 1,
                'phone_active' => 1,
                'created_at' => getDay(),
                'updated_at' => getDay(),
            ]);
            if (!$uid) {
                DB::rollBack();
                return returnArr(0, '注册失败，请重试');
            }

            if ($three_tig && $all['openid']) {
                if (!ServiceModel::apiGetOneArray(ThreeLogin::TABLE_NAME, [$three_tig => $all['openid']])) {
                    $addOpenid = ThreeLogin::add(['uid' => $uid, $three_tig => $all['openid'],]);
                    if (!$addOpenid) {
                        DB::rollBack();
                        return returnArr(0, '保存登录失败，请重试');
                    }
                }
            }
            DB::commit();
            $user = Member::query()
                ->select([
                    'uid', 'userid', 'avatar', 'phone', 'email',
                    'username', 'nickname', 'password', 'status', 'male', 'birthday',
                    'signature', 'created_at',
                ])
                ->find($uid);
            if (!$user) return returnArr(0, '注册失败');
            $msg = '注册成功';
        }
        if ($user['status'] != 1) return returnArr(0, '账号已禁用');
        unset($user['password']);
        $user['token'] = JWTAuth::fromUser($user);
        return returnArr(200, $msg, $user);
    }

    //绑定
    public function idBingAccount(Request $request) {
        $all = $request->all();
        if ($if = ifCondition([
            'phone' => '手机号不能为空',
            'password' => '密码不能为空',
            'openid' => 'openid不能为空',
            'login_method' => 'login_method不能为空',
            'login_type' => 'login_type不能为空',
        ])) return $if;

        $user = Member::query()
            ->where(['phone' => $request->phone])
            ->select([
                'uid', 'userid', 'avatar', 'phone', 'email',
                'username', 'nickname', 'password', 'status', 'male', 'birthday',
                'signature', 'created_at',
            ])
            ->first();
        if (!$user) return returnArr(0, '账号不存在');
        if ($user['password'] != ServiceModel::getPassword($request->password)) return returnArr(0, '密码不正确');
        if ($user['status'] != 1) return returnArr(0, '账号已禁用');

        $three_tig = returnArrGetThreePrefix()[$all['login_method']] . '_' . $all['login_type'] . '_openid';
        try {
            $findThreeLogin = ServiceModel::apiGetOneArray(ThreeLogin::TABLE_NAME, [$three_tig => $all['openid']]);
        } catch (\Exception $exception) {
            return returnArr(0, '标识不存在');
        }
        if (!$findThreeLogin) {
            $addOpenid = ThreeLogin::add(['uid' => $user['uid'], $three_tig => $all['openid'],]);
            if (!$addOpenid) return returnArr(0, '保存登录失败，请重试');
        } else {
            $addOpenid = true;
        }
        if ($addOpenid) {
            unset($user['password']);
            $user['token'] = JWTAuth::fromUser($user);
            return returnArr(200, '绑定成功', $user);
        } else {
            return returnArr(0, '绑定失败');
        }
    }

    //退出登录
    public function logout(Request $request) {
        if ($_SERVER['HTTP_AUTHORIZATION'] || $_GET['token']) JWTAuth::invalidate(JWTAuth::getToken());
        return returnArr(200, '退出成功');
    }

    //获取openid
    public function getOpenid(Request $request) {
        $all = $request->all();
        if ($if = ifCondition([
            'login_method' => 'login_method不能为空',
            'login_type' => 'login_type不能为空',
            'code' => 'code不能为空',
        ], $all)) return $if;

        $token_arr = hook("Login", ['moduleName' => __E("login_driver"), 'cloudType' => "plugin", 'data' => [
            'request' => $this->request,
            'req_type' => 'codeGetInfo',
            'login_method' => $all['login_method'],
            'login_type' => $all['login_type'],
            'data' => ['code' => $all['code']],
        ]])[0];
        if (!$token_arr['openid']) return returnArr(0, $token_arr['errmsg'] ?: '获取openid失败');
        return returnArr(200, '获取成功', $token_arr);
    }

    //获取验证码
    public function getCode(Request $request) {
        $all = $request->all();

        $findPhone = ServiceModel::apiGetOneArray(Member::TABLE_NAME, ['phone' => $all['phone']]);
        if (!$all['code_type']) $all['code_type'] = $all['get_type'] + 1;
        if ($all['get_type'] == 1) $all['code_type'] = $findPhone ? 1 : 2;

        $array['moduleName'] = 'System';
        $array['object_type'] = 'phone';
        $array['data'] = [
            'operate_type' => 'send',
            'module' => $this->moduleName,
            'uid' => $findPhone['uid'],
            'phone' => $all['phone'],
            'code_type' => $all['code_type'],
        ];
        return hook('GetSendCode', $array)[0];
    }

    //忘记密码
    public function forgot(Request $request) {
        if ($if = ifCondition([
            'phone' => '手机号不能为空',
            'password' => '密码不能为空',
            'code' => '验证码不能为空',
        ])) return $if;

        $phone = trim($request->phone);
        $password = ServiceModel::getPassword(trim($request->password));

        $user = Member::query()
            ->where(['phone' => $phone])
            ->first();
        if (!$user) return returnArr(0, '账号不存在');

        //验证验证码
        $array['moduleName'] = 'System';
        $array['object_type'] = 'phone';
        $array['data'] = [
            'operate_type' => 'verify',
            'module' => $this->moduleName,
            'uid' => $user['uid'],
            'phone' => $phone,
            'code' => $request->code,
            'code_type' => 3,
        ];
        $verify = hook('GetSendCode', $array)[0];
        if ($verify['status'] != 200) return $verify;

        $res = ServiceModel::whereUpdate(Member::TABLE_NAME, ['uid' => $user['uid']], [
            'password' => $password
        ]);
        if ($res) {
            return returnArr(200, '修改成功');
        } else {
            return returnArr(0, '修改失败');
        }
    }

    //修改手机号
    public function updatePhone(Request $request) {
        if ($if = ifCondition([
            'old_phone' => '旧手机号不能为空',
            'old_phone_code' => '旧手机号验证码不能为空',
            'new_phone' => '新手机号不能为空',
            'new_phone_code' => '新手机号验证码不能为空',
        ])) return $if;

        $old_phone = trim($request->old_phone);
        $new_phone = trim($request->new_phone);
        if ($old_phone == $new_phone) return returnArr(0, '两个手机号不能相同');

        $user = Member::query()
            ->where(['phone' => $old_phone])
            ->first();
        if (!$user) return returnArr(0, $old_phone . '账号不存在');


        //验证验证码
        $array['moduleName'] = 'System';
        $array['object_type'] = 'phone';
        $array['data'] = [
            'operate_type' => 'verify',
            'module' => $this->moduleName,
            'uid' => $user['uid'],
            'phone' => $old_phone,
            'code' => $request->old_phone_code,
            'code_type' => 5,
        ];
        $verify = hook('GetSendCode', $array)[0];
        if ($verify['status'] != 200) return $verify;

        if (Member::query()->where('phone', $new_phone)->first()) return returnArr(0, $new_phone . '账号已存在');
        //验证验证码
        $array['moduleName'] = 'System';
        $array['object_type'] = 'phone';
        $array['data'] = [
            'operate_type' => 'verify',
            'module' => $this->moduleName,
            'uid' => 0,
            'phone' => $new_phone,
            'code' => $request->new_phone_code,
            'code_type' => 4,
        ];
        $verify = hook('GetSendCode', $array)[0];
        if ($verify['status'] != 200) return $verify;


        $res = ServiceModel::whereUpdate(Member::TABLE_NAME, ['uid' => $user['uid']], [
            'phone' => $new_phone
        ]);
        if ($res) {
            return returnArr(200, '修改成功');
        } else {
            return returnArr(0, '修改失败');
        }
    }
}
