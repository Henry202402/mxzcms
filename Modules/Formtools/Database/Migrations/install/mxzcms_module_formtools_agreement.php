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

        if (Schema::hasTable("module_formtools_agreement")) Schema::drop('module_formtools_agreement');

        DB::statement("CREATE TABLE `".env("DB_PREFIX")."module_formtools_agreement` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `cate_id` int(11) DEFAULT NULL COMMENT '协议分类',
  `name` varchar(255) DEFAULT NULL COMMENT '协议名称',
  `content` text COMMENT '协议内容',
  `seo_keywords` varchar(255) DEFAULT NULL COMMENT 'SEO关键字',
  `seo_description` varchar(255) DEFAULT NULL COMMENT 'SEO描述',
  `status` tinyint(4) DEFAULT NULL COMMENT '状态',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=".env("DB_CHARSET")." COMMENT='协议列表'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('module_formtools_agreement');
    }
};
