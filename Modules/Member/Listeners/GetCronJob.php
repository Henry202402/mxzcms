<?php

namespace Modules\Member\Listeners;

use Modules\Member\Http\Controllers\Api\TimingController;

class GetCronJob {

    public function handle(\App\Events\GetCronJob $event) {
        $list = [
            [TimingController::class, 'test', '测试定时任务'],
        ];
        return ['Member', $list];
    }

}
