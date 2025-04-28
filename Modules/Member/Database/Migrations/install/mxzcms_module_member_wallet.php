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

        if (Schema::hasTable("module_member_wallet")) Schema::drop('module_member_wallet');

        DB::statement("CREATE TABLE `".env("DB_PREFIX")."module_member_wallet` (
  `wallet_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uid` int(11) DEFAULT NULL COMMENT '用户uid',
  `withdrawable` decimal(11,2) DEFAULT '0.00' COMMENT '可提现余额',
  `balance` decimal(11,2) DEFAULT '0.00' COMMENT '余额',
  `integral` decimal(11,2) DEFAULT '0.00' COMMENT '积分或者其他名称',
  `vip_time` date DEFAULT NULL COMMENT 'vip时间',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`wallet_id`)
) ENGINE=InnoDB DEFAULT CHARSET=".env("DB_CHARSET")." COMMENT='钱包表'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('module_member_wallet');
    }
};
