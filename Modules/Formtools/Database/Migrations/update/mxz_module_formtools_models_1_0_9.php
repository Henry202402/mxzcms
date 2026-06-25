<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        $tables = DB::connection()->getDoctrineSchemaManager()->listTables();
        $tableList = [];
        foreach ($tables as $table) {
            $name = $table->getName();
            if (!str_starts_with($name, 'module_formtools_') || $name === 'module_formtools_models') {
                continue;
            }
            $tableList[] = $name;
        }

        foreach ($tableList as $tableName) {
            if (!Schema::hasColumn($tableName, 'remark')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->string('remark', 255)->nullable()->default(null)->after('id')->comment('备注');
                });
            }
            if (!Schema::hasColumn($tableName, 'status')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->tinyInteger('status')->nullable()->default(1)->after('id')->comment('状态，0待审核，1通过，2下架');
                });
            }
            if (!Schema::hasColumn($tableName, 'seo_title')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->string('seo_title', 255)->nullable()->default(null)->after('id')->comment('SEO标题');
                });
            }
            if (!Schema::hasColumn($tableName, 'seo_keywords')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->string('seo_keywords', 2250)->nullable()->default(null)->after('id')->comment('SEO关键词');
                });
            }
            if (!Schema::hasColumn($tableName, 'seo_description')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->string('seo_description', 2250)->nullable()->default(null)->after('id')->comment('SEO描述');
                });
            }
            if (!Schema::hasColumn($tableName, 'download_count')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->integer('download_count')->nullable()->default(0)->after('id')->comment('下载次数');
                });
            }
            if (!Schema::hasColumn($tableName, 'comment_count')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->integer('comment_count')->nullable()->default(0)->after('id')->comment('评论数');
                });
            }
            if (!Schema::hasColumn($tableName, 'good_count')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->integer('good_count')->nullable()->default(0)->after('id')->comment('点赞次数统计');
                });
            }
            if (!Schema::hasColumn($tableName, 'access_count')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->integer('access_count')->nullable()->default(0)->after('id')->comment('访问次数统计');
                });
            }
            if (!Schema::hasColumn($tableName, 'uid')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->integer('uid')->nullable()->after('id')->comment('发布者uid');
                });
            }
            if (!Schema::hasColumn($tableName, 'created_at')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->timestamp('created_at')->nullable()->after('id');
                });
            }
            if (!Schema::hasColumn($tableName, 'updated_at')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->timestamp('updated_at')->nullable()->after('id');
                });
            }
        }
    }

    public function down()
    {
    }
};
