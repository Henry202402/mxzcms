<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
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
        //cms 1.0.2
        if (Schema::hasTable('modules')){
            Schema::table('modules', function (Blueprint $table){
                if (!Schema::hasColumn('modules', 'order')) {
                    $table->integer('order')->default(0)->after("form")->nullable()->comment("排序，最大排在前");
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
};
