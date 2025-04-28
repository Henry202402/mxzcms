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

        if (Schema::hasTable("home_menu")) Schema::drop('home_menu');

        DB::statement("CREATE TABLE `".env("DB_PREFIX")."home_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `module` varchar(100) DEFAULT 'Main' COMMENT '模块',
  `position` varchar(100) DEFAULT '' COMMENT '位置【top=顶部，bottom=底部】',
  `pid` int(11) DEFAULT '0' COMMENT '上级id',
  `name` varchar(20) DEFAULT '' COMMENT '菜单名称',
  `url` varchar(255) DEFAULT '#' COMMENT '跳转路径',
  `icon` varchar(100) DEFAULT '' COMMENT '图标',
  `icon_character` varchar(20) DEFAULT '' COMMENT 'icon文字',
  `cover` varchar(255) DEFAULT '' COMMENT '封面',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态【1=启用，2=禁用】',
  `sort` tinyint(4) DEFAULT '0' COMMENT '排序',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `position` (`position`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=".env("DB_CHARSET")."");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('home_menu');
    }
};
