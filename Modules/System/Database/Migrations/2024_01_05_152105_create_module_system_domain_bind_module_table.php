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
        Schema::create('module_system_domain_bind_module', function (Blueprint $table) {
            $table->comment('域名绑定模块表');
            $table->increments('id')->comment('主键id');
            $table->integer('module_id')->comment('模块id');
            $table->string('module')->default('')->unique('module')->comment('模块');
            $table->text('domain')->nullable()->comment('域名');
            $table->tinyInteger('num')->nullable()->default(0)->comment('域名数量');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('创建时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('module_system_domain_bind_module');
    }
};
