<?php

namespace Modules\System\Listeners;


use Illuminate\Support\Facades\Validator;

class GetSendCaptcha {

    public function handle(\Modules\System\Events\GetSendCaptcha $event) {
        //事件逻辑 ...
        $all = $event->data;
        $moduleName = ucfirst($all['moduleName']);

        switch ($all['operate_type']) {
            //发送验证码
            case 'send':
                $url = captcha_src ('math');
                return '<img class="captcha cursor-img" src="'.$url.'" alt="captcha" onclick="this.src=\''.$url.'\'+Math.random()"/>';
                break;
            //验证验证码
            case 'verify':
                $validator = Validator::make($all,[
                    'captcha'=>'required|captcha'
                ],[
                    'captcha.required' => '验证码不能为空',
                    'captcha.captcha' => '验证码不正确',
                ]);
                //单个验证规则失败后停止
                if($validator->stopOnFirstFailure()->fails()){
                    //验证不通过,输出第一条错误信息
                    return returnArr(0, $validator->errors()->first());
                }
                //验证通过
                return returnArr(200, '验证码验证成功');
                break;
            default:
                return returnArr(0, '发送类型错误');
        }
    }

}
