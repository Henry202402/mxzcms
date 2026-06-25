<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('themes')) {
            return;
        }

        if (!Schema::hasColumn('themes', 'order')) {
            Schema::table('themes', function (Blueprint $table) {
                $table->integer('order')->default(0)->after('status');
                $table->index(['order', 'status'], 'themes_order_status');
            });
        }
    }

    public function down()
    {
    }
};

