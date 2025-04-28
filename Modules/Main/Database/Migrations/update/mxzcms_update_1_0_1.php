<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
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
        //cms  1.0.1
        if (!Schema::hasTable("system_message")){
            DB::statement("CREATE TABLE `".env("DB_PREFIX")."system_message` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自动增长ID',
  `module` varchar(255) DEFAULT 'Main' COMMENT '模块',
  `title` varchar(255) DEFAULT '' COMMENT '标题',
  `content` text COMMENT '内容',
  `uid` int(11) unsigned DEFAULT '0' COMMENT '发送者用户id',
  `receive_uid` int(11) unsigned DEFAULT '0' COMMENT '接收人UID的',
  `status` tinyint(1) unsigned DEFAULT '0' COMMENT '阅读状态，0=未读，1=已读',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  `json_str` text COMMENT 'json字符串',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE,
  KEY `receive_uid` (`receive_uid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='系统站内信'");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
};
