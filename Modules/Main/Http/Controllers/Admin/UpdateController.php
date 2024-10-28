<?php

namespace Modules\Main\Http\Controllers\Admin;

use Modules\ModulesController;

class UpdateController extends ModulesController {

    public function updateCmsVersion(){
        $all = $this->request->all();
        $UPDATECMS = new \Modules\Main\Libs\UPDATECMS();
        if ($all["identification"]=="cms"){
            $res = $UPDATECMS->cmsAction($all);
            return $res;
        }else{
            $res = $UPDATECMS->appAction($all);
            return $res;
        }

        return [];
    }

}
