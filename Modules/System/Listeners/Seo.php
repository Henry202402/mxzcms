<?php

namespace Modules\System\Listeners;

use Modules\System\Http\Controllers\Admin\SeoController;

class Seo
{

    public function handle( \App\Events\Seo $event) {
        //事件逻辑 ...
        $pageData = $event->data;

        $data = getURIByRoute(request());

        if ($data["moduleName"] != "Main"){
            $seoconfig = hook('GetSeo', array_merge($pageData?:[],$data))[0];
        }else{
            $seoconfig = call_user_func([new SeoController(),"GetSeo"],array_merge($pageData?:[],$data));
        }

        return '<title>'.$seoconfig['title'].'  -- Powered By 梦小记CMS</title>
    <meta name="description" content="'.$seoconfig['description'].'">
    <meta name="keywords" content="'.$seoconfig['keywords'].'">';

    }

}
