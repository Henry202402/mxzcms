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

        if (!Schema::hasColumn('home_menu', 'lang')) {
            Schema::table('home_menu', function (Blueprint $table) {
                $table->string('lang', 20)->default('')->after('position')->comment('语言范围【空=全局共享】');
                $table->index(['position', 'lang', 'status'], 'home_menu_position_lang_status');
            });
        }
    }

    public function down()
    {
    }
};
