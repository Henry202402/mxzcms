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
        //V1.0.5
        //处理字段
        if (!Schema::hasColumn('module_formtools_models', 'other_config')) {
            Schema::table('module_formtools_models', function (Blueprint $table) {
                $table->text('other_config')->default(null)->nullable()->after('remark')->comment('其他配置json');
            });
        }
        if (!Schema::hasColumn('module_formtools_models', 'home_seo_detail_config')) {
            Schema::table('module_formtools_models', function (Blueprint $table) {
                $table->text('home_seo_detail_config')->default(null)->nullable()->after('remark')->comment('前台详情页SEO配置sjon');
            });
        }
        if (!Schema::hasColumn('module_formtools_models', 'home_seo_config')) {
            Schema::table('module_formtools_models', function (Blueprint $table) {
                $table->text('home_seo_config')->default(null)->nullable()->after('remark')->comment('前台列表seo配置json');
            });
        }
        if (!Schema::hasColumn('module_formtools_models', 'home_config')) {
            Schema::table('module_formtools_models', function (Blueprint $table) {
                $table->text('home_config')->default(null)->nullable()->after('remark')->comment('前台配置json');
            });
        }
        if (!Schema::hasColumn('module_formtools_models', 'admin_config')) {
            Schema::table('module_formtools_models', function (Blueprint $table) {
                $table->text('admin_config')->default(null)->nullable()->after('remark')->comment('后台配置json');
            });
        }

        //处理字段数据
        $datas = DB::table("module_formtools_models")->get();
        foreach ($datas as $data) {

            $update['admin_config'] = json_encode([
                'form_template'=>$data->form_template
            ], true);
            $update['home_config'] = json_encode([
                'list_template'=>$data->list_template,
                'custom_list_template'=>$data->custom_list_template,
                'detail_template'=>$data->detail_template,
                'page_num'=>$data->page_num,
                'list_page_template'=>$data->list_page_template,
                'home_page_title'=>$data->home_page_title,
                'home_page_describe'=>$data->home_page_describe,
                'home_page_bg_img'=>$data->home_page_bg_img
            ], true);
            $update['home_seo_config'] = null;
            $update['home_seo_detail_config'] = null;
            $update['other_config'] = json_encode([
                'data_source'=>$data->data_source,
                'data_source_api_url'=>$data->data_source_api_url,
                'data_source_api_url_detail'=>$data->data_source_api_url_detail,
                'data_source_field_mapping'=>$data->data_source_field_mapping,
            ], true);

            DB::table('module_formtools_models')->where('id', '=', $data->id)->update($update);
        }

        //删除字段
        Schema::table('module_formtools_models', function (Blueprint $table) {
            $table->dropColumn('form_template');
            $table->dropColumn('list_template');
            $table->dropColumn('detail_template');
            $table->dropColumn('data_source');
            $table->dropColumn('data_source_api_url');
            $table->dropColumn('data_source_api_url_detail');
            $table->dropColumn('data_source_field_mapping');
            $table->dropColumn('page_num');
            $table->dropColumn('list_page_template');
            $table->dropColumn('home_page_title');
            $table->dropColumn('home_page_describe');
            $table->dropColumn('home_page_bg_img');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {

    }
};
