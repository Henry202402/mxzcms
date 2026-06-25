<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('module_formtools_pages')) {
            return;
        }

        if (!Schema::hasColumn('module_formtools_pages', 'is_home')) {
            Schema::table('module_formtools_pages', function (Blueprint $table) {
                $table->tinyInteger('is_home')->default(0)->comment('是否作为站点首页')->after('is_nav');
            });
        }

        $this->ensureIndex();
    }

    public function down()
    {
    }

    private function ensureIndex(): void
    {
        $sm = DB::connection()->getDoctrineSchemaManager();
        $indexes = $sm->listTableIndexes('module_formtools_pages');
        if (isset($indexes['module_formtools_pages_is_home_index'])) {
            return;
        }

        Schema::table('module_formtools_pages', function (Blueprint $table) {
            $table->index('is_home');
        });
    }
};
