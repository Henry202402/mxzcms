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
        if (Schema::hasTable('module_formtools_pages')) {
            return;
        }

        Schema::create('module_formtools_pages', function (Blueprint $table) {
            $table->increments('id')->comment('主键ID');
            $table->string('name', 255)->default('')->comment('页面名称');
            $table->string('identification', 100)->default('')->unique()->comment('页面标识');
            $table->string('slug', 150)->default('')->unique()->comment('访问路径');
            $table->string('type', 30)->default('custom')->comment('页面类型 single/list/custom/landing');
            $table->unsignedInteger('model_id')->nullable()->comment('绑定模型ID');
            $table->string('template', 100)->default('default')->comment('模板标识');
            $table->string('builder_type', 30)->default('visual')->comment('编辑模式 visual/html');
            $table->tinyInteger('status')->default(1)->comment('状态 1启用 0停用');
            $table->tinyInteger('is_nav')->default(0)->comment('是否导航显示');
            $table->tinyInteger('is_home')->default(0)->comment('是否作为站点首页');
            $table->integer('sort')->default(0)->comment('排序');
            $table->string('remark', 255)->default('')->comment('备注');
            $table->text('seo_title')->nullable()->comment('SEO标题');
            $table->text('seo_keywords')->nullable()->comment('SEO关键词');
            $table->text('seo_description')->nullable()->comment('SEO描述');
            $table->longText('layout_schema')->nullable()->comment('布局JSON');
            $table->longText('page_html')->nullable()->comment('页面HTML');
            $table->longText('custom_css')->nullable()->comment('独立CSS');
            $table->longText('custom_js')->nullable()->comment('独立JS');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');

            $table->index('model_id');
            $table->index('status');
            $table->index('is_home');
            $table->index('sort');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('module_formtools_pages');
    }
};
