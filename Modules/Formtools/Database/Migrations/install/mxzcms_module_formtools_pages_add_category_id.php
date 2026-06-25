<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('module_formtools_pages')) {
            return;
        }

        Schema::table('module_formtools_pages', function (Blueprint $table) {
            if (!Schema::hasColumn('module_formtools_pages', 'category_id')) {
                $table->unsignedInteger('category_id')->nullable()->after('type')->comment('页面分类ID');
                $table->index('category_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('module_formtools_pages') || !Schema::hasColumn('module_formtools_pages', 'category_id')) {
            return;
        }

        Schema::table('module_formtools_pages', function (Blueprint $table) {
            $table->dropIndex(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};
