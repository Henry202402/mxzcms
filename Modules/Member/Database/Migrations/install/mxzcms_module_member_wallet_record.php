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

        if (Schema::hasTable("module_member_wallet_record")) Schema::drop('module_member_wallet_record');

        DB::statement("CREATE TABLE `".env("DB_PREFIX")."module_member_wallet_record` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `module` varchar(100) NOT NULL DEFAULT '' COMMENT '模块',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户uid',
  `bill_order_num` varchar(64) DEFAULT '' COMMENT '流水号',
  `order_num` varchar(64) DEFAULT '' COMMENT '原订单号',
  `type` tinyint(4) DEFAULT '0' COMMENT '类型【1=加，2=减】',
  `amount_type` varchar(64) NOT NULL DEFAULT '' COMMENT '操作对象类型【可提现余额，余额，积分等等，在线支付】',
  `amount` decimal(11,2) DEFAULT '0.00' COMMENT '额度/金额/积分等',
  `unit` varchar(50) DEFAULT '' COMMENT '货币单位',
  `remark` varchar(255) DEFAULT '' COMMENT '备注',
  `extra` json DEFAULT NULL COMMENT '扩展json',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `module` (`module`),
  KEY `uid` (`uid`),
  KEY `bill_order_num` (`bill_order_num`),
  KEY `order_num` (`order_num`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=".env("DB_CHARSET")." COMMENT='钱包记录表'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('module_member_wallet_record');
    }
};
