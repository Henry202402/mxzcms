<?php

namespace Modules\System\Listeners;

class SearchMenuFromModule {

    public function handle(\Modules\System\Events\SearchMenuFromModule $event) {
        $data = $event->data;
        $list = [
            ['name' => '名称', 'url' => 'test/test'],
        ];
        return ['identification' => 'System', 'menuList' => $list];
    }
}
