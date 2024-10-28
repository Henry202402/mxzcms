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

        if (Schema::hasTable("module_system_attachments")) Schema::drop('module_system_attachments');

        DB::statement("CREATE TABLE `".env("DB_PREFIX")."module_system_attachments` (
  `path` varchar(655) NOT NULL DEFAULT '' COMMENT '路径，也就是文件名',
  `path_md5` varchar(255) NOT NULL DEFAULT '' COMMENT 'Path 的md5值，用于快速查找',
  `drive` varchar(255) NOT NULL DEFAULT '' COMMENT '驱动，local',
  `create_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  KEY `path` (`path`),
  KEY `path_md5` (`path_md5`)
) ENGINE=InnoDB DEFAULT CHARSET=".env("DB_CHARSET")." COMMENT='附件表'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('module_system_attachments');
    }
};
