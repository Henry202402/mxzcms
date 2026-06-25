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

        $title = e((string) ($seoconfig['title'] ?? ''));
        $keywords = e((string) ($seoconfig['keywords'] ?? ''));
        $description = e((string) ($seoconfig['description'] ?? ''));

        return '<title>'.$title.'</title>
    <meta name="keywords" content="'.$keywords.'">
    <meta name="description" content="'.$description.'">';

    }
}

