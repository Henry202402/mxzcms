<?php

namespace Modules\System\Http\Controllers\Admin;

use Illuminate\Support\Facades\Artisan;
use Modules\Main\Models\Modules;
use Modules\System\Http\Controllers\Common\CommonController;
use Illuminate\Http\Request;
use Modules\System\Http\Controllers\Common\SessionKey;
use Modules\System\Services\ServiceModel;

class BaseController extends CommonController {

    protected function resolveMaintenanceStatus(string $key, string $legacyKey = 'website_status', int $default = 1): int {
        $value = cacheGlobalSettingsByKey($key);
        if ($value !== null && $value !== '') {
            return (int) $value;
        }

        $legacyValue = cacheGlobalSettingsByKey($legacyKey);
        if ($legacyValue !== null && $legacyValue !== '') {
            return (int) $legacyValue;
        }

        return $default;
    }

    protected function resolveMaintenanceMessage(string $key, string $legacyKey = 'website_status_when'): string {
        $value = cacheGlobalSettingsByKey($key);
        if ($value !== null && $value !== '') {
            return (string) $value;
        }

        return (string) (cacheGlobalSettingsByKey($legacyKey) ?: '');
    }

    public function __construct(Request $request) {
        parent::__construct($request);
    }


    public function baseConfig(Request $request) {
        $currentType = (int) $request->get('type', 0);
        $pageData = [
            'title' => '基本配置',
            'controller' => 'Setting',
            'action' => 'baseConfig',
        ];

        //获取当前所有的SMS插件
        $local_start_plugin_list = [];
        $plugin_sms_list = hook("GetSMSPluginList");
        foreach ($plugin_sms_list as $plugin) {
            if ($plugin) {
                $local_start_plugin_list[] = $plugin;
            }
        }

        $plugin_editor_list2 = [];
        $plugin_editor_list = hook("GetEditorList");
        foreach ($plugin_editor_list as $plugin) {
            if ($plugin) {
                $plugin_editor_list2[] = $plugin;
            }
        }
        //协议列表
        $agreementResponse = hook('GetModelAgreement', ['moduleName' => 'Formtools']);
        $agreementList = [];
        if (!empty($agreementResponse[0]) && is_array($agreementResponse[0]) && !empty($agreementResponse[0]['agreementList'])) {
            $agreementList = $agreementResponse[0]['agreementList'];
        }

        //验证码列表
        $plugin_captcha_list2 = [];
        $plugin_captcha_list = hook("GetCaptchaList");
        foreach ($plugin_captcha_list as $captcha) {
            if ($captcha) $plugin_captcha_list2[] = $captcha;
        }

        //支付列表
        $plugin_pay_list2 = [];
        $plugin_pay_list = hook("GetPayPluginList");
        foreach ($plugin_pay_list as $pay) {
            if ($pay) $plugin_pay_list2[] = $pay;
        }

        //登录列表
        $plugin_login_list2 = [];
        $plugin_login_list = hook("GetLoginPluginList");
        foreach ($plugin_login_list as $login) {
            if ($login) $plugin_login_list2[] = $login;
        }

        //地图列表
        $plugin_map_list2 = [];
        $plugin_map_list = hook("GetMapPluginList");
        foreach ($plugin_map_list as $map) {
            if ($map) $plugin_map_list2[] = $map;
        }

        $settingTabs = [
            ['type' => 0, 'target' => 'web', 'name' => '系统配置', 'desc' => '站点名称、SEO、注册和后台设置'],
            ['type' => 1, 'target' => 'captcha', 'name' => '提交验证码', 'desc' => '表单和提交验证码来源'],
            ['type' => 2, 'target' => 'email', 'name' => 'SMTP邮箱设置', 'desc' => '发信账号、端口和测试发送'],
            ['type' => 3, 'target' => 'sms', 'name' => 'SMS短信配置', 'desc' => '短信服务商和验证码短信发送'],
            ['type' => 4, 'target' => 'pay', 'name' => '支付配置', 'desc' => '支付通道和默认支付驱动'],
            ['type' => 5, 'target' => 'login', 'name' => '登录配置', 'desc' => '第三方登录和登录方式来源'],
            ['type' => 6, 'target' => 'editor', 'name' => '富文本编辑器', 'desc' => '系统默认富文本驱动'],
            ['type' => 7, 'target' => 'map', 'name' => '地图配置', 'desc' => '地图服务来源和定位能力'],
        ];

        $settingOverview = [
            [
                'name' => '当前站点名称',
                'value' => cacheGlobalSettingsByKey('base_name') ?: '未设置',
                'desc' => '用于后台和站点名称展示',
            ],
            [
                'name' => '当前富文本',
                'value' => __E('editor_driver') ?: '未设置',
                'desc' => '系统默认调用的富文本驱动',
            ],
            [
                'name' => '短信服务',
                'value' => __E('sms_driver') ?: '未设置',
                'desc' => '短信验证码和通知当前使用来源',
            ],
            [
                'name' => '验证码来源',
                'value' => __E('captcha_driver') ?: '未设置',
                'desc' => '表单验证码当前使用来源',
            ],
            [
                'name' => '支付通道',
                'value' => __E('pay_driver') ?: '未设置',
                'desc' => '支付模块当前默认驱动',
            ],
            [
                'name' => '地图来源',
                'value' => __E('map_driver') ?: '未设置',
                'desc' => '定位和地图展示当前来源',
            ],
            [
                'name' => 'PC端状态',
                'value' => $this->resolveMaintenanceStatus('website_pc_status') === 1 ? '正常访问' : '维护中',
                'desc' => '控制首页、列表、详情、登录注册等前台页面访问',
            ],
            [
                'name' => 'API端状态',
                'value' => $this->resolveMaintenanceStatus('website_api_status') === 1 ? '正常访问' : '维护中',
                'desc' => '控制 /api 下数据接口、AnyCall 和异步接口访问',
            ],
        ];

        $maintenanceConfig = [
            'pc_status' => $this->resolveMaintenanceStatus('website_pc_status'),
            'pc_message' => $this->resolveMaintenanceMessage('website_pc_status_when'),
            'api_status' => $this->resolveMaintenanceStatus('website_api_status'),
            'api_message' => $this->resolveMaintenanceMessage('website_api_status_when'),
        ];

        return $this->adminView('base.baseConfig', [
            'pageData' => $pageData,
            'currentType' => $currentType,
            'settingTabs' => $settingTabs,
            'settingOverview' => $settingOverview,
            'plugin_list' => $local_start_plugin_list,
            "plugin_editor_list" => $plugin_editor_list2,
            "plugin_captcha_list" => $plugin_captcha_list2,
            "plugin_pay_list" => $plugin_pay_list2,
            "plugin_login_list" => $plugin_login_list2,
            "plugin_map_list" => $plugin_map_list2,
            "agreementList" => $agreementList,
            'maintenanceConfig' => $maintenanceConfig,
        ]);
    }

