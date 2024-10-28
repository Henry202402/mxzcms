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

        if (Schema::hasTable("module_system_settings")) Schema::drop('module_system_settings');

        DB::statement("CREATE TABLE `".env("DB_PREFIX")."module_system_settings` (
  `module` varchar(255) NOT NULL DEFAULT '' COMMENT '模块标识',
  `type` varchar(255) DEFAULT '' COMMENT '设置类型，基本设置=website',
  `key` varchar(255) DEFAULT '' COMMENT 'Key',
  `value` text COMMENT 'Key 的值',
  KEY `module` (`module`,`type`,`key`)
) ENGINE=InnoDB DEFAULT CHARSET=".env("DB_CHARSET")." COMMENT='设置表'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('module_system_settings');
    }
};
