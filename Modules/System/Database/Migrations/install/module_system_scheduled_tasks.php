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

        if (Schema::hasTable("module_system_scheduled_tasks")) Schema::drop('module_system_scheduled_tasks');

        DB::statement("CREATE TABLE `".env("DB_PREFIX")."module_system_scheduled_tasks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `module` varchar(32) DEFAULT 'system' COMMENT '模块',
  `task_type` tinyint(4) DEFAULT '1' COMMENT '任务类型',
  `name` varchar(255) DEFAULT '' COMMENT '任务名称',
  `type` tinyint(4) DEFAULT '1' COMMENT '类型',
  `day` tinyint(4) DEFAULT '1' COMMENT '天/日/星期，',
  `hour` tinyint(4) DEFAULT '0' COMMENT '小时【0-23】',
  `minute` tinyint(4) DEFAULT '0' COMMENT '分钟【0-59】',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态，1=正常，2=停用',
  `last_execution_time` timestamp NULL DEFAULT NULL COMMENT '上次执行时间',
  `content` text COMMENT '脚本内容',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `module_class` varchar(255) DEFAULT '',
  `module_class_method` varchar(255) DEFAULT '' COMMENT '模块类方法',
  `remark` varchar(255) DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=".env("DB_CHARSET")." COMMENT='定时任务表'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('module_system_scheduled_tasks');
    }
};
