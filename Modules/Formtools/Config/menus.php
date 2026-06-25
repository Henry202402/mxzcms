<?php

return [
    [
        'icon' => 'icon-list-unordered',
        'title' => '模型工作台',
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
                'match_actions' => ['index', 'modelAdd', 'modelEdit', 'modelDelete', 'fieldList', 'fieldAdd', 'fieldEdit', 'fieldDel', 'fieldMove', 'modelStatistics', 'synmodel', 'seedDemoContent', 'resetModelData', 'getModel'],
            ],
            [
                'icon' => 'icon-files-empty2',
                'title' => '页面列表',
                "controller" => "Page",
                "action" => "index",
                'url' => 'admin/formtools/pageList',
                'match_actions' => ['index', 'pageAdd', 'pageEdit', 'pageDelete', 'pageCopy'],
            ],
            [
                'icon' => 'icon-folder-open2',
                'title' => '页面分类',
                "controller" => "PageCategory",
                "action" => "index",
                'url' => 'admin/formtools/pageCategoryList',
                'match_actions' => ['index', 'add', 'edit', 'delete'],
            ]
        ]
    ],
    [
        'icon' => 'icon-cog2',
        'title' => '模块设置',
        "controller" => "Setting",
        "action" => "#",//顶级菜单必须为#，否则无法展开
        'url' => '#', //顶级菜单必须为#，否则无法展开
        'submenu' => [
            [
                'icon' => 'icon-cog2',
                'title' => '基本设置',
                "controller" => "Setting",
                "action" => "index",
                'url' => 'admin/formtools/setting',
            ]
        ]
    ],

];
