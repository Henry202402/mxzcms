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

        if (Schema::hasTable("module_formtools_contact_us")) Schema::drop('module_formtools_contact_us');

        DB::statement("CREATE TABLE `".env("DB_PREFIX")."module_formtools_contact_us` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态，0待审核，1通过，2下架',
  `seo_title` varchar(255) DEFAULT NULL COMMENT 'SEO标题',
  `seo_keywords` varchar(2250) DEFAULT NULL COMMENT 'SEO关键词',
  `seo_description` varchar(2250) DEFAULT NULL COMMENT 'SEO描述',
  `download_count` int(11) DEFAULT '0' COMMENT '下载次数',
  `comment_count` int(11) DEFAULT '0' COMMENT '评论数',
  `good_count` int(11) DEFAULT '0' COMMENT '点赞次数统计',
  `access_count` int(11) DEFAULT '0' COMMENT '访问次数统计',
  `uid` int(11) DEFAULT NULL COMMENT '发布者uid',
  `company_name` varchar(100) DEFAULT NULL COMMENT '公司名称',
  `company_address` varchar(255) DEFAULT NULL COMMENT '联系地址',
  `username` varchar(30) DEFAULT NULL COMMENT '联系人',
  `phone` varchar(50) DEFAULT NULL COMMENT '联系电话',
  `email` varchar(100) DEFAULT NULL COMMENT '邮箱',
  `is_open_leave` tinyint(4) DEFAULT NULL COMMENT '是否开启留言',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=".env("DB_CHARSET")." COMMENT='联系我们'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('module_formtools_contact_us');
    }
};
