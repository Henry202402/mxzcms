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

        if (Schema::hasTable("module_member_vip")) Schema::drop('module_member_vip');

        DB::statement("CREATE TABLE `".env("DB_PREFIX")."module_member_vip` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(50) DEFAULT '' COMMENT '名称',
  `type` tinyint(1) DEFAULT '0' COMMENT '类型【1=年 2=月 3=日】',
  `number` int(11) DEFAULT '0' COMMENT '数量',
  `price` decimal(11,2) DEFAULT '0.00' COMMENT '价格',
  `discount_price` decimal(11,2) DEFAULT '0.00' COMMENT '折扣价',
  `describe` text COMMENT '描述',
  `tig` varchar(20) DEFAULT '' COMMENT '标签',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态【1=启用，2=禁用】',
  `sort` tinyint(4) DEFAULT '0' COMMENT '排序【降序】',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  `is_only_buy_one` tinyint(1) DEFAULT '2' COMMENT '是否只能购买一次【1=是，2=否】',
  PRIMARY KEY (`id`),
  KEY `status` (`status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=".env("DB_CHARSET")." COMMENT='vip列表'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('module_member_vip');
    }
};
