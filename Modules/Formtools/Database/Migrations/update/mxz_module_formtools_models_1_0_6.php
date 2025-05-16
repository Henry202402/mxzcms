<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        //V1.0.6
        //处理字段
        $tables = DB::connection()->getDoctrineSchemaManager()->listTables();
        $tableList = [];
        foreach ($tables as $key => $table) {
            $check = stristr($table->getName(), 'module_formtools_');
            if($check !== false ){
                if($check=='module_formtools_models'){
                    continue;
                }
                array_push($tableList, $check);
            }
        }

        foreach ($tableList as $key => $item) {
            if (!Schema::hasColumn($item, 'remark')) {
                Schema::table($item, function (Blueprint $table) {
                    $table->string('remark',255)->default(null)->nullable()->after('id')->comment('备注');
                });
            }
            if (!Schema::hasColumn($item, 'status')) {
                Schema::table($item, function (Blueprint $table) {
                    $table->tinyInteger('status')->default(1)->nullable()->after('id')->comment('状态，0待审核，1通过，2下架');
                });
            }
            if (!Schema::hasColumn($item, 'seo_description')) {
                Schema::table($item, function (Blueprint $table) {
                    $table->string('seo_description',2250)->default(null)->nullable()->after('id')->comment('SEO描述');
                });
            }
            if (!Schema::hasColumn($item, 'seo_keywords')) {
                Schema::table($item, function (Blueprint $table) {
                    $table->string('seo_keywords',2250)->default(null)->nullable()->after('id')->comment('SEO关键词');
                });
            }
            if (!Schema::hasColumn($item, 'seo_title')) {
                Schema::table($item, function (Blueprint $table) {
                    $table->string('seo_title',255)->default(null)->nullable()->after('id')->comment('SEO标题');
                });
            }
            if (!Schema::hasColumn($item, 'download_count')) {
                Schema::table($item, function (Blueprint $table) {
                    $table->integer('download_count')->default(0)->nullable()->after('id')->comment('下载次数');
                });
            }
            if (!Schema::hasColumn($item, 'comment_count')) {
                Schema::table($item, function (Blueprint $table) {
                    $table->integer('comment_count')->default(0)->nullable()->after('id')->comment('评论数');
                });
            }
            if (!Schema::hasColumn($item, 'good_count')) {
                Schema::table($item, function (Blueprint $table) {
                    $table->integer('good_count')->default(0)->nullable()->after('id')->comment('点赞次数统计');
                });
            }
            if (!Schema::hasColumn($item, 'access_count')) {
                Schema::table($item, function (Blueprint $table) {
                    $table->integer('access_count')->default(0)->nullable()->after('id')->comment('访问次数统计');
                });
            }
            if (!Schema::hasColumn($item, 'uid')) {
                Schema::table($item, function (Blueprint $table) {
                    $table->integer('uid')->nullable()->after('id')->comment('发布者uid');
                });
            }
        }




    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {

    }
};
