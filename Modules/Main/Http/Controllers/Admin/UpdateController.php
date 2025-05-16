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

    public function checklimit(){
        $all = $this->request->all();
        $data  = session()->get($all["cloudtype"]."_".$all["identification"]);
        if($data){
            return [
                'status'=>0,
                'msg' => $data
            ];
        }
        return [
            'status'=>200,
            'msg' => ""
        ];

    }

}
