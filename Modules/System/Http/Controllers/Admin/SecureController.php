<?php

namespace Modules\System\Http\Controllers\Admin;

use Modules\Main\Models\Modules;
use Modules\System\Http\Controllers\Common\CommonController;
use Illuminate\Http\Request;
use Modules\System\Services\ServiceModel;
use Modules\System\Models\Setting;

class SecureController extends CommonController {

    public function __construct(Request $request) {
        parent::__construct($request);
    }

    //安全设置
    public function secureConfig(Request $request) {
        $pageData = [
            'subtitle' => '安全与工具',
            'title' => '安全设置',
            'controller' => 'Secure',
            'action' => 'secureConfig',
        ];


        return $this->adminView('secure.secureConfig', [
            'pageData' => $pageData,
        ]);
    }


    //上传设置
    public function uploadsConfig(Request $request) {
        $pageData = [
            'subtitle' => '安全与工具',
            'title' => '上传设置',
            'controller' => 'Secure',
            'action' => 'uploadsConfig',
        ];

        //获取当前所有的上传插件
        $plugin_upload_lists = hook("GetUploadPluginList");

        $local_start_plugin_list = [];
        foreach ($plugin_upload_lists as $plugin) {
            $local_start_plugin_list[] = $plugin;
        }
        return $this->adminView('secure.uploadsConfig', [
            'pageData' => $pageData,
            'plugin_list' => $local_start_plugin_list,
        ]);
    }

    //缓存配置
    public function cacheConfig(Request $request) {
        $pageData = [
            'subtitle' => '安全与工具',
            'title' => '缓存配置',
            'controller' => 'Secure',
            'action' => 'cacheConfig',
        ];


        return $this->adminView('secure.cacheConfig', [
            'pageData' => $pageData,
        ]);
    }

    //提交处理
    public function toolSubmit() {

        if ($this->request->ismethod('post')) {

            $all = $this->request->all();

            switch ($all['form']) {
                case "safe":

                    $env['COOKIE_NAME'] = $all['COOKIE_NAME'];
                    $env['SESSION_DOMAIN'] = $all['SESSION_DOMAIN'];

                    if ($all['SESSION_DRIVER']) {
                        $env['SESSION_DRIVER'] = $all['SESSION_DRIVER'];
                    }

                    if ($all['SESSION_LIFETIME']) {
                        $env['SESSION_LIFETIME'] = $all['SESSION_LIFETIME'];
                    }

                    if ($all['SESSION_ENCRYPT']) {
                        $env['SESSION_ENCRYPT'] = $all['SESSION_ENCRYPT'];
                    }


                    $settings = [];
                    $in_database = [
                        'limit_count',
                        'limit_time',
                        'filter_strings',
                        'blacklist_ip',
                        'admin_login_entrance',
                        'password_key',
                        'admin_login_code',
                        'home_submit_code'
                    ];
                    foreach ($all as $key => $value) {
                        if (in_array($key, $in_database)) {
                            array_push($settings, ['key' => $key, 'value' => $value]);
                        }
                    }

                    modifyEnv($env);
                    $setting = new ServiceModel;
                    $setting->updateBatch($settings);
                    //更新缓存
                    cacheGlobalSettings(2);
                    break;

                case "cache":

                    if ($all['CACHE_PREFIX']) {
                        $env['CACHE_PREFIX'] = $all['CACHE_PREFIX'];
                    }

                    if ($all['CACHE_DRIVER']) {
                        $env['CACHE_DRIVER'] = $all['CACHE_DRIVER'];
                    }

                    if ($all['REDIS_HOST']) {
                        $env['REDIS_HOST'] = $all['REDIS_HOST'];
                    }

                    if ($all['REDIS_PASSWORD']) {
                        $env['REDIS_PASSWORD'] = $all['REDIS_PASSWORD'];
                    }

                    if ($all['REDIS_PORT']) {
                        $env['REDIS_PORT'] = $all['REDIS_PORT'];
                    }

                    if ($all['MEMCACHED_HOST']) {
                        $env['MEMCACHED_HOST'] = $all['MEMCACHED_HOST'];
                    }

                    if ($all['MEMCACHED_USERNAME']) {
                        $env['MEMCACHED_USERNAME'] = $all['MEMCACHED_USERNAME'];
                    }

                    if ($all['MEMCACHED_PASSWORD']) {
                        $env['MEMCACHED_PASSWORD'] = $all['MEMCACHED_PASSWORD'];
                    }

                    if ($all['MEMCACHED_PORT']) {
                        $env['MEMCACHED_PORT'] = $all['MEMCACHED_PORT'];
                    }


                    modifyEnv($env);


                    break;

                case "upload":

                    $settings = [];
                    $in_database = [
                        'upload_status',
                        'upload_limit',
                        'upload_format',
                        'upload_driver',
                        'thumb_auto',
                        'thumb_method',
                        'watermark_type',
                        'watermark_position',
                        'watermark_text',
                        'watermark_text_size',
                        'watermark_text_angle',
                        'watermark_text_color',
                        'watermark_upload_format',
                        //'watermark_img',等等图片完成后开放

                    ];
                    foreach ($all as $key => $value) {
                        if (in_array($key, $in_database)) {
                            array_push($settings, ['key' => $key, 'value' => $value]);
                        }
                    }

                    $setting = new ServiceModel;
                    $setting->updateBatch($settings);

                    //更新缓存
                    cacheGlobalSettings(2);

                    break;

                default :

                    return ["status" => 40000, "msg" => "Method does not exist"];
            }


            return ["status" => 200, "msg" => "保存成功"];

        } else {
            return ["status" => 40000, "msg" => "method error,must post method"];
        }
    }



}
