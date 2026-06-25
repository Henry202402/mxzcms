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
        if (Schema::hasTable('module_formtools_page_categories')) {
            return;
        }

        Schema::create('module_formtools_page_categories', function (Blueprint $table) {
            $table->increments('id')->comment('主键ID');
            $table->string('name', 255)->default('')->comment('分类名称');
            $table->string('identification', 100)->default('')->unique()->comment('分类标识');
            $table->integer('sort')->default(0)->comment('排序');
            $table->tinyInteger('status')->default(1)->comment('状态 1启用 0停用');
            $table->string('remark', 255)->default('')->comment('备注');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');

            $table->index('sort');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('module_formtools_page_categories');
    }
};
