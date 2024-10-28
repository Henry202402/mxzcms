<?php

return [
    [
        'icon' => 'icon-list-unordered',
        'title' => '模型管理',
        "controller" => "Home",
        "action" => "#",//顶级菜单必须为#，否则无法展开
        'url' => '#', //顶级菜单必须为#，否则无法展开
        'submenu' => [
            [
                'icon' => 'icon-book3',
                'title' => '模型列表',
                "controller" => "Home",
                "action" => "index",
                'url' => 'admin/formtools/index',
            ]
        ]
    ],

];
