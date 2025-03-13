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

        if (Schema::hasTable("module_member_bill_record")) Schema::drop('module_member_bill_record');

        DB::statement("CREATE TABLE `".env("DB_PREFIX")."module_member_bill_record` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uid` int(11) DEFAULT '0' COMMENT '用户uid',
  `module` varchar(64) DEFAULT '' COMMENT '模块',
  `bill_order_num` varchar(64) DEFAULT '' COMMENT '流水订单号',
  `order_num` varchar(64) DEFAULT '' COMMENT '原订单号',
  `amount` decimal(11,2) DEFAULT '0.00' COMMENT '金额/数量等',
  `remark` varchar(255) DEFAULT '' COMMENT '备注',
  `json_str` json DEFAULT NULL COMMENT '其他参数json',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `module` (`module`),
  KEY `order_num` (`order_num`),
  KEY `created_at` (`created_at`),
  KEY `bill_order_num` (`bill_order_num`)
) ENGINE=InnoDB DEFAULT CHARSET=".env("DB_CHARSET")." COMMENT='流水账单记录'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('module_member_bill_record');
    }
};
