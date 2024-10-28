<?php

return [
    [
        'icon' => 'icon-list-unordered',
        'title' => '权限控制',
        "controller" => "Group",
        "action" => "#",//顶级菜单必须为#，否则无法展开
        'url' => '#', //顶级菜单必须为#，否则无法展开
        'submenu' => [
            [
                'icon' => '',
                'title' => '权限组列表',
                "controller" => "Group",
                "action" => "list",
                'url' => 'admin/auth/group/list',
            ],
        ]
    ]
];
