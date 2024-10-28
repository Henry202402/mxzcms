<?php

namespace Modules\System\Database\Seeders;

use Illuminate\Database\Seeder;

class ModuleSystemSettingsTableSeeder extends Seeder
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
            array (
                'type' => 'website',
                'key' => 'default_currency',
                'value' => '1',
            ),
            1 => 
            array (
                'type' => 'website',
                'key' => 'default_language',
                'value' => 'zh',
            ),
            2 => 
            array (
                'type' => 'website',
                'key' => 'multilingual',
                'value' => '1',
            ),
            3 => 
            array (
                'type' => 'website',
                'key' => 'multi_currency',
                'value' => '1',
            ),
            4 => 
            array (
                'type' => 'website',
                'key' => 'webicon',
                'value' => 'website/webicon.ico',
            ),
            5 => 
            array (
                'type' => 'website',
                'key' => 'weblogo',
                'value' => 'website/logo.png',
            ),
            6 => 
            array (
                'type' => 'website',
                'key' => 'website_desc',
                'value' => 'CMS是一款基于laravel开发框架的cms内容管理系统，采用低耦合、模块化设计思想，适用各行各业使用。感谢广大企业、个人、开发者的支持。',
            ),
            7 => 
            array (
                'type' => 'website',
                'key' => 'website_keys',
                'value' => 'CMS,cms,免费cms,开源cms,简单cms',
            ),
            8 => 
            array (
                'type' => 'website',
                'key' => 'website_name',
                'value' => 'CMS内容管理系统，开源CMS',
            ),
            9 => 
            array (
                'type' => 'website',
                'key' => 'website_open_reg',
                'value' => '1',
            ),
            10 => 
            array (
                'type' => 'website',
                'key' => 'website_reg_rqstd',
                'value' => 'phone,email',
            ),
            11 => 
            array (
                'type' => 'website',
                'key' => 'website_status',
                'value' => '1',
            ),
            12 => 
            array (
                'type' => 'website',
                'key' => 'website_status_when',
                'value' => '正常啊',
            ),
            13 => 
            array (
                'type' => 'website',
                'key' => 'admin_page_count',
                'value' => '10',
            ),
            14 => 
            array (
                'type' => 'website',
                'key' => 'Useofcloud',
                'value' => 'false',
            ),
            15 => 
            array (
                'type' => 'third_party',
                'key' => 'head_codes',
                'value' => '1',
            ),
            16 => 
            array (
                'type' => 'third_party',
                'key' => 'foot_codes',
                'value' => '2',
            ),
            17 => 
            array (
                'type' => 'safe',
                'key' => 'limit_count',
                'value' => '5',
            ),
            18 => 
            array (
                'type' => 'safe',
                'key' => 'limit_time',
                'value' => '30',
            ),
            19 => 
            array (
                'type' => 'safe',
                'key' => 'filter_strings',
                'value' => 'admin',
            ),
            20 => 
            array (
                'type' => 'safe',
                'key' => 'blacklist_ip',
                'value' => '127.0.0.2,127.0.0.3',
            ),
            21 => 
            array (
                'type' => 'safe',
                'key' => 'admin_login_code',
                'value' => '0',
            ),
            22 => 
            array (
                'type' => 'safe',
                'key' => 'home_submit_code',
                'value' => '0',
            ),
            23 => 
            array (
                'type' => 'safe',
                'key' => 'admin_login_entrance',
                'value' => '',
            ),
            24 => 
            array (
                'type' => 'upload',
                'key' => 'upload_status',
                'value' => '1',
            ),
            25 => 
            array (
                'type' => 'upload',
                'key' => 'upload_limit',
                'value' => '500000',
            ),
            26 => 
            array (
                'type' => 'upload',
                'key' => 'upload_format',
                'value' => 'png,jpeg,jpg,gif,zip,rar,pdf,doc,txt,xls,avi,mp3,mp4',
            ),
            27 => 
            array (
                'type' => 'upload',
                'key' => 'upload_driver',
                'value' => 'local',
            ),
            28 => 
            array (
                'type' => 'upload',
                'key' => 'thumb_auto',
                'value' => '0',
            ),
            29 => 
            array (
                'type' => 'upload',
                'key' => 'thumb_method',
                'value' => 'message',
            ),
            30 => 
            array (
                'type' => 'upload',
                'key' => 'watermark_type',
                'value' => 'text',
            ),
            31 => 
            array (
                'type' => 'upload',
                'key' => 'watermark_position',
                'value' => 'center',
            ),
            32 => 
            array (
                'type' => 'upload',
                'key' => 'watermark_text',
                'value' => 'UnionCMS',
            ),
            33 => 
            array (
                'type' => 'upload',
                'key' => 'watermark_text_size',
                'value' => '19',
            ),
            34 => 
            array (
                'type' => 'upload',
                'key' => 'watermark_text_angle',
                'value' => '180',
            ),
            35 => 
            array (
                'type' => 'upload',
                'key' => 'watermark_text_color',
                'value' => '#000000',
            ),
            36 => 
            array (
                'type' => 'upload',
                'key' => 'watermark_upload_format',
                'value' => 'png,jpeg,jpg',
            ),
            37 => 
            array (
                'type' => 'sms',
                'key' => 'sms_driver',
                'value' => 'SMSTencent',
            ),
            38 => 
            array (
                'type' => 'pay',
                'key' => 'pay_driver',
                'value' => 'UnionPay',
            ),
        ));
        
        
    }
}