<?php
$module_prefix = 'admin/member/';
return [
    [
        'icon' => 'icon-users',
        'title' => '账号管理',
        "controller" => "User",
        "action" => "#",//顶级菜单必须为#，否则无法展开
        'url' => '#', //顶级菜单必须为#，否则无法展开
        'submenu' => [
            [
                'icon' => '',
                'title' => '账号列表',
                "controller" => "User",
                "action" => "user/userList",
                'url' => $module_prefix . 'user/userList',
            ],
            [
                'icon' => '',
                'title' => '认证列表',
                "controller" => "User",
                "action" => "user/userAuthList",
                'url' => $module_prefix . 'user/userAuthList',
            ]
        ]
    ],
    [
        'icon' => 'icon-bars-alt',
        'title' => '等级管理',
        "controller" => "Level",
        "action" => "#",//顶级菜单必须为#，否则无法展开
        'url' => '#', //顶级菜单必须为#，否则无法展开
        'submenu' => [
            [
                'icon' => '',
                'title' => 'VIP列表',
                "controller" => "Level",
                "action" => "level/vipList",
                'url' => $module_prefix . 'level/vipList',
            ],
            /*[
                'icon' => '',
                'title' => '等级列表',
                "controller" => "Level",
                "action" => "level/levelList",
                'url' => $module_prefix . 'level/levelList',
            ]*/
        ]
    ],
    [
        'icon' => 'icon-coins',
        'title' => '对账中心',
        "controller" => "finance",
        "action" => "#",//顶级菜单必须为#，否则无法展开
        'url' => '#', //顶级菜单必须为#，否则无法展开
        'submenu' => [
            [
                'icon' => '',
                'title' => '钱包列表',
                "controller" => "Finance",
                "action" => "finance/walletList",
                'url' => $module_prefix . 'finance/walletList',
            ],
            [
                'icon' => '',
                'title' => '流水记录',
                "controller" => "Finance",
                "action" => "finance/flowRecord",
                'url' => $module_prefix . 'finance/flowRecord',
            ],
        ]
    ],
    [
        'icon' => 'icon-gear',
        'title' => '系统管理',
        "controller" => "Setting",
        "action" => "#",//顶级菜单必须为#，否则无法展开
        'url' => '#', //顶级菜单必须为#，否则无法展开
        'submenu' => [
            [
                'icon' => '',
                'title' => '基本设置',
                "controller" => "Setting",
                "action" => "setting/baseConfig",
                'url' => $module_prefix . 'setting/baseConfig',
            ],
            [
                'icon' => '',
                'title' => '站内信',
                "controller" => "Setting",
                "action" => "setting/messageList",
                'url' => $module_prefix . 'setting/messageList',
            ],
        ]
    ],
];
