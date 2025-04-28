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
        //cms 1.0.3
        if (Schema::hasTable('modules')){
            Schema::table('modules', function (Blueprint $table){
                if (!Schema::hasColumn('modules', 'is_backend')) {
                    $table->tinyInteger('is_backend')->default(0)->after("is_index")->nullable()->comment("设为后台入口【0.否；1.是】");
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
