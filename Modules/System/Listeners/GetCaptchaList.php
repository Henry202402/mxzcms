<?php

namespace Modules\System\Listeners;

class GetCaptchaList {

    public function handle(\App\Events\GetCaptchaList $event) {
        return ['identification' => 'System', 'name' => '内置验证码'];
    }

}
