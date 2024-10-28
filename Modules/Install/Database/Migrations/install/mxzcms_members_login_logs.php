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

        if (Schema::hasTable("members_login_logs")) Schema::drop('members_login_logs');

        DB::statement("CREATE TABLE `".env("DB_PREFIX")."members_login_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uid` bigint(20) DEFAULT NULL COMMENT '登录的uid',
  `module` varchar(100) DEFAULT '' COMMENT '模块',
  `ip` varchar(25) DEFAULT '' COMMENT '登录ip',
  `device_type` varchar(255) DEFAULT '' COMMENT '设备类型，PC、iOS、android',
  `device_name` varchar(255) DEFAULT '' COMMENT '设备名字',
  `device_token` varchar(255) DEFAULT '' COMMENT '设备token',
  `login_at` timestamp NULL DEFAULT NULL COMMENT '登录时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `module` (`module`)
) ENGINE=InnoDB DEFAULT CHARSET=".env("DB_CHARSET")." COMMENT='登录日志表'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('members_login_logs');
    }
};
