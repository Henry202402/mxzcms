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
     'type' => 'website',
     'key' => 'default_currency',
     'value' => '1',
  ),
  1 => 
  array(
     'module' => 'Main',
     'type' => 'website',
     'key' => 'default_language',
     'value' => 'zh',
  ),
  2 => 
  array(
     'module' => 'Main',
     'type' => 'website',
     'key' => 'multilingual',
     'value' => '1',
  ),
  3 => 
  array(
     'module' => 'Main',
     'type' => 'website',
     'key' => 'multi_currency',
     'value' => '1',
  ),
  4 => 
  array(
     'module' => 'Main',
     'type' => 'website',
     'key' => 'webicon',
     'value' => 'website/webicon.ico',
  ),
  5 => 
  array(
     'module' => 'Main',
     'type' => 'website',
     'key' => 'weblogo',
     'value' => 'website/logo.png',
  ),
  6 => 
  array(
     'module' => 'Main',
     'type' => 'website',
     'key' => 'website_desc',
     'value' => '梦小记CMS是一款基于PHP laravel框架的内容管理系统，采用低耦合、模块化设计思想，适用各行各业使用。感谢广大企业、个人、开发者的支持。',
  ),
  7 => 
  array(
     'module' => 'Main',
     'type' => 'website',
     'key' => 'website_keys',
     'value' => '梦小记CMS,免费cms,开源cms',
  ),
  8 => 
  array(
     'module' => 'Main',
     'type' => 'website',
     'key' => 'website_name',
     'value' => '梦小记',
  ),
  9 => 
  array(
     'module' => 'Main',
     'type' => 'website',
     'key' => 'website_open_reg',
     'value' => '1',
  ),
  10 => 
  array(
     'module' => 'Main',
     'type' => 'website',
     'key' => 'website_reg_rqstd',
     'value' => 'phone,email',
  ),
  11 => 
  array(
     'module' => 'Main',
     'type' => 'website',
     'key' => 'website_status',
     'value' => '1',
  ),
  12 => 
  array(
     'module' => 'Main',
     'type' => 'website',
     'key' => 'website_status_when',
     'value' => '正常啊',
  ),
  13 => 
  array(
     'module' => 'Main',
     'type' => 'website',
     'key' => 'admin_page_count',
     'value' => '10',
  ),
  14 => 
  array(
     'module' => 'Main',
     'type' => 'website',
     'key' => 'Useofcloud',
     'value' => 'false',
  ),
  15 => 
  array(
     'module' => 'Main',
     'type' => 'safe',
     'key' => 'limit_count',
     'value' => '5',
  ),
  16 => 
  array(
     'module' => 'Main',
     'type' => 'safe',
     'key' => 'limit_time',
     'value' => '30',
  ),
  17 => 
  array(
     'module' => 'Main',
     'type' => 'safe',
     'key' => 'filter_strings',
     'value' => 'admin',
  ),
  18 => 
  array(
     'module' => 'Main',
     'type' => 'safe',
     'key' => 'blacklist_ip',
     'value' => '127.0.0.2,127.0.0.3',
  ),
  19 => 
  array(
     'module' => 'Main',
     'type' => 'safe',
     'key' => 'admin_login_code',
     'value' => '0',
  ),
  20 => 
  array(
     'module' => 'Main',
     'type' => 'safe',
     'key' => 'home_submit_code',
     'value' => '0',
  ),
  21 => 
  array(
     'module' => 'Main',
     'type' => 'safe',
     'key' => 'admin_login_entrance',
     'value' => NULL,
  ),
  22 => 
  array(
     'module' => 'Main',
     'type' => 'upload',
     'key' => 'upload_status',
     'value' => '1',
  ),
  23 => 
  array(
     'module' => 'Main',
     'type' => 'upload',
     'key' => 'upload_limit',
     'value' => '500000',
  ),
  24 => 
  array(
     'module' => 'Main',
     'type' => 'upload',
     'key' => 'upload_format',
     'value' => 'png,jpeg,jpg,gif,zip,rar,pdf,doc,txt,xls,avi,mp3,mp4',
  ),
  25 => 
  array(
     'module' => 'Main',
     'type' => 'upload',
     'key' => 'upload_driver',
     'value' => 'local',
  ),
  26 => 
  array(
     'module' => 'Main',
     'type' => 'upload',
     'key' => 'thumb_auto',
     'value' => '0',
  ),
  27 => 
  array(
     'module' => 'Main',
     'type' => 'upload',
     'key' => 'thumb_method',
     'value' => 'message',
  ),
  28 => 
  array(
     'module' => 'Main',
     'type' => 'upload',
     'key' => 'watermark_type',
     'value' => 'text',
  ),
  29 => 
  array(
     'module' => 'Main',
     'type' => 'upload',
     'key' => 'watermark_position',
     'value' => 'center',
  ),
  30 => 
  array(
     'module' => 'Main',
     'type' => 'upload',
     'key' => 'watermark_text',
     'value' => '梦小记CMS',
  ),
  31 => 
  array(
     'module' => 'Main',
     'type' => 'upload',
     'key' => 'watermark_text_size',
     'value' => '19',
  ),
  32 => 
  array(
     'module' => 'Main',
     'type' => 'upload',
     'key' => 'watermark_text_angle',
     'value' => '180',
  ),
  33 => 
  array(
     'module' => 'Main',
     'type' => 'upload',
     'key' => 'watermark_text_color',
     'value' => '#000000',
  ),
  34 => 
  array(
     'module' => 'Main',
     'type' => 'upload',
     'key' => 'watermark_upload_format',
     'value' => 'png,jpeg,jpg',
  ),
  35 => 
  array(
     'module' => 'Main',
     'type' => 'sms',
     'key' => 'sms_driver',
     'value' => '',
  ),
  36 => 
  array(
     'module' => 'Main',
     'type' => 'pay',
     'key' => 'pay_driver',
     'value' => '',
  ),
  37 => 
  array(
     'module' => 'Main',
     'type' => 'theme',
     'key' => 'logo_animated',
     'value' => NULL,
  ),
  38 => 
  array(
     'module' => 'Main',
     'type' => 'theme',
     'key' => 'global_bgcolor',
     'value' => 'red',
  ),
  39 => 
  array(
     'module' => 'Main',
     'type' => 'theme',
     'key' => 'nav_bgcolor',
     'value' => NULL,
  ),
  40 => 
  array(
     'module' => 'Main',
     'type' => 'theme',
     'key' => 'nav_position',
     'value' => 'static',
  ),
  41 => 
  array(
     'module' => 'Main',
     'type' => 'theme',
     'key' => 'page_width',
     'value' => 'container',
  ),
  42 => 
  array(
     'module' => 'Main',
     'type' => 'theme',
     'key' => 'home_screen',
     'value' => 'on',
  ),
  43 => 
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
  44 => 
  array(
     'module' => 'Main',
     'type' => 'theme',
     'key' => 'home_screen_image',
     'value' => '',
  ),
  45 => 
  array(
     'module' => 'Main',
     'type' => 'theme',
     'key' => 'nav_color',
     'value' => NULL,
  ),
  46 => 
  array(
     'module' => 'Main',
     'type' => 'theme',
     'key' => 'preloader',
     'value' => 'off',
  ),
  47 => 
  array(
     'module' => 'Main',
     'type' => 'theme',
     'key' => 'global_font',
     'value' => NULL,
  ),
  48 => 
  array(
     'module' => 'Main',
     'type' => 'theme',
     'key' => 'head_codes',
     'value' => '1',
  ),
  49 => 
  array(
     'module' => 'Main',
     'type' => 'theme',
     'key' => 'foot_codes',
     'value' => '2',
  ),
  50 => 
  array(
     'module' => 'Main',
     'type' => 'safe',
     'key' => 'password_key',
     'value' => 'mxz_',
  ),
  51 => 
  array(
     'module' => 'Main',
     'type' => 'editor',
     'key' => 'editor_driver',
     'value' => 'Editor',
  ),
  52 => 
  array(
     'module' => 'Main',
     'type' => 'website',
     'key' => 'website_open_login',
     'value' => '0',
  ),
  53 => 
  array(
     'module' => 'Main',
     'type' => 'website',
     'key' => 'open_captcha',
     'value' => '0',
  ),
  54 => 
  array(
     'module' => 'Main',
     'type' => 'website',
     'key' => 'website_reg_agreement',
     'value' => NULL,
  ),
));


    }
}
