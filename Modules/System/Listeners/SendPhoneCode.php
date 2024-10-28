<?php

namespace Modules\System\Listeners;


class SendPhoneCode {

    public function handle(\Modules\System\Events\SendPhoneCode $event) {
        //事件逻辑 ...
        $all = $event->data;
        $moduleName = ucfirst($all['moduleName']);
        //参数
        if (!$all['phone']) return returnArr(0, '手机号不能为空');

        //获取当前手机供应商


        //使用供应商发送消息

    }

}
