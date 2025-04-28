<?php

namespace Modules\System\Listeners;

use Illuminate\Support\Facades\DB;

class GetSiteMapUrl
{

    public function handle( \App\Events\GetSiteMapUrl $event) {
        //事件逻辑 ...
        $pageData = $event->data['pageData'];//获取事件数据

        $urls = [
            "/",
            "index",
            "about",
            "contacts",
            "login",
            "register",
        ];
        $models = DB::table('module_formtools_models')->get('access_identification');
        foreach ($models as $model) {
            array_push($urls, 'list/'.$model->access_identification);
        }
        foreach ($urls as $k=>$url) {
            $urls[$k] = url($url);
        }
        return $urls;

    }

}
