<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        $prefix = (string) config('database.connections.' . config('database.default') . '.prefix', '');
        $tableNames = DB::connection()->getDoctrineSchemaManager()->listTableNames();

        foreach ($tableNames as $physicalTableName) {
            $logicalTableName = $physicalTableName;
            if ($prefix !== '' && str_starts_with($physicalTableName, $prefix)) {
                $logicalTableName = substr($physicalTableName, strlen($prefix));
            }

            if (!str_starts_with($logicalTableName, 'module_formtools_') || $logicalTableName === 'module_formtools_models') {
                continue;
            }

            $this->ensureStandardColumns($logicalTableName);
        }
    }

    public function down()
    {
    }

    private function ensureStandardColumns(string $tableName): void
    {
        $columns = [
            'remark' => fn (Blueprint $table) => $table->string('remark', 255)->nullable()->default(null)->comment('备注'),
            'status' => fn (Blueprint $table) => $table->tinyInteger('status')->nullable()->default(1)->comment('状态，0待审核，1通过，2下架'),
            'seo_title' => fn (Blueprint $table) => $table->string('seo_title', 255)->nullable()->default(null)->comment('SEO标题'),
            'seo_keywords' => fn (Blueprint $table) => $table->string('seo_keywords', 2250)->nullable()->default(null)->comment('SEO关键词'),
            'seo_description' => fn (Blueprint $table) => $table->string('seo_description', 2250)->nullable()->default(null)->comment('SEO描述'),
            'download_count' => fn (Blueprint $table) => $table->integer('download_count')->nullable()->default(0)->comment('下载次数'),
            'comment_count' => fn (Blueprint $table) => $table->integer('comment_count')->nullable()->default(0)->comment('评论数'),
            'good_count' => fn (Blueprint $table) => $table->integer('good_count')->nullable()->default(0)->comment('点赞次数统计'),
            'access_count' => fn (Blueprint $table) => $table->integer('access_count')->nullable()->default(0)->comment('访问次数统计'),
            'uid' => fn (Blueprint $table) => $table->integer('uid')->nullable()->comment('发布者uid'),
            'created_at' => fn (Blueprint $table) => $table->timestamp('created_at')->nullable(),
            'updated_at' => fn (Blueprint $table) => $table->timestamp('updated_at')->nullable(),
        ];

        foreach ($columns as $column => $definition) {
            if (Schema::hasColumn($tableName, $column)) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) use ($definition) {
                $definition($table);
            });
        }
    }
};
