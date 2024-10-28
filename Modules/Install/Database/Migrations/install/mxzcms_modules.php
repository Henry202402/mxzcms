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

        if (Schema::hasTable("modules")) Schema::drop('modules');

        DB::statement("CREATE TABLE `".env("DB_PREFIX")."modules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `name` varchar(255) DEFAULT '' COMMENT '名称',
  `identification` varchar(255) DEFAULT '' COMMENT '唯一标识',
  `status` tinyint(4) DEFAULT '0' COMMENT '模块状态，1=已启用，0=未启用',
  `is_index` tinyint(4) DEFAULT '0' COMMENT '设为首页【0.否；1.是】',
  `cloud_type` varchar(25) DEFAULT 'module' COMMENT '拓展类型：module=功能模块；plugin=插件',
  `type` varchar(255) DEFAULT '2' COMMENT '模块类型，system=内置模块，function=功能模块',
  `domain` varchar(255) DEFAULT '2' COMMENT '域名绑定，y=有，n=没有',
  `form` varchar(25) DEFAULT '' COMMENT '来源local cloud',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `identification` (`identification`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=".env("DB_CHARSET")." COMMENT='模块表'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('modules');
    }
};
