<?php

namespace Modules\Formtools\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Formtools\Support\FormTemplateResolver;

class ModuleFormtoolsModelsSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('module_formtools_models')->delete();

        $legacyRows = array (
  0 => 
  array(
     'id' => 1,
     'name' => '关于我们',
     'identification' => 'about_us',
     'access_identification' => 'about',
     'menuname' => '内容管理',
     'module' => NULL,
     'fields' => '[{"name": "内容", "rule": "unlimited", "regex": null, "remark": "内容", "foreign": null, "isindex": "NOINDEX", "formtype": "editor", "required": "required", "fieldtype": "longText", "maxlength": "0", "foreign_key": null, "is_show_list": "2", "identification": "content"}]',
     'icon' => 'icon-pencil7',
     'supermodel' => NULL,
     'remark' => '关于我们',
     'form_template' => NULL,
     'list_template' => 'about',
     'detail_template' => 'detail',
     'data_source' => 'local',
     'data_source_api_url' => '',
     'data_source_api_url_detail' => '',
     'data_source_field_mapping' => NULL,
     'page_num' => 20,
     'list_page_template' => 'center',
     'home_page_title' => '关于我们',
     'home_page_describe' => '选择适合所显示内容类型的布局。列表式布局是为坐着的人设计的，因为用户的行为往往具有非常独特的目的。另一方面，网格视图用于站立。它是为那些不安和好奇的人准备的。',
     'show_home_page' => 'yes',
     'home_page_num' => 0,
     'created_at' => '2024-01-15 15:39:58',
     'updated_at' => '2024-02-24 09:48:24',
  ),
  1 => 
  array(
     'id' => 2,
     'name' => '联系我们',
     'identification' => 'contact_us',
     'access_identification' => 'contacts',
     'menuname' => '内容管理',
     'module' => NULL,
     'fields' => '[{"name": "公司名称", "rule": "unlimited", "regex": null, "remark": "公司名称", "foreign": null, "isindex": "NOINDEX", "formtype": "text", "required": "required", "fieldtype": "string", "maxlength": "100", "foreign_key": null, "is_show_list": "1", "identification": "company_name", "is_show_home_form": "1", "is_show_home_list_search": "2", "is_show_admin_list_search": "1"}, {"name": "联系地址", "rule": "unlimited", "regex": null, "remark": "联系地址", "foreign": null, "isindex": "NOINDEX", "formtype": "text", "required": "required", "fieldtype": "string", "maxlength": "255", "foreign_key": null, "is_show_list": "1", "identification": "company_address", "is_show_home_form": "1", "is_show_home_list_search": "2", "is_show_admin_list_search": "1"}, {"name": "联系人", "rule": "unlimited", "regex": null, "remark": "联系人", "foreign": null, "isindex": "NOINDEX", "formtype": "text", "required": "required", "fieldtype": "string", "maxlength": "30", "foreign_key": null, "is_show_list": "1", "identification": "username", "is_show_home_form": "1", "is_show_home_list_search": "2", "is_show_admin_list_search": "1"}, {"name": "联系电话", "rule": "unlimited", "regex": null, "remark": "联系电话", "foreign": null, "isindex": "NOINDEX", "formtype": "text", "required": "required", "fieldtype": "string", "maxlength": "50", "foreign_key": null, "is_show_list": "1", "identification": "phone", "is_show_home_form": "1", "is_show_home_list_search": "2", "is_show_admin_list_search": "1"}, {"name": "邮箱", "rule": "unlimited", "regex": null, "remark": "邮箱", "foreign": null, "isindex": "NOINDEX", "formtype": "text", "required": "required", "fieldtype": "string", "maxlength": "100", "foreign_key": null, "is_show_list": "1", "identification": "email", "is_show_home_form": "1", "is_show_home_list_search": "2", "is_show_admin_list_search": "1"}, {"name": "是否开启留言", "rule": "unlimited", "datas": "[{\\"value\\":\\"1\\",\\"name\\":\\"开启\\"},{\\"value\\":\\"2\\",\\"name\\":\\"关闭\\"}]", "regex": null, "remark": "是否开启留言", "foreign": null, "isindex": "NOINDEX", "formtype": "radio", "required": "required", "fieldtype": "tinyInteger", "maxlength": "4", "foreign_key": null, "is_show_list": "1", "identification": "is_open_leave", "is_show_home_form": "2", "is_show_home_list_search": "2", "is_show_admin_list_search": "1"}]',
     'icon' => 'icon-pencil7',
     'supermodel' => NULL,
     'remark' => '联系我们',
     'form_template' => NULL,
     'list_template' => 'contacts',
     'detail_template' => 'detail',
     'data_source' => 'local',
     'data_source_api_url' => '',
     'data_source_api_url_detail' => NULL,
     'data_source_field_mapping' => NULL,
     'page_num' => 20,
     'list_page_template' => 'center',
     'home_page_title' => '联系我们',
     'home_page_describe' => '选择适合所显示内容类型的布局。列表式布局是为坐着的人设计的，因为用户的行为往往具有非常独特的目的。另一方面，网格视图用于站立。它是为那些不安和好奇的人准备的。',
     'show_home_page' => NULL,
     'home_page_num' => NULL,
     'created_at' => '2024-01-15 15:44:31',
     'updated_at' => '2024-01-19 16:22:17',
  ),
  2 => 
  array(
     'id' => 3,
     'name' => '留言',
     'identification' => 'feedback',
     'access_identification' => 'feedback',
     'menuname' => '内容管理',
     'module' => NULL,
     'fields' => '[{"name": "全名", "rule": "unlimited", "regex": null, "remark": "全名", "foreign": null, "isindex": "NOINDEX", "formtype": "text", "required": "required", "fieldtype": "string", "maxlength": "255", "foreign_key": null, "is_show_list": "1", "identification": "full_name", "is_show_home_form": "1", "is_show_home_list_search": "2", "is_show_admin_list_search": "1"}, {"name": "邮箱", "rule": "unlimited", "regex": null, "remark": "邮箱", "foreign": null, "isindex": "NOINDEX", "formtype": "text", "required": "required", "fieldtype": "string", "maxlength": "50", "foreign_key": null, "is_show_list": "1", "identification": "email", "is_show_home_form": "1", "is_show_home_list_search": "2", "is_show_admin_list_search": "1"}, {"name": "公司", "rule": "unlimited", "regex": null, "remark": "公司", "foreign": null, "isindex": "NOINDEX", "formtype": "text", "required": null, "fieldtype": "string", "maxlength": "255", "foreign_key": null, "is_show_list": "1", "identification": "company", "is_show_home_form": "1", "is_show_home_list_search": "2", "is_show_admin_list_search": "1"}, {"name": "网站", "rule": "unlimited", "regex": null, "remark": "网站", "foreign": null, "isindex": "NOINDEX", "formtype": "text", "required": null, "fieldtype": "string", "maxlength": "255", "foreign_key": null, "is_show_list": "1", "identification": "website", "is_show_home_form": "1", "is_show_home_list_search": "2", "is_show_admin_list_search": "1"}, {"name": "留言内容", "rule": "unlimited", "regex": null, "remark": "留言内容", "foreign": null, "isindex": "NOINDEX", "formtype": "textarea", "required": "required", "fieldtype": "text", "maxlength": 0, "foreign_key": null, "is_show_list": "2", "identification": "content", "is_show_home_form": "1", "is_show_home_list_search": "2", "is_show_admin_list_search": "2"}]',
     'icon' => 'icon-pencil7',
     'supermodel' => NULL,
     'remark' => '留言表',
     'form_template' => NULL,
     'list_template' => 'feedback',
     'detail_template' => 'detail',
     'data_source' => 'local',
     'data_source_api_url' => '',
     'data_source_api_url_detail' => NULL,
     'data_source_field_mapping' => NULL,
     'page_num' => 20,
     'list_page_template' => 'center',
     'home_page_title' => NULL,
     'home_page_describe' => NULL,
     'show_home_page' => NULL,
     'home_page_num' => NULL,
     'created_at' => '2024-01-16 12:08:25',
     'updated_at' => '2024-02-17 05:58:11',
  ),
  3 => 
  array(
     'id' => 4,
     'name' => '新闻分类',
     'identification' => 'news_cate',
     'access_identification' => 'news_cate',
     'menuname' => '内容管理',
     'module' => NULL,
     'fields' => '[{"name": "分类名称", "rule": "unlimited", "datas": "[]", "regex": null, "remark": "分类名称", "foreign": null, "isindex": "NOINDEX", "formtype": "text", "required": "required", "fieldtype": "string", "maxlength": "20", "foreign_key": null, "is_show_list": "1", "identification": "cate_name", "is_show_home_form": "1", "is_show_home_list_search": "2", "is_show_admin_list_search": "2"}]',
     'icon' => 'icon-list-numbered',
     'supermodel' => NULL,
     'remark' => '新闻分类',
     'form_template' => NULL,
     'list_template' => 'list',
     'detail_template' => 'detail',
     'data_source' => 'local',
     'data_source_api_url' => '',
     'data_source_api_url_detail' => '',
     'data_source_field_mapping' => NULL,
     'page_num' => 20,
     'list_page_template' => 'center',
     'home_page_title' => NULL,
     'home_page_describe' => NULL,
     'show_home_page' => 'no',
     'home_page_num' => NULL,
     'created_at' => '2024-01-18 17:34:07',
     'updated_at' => '2024-02-24 09:45:44',
  ),
  4 => 
  array(
     'id' => 5,
     'name' => '新闻列表',
     'identification' => 'news',
     'access_identification' => 'news',
     'menuname' => '内容管理',
     'module' => NULL,
     'fields' => '[{"name": "封面", "rule": "unlimited", "datas": "[]", "regex": null, "remark": "封面", "foreign": null, "isindex": "NOINDEX", "formtype": "image", "required": null, "fieldtype": "string", "maxlength": "255", "foreign_key": null, "is_show_list": "1", "identification": "cover", "is_show_home_form": "2", "is_show_home_list_search": "2", "is_show_admin_list_search": "2"}, {"name": "分类id", "rule": "unlimited", "datas": "[]", "regex": null, "remark": "分类id", "foreign": "news_cate-4", "isindex": "NOINDEX", "formtype": "select", "required": "required", "fieldtype": "tinyInteger", "maxlength": "11", "foreign_key": "cate_name", "is_show_list": "1", "identification": "pid", "is_show_home_form": "2", "is_show_home_list_search": "2", "is_show_admin_list_search": "2"}, {"name": "标题", "rule": "unlimited", "datas": "[]", "regex": null, "remark": "标题", "foreign": null, "isindex": "NOINDEX", "formtype": "text", "required": "required", "fieldtype": "string", "maxlength": "100", "foreign_key": null, "is_show_list": "1", "identification": "title", "is_show_home_form": "1", "is_show_home_list_search": "2", "is_show_admin_list_search": "2"}, {"name": "内容", "rule": "unlimited", "datas": "[]", "regex": null, "remark": "内容", "foreign": null, "isindex": "NOINDEX", "formtype": "editor", "required": "required", "fieldtype": "longText", "maxlength": "0", "foreign_key": null, "is_show_list": "2", "identification": "content", "is_show_home_form": "1", "is_show_home_list_search": "2", "is_show_admin_list_search": "2"}]',
     'icon' => 'icon-list-numbered',
     'supermodel' => 4,
     'remark' => '新闻列表',
     'form_template' => NULL,
     'list_template' => 'titleContentImage',
     'detail_template' => 'detailLeftList',
     'data_source' => 'local',
     'data_source_api_url' => '',
     'data_source_api_url_detail' => '',
     'data_source_field_mapping' => NULL,
     'page_num' => 20,
     'list_page_template' => 'center',
     'home_page_title' => NULL,
     'home_page_describe' => NULL,
     'show_home_page' => 'yes',
     'home_page_num' => 8,
     'created_at' => '2024-01-19 15:23:00',
     'updated_at' => '2024-02-24 09:48:38',
  ),
  5 => 
  array(
     'id' => 6,
     'name' => '发展历程',
     'identification' => 'milestone',
     'access_identification' => 'milestone',
     'menuname' => '内容管理',
     'module' => NULL,
     'fields' => '[{"name": "标题", "rule": "unlimited", "datas": "[]", "regex": null, "remark": "标题", "foreign": null, "isindex": "NOINDEX", "formtype": "text", "required": "required", "fieldtype": "string", "maxlength": "100", "foreign_key": null, "is_show_list": "1", "identification": "title", "is_show_home_form": "1", "is_show_home_list_search": "2", "is_show_admin_list_search": "2"}, {"name": "时间", "rule": "unlimited", "datas": "[]", "regex": null, "remark": "时间", "foreign": null, "isindex": "NOINDEX", "formtype": "date", "required": "required", "fieldtype": "date", "maxlength": "0", "foreign_key": null, "is_show_list": "1", "identification": "date", "is_show_home_form": "2", "is_show_home_list_search": "2", "is_show_admin_list_search": "2"}, {"name": "内容", "rule": "unlimited", "datas": "[]", "regex": null, "remark": "内容", "foreign": null, "isindex": "NOINDEX", "formtype": "editor", "required": "required", "fieldtype": "longText", "maxlength": "0", "foreign_key": null, "is_show_list": "2", "identification": "content", "is_show_home_form": "1", "is_show_home_list_search": "2", "is_show_admin_list_search": "2"}]',
     'icon' => 'icon-clipboard2',
     'supermodel' => NULL,
     'remark' => '发展历程',
     'form_template' => NULL,
     'list_template' => 'milestone',
     'detail_template' => 'detail',
     'data_source' => 'local',
     'data_source_api_url' => '',
     'data_source_api_url_detail' => '',
     'data_source_field_mapping' => NULL,
     'page_num' => 20,
     'list_page_template' => 'center',
     'home_page_title' => '发展历程',
     'home_page_describe' => 'Here you can find all the new features we have implemented. This page is also included in the package so you can use it to build the changelog of your awesome project.',
     'show_home_page' => 'yes',
     'home_page_num' => 3,
     'created_at' => '2024-01-19 16:29:45',
     'updated_at' => '2024-02-24 09:48:58',
  ),
  6 => 
  array(
     'id' => 7,
     'name' => '协议分类',
     'identification' => 'agreement_cate',
     'access_identification' => 'agreementCate',
     'menuname' => '内容管理',
     'module' => NULL,
     'fields' => '[{"name": "协议名称", "rule": "unlimited", "datas": "[]", "regex": null, "remark": "协议名称", "foreign": null, "isindex": "NOINDEX", "formtype": "text", "required": "required", "fieldtype": "string", "maxlength": "100", "foreign_key": null, "is_show_list": "1", "identification": "cate_name", "is_show_home_form": "1", "is_show_home_list_search": "2", "is_show_admin_list_search": "2"}]',
     'icon' => 'icon-grid2',
     'supermodel' => NULL,
     'remark' => '协议分类',
     'form_template' => NULL,
     'list_template' => 'list',
     'detail_template' => 'detail',
     'data_source' => 'local',
     'data_source_api_url' => NULL,
     'data_source_api_url_detail' => NULL,
     'data_source_field_mapping' => NULL,
     'page_num' => 20,
     'list_page_template' => 'center',
     'home_page_title' => NULL,
     'home_page_describe' => NULL,
     'show_home_page' => 'no',
     'home_page_num' => NULL,
     'created_at' => '2024-03-06 09:43:48',
     'updated_at' => '2024-03-06 09:50:33',
  ),
  7 => 
  array(
     'id' => 8,
     'name' => '协议列表',
     'identification' => 'agreement',
     'access_identification' => 'agreement',
     'menuname' => '内容管理',
     'module' => NULL,
     'fields' => '[{"name": "协议分类", "rule": "unlimited", "datas": "[]", "regex": null, "remark": "协议分类", "foreign": "agreement_cate-7", "isindex": "NOINDEX", "formtype": "select", "required": "required", "fieldtype": "integer", "maxlength": "11", "foreign_key": "cate_name", "is_show_list": "1", "identification": "cate_id", "is_show_home_form": "1", "is_show_home_list_search": "2", "is_show_admin_list_search": "2"}, {"name": "协议名称", "rule": "unlimited", "datas": "[]", "regex": null, "remark": "协议名称", "foreign": null, "isindex": "NOINDEX", "formtype": "text", "required": "required", "fieldtype": "string", "maxlength": "255", "foreign_key": null, "is_show_list": "1", "identification": "name", "is_show_home_form": "1", "is_show_home_list_search": "2", "is_show_admin_list_search": "2"}, {"name": "协议内容", "rule": "unlimited", "datas": "[]", "regex": null, "remark": "协议内容", "foreign": null, "isindex": "NOINDEX", "formtype": "editor", "required": "required", "fieldtype": "text", "maxlength": "0", "foreign_key": null, "is_show_list": "2", "identification": "content", "is_show_home_form": "1", "is_show_home_list_search": "2", "is_show_admin_list_search": "2"}, {"name": "SEO关键字", "rule": "unlimited", "datas": "[]", "regex": null, "remark": "SEO关键字", "foreign": null, "isindex": "NOINDEX", "formtype": "text", "required": "required", "fieldtype": "string", "maxlength": "255", "foreign_key": null, "is_show_list": "2", "identification": "seo_keywords", "is_show_home_form": "1", "is_show_home_list_search": "2", "is_show_admin_list_search": "2"}, {"name": "SEO描述", "rule": "unlimited", "datas": "[]", "regex": null, "remark": "SEO描述", "foreign": null, "isindex": "NOINDEX", "formtype": "text", "required": "required", "fieldtype": "string", "maxlength": "255", "foreign_key": null, "is_show_list": "2", "identification": "seo_description", "is_show_home_form": "1", "is_show_home_list_search": "2", "is_show_admin_list_search": "2"}, {"name": "状态", "rule": "unlimited", "datas": "[{\\"value\\":\\"1\\",\\"name\\":\\"启用\\"},{\\"value\\":\\"2\\",\\"name\\":\\"禁用\\"}]", "regex": null, "remark": "状态", "foreign": null, "isindex": "NOINDEX", "formtype": "radio", "required": "required", "fieldtype": "tinyInteger", "maxlength": "1", "foreign_key": null, "is_show_list": "1", "identification": "status", "is_show_home_form": "1", "is_show_home_list_search": "2", "is_show_admin_list_search": "2"}]',
     'icon' => 'icon-grid2',
     'supermodel' => 7,
     'remark' => '协议列表',
     'form_template' => NULL,
     'list_template' => 'list',
     'detail_template' => 'detail',
     'data_source' => 'local',
     'data_source_api_url' => NULL,
     'data_source_api_url_detail' => NULL,
     'data_source_field_mapping' => NULL,
     'page_num' => 20,
     'list_page_template' => 'center',
     'home_page_title' => NULL,
     'home_page_describe' => NULL,
     'show_home_page' => NULL,
     'home_page_num' => NULL,
     'created_at' => '2024-03-06 09:44:32',
     'updated_at' => '2024-03-06 11:54:37',
  ),
);

        \DB::table('module_formtools_models')->insert($this->normalizeRows($legacyRows));


    }

    private function normalizeRows(array $legacyRows): array
    {
        return array_map(function (array $row) {
            $adminConfig = FormTemplateResolver::normalizeAdminConfig([
                'form_template' => $row['form_template'] ?? '',
            ]);

            $homeConfig = FormTemplateResolver::normalizeHomeConfig([
                'list_template' => $row['list_template'] ?? '',
                'custom_list_template' => $row['custom_list_template'] ?? '',
                'detail_template' => $row['detail_template'] ?? '',
                'custom_detail_template' => $row['custom_detail_template'] ?? '',
                'page_num' => $row['page_num'] ?? 20,
                'list_page_template' => $row['list_page_template'] ?? 'center',
                'detail_page_title' => '',
                'detail_page_describe' => '',
                'detail_page_show_type' => 'color',
                'detail_page_bg_color' => '#ffffff',
                'detail_page_bg_img' => '',
                'home_page_title' => $row['home_page_title'] ?? '',
                'home_page_title_size' => '',
                'home_page_title_color' => '#222222',
                'home_page_describe' => $row['home_page_describe'] ?? '',
                'home_page_describe_size' => '',
                'home_page_describe_color' => '#666666',
                'show_home_type' => 'color',
                'home_page_bg_color' => '#ffffff',
                'home_page_bg_img' => $row['home_page_bg_img'] ?? '',
            ]);

            $otherConfig = [
                'data_source' => $row['data_source'] ?? 'local',
                'data_source_api_url' => $row['data_source_api_url'] ?? '',
                'data_source_api_url_detail' => $row['data_source_api_url_detail'] ?? '',
                'data_source_field_mapping' => $row['data_source_field_mapping'] ?? '',
            ];

            return [
                'id' => $row['id'],
                'name' => $row['name'],
                'identification' => $row['identification'],
                'access_identification' => $row['access_identification'],
                'menuname' => $row['menuname'] ?? '',
                'module' => $row['module'] ?? '',
                'fields' => $row['fields'],
                'icon' => $row['icon'] ?? 'icon-list-numbered',
                'type' => $row['type'] ?? $this->guessModelType($row['identification'] ?? ''),
                'supermodel' => $row['supermodel'] ?? null,
                'remark' => $row['remark'] ?? '',
                'admin_config' => json_encode($adminConfig, JSON_UNESCAPED_UNICODE),
                'home_config' => json_encode($homeConfig, JSON_UNESCAPED_UNICODE),
                'home_seo_config' => json_encode([
                    'title' => '',
                    'keyword' => '',
                    'describe' => '',
                ], JSON_UNESCAPED_UNICODE),
                'home_seo_detail_config' => json_encode([
                    'title' => '',
                    'keyword' => '',
                    'describe' => '',
                ], JSON_UNESCAPED_UNICODE),
                'other_config' => json_encode($otherConfig, JSON_UNESCAPED_UNICODE),
                'show_home_page' => $row['show_home_page'] ?? 'no',
                'home_page_num' => $row['home_page_num'] ?? null,
                'home_page_sort' => $row['home_page_sort'] ?? 0,
                'created_at' => $row['created_at'] ?? null,
                'updated_at' => $row['updated_at'] ?? null,
            ];
        }, $legacyRows);
    }

    private function guessModelType(string $identification): string
    {
        return in_array($identification, ['about_us', 'contact_us'], true) ? 'single' : 'multi';
    }
}
