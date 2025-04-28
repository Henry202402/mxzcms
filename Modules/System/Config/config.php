<?php  return array (
                "name" => "系统设置",
                "author" => "梦小记",
                "version" => "1.0.2",
                "description" => "系统模块，安全设置，上传设置，缓存配置，定时任务，系统设置，SEO配置和模块域名绑定",
                "identification" => "System",
                "type" => "function",
                "domain" => "n",
                "auth" => "y",
                "addmodel" => "n",
                "links" => [public_path("views/modules/system") => module_path("System", "Resources/views")],
            );
