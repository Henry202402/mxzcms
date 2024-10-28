<?php

namespace Modules\System\Listeners;


use Illuminate\Support\Facades\Cache;
use Modules\Main\Models\Member;
use Modules\Main\Services\ServiceModel;

class GetSendEmail {
    //保存时间
    public function saveTime() {
        return 600;
    }

    //检测邮箱
    public function checkEmail($email) {
        return @checkdnsrr(array_pop(explode("@", $email)), "MX");
    }

    public function handle(\Modules\System\Events\GetSendEmail $event) {
        //事件逻辑 ...
        $all = $event->data;
        $moduleName = ucfirst($all['moduleName']);
        //参数
        if (!$all['email']) return returnArr(0, '邮箱不能为空');

        switch ($all['operate_type']) {
            //发送验证码
            case 'send':
                if (!$all['key']) return returnArr(0, 'key不能为空');
                $ip = str_replace('.', '', str_replace(':', '', getIP()));
                if ($ip == '0000') $ip = rand(10000, 99999) . rand(10000, 99999);
                $key = md5($ip . $all['code_type'] . $all['uid'] . $all['email'] . getDay(2) . $all['key']);
                //验证邮箱
                if (!$this->checkEmail($all['email'])) return returnArr(0, '邮箱格式错误');

                $code = rand(100000, 999999);
                Cache::put($key, ['email' => $all['email'], 'code' => $code], $this->saveTime());

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
                    case 1000;
                        $code_msg = '测试';
                        break;
                    default:
                        return returnArr(0, '验证码类型错误');
                        break;
                }

                //检测邮箱是否存在
                if (in_array($all['code_type'], [1, 3, 5])) {
                    if (!ServiceModel::apiGetOne(Member::TABLE_NAME, ['email' => $all['email']])) return returnArr(0, '邮箱不存在');
                } elseif (in_array($all['code_type'], [2, 4])) {
                    //检测邮箱是否不存在
                    if (ServiceModel::apiGetOne(Member::TABLE_NAME, ['email' => $all['email']])) return returnArr(0, '邮箱已存在');
                }

                $title = $code_msg . '验证码';//标题
                try {
                    //发送邮件
                    try {
                        $htmlContent = view('system::admin.public.email', ['code_msg' => $code_msg, 'code' => $code])->render();
                        $res = \Illuminate\Support\Facades\Mail::html($htmlContent, function ($m) use ($all, $title) {
                            $m->to($all['email'])->subject($title);
                        });
                    } catch (\Exception $exception) {
                        return returnArr(0, $exception->getMessage());
                    }
                    return ["msg" => '发送成功', "status" => 200, 'data' => ['email_key' => $key]];

                } catch (\Exception $exception) {
                    return returnArr(0, $exception->getMessage());
                }

                break;

            //获取发送验证码
            case 'get':
                if (!$all['email_key']) return returnArr(0, 'key不能为空');
                $codeArr = Cache::get($all['email_key']);
                return returnArr(200, 'ok', ['email_key' => $all['email_key'], 'email' => $codeArr['email'], 'code' => $codeArr['code']]);
                break;

            //获取发送验证码
            case 'verify':
                if (!$all['email_key']) return returnArr(0, 'email_key不能为空');
                if (!$all['email_captcha']) return returnArr(0, '邮箱验证码不能为空');
                //获取发送验证码
                $codeArr = Cache::get($all['email_key']);
                $code = $codeArr['code'];
                if (!$code) return returnArr(0, '邮箱验证码已过期');
                if ($codeArr['email'] != $all['email']) return returnArr(0, '邮箱验证失败');
                //验证发送验证码
                if ($code == $all['email_captcha']) {
                    return returnArr(200, '邮箱验证码验证成功');
                } else {
                    return returnArr(0, '邮箱验证码验证失败');
                }
                break;
            default:
                return returnArr(0, '发送类型错误');
        }
    }

}
