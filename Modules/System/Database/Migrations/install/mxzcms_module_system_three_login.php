<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (Schema::hasTable("module_system_three_login")) Schema::drop('module_system_three_login');

        DB::statement("CREATE TABLE `".env("DB_PREFIX")."module_system_three_login` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户uid',
  `wx_unionid` varchar(50) DEFAULT '' COMMENT '微信用户第三方唯一标识',
  `wx_app_openid` varchar(50) DEFAULT '' COMMENT '微信公众号标识openid; three_tig = wx_app',
  `wx_public_openid` varchar(50) DEFAULT '' COMMENT '微信公众号openid, three_tig = wx_public',
  `wx_small_openid` varchar(50) DEFAULT '' COMMENT '微信小程序openid, three_tig = wx_small',
  `apple_openid` varchar(50) DEFAULT '' COMMENT '苹果APP标识openid; three_tig = apple',
  `qq_app_openid` varchar(50) DEFAULT '' COMMENT 'QQAPP标识openid; three_tig = qq_app',
  `qq_public_openid` varchar(50) DEFAULT '' COMMENT 'QQ网页标识openid; three_tig = qq_public',
  `qq_small_openid` varchar(50) DEFAULT '' COMMENT 'QQ小程序标识openid; three_tig = qq_small',
  `wb_app_openid` varchar(50) DEFAULT '' COMMENT '微博APPopenid; three_tig = wb_app',
  `wb_public_openid` varchar(50) DEFAULT '' COMMENT '微博网页openid; three_tig = wb_public',
  `bd_app_openid` varchar(50) DEFAULT '' COMMENT '百度APPopenid; three_tig = bd_app',
  `bd_public_openid` varchar(50) DEFAULT '' COMMENT '百度公众号openid; three_tig = bd_public',
  `bd_small_openid` varchar(50) DEFAULT '' COMMENT '百度小程序openid; three_tig = bd_small',
  `create_at` datetime DEFAULT NULL COMMENT '创建时间',
  `update_at` varchar(12) DEFAULT '' COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uid` (`uid`),
  KEY `wx_unionid` (`wx_unionid`),
  KEY `wx_small_openid` (`wx_small_openid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=".env("DB_CHARSET")." COMMENT='用户第三方登录表'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('module_system_three_login');
    }
};
