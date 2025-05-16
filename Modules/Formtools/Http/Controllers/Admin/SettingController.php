<?php

namespace Modules\Formtools\Http\Controllers\Admin;

use Modules\Main\Services\ServiceModel;
use Modules\ModulesController;

class SettingController extends ModulesController {

    public function setting() {

        if($this->request->isMethod('post')){
            $all = $this->request->all();
            if(!$all['api_key']){
                $all['api_key'] = md5(time());
            }
            ServiceModel::SettingInsertOrUpdate('Formtools','setting','api_key',$all['api_key']);
            //更新缓存
            cacheGlobalSettings(2);
            return redirect(url('admin/formtools/setting'))->with('pageDataMsg', '保存成功')->with("pageDataStatus",200);
        };

        $pageData = getURIByRoute($this->request);
        $pageData['title'] = "模块设置";
        $pageData['subtitle'] = "基本设置";


        $formtool = FormTool::create();

        $formtool->field("api_key",'API密钥',cacheGlobalSettingsByKey('api_key',"Formtools"))->notes('api_key',"第三方调用key，不填写默认随机");



        $formtool->csrf_field();

        $formtool->formaction(url('admin/formtools/setting'));
        $formtool->actionName("保存");

        return $formtool->formView($pageData);
    }

}
