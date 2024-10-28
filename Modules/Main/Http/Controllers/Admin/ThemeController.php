<?php

namespace Modules\Main\Http\Controllers\Admin;

use Illuminate\Support\Facades\Cache;
use Modules\Main\Services\ServiceModel;
use Modules\ModulesController;
use Modules\System\Models\Setting;

class ThemeController extends ModulesController {

    public function diy() {

        if ($this->request->isMethod("POST")){

            $all = $this->request->all();
            unset($all['_token'],$all['m']);

            if($_FILES['home_screen_image']['size']>0){
                $all['home_screen_image'] = UploadFile($this->request,"home_screen_image","home/screen/".md5(time().rand(100,999)));
            }

            foreach ($all as $key=>$value){
                ServiceModel::SettingInsertOrUpdate("Main","theme",$key,$value);
            }

            Cache::forget("settings");
            return [
                "status"=>200,
                "msg"=>"更新成功"
            ];
        }

        $pageData = getURIByRoute($this->request);
        $pageData['title'] = "开发者助手，快速创建本地应用";
        $pageData['subtitle'] = "开发者助手，子标题";

        return view("admin.func.diy",[
            "pageData" =>$pageData
        ]);

    }


}
