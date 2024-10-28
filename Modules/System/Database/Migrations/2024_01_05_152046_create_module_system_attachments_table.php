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
        Schema::create('module_system_attachments', function (Blueprint $table) {
            $table->comment('附件表');
            $table->string('path', 655)->default('')->index('path')->comment('路径，也就是文件名');
            $table->string('path_md5')->default('')->index('path_md5')->comment('Path 的md5值，用于快速查找');
            $table->string('drive')->default('')->comment('驱动，local');
            $table->timestamp('create_at')->nullable()->comment('创建时间');
            $table->timestamp('update_at')->nullable()->comment('更新时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('module_system_attachments');
    }
};
