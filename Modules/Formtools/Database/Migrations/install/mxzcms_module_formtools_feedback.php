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

        if (Schema::hasTable("module_formtools_feedback")) Schema::drop('module_formtools_feedback');

        DB::statement("CREATE TABLE `".env("DB_PREFIX")."module_formtools_feedback` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `full_name` varchar(255) DEFAULT NULL COMMENT '全名',
  `email` varchar(50) DEFAULT NULL COMMENT '邮箱',
  `company` varchar(255) DEFAULT NULL COMMENT '公司',
  `website` varchar(255) DEFAULT NULL COMMENT '网站',
  `content` text COMMENT '留言内容',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=".env("DB_CHARSET")." COMMENT='留言表'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('module_formtools_feedback');
    }
};
