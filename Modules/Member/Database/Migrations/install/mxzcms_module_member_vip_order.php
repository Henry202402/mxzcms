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

        if (Schema::hasTable("module_member_vip_order")) Schema::drop('module_member_vip_order');

        DB::statement("CREATE TABLE `".env("DB_PREFIX")."module_member_vip_order` (
  `order_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `payment_id` varchar(32) DEFAULT '' COMMENT '第三方付款id',
  `order_num` varchar(32) NOT NULL DEFAULT '' COMMENT '订单号',
  `uid` int(11) NOT NULL COMMENT '用户uid',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `vip_id` int(11) NOT NULL DEFAULT '0' COMMENT 'vip id',
  `type` tinyint(1) DEFAULT '0' COMMENT '类型【1=年 2=月 3=日】',
  `number` int(11) DEFAULT '0' COMMENT '数量',
  `pay_method` varchar(20) DEFAULT 'WeChat' COMMENT '支付方式  WeChat=微信    Alipay=支付宝',
  `pay_type` varchar(20) DEFAULT 'pc' COMMENT '请求类型  app=APP端，public=公众号，small=小程序， pc=电脑端',
  `pay_status` tinyint(1) DEFAULT '0' COMMENT '支付状态  0=待支付 1=支付成功 2=支付失败 3=取消支付',
  `remark` varchar(255) DEFAULT '' COMMENT '用户备注',
  `callback_msg` text COMMENT '回调信息',
  `pay_at` timestamp NULL DEFAULT NULL COMMENT '支付时间',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `expire_at` timestamp NULL DEFAULT NULL COMMENT '过期时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`order_id`),
  KEY `order_num` (`order_num`),
  KEY `uid` (`uid`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=".env("DB_CHARSET")." COMMENT='Vip订单表'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('module_member_vip_order');
    }
};
