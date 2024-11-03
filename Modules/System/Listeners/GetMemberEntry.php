<?php

namespace Modules\System\Listeners;

class GetMemberEntry
{

    public function handle( \App\Events\GetMemberEntry $event) {
        //事件逻辑 ...
        $pageData = $event->data['pageData'];//获取事件数据
//        $config = include MODULE_PATH .  'System/Config/config.php';
//        $res = [
//            'icontype'=>"imgage",//imgage 图片 font-imgage 字体图标 fa fa-gears
//            "icon"=>"",
//            "name"=>$config['name'],
//            "url"=>url("admin/system/user/userList")
//        ];
//        return $res;


    }

}
