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
        Schema::create('module_system_settings', function (Blueprint $table) {
            $table->comment('设置表');
            $table->string('type')->nullable()->default('')->comment('设置类型，基本设置=website');
            $table->string('key')->nullable()->default('')->comment('Key');
            $table->text('value')->comment('Key 的值');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('module_system_settings');
    }
};
