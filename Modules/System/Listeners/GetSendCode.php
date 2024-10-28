<?php

namespace Modules\System\Listeners;

use Modules\Main\Models\Member;
use Modules\Main\Services\ServiceModel;
use Modules\System\Http\Controllers\Common\SessionKey;

class GetSendCode {

    public function handle(\Modules\System\Events\GetSendCode $event) {
        //事件逻辑 ...
        $all = $event->data;
        $moduleName = ucfirst($all['moduleName']);
        //参数
        switch ($all['object_type']) {
            case 'phone':
                return hook('GetSendPhone', $all)[0];
                break;
            case 'email':
                return hook('GetSendEmail', $all)[0];
                break;
            case 'captcha':
                $all['moduleName'] = __E("captcha_driver") ?: 'System';
                if ($all['moduleName'] != 'System') $all['cloudType'] = 'plugin';
                return hook('GetSendCaptcha', $all)[0];
                break;
            default:
                return returnArr(0, '发送类型错误');
        }
    }

}
