<?php

namespace Modules\System\Listeners;


use Illuminate\Support\Facades\Cache;
use Modules\Main\Models\Member;
use Modules\Main\Services\ServiceModel;
use Modules\System\Models\VerifyLog;

class GetSendPhone {
    //保存时间
    public function saveTime() {
        return 600;
    }

    //检测手机号
    public function checkPhone($phone) {
        return preg_match('/^(1([3456789][0-9]))\d{8}$/', $phone) ? true : false;
    }

    public function handle(\Modules\System\Events\GetSendPhone $event) {
        //事件逻辑 ...
        $event = $event->data;
        $moduleName = ucfirst($event['moduleName']);
        $all = $event['data'];
        //参数
        if (!$all['operate_type']) return returnArr(0, '发送类型不能为空');
        if (!$all['phone']) return returnArr(0, '手机号不能为空');
        if (!__E("sms_driver")) return returnArr(0, '请先安装短信插件');
        switch ($all['operate_type']) {
            //发送验证码
            case 'send':
                if ($if = ifCondition([
                    'module' => '模块不能为空',
                    'phone' => '手机号不能为空',
                    'code_type' => '短信类型不为空',
                ], $all)) return $if;
                /*if (!$all['key']) return returnArr(0, 'key不能为空');
                $ip = str_replace('.', '', str_replace(':', '', getIP()));
                if ($ip == '0000') $ip = rand(10000, 99999) . rand(10000, 99999);
                $key = md5($ip . $all['code_type'] . $all['uid'] . $all['phone'] . getDay(2) . $all['key']);*/
                //验证手机号
                if (!$this->checkPhone($all['phone'])) return returnArr(0, '手机号格式错误');

                $code = rand(100000, 999999);
                //Cache::put($key, ['phone' => $all['phone'], 'code' => $code], $this->saveTime());

                switch ($all['code_type']) {
                    case 1;
                        $code_msg = '登录';
                        break;
                    case 2;
                        $code_msg = '注册';
                        break;
                    case 3;
                        $code_msg = '忘记密码';
                        break;
                    case 4;
                        $code_msg = '绑定';
                        break;
                    case 5;
                        $code_msg = '解绑';
                        break;
                    case 6;
                        $code_msg = '认证';
                        break;
                    case 1000;
                        $code_msg = '测试';
                        break;
                    default:
                        return returnArr(0, '验证码类型错误');
                        break;
                }

                //检测手机号是否存在
                if (in_array($all['code_type'], [1, 3, 5, 6])) {
                    if (!ServiceModel::apiGetOne(Member::TABLE_NAME, ['phone' => $all['phone']])) return returnArr(0, '手机号不存在');
                } elseif (in_array($all['code_type'], [2, 4])) {
                    //检测手机号是否不存在
                    if (ServiceModel::apiGetOne(Member::TABLE_NAME, ['phone' => $all['phone']])) return returnArr(0, '手机号已存在');
                }

                $content = "您正在进行{$code_msg}操作，您的验证码是：{$code}";
                $title = $code_msg . '验证码';//标题

                //入库
                $addSMS = ServiceModel::add(VerifyLog::TABLE_NAME, [
                    'module' => $all['module'],
                    'uid' => intval($all['uid']),
                    'verify_type' => 1,
                    'verify_code' => $code,
                    'verify_receive' => $all['phone'],
                    'verify_title' => $title,
                    'verify_content' => $content,
                    'enddate_at' => date('Y-m-d H:i:s', time() + VerifyLog::CODE_EXPIRE_TIME),
                    'sms_type' => $all['code_type'],
                ]);
                if (!$addSMS) return returnArr(0, '入库失败，请重试');

                //return ["msg" => '发送成功', "status" => 200];
                try {
                    //发送手机验证码
                    try {
                        //发送手机验证码钩子
                        $arr['cloudType'] = 'plugin';
                        $arr['moduleName'] = __E("sms_driver");
                        $arr['params'] = [
                            'phone' => $all['phone'],
                            'content' => $content,
                            'title' => $title,
                            'code_type' => $all['code_type'],
                            'msg' => $code_msg,
                            'code' => $code,
                        ];
                        $res = hook('SendSMS', $arr)[0];
                        if ($res['status'] != 200) return returnArr(0, $res['msg'] ?: '发送失败');
                    } catch (\Exception $exception) {
                        return returnArr(0, $exception->getMessage());
                    }
                    return ["msg" => '发送成功', "status" => 200];

                } catch (\Exception $exception) {
                    return returnArr(0, $exception->getMessage());
                }

                break;

            //获取发送验证码
            case 'get':
                if ($if = ifCondition([
                    'module' => '模块不能为空',
                    'phone' => '手机号不能为空',
                    'code_type' => '短信类型不为空',
                ], $all)) return $if;
                $getSMS = VerifyLog::query()->where([
                    'module' => $all['module'],
                    'uid' => intval($all['uid']),
                    'verify_type' => 1,
                    'is_active' => 0,
                    'verify_receive' => $all['phone'],
                    'sms_type' => $all['code_type'],
                ])->latest()->first();
                if (!$getSMS || $getSMS['enddate_at'] <= getDay()) return returnArr(0, '验证码已过期');
                return returnArr(200, 'ok', ['phone' => $getSMS['verify_receive'], 'code' => $getSMS['verify_code']]);
                break;

            //获取发送验证码
            case 'verify':
                if ($if = ifCondition([
                    'module' => '模块不能为空',
                    'phone' => '手机号不能为空',
                    'code' => '验证码不能为空',
                    'code_type' => '短信类型不为空',
                ], $all)) return $if;
                $getSMS = VerifyLog::query()->where([
                    'module' => $all['module'],
                    'uid' => intval($all['uid']),
                    'verify_type' => 1,
                    'is_active' => 0,
                    'verify_receive' => $all['phone'],
                    'sms_type' => $all['code_type'],
                ])->latest()->first();
                if (!$getSMS || $getSMS['enddate_at'] <= getDay()) return returnArr(0, $all['phone'] . ' 验证码已过期');
                //验证发送验证码
                if ($getSMS['verify_code'] == $all['code']) {
                    VerifyLog::updateSMS($getSMS);
                    return returnArr(200, $all['phone'] . ' 验证码验证成功');
                } else {
                    return returnArr(0, $all['phone'] . ' 验证码验证失败');
                }
                break;
            default:
                return returnArr(0, '发送类型错误');
        }
    }

}
