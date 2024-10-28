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

        if (Schema::hasTable("module_formtools_news")) Schema::drop('module_formtools_news');

        DB::statement("CREATE TABLE `".env("DB_PREFIX")."module_formtools_news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `cover` varchar(255) DEFAULT NULL COMMENT '封面',
  `pid` tinyint(4) DEFAULT NULL COMMENT '分类id',
  `title` varchar(100) DEFAULT NULL COMMENT '标题',
  `content` longtext COMMENT '内容',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=".env("DB_CHARSET")." COMMENT='新闻列表'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('module_formtools_news');
    }
};
