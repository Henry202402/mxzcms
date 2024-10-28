<?php

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
                "action" => "userList",//顶级菜单必须为#，否则无法展开
                'url' => 'admin/system/user/userList', //顶级菜单必须为#，否则无法展开
            ]
        ]
    ],
    [
        'icon' => 'icon-wrench',
        'title' => '安全与工具',
        "controller" => "Secure",
        "action" => "#",//顶级菜单必须为#，否则无法展开
        'url' => '#', //顶级菜单必须为#，否则无法展开
        'submenu' => [
            [
                'icon' => '',
                'title' => '安全设置',
                "controller" => "Secure",
                "action" => "secureConfig",//顶级菜单必须为#，否则无法展开
                'url' => 'admin/system/secure/secureConfig', //顶级菜单必须为#，否则无法展开
            ],
            [
                'icon' => '',
                'title' => '上传设置',
                "controller" => "Secure",
                "action" => "uploadsConfig",//顶级菜单必须为#，否则无法展开
                'url' => 'admin/system/secure/uploadsConfig', //顶级菜单必须为#，否则无法展开
            ],
            [
                'icon' => '',
                'title' => '缓存配置',
                "controller" => "Secure",
                "action" => "cacheConfig",//顶级菜单必须为#，否则无法展开
                'url' => 'admin/system/secure/cacheConfig', //顶级菜单必须为#，否则无法展开
            ],
            [
                'icon' => '',
                'title' => '定时任务',
                "controller" => "Secure",
                "action" => "scheduledTasksList",//顶级菜单必须为#，否则无法展开
                'url' => 'admin/system/secure/scheduledTasksList', //顶级菜单必须为#，否则无法展开
            ],
        ]
    ],
    [
        'icon' => 'icon-gear',
        'title' => '系统设置',
        "controller" => "Setting",
        "action" => "#",//顶级菜单必须为#，否则无法展开
        'url' => '#', //顶级菜单必须为#，否则无法展开
        'submenu' => [
            [
                'icon' => '',
                'title' => '基本配置',
                "controller" => "Setting",
                "action" => "baseConfig",//顶级菜单必须为#，否则无法展开
                'url' => 'admin/system/base/baseConfig', //顶级菜单必须为#，否则无法展开
            ],
            [
                'icon' => '',
                'title' => 'SEO配置',
                "controller" => "Seo",
                "action" => "seoConfig",//顶级菜单必须为#，否则无法展开
                'url' => 'admin/system/seo/config', //顶级菜单必须为#，否则无法展开
            ],

            [
                'icon' => '',
                'title' => '模块绑定域名',
                "controller" => "Setting",
                "action" => "moduleBindDomain",//顶级菜单必须为#，否则无法展开
                'url' => 'admin/system/setting/moduleBindDomain', //顶级菜单必须为#，否则无法展开
            ],
        ]
    ]

];