    //base 提交
    function baseSubmit(Request $request) {

        if ($request->isMethod('POST')) {

            $all = $request->all();

            switch ($all['form']) {
                case "website":

                    //文件上传
                    if (isset($_FILES['weblogo']) && $_FILES['weblogo']["size"] > 0) {
                        try {
                            $all['weblogo'] = UploadFile($request, "weblogo", "website/logo", ALLOWEXT, __E("upload_driver"));
                        } catch (\Exception $exception) {
                        }

                    }

                    if (isset($_FILES['member_weblogo']) && $_FILES['member_weblogo']["size"] > 0) {
                        try {
                            $all['member_weblogo'] = UploadFile($request, "member_weblogo", "website/member_logo", ALLOWEXT, __E("upload_driver"));
                        } catch (\Exception $exception) {
                        }

                    }
                    if (isset($_FILES['webicon']) && $_FILES['webicon']["size"] > 0) {
                        try {
                            $all['webicon'] = UploadFile($request, "webicon", "website/webicon", 'ico', __E("upload_driver"));
                        } catch (\Exception $exception) {
                        }


                    }

                    if ($all['website_debug']) {
                        $env['APP_DEBUG'] = $all['website_debug'];
                    }

                    if (isset($all['APP_LOG'])) {
                        $env['APP_LOG'] = $all['APP_LOG'];
                    }

                    if (isset($all['LOG_LEVEL'])) {
                        $env['LOG_LEVEL'] = $all['LOG_LEVEL'];
                    }

                    $settings = [];
                    $in_database = [
                        'base_name',
                        'weblogo',
                        'member_weblogo',
                        'webicon',
                        'website_name',
                        'website_keys',
                        'website_desc',
                        'website_open_login',
                        'website_open_reg',
                        'website_reg_rqstd',
                        'website_reg_fields',
                        'website_reg_required',
                        'website_icp',
                        'website_copyright',
                        'website_reg_agreement',
                        'open_captcha',
                        'website_pc_status',
                        'website_pc_status_when',
                        'website_api_status',
                        'website_api_status_when',
                        'website_status',
                        'website_status_when',
                        'multi_currency',
                        'default_currency',
                        'multilingual',
                        'default_language',
                        'website_statut',
                        'website_statut_when',
                        'moduleHomeLock',
                        'admin_page_count',
                        'use_of_cloud',
                    ];
                    foreach ($all as $key => $value) {
                        if (in_array($key, $in_database)) {
                            if (in_array($key, ['website_reg_rqstd', 'website_reg_agreement', 'website_reg_fields', 'website_reg_required'])) {
                                $value = implode(",", $value);
                            }
                            $settings[$key] = $value;
                        }
                    }
                    $settings['website_reg_rqstd'] = $settings['website_reg_rqstd'] ?: '';
                    $settings['website_reg_fields'] = $settings['website_reg_fields'] ?: '';
                    $settings['website_reg_required'] = $settings['website_reg_required'] ?: '';
                    $settings['website_reg_agreement'] = $settings['website_reg_agreement'] ?: '';
                    $pcStatus = array_key_exists('website_pc_status', $settings)
                        ? (int) $settings['website_pc_status']
                        : $this->resolveMaintenanceStatus('website_pc_status');
                    $apiStatus = array_key_exists('website_api_status', $settings)
                        ? (int) $settings['website_api_status']
                        : $this->resolveMaintenanceStatus('website_api_status');
                    $pcMessage = array_key_exists('website_pc_status_when', $settings)
                        ? (string) $settings['website_pc_status_when']
                        : $this->resolveMaintenanceMessage('website_pc_status_when');
                    $apiMessage = array_key_exists('website_api_status_when', $settings)
                        ? (string) $settings['website_api_status_when']
                        : $this->resolveMaintenanceMessage('website_api_status_when');
                    $settings['website_status'] = ($pcStatus === 1 && $apiStatus === 1) ? 1 : 0;
                    $settings['website_status_when'] = $pcStatus !== 1 ? $pcMessage : $apiMessage;
                    if (isset($env)) {
                        modifyEnv($env);
                        try {
                            Artisan::call("config:clear");
                        } catch (\Throwable $e) {
                        }
                    }
                    foreach ($settings as $key => $value) {
                        $type = "website";
                        $module = "Main";
                        \Modules\Main\Services\ServiceModel::SettingInsertOrUpdate($module, $type, $key, $value);
                    }

                    //更新缓存
                    cacheGlobalSettings(2);
                    break;

                case "email":

                    $update['MAIL_HOST'] = $all['MAIL_HOST'];

                    $update['MAIL_PORT'] = $all['MAIL_PORT'];

                    $update['MAIL_USERNAME'] = $update['MAIL_FROM_ADDRESS'] = $all['MAIL_FROM_ADDRESS'];

                    $update['MAIL_FROM_NAME'] = $all['MAIL_FROM_NAME'];

                    $update['MAIL_PASSWORD'] = $all['MAIL_PASSWORD'];

                    $update['MAIL_ENCRYPTION'] = $all['MAIL_ENCRYPTION'];

                    modifyEnv($update);
                    try {
                        Artisan::call("config:clear");
                    } catch (\Throwable $e) {
                    }

                    //更新缓存
                    cacheGlobalSettings(2);
                    break;
                case "test_email" :
                    $all['moduleName'] = $this->moduleName;
                    $all['operate_type'] = 'send';
                    $all['email'] = $all['email_adress'];
                    $all['key'] = time();
                    $all['uid'] = session(SessionKey::AdminInfo);
                    $all['code_type'] = 1000;
                    $res = hook('GetSendEmail', $all)[0];
                    return $res;
                    break;

                case "sms":
                    $settings = [];
                    $in_database = [
                        'sms_driver'
                    ];
                    foreach ($all as $key => $value) {
                        if (in_array($key, $in_database)) {
                            array_push($settings, ['key' => $key, 'value' => $value]);
                            $type = "sms";
                            $module = "Main";
                            \Modules\Main\Services\ServiceModel::SettingInsertOrUpdate($module, $type, $key, $value);
                        }
                    }
//                    $setting = new ServiceModel;
//                    $setting->updateBatch($settings);
                    //更新缓存
                    cacheGlobalSettings(2);

                    break;
                case "editor":
                    $settings = [];
                    $in_database = [
                        'editor_driver'
                    ];
                    foreach ($all as $key => $value) {
                        if (in_array($key, $in_database)) {
                            array_push($settings, ['key' => $key, 'value' => $value]);
                            $type = "editor";
                            $module = "Main";
                            \Modules\Main\Services\ServiceModel::SettingInsertOrUpdate($module, $type, $key, $value);
                        }
                    }
//                    $setting = new ServiceModel;
//                    $setting->updateBatch($settings);
                    //更新缓存
                    cacheGlobalSettings(2);
                    break;
                case "captcha":
                    $settings = [];
                    $in_database = [
                        'captcha_driver'
                    ];
                    foreach ($all as $key => $value) {
                        if (in_array($key, $in_database)) {
                            array_push($settings, ['key' => $key, 'value' => $value]);
                            $type = "captcha";
                            $module = "Main";
                            \Modules\Main\Services\ServiceModel::SettingInsertOrUpdate($module, $type, $key, $value);
                        }
                    }
//                    $setting = new ServiceModel;
//                    $setting->updateBatch($settings);
                    //更新缓存
                    cacheGlobalSettings(2);
                    break;
                case "pay":
                    $settings = [];
                    $in_database = [
                        'pay_driver'
                    ];
                    foreach ($all as $key => $value) {
                        if (in_array($key, $in_database)) {
                            array_push($settings, ['key' => $key, 'value' => $value]);
                            $type = "pay";
                            $module = "Main";
                            \Modules\Main\Services\ServiceModel::SettingInsertOrUpdate($module, $type, $key, $value);
                        }
                    }
//                    $setting = new ServiceModel;
//                    $setting->updateBatch($settings);
                    //更新缓存
                    cacheGlobalSettings(2);

                    break;
                case "login":
                    $settings = [];
                    $in_database = [
                        'login_driver'
                    ];
                    foreach ($all as $key => $value) {
                        if (in_array($key, $in_database)) {
                            array_push($settings, ['key' => $key, 'value' => $value]);
                            $type = "login";
                            $module = "Main";
                            \Modules\Main\Services\ServiceModel::SettingInsertOrUpdate($module, $type, $key, $value);
                        }
                    }
//                    $setting = new ServiceModel;
//                    $setting->updateBatch($settings);
                    //更新缓存
                    cacheGlobalSettings(2);

                    break;
                case "map":
                    $settings = [];
                    $in_database = [
                        'map_driver'
                    ];
                    foreach ($all as $key => $value) {
                        if (in_array($key, $in_database)) {
                            array_push($settings, ['key' => $key, 'value' => $value]);
                            $type = "map";
                            $module = "Main";
                            \Modules\Main\Services\ServiceModel::SettingInsertOrUpdate($module, $type, $key, $value);
                        }
                    }
//                    $setting = new ServiceModel;
//                    $setting->updateBatch($settings);
                    //更新缓存
                    cacheGlobalSettings(2);

                    break;
                default :
                    return ["status" => 40000, "msg" => "Method does not exist"];
            }
            return ["status" => 200, "msg" => "更新成功"];
        } else {
            return ["status" => 40000, "msg" => "method error,must post method"];
        }

    }

}
