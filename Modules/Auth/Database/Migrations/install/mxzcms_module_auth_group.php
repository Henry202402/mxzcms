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

        if (Schema::hasTable("module_auth_group")) Schema::drop('module_auth_group');

        DB::statement("CREATE TABLE `".env("DB_PREFIX")."module_auth_group` (
  `group_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `type` varchar(50) NOT NULL DEFAULT '' COMMENT '用户类型， admin=超级管理员，member=用户',
  `group_name` varchar(50) NOT NULL DEFAULT 'member' COMMENT '权限组名称',
  `role_json` json DEFAULT NULL COMMENT '权限id',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`group_id`),
  UNIQUE KEY `group_name` (`group_name`),
  KEY `type` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=".env("DB_CHARSET")." COMMENT='权限组'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('module_auth_group');
    }
};
