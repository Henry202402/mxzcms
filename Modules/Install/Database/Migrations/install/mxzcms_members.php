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

        if (Schema::hasTable("members")) Schema::drop('members');

        DB::statement("CREATE TABLE `".env("DB_PREFIX")."members` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `avatar` varchar(255) DEFAULT '' COMMENT '头像路径',
  `c_code` varchar(10) DEFAULT '86' COMMENT '国家代号，如+86',
  `phone` varchar(25) DEFAULT '' COMMENT '联系手机',
  `email` varchar(255) DEFAULT NULL COMMENT '邮箱地址',
  `username` varchar(255) DEFAULT '' COMMENT '用户名',
  `nickname` varchar(255) DEFAULT '' COMMENT '昵称',
  `password` varchar(32) DEFAULT '' COMMENT '密码',
  `status` tinyint(4) DEFAULT '0' COMMENT '账号状态，0=禁用，1=启用',
  `pid` int(11) DEFAULT '1' COMMENT '父id',
  `pid_path` varchar(255) DEFAULT '1' COMMENT '上级路径',
  `male` varchar(25) DEFAULT NULL COMMENT '性别，男，女，其他',
  `birthday` date DEFAULT NULL COMMENT '出生日期',
  `phone_active` tinyint(4) DEFAULT NULL COMMENT '手机是否认证，0=否，1=是',
  `email_active` tinyint(4) DEFAULT NULL COMMENT '邮件是否认证，0=否，1=是',
  `signature` varchar(255) DEFAULT '' COMMENT '个性签名',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`uid`),
  KEY `phone` (`phone`),
  KEY `username` (`username`),
  KEY `password` (`password`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=".env("DB_CHARSET")." COMMENT='会员表'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('members');
    }
};
