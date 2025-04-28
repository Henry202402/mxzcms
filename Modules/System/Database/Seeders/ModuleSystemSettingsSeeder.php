<?php

namespace Modules\System\Database\Seeders;

use Illuminate\Database\Seeder;

class ModuleSystemSettingsSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('module_system_settings')->delete();

        \DB::table('module_system_settings')->insert(array (
  0 =>
  array(
     'module' => 'Main',
     'type' => 'safe',
     'key' => 'limit_count',
     'value' => '5',
  ),
  1 =>
  array(
     'module' => 'Main',
     'type' => 'safe',
     'key' => 'limit_time',
     'value' => '30',
  ),
  2 =>
  array(
     'module' => 'Main',
     'type' => 'safe',
     'key' => 'filter_strings',
     'value' => 'admin',
  ),
  3 =>
  array(
     'module' => 'Main',
     'type' => 'safe',
     'key' => 'blacklist_ip',
     'value' => '127.0.0.2,127.0.0.3',
  ),
  4 =>
  array(
     'module' => 'Main',
     'type' => 'safe',
     'key' => 'admin_login_code',
     'value' => '0',
  ),
  5 =>
  array(
     'module' => 'Main',
     'type' => 'safe',
     'key' => 'home_submit_code',
     'value' => '0',
  ),
  6 =>
  array(
     'module' => 'Main',
     'type' => 'safe',
     'key' => 'admin_login_entrance',
     'value' => NULL,
  ),
  7 =>
  array(
     'module' => 'Main',
     'type' => 'upload',
     'key' => 'upload_status',
     'value' => '1',
  ),
  8 =>
  array(
     'module' => 'Main',
     'type' => 'upload',
     'key' => 'upload_limit',
     'value' => '500000',
  ),
  9 =>
  array(
     'module' => 'Main',
     'type' => 'upload',
     'key' => 'upload_format',
     'value' => 'png,jpeg,jpg,gif,zip,rar,pdf,doc,txt,xls,avi,mp3,mp4',
  ),
  10 =>
  array(
     'module' => 'Main',
     'type' => 'upload',
     'key' => 'upload_driver',
     'value' => 'local',
  ),
  11 =>
  array(
     'module' => 'Main',
     'type' => 'upload',
     'key' => 'thumb_auto',
     'value' => '0',
  ),
  12 =>
  array(
     'module' => 'Main',
     'type' => 'upload',
     'key' => 'thumb_method',
     'value' => 'message',
  ),
  13 =>
  array(
     'module' => 'Main',
     'type' => 'upload',
     'key' => 'watermark_type',
     'value' => 'text',
  ),
  14 =>
  array(
     'module' => 'Main',
     'type' => 'upload',
     'key' => 'watermark_position',
     'value' => 'center',
  ),
  15 =>
  array(
     'module' => 'Main',
     'type' => 'upload',
     'key' => 'watermark_text',
     'value' => '梦小记CMS',
  ),
  16 =>
  array(
     'module' => 'Main',
     'type' => 'upload',
     'key' => 'watermark_text_size',
     'value' => '19',
  ),
  17 =>
  array(
     'module' => 'Main',
     'type' => 'upload',
     'key' => 'watermark_text_angle',
     'value' => '180',
  ),
  18 =>
  array(
     'module' => 'Main',
     'type' => 'upload',
     'key' => 'watermark_text_color',
     'value' => '#000000',
  ),
  19 =>
  array(
     'module' => 'Main',
     'type' => 'upload',
     'key' => 'watermark_upload_format',
     'value' => 'png,jpeg,jpg',
  ),
  20 =>
  array(
     'module' => 'Main',
     'type' => 'sms',
     'key' => 'sms_driver',
     'value' => '',
  ),
  21 =>
  array(
     'module' => 'Main',
     'type' => 'pay',
     'key' => 'pay_driver',
     'value' => '',
  ),
  22 =>
  array(
     'module' => 'Main',
     'type' => 'theme',
     'key' => 'logo_animated',
     'value' => NULL,
  ),
  23 =>
  array(
     'module' => 'Main',
     'type' => 'theme',
     'key' => 'global_bgcolor',
     'value' => NULL,
  ),
  24 =>
  array(
     'module' => 'Main',
     'type' => 'theme',
     'key' => 'nav_bgcolor',
     'value' => NULL,
  ),
  25 =>
  array(
     'module' => 'Main',
     'type' => 'theme',
     'key' => 'nav_position',
     'value' => 'static',
  ),
  26 =>
  array(
     'module' => 'Main',
     'type' => 'theme',
     'key' => 'page_width',
     'value' => 'container',
  ),
  27 =>
  array(
     'module' => 'Main',
     'type' => 'theme',
     'key' => 'home_screen',
     'value' => 'on',
  ),
  28 =>
  array(
     'module' => 'Main',
     'type' => 'theme',
     'key' => 'home_screen_code',
     'value' => '<div class="header-back-container">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="page-info helper center">
                                <h1 class="page-title">嗨，别来无恙！</h1>
                                <h2 class="page-description">听闻远方有你，你若安好，便是晴天</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>',
  ),
  29 =>
  array(
     'module' => 'Main',
     'type' => 'theme',
     'key' => 'home_screen_image',
     'value' => NULL,
  ),
  30 =>
  array(
     'module' => 'Main',
     'type' => 'theme',
     'key' => 'nav_color',
     'value' => NULL,
  ),
  31 =>
  array(
     'module' => 'Main',
     'type' => 'theme',
     'key' => 'preloader',
     'value' => 'off',
  ),
  32 =>
  array(
     'module' => 'Main',
     'type' => 'theme',
     'key' => 'global_font',
     'value' => NULL,
  ),
  33 =>
  array(
     'module' => 'Main',
     'type' => 'theme',
     'key' => 'head_codes',
     'value' => NULL,
  ),
  34 =>
  array(
     'module' => 'Main',
     'type' => 'theme',
     'key' => 'foot_codes',
     'value' => NULL,
  ),
  35 =>
  array(
     'module' => 'Main',
     'type' => 'safe',
     'key' => 'password_key',
     'value' => 'mxz_',
  ),
  36 =>
  array(
     'module' => 'Main',
     'type' => 'editor',
     'key' => 'editor_driver',
     'value' => 'Editor',
  ),
  37 =>
  array(
     'module' => 'Main',
     'type' => 'seo',
     'key' => 'seo_title',
     'value' => '{{model_name}} 列表',
  ),
  38 =>
  array(
     'module' => 'Main',
     'type' => 'seo',
     'key' => 'seo_keywords',
     'value' => '{{model_name}}',
  ),
  39 =>
  array(
     'module' => 'Main',
     'type' => 'seo',
     'key' => 'seo_website_desc',
     'value' => NULL,
  ),
  40 =>
  array(
     'module' => 'Main',
     'type' => 'seo',
     'key' => 'seo_limit_domain',
     'value' => NULL,
  ),
  41 =>
  array(
     'module' => 'Main',
     'type' => 'seo',
     'key' => 'seo_bot_keywords',
     'value' => 'bot
crawl
spider',
  ),
  42 =>
  array(
     'module' => 'Main',
     'type' => 'website',
     'key' => 'website_name',
     'value' => '梦小记CMS',
  ),
  43 =>
  array(
     'module' => 'Main',
     'type' => 'website',
     'key' => 'website_keys',
     'value' => '梦小记CMS,cms,免费cms,开源cms,简单cms',
  ),
  44 =>
  array(
     'module' => 'Main',
     'type' => 'website',
     'key' => 'website_desc',
     'value' => '梦小记CMS',
  ),
  45 =>
  array(
     'module' => 'Main',
     'type' => 'website',
     'key' => 'website_open_reg',
     'value' => '1',
  ),
  46 =>
  array(
     'module' => 'Main',
     'type' => 'website',
     'key' => 'website_reg_rqstd',
     'value' => 'phone,email',
  ),
  47 =>
  array(
     'module' => 'Main',
     'type' => 'website',
     'key' => 'open_captcha',
     'value' => '1',
  ),
  48 =>
  array(
     'module' => 'Main',
     'type' => 'website',
     'key' => 'multilingual',
     'value' => '1',
  ),
  49 =>
  array(
     'module' => 'Main',
     'type' => 'website',
     'key' => 'website_status',
     'value' => '1',
  ),
  50 =>
  array(
     'module' => 'Main',
     'type' => 'website',
     'key' => 'website_status_when',
     'value' => '正常的',
  ),
  51 =>
  array(
     'module' => 'Main',
     'type' => 'website',
     'key' => 'admin_page_count',
     'value' => '10',
  ),
  52 =>
  array(
     'module' => 'Main',
     'type' => 'website',
     'key' => 'weblogo',
     'value' => 'website/logo.png',
  ),
  53 =>
  array(
     'module' => 'Main',
     'type' => 'website',
     'key' => 'webicon',
     'value' => 'website/webicon.ico',
  ),
  54 =>
  array(
     'module' => 'Main',
     'type' => 'website',
     'key' => 'website_open_login',
     'value' => '1',
  ),
  55 =>
  array(
     'module' => 'Main',
     'type' => 'website',
     'key' => 'website_icp',
     'value' => '',
  ),
  56 =>
  array(
     'module' => 'Main',
     'type' => 'website',
     'key' => 'website_copyright',
     'value' => '',
  ),
  57 =>
  array(
     'module' => 'Main',
     'type' => 'website',
     'key' => 'use_of_cloud',
     'value' => '1',
  ),
  58 =>
  array(
     'module' => 'Main',
     'type' => 'website',
     'key' => 'website_reg_agreement',
     'value' => '',
  ),
  59 =>
  array(
     'module' => 'Main',
     'type' => 'captcha',
     'key' => 'captcha_driver',
     'value' => 'System',
  ),
  60 =>
  array(
     'module' => 'Main',
     'type' => 'seo',
     'key' => 'seo_title_detail',
     'value' => NULL,
  ),
  61 =>
  array(
     'module' => 'Main',
     'type' => 'seo',
     'key' => 'seo_keywords_detail',
     'value' => NULL,
  ),
  62 =>
  array(
     'module' => 'Main',
     'type' => 'seo',
     'key' => 'seo_website_desc_detail',
     'value' => NULL,
  ),
  63 =>
  array(
     'module' => 'Main',
     'type' => 'website',
     'key' => 'default_language',
     'value' => 'zh-CN',
  ),
  64 =>
  array(
     'module' => 'Main',
     'type' => 'website',
     'key' => 'member_weblogo',
     'value' => 'website/member_logo.png',
  ),
  65 =>
  array(
     'module' => 'Main',
     'type' => 'website',
     'key' => 'moduleHomeLock',
     'value' => NULL,
  ),
));


    }
}
