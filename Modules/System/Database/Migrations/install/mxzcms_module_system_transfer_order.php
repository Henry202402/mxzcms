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

        if (Schema::hasTable("module_system_transfer_order")) Schema::drop('module_system_transfer_order');

        DB::statement("CREATE TABLE `".env("DB_PREFIX")."module_system_transfer_order` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `order_num` varchar(32) NOT NULL COMMENT '订单号',
  `module` varchar(30) NOT NULL DEFAULT '' COMMENT '模块',
  `action` varchar(30) NOT NULL DEFAULT '' COMMENT '回调函数名',
  `pay_method` varchar(30) NOT NULL DEFAULT '' COMMENT 'WeChat=微信  Alipay=支付宝',
  `create_at` datetime DEFAULT NULL COMMENT '订单时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_num` (`order_num`) USING BTREE,
  KEY `module` (`module`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=".env("DB_CHARSET")." ROW_FORMAT=DYNAMIC COMMENT='中转订单表'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('module_system_transfer_order');
    }
};
