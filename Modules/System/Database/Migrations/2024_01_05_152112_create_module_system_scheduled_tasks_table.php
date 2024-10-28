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
        Schema::create('module_system_scheduled_tasks', function (Blueprint $table) {
            $table->comment('定时任务表');
            $table->increments('id')->comment('主键id');
            $table->string('module', 32)->nullable()->default('system')->comment('模块');
            $table->tinyInteger('task_type')->nullable()->default(1)->comment('任务类型');
            $table->string('name')->nullable()->default('')->comment('任务名称');
            $table->tinyInteger('type')->nullable()->default(1)->comment('类型');
            $table->tinyInteger('day')->nullable()->default(1)->comment('天/日/星期，');
            $table->tinyInteger('hour')->nullable()->default(0)->comment('小时【0-23】');
            $table->tinyInteger('minute')->nullable()->default(0)->comment('分钟【0-59】');
            $table->tinyInteger('status')->nullable()->default(1)->comment('状态，1=正常，2=停用');
            $table->timestamp('last_execution_time')->nullable()->comment('上次执行时间');
            $table->text('content')->nullable()->comment('脚本内容');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('创建时间');
            $table->string('module_class')->nullable()->default('');
            $table->string('module_class_method')->nullable()->default('')->comment('模块类方法');
            $table->string('remark')->nullable()->default('')->comment('备注');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('module_system_scheduled_tasks');
    }
};
