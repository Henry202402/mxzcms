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

        if (Schema::hasTable("module_formtools_agreement_cate")) Schema::drop('module_formtools_agreement_cate');

        DB::statement("CREATE TABLE `".env("DB_PREFIX")."module_formtools_agreement_cate` (
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
  `cate_name` varchar(100) DEFAULT NULL COMMENT '协议名称',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=".env("DB_CHARSET")." COMMENT='协议分类'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('module_formtools_agreement_cate');
    }
};
