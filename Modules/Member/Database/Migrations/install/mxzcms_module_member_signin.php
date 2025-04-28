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

        if (Schema::hasTable("module_member_signin")) Schema::drop('module_member_signin');

        DB::statement("CREATE TABLE `".env("DB_PREFIX")."module_member_signin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `order_num` varchar(64) DEFAULT '' COMMENT '订单号',
  `uid` int(11) NOT NULL COMMENT '用户uid',
  `day` date NOT NULL COMMENT '签到日期',
  `tig` varchar(32) NOT NULL DEFAULT '' COMMENT '连签标识',
  `point` int(11) DEFAULT '0' COMMENT '签到积分',
  `remark` varchar(255) DEFAULT '' COMMENT '备注',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE,
  KEY `day` (`day`) USING BTREE,
  KEY `tig` (`tig`)
) ENGINE=InnoDB DEFAULT CHARSET=".env("DB_CHARSET")." COMMENT='签到表'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('module_member_signin');
    }
};
