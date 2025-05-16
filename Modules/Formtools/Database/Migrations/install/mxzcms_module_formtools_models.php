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

        if (Schema::hasTable("module_formtools_models")) Schema::drop('module_formtools_models');

        DB::statement("CREATE TABLE `".env("DB_PREFIX")."module_formtools_models` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `name` varchar(255) DEFAULT '' COMMENT '模型名称',
  `identification` varchar(255) DEFAULT '' COMMENT '模型标识，即表名称',
  `access_identification` varchar(255) DEFAULT '' COMMENT '访问标识',
  `menuname` varchar(255) DEFAULT '' COMMENT '一级菜单入口名称',
  `module` varchar(255) DEFAULT '' COMMENT '被追加的模块标识',
  `fields` json DEFAULT NULL COMMENT '字段信息josn',
  `icon` varchar(255) DEFAULT '' COMMENT '菜单图标',
  `type` varchar(255) DEFAULT NULL COMMENT '模型类型，multi多数据  single单条',
  `supermodel` int(11) DEFAULT NULL COMMENT '关联的父模型',
  `remark` varchar(255) DEFAULT '' COMMENT '备注',
  `admin_config` tinytext COMMENT '后台配置json',
  `home_config` tinytext COMMENT '前台配置json',
  `home_seo_config` tinytext COMMENT '前台列表seo配置json',
  `home_seo_detail_config` tinytext COMMENT '前台详情页SEO配置sjon',
  `other_config` tinytext COMMENT '其他配置json',
  `show_home_page` varchar(15) DEFAULT NULL COMMENT '是否显示在前台首页，yes no',
  `home_page_num` int(5) DEFAULT NULL COMMENT '显示在首页的数量',
  `home_page_sort` int(11) DEFAULT '0' COMMENT '显示在前台的顺序【升序排序】',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `identification` (`identification`),
  KEY `access_identification` (`access_identification`),
  KEY `supermodel` (`supermodel`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=".env("DB_CHARSET")." COMMENT='模型表'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('module_formtools_models');
    }
};
