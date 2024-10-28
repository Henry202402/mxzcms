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

        if (Schema::hasTable("themes")) Schema::drop('themes');

        DB::statement("CREATE TABLE `".env("DB_PREFIX")."themes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `name` varchar(255) DEFAULT '' COMMENT '名称',
  `preview` varchar(255) DEFAULT '' COMMENT '预览图',
  `identification` varchar(255) DEFAULT '' COMMENT '唯一标识',
  `status` tinyint(4) DEFAULT '2' COMMENT '使用状态，1正在使用，2未使用',
  `form` varchar(25) DEFAULT '' COMMENT '来源local cloud',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `identification` (`identification`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=".env("DB_CHARSET")."");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('themes');
    }
};
