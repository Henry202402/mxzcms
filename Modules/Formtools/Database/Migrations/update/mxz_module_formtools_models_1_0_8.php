<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (!Schema::hasTable('module_formtools_models')) {
            return;
        }

        $tableName = env('DB_PREFIX') . 'module_formtools_models';
        $columns = [
            'admin_config' => '后台配置json',
            'home_config' => '前台配置json',
            'home_seo_config' => '前台列表seo配置json',
            'home_seo_detail_config' => '前台详情页SEO配置sjon',
            'other_config' => '其他配置json',
        ];

        foreach ($columns as $column => $comment) {
            if (!Schema::hasColumn('module_formtools_models', $column)) {
                continue;
            }

            DB::statement(sprintf(
                "ALTER TABLE `%s` MODIFY `%s` TEXT NULL COMMENT '%s'",
                $tableName,
                $column,
                $comment
            ));
        }
    }

    public function down()
    {
    }
};
