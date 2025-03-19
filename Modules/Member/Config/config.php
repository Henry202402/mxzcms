<?php  return array (
                "name" => "账号管理",
                "author" => "梦小记",
                "version" => "1.0.0",
                "description" => "账号管理（管理员，代理，普通用户，子账号），不同系统间会员数据自动同步设置，订单中心流水账明细和统计，实名管理，VIP(会员等级)，货币管理（钱包，积分，余额，币），提现管理",
                "identification" => "Member",
                "type" => "system",
                "domain" => "n",
                "auth" => "y",
                "addmodel" => "y",
                "links" => [public_path("views/modules/member") => module_path("Member", "Resources/views")],
            );