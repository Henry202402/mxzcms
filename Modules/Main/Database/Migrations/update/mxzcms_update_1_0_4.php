<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('home_menu')) {
            return;
        }

        Schema::table('home_menu', function (Blueprint $table) {
            if (!Schema::hasColumn('home_menu', 'target')) {
                $table->string('target', 20)->default('_self')->after('url')->comment('打开方式【_self=当前窗口；_blank=新窗口】');
            }
            if (!Schema::hasColumn('home_menu', 'menu_type')) {
                $table->string('menu_type', 30)->default('manual')->after('icon_character')->comment('菜单来源【manual/module/model/search】');
            }
            if (!Schema::hasColumn('home_menu', 'source_module')) {
                $table->string('source_module', 100)->default('')->after('menu_type')->comment('来源模块');
            }
            if (!Schema::hasColumn('home_menu', 'source_value')) {
                $table->string('source_value', 255)->default('')->after('source_module')->comment('来源标识');
            }
        });
    }

    public function down()
    {
    }
};
