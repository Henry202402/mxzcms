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

        if (Schema::hasTable("module_member_auth_record")) Schema::drop('module_member_auth_record');

        DB::statement("CREATE TABLE `".env("DB_PREFIX")."module_member_auth_record` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `type` tinyint(1) DEFAULT '1' COMMENT '类型【1=个人认证，2=企业认证】',
  `uid` int(11) DEFAULT NULL COMMENT '用户uid',
  `real_name` varchar(50) DEFAULT '' COMMENT '用户实名',
  `id_card` varchar(18) DEFAULT '' COMMENT '身份证号码',
  `id_card_positive_img` varchar(255) DEFAULT '' COMMENT '身份证人像面',
  `id_card_back_img` varchar(255) DEFAULT '' COMMENT '身份证国徽面',
  `id_card_hand_img` varchar(255) DEFAULT '' COMMENT '手持身份证',
  `company_name` varchar(100) DEFAULT '' COMMENT '企业名称',
  `unified_social_credit_code` varchar(20) DEFAULT '' COMMENT '统一社会信用代码',
  `business_license_img` varchar(255) DEFAULT '' COMMENT '营业执照',
  `legal_person` varchar(100) DEFAULT '' COMMENT '法人名称',
  `legal_id_card` varchar(18) DEFAULT '' COMMENT '法人身份证号码',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态【0=待审核，1=已审核，2=审核失败】',
  `remark` varchar(255) DEFAULT '' COMMENT '备注',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=".env("DB_CHARSET")." COMMENT='认证记录表'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('module_member_auth_record');
    }
};
