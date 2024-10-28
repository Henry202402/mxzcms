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

        if (Schema::hasTable("module_system_domain_bind_module")) Schema::drop('module_system_domain_bind_module');

        DB::statement("CREATE TABLE `".env("DB_PREFIX")."module_system_domain_bind_module` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `module_id` int(11) NOT NULL COMMENT '模块id',
  `module` varchar(255) NOT NULL DEFAULT '' COMMENT '模块',
  `domain` text COMMENT '域名',
  `num` tinyint(4) DEFAULT '0' COMMENT '域名数量',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `module` (`module`)
) ENGINE=InnoDB DEFAULT CHARSET=".env("DB_CHARSET")." COMMENT='域名绑定模块表'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('module_system_domain_bind_module');
    }
};
