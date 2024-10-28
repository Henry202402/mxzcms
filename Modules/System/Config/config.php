<?php  return array (
                "name" => "系统设置",
                "author" => "梦小记",
                "version" => "1.0.0",
                "description" => "系统模块，主系统设置，菜单管理和会员管理",
                "identification" => "System",
                "type" => "system",
                "domain" => "n",
                "auth" => "y",
                "addmodel" => "n",
                "links" => [public_path("views/modules/system") => module_path("System", "Resources/views")],
            );