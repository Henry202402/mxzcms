<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasColumn('module_formtools_models', 'home_page_sort')) {
            Schema::table('module_formtools_models', function (Blueprint $table) {
                $table->integer('home_page_sort')->default(0)->nullable()->after('home_page_num')->comment('显示在前台的顺序【升序排序】');
            });
        }

        if (!Schema::hasColumn('module_formtools_models', 'home_page_bg_img')) {
            Schema::table('module_formtools_models', function (Blueprint $table) {
                $table->string('home_page_bg_img')->default('')->nullable()->after('home_page_describe')->comment('前台页面背景图');
            });
        }


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {

    }
};
