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
  `uid` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `userid` varchar(32) DEFAULT '' COMMENT '用户身份id',
  `avatar` varchar(255) DEFAULT '' COMMENT '头像路径',
  `c_code` varchar(10) DEFAULT '' COMMENT '国家代号，如+86',
  `phone` varchar(64) NOT NULL DEFAULT '' COMMENT '联系手机',
  `email` varchar(255) DEFAULT '' COMMENT '邮箱',
  `username` varchar(32) NOT NULL DEFAULT '' COMMENT '用户名',
  `nickname` varchar(32) DEFAULT '' COMMENT '昵称',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '密码',
  `status` tinyint(1) unsigned DEFAULT '0' COMMENT '账号状态，0=禁用，1=启用',
  `pid` int(11) DEFAULT '1' COMMENT '上级',
  `pid_path` varchar(255) DEFAULT '1' COMMENT '上级路径',
  `male` varchar(2) DEFAULT '' COMMENT '性别',
  `birthday` date DEFAULT NULL COMMENT '生日',
  `phone_active` tinyint(1) unsigned DEFAULT '0' COMMENT '手机是否认证，0=否，1=是',
  `email_active` tinyint(1) unsigned DEFAULT '0' COMMENT '邮件是否认证，0=否，1=是',
  `signature` varchar(555) DEFAULT '' COMMENT '个性签名',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`uid`) USING BTREE,
  KEY `phone` (`phone`) USING BTREE,
  KEY `email` (`email`) USING BTREE,
  KEY `username` (`username`) USING BTREE,
  KEY `password` (`password`) USING BTREE,
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=" . env("DB_CHARSET") . " COMMENT='会员信息'");

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
