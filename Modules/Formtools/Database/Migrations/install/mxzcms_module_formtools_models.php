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
  `supermodel` int(11) DEFAULT NULL COMMENT '关联的父模型',
  `remark` varchar(255) DEFAULT '' COMMENT '备注',
  `form_template` varchar(255) DEFAULT '' COMMENT '表单模板',
  `list_template` varchar(255) DEFAULT '' COMMENT '列表模板',
  `detail_template` varchar(255) DEFAULT '' COMMENT '详情模板',
  `data_source` varchar(30) DEFAULT 'local' COMMENT '数据源',
  `data_source_api_url` varchar(255) DEFAULT '' COMMENT '数据源列表API请求地址',
  `data_source_api_url_detail` varchar(255) DEFAULT NULL COMMENT '数据源详情API请求地址',
  `data_source_field_mapping` text COMMENT 'API字段映射',
  `page_num` int(11) DEFAULT '20' COMMENT '分页数量，每页显示的条数，0代表全部',
  `list_page_template` varchar(50) DEFAULT NULL COMMENT '分页模板',
  `home_page_title` varchar(100) DEFAULT NULL COMMENT '前台页面标题',
  `home_page_describe` varchar(255) DEFAULT NULL COMMENT '前台页面简介',
  `show_home_page` varchar(15) DEFAULT NULL COMMENT '是否显示在前台首页，yes no',
  `home_page_num` int(5) DEFAULT NULL COMMENT '显示在首页的数量',
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
