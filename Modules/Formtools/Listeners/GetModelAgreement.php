<?php

namespace Modules\Formtools\Listeners;

use Modules\Formtools\Services\ServiceModel;

class GetModelAgreement {

    public function handle(\Modules\Formtools\Events\GetModelAgreement $event) {
        //事件逻辑 ...
        $data = $event->data;
        $list = ServiceModel::getEnableAgreementList();
        return ['identification' => 'Formtools', 'agreementList' => $list];
    }

}
