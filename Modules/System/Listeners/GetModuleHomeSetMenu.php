<?php

namespace Modules\System\Listeners;


class GetModuleHomeSetMenu {

    public function handle(\Modules\System\Events\GetModuleHomeSetMenu $event) {
        //可设置菜单列表
        $list = [
//            ['name' => '协议1', 'url' => 'index/agreement?id=1'],
//            ['name' => '协议2', 'url' => 'index/agreement?id=2'],
        ];
        $modelList = [
//            ['name' => '新闻表', 'table' => 'news'],
//            ['name' => '公告表', 'table' => 'notice'],
        ];
        return ['identification' => 'System', 'menuList' => $list, 'modelList' => $modelList];
    }
}
