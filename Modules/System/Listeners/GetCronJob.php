<?php

namespace Modules\System\Listeners;

class GetCronJob {

    public function handle(\App\Events\GetCronJob $event) {
        $data = \Modules\System\Services\ServiceModel::scheduledTasksList();
        return ['System', $data];
    }

}
