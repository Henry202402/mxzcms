<?php

namespace Modules\System\Http\Controllers\Admin;

use Modules\Main\Models\Modules;
use Modules\System\Http\Controllers\Common\CommonController;
use Illuminate\Http\Request;
use Modules\System\Http\Controllers\Common\SessionKey;
use Modules\System\Services\ServiceModel;

class BaseController extends CommonController {

    public function __construct(Request $request) {
        parent::__construct($request);
    }


    public function baseConfig(Request $request) {
        $pageData = [
            'title' => '基本配置',
            'controller' => 'Setting',
            'action' => 'baseConfig',
        ];

        //获取当前所有的SMS插件
        $plugin_sms_list = hook("GetSMSPluginList");
        foreach ($plugin_sms_list as $plugin) {
            $local_start_plugin_list[] = $plugin;
        }

        $plugin_editor_list = hook("GetEditorList");
        foreach ($plugin_editor_list as $plugin) {
            $plugin_editor_list2[] = $plugin;
        }
        //协议列表
        $agreementList = hook('GetModelAgreement', ['moduleName' => 'Formtools'])[0]['agreementList'];

        //验证码列表
        $plugin_captcha_list = hook("GetCaptchaList");
        foreach ($plugin_captcha_list as $captcha) {
            if ($captcha) $plugin_captcha_list2[] = $captcha;
        }

        return $this->adminView('base.baseConfig', [
            'pageData' => $pageData,
            'plugin_list' => $local_start_plugin_list,
            "plugin_editor_list" => $plugin_editor_list2,
            "plugin_captcha_list" => $plugin_captcha_list2,
            "agreementList" => $agreementList,
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
                            $weblogo = UploadFile($request, "weblogo", "website/logo", ALLOWEXT, __E("upload_driver"));
                            if ($weblogo) $all['weblogo'] = $weblogo;
                        } catch (\Exception $exception) {
                        }

                    }
                    if (isset($_FILES['webicon']) && $_FILES['webicon']["size"] > 0) {
                        try {
                            $webicon = UploadFile($request, "webicon", "website/webicon", 'ico', __E("upload_driver"));
                            if ($webicon) $all['webicon'] = $webicon;
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
                        'website_name',
                        'weblogo',
                        'webicon',
                        'website_keys',
                        'website_desc',
                        'website_open_login',
                        'website_open_reg',
                        'website_reg_rqstd',
                        'website_icp',
                        'website_copyright',
                        'website_reg_agreement',
                        'open_captcha',
                        'website_status',
                        'website_status_when',
                        'multi_currency',
                        'default_currency',
                        'multilingual',
                        'default_language',
                        'website_statut',
                        'website_statut_when',
                        'admin_page_count',
                        'use_of_cloud',
                    ];
                    foreach ($all as $key => $value) {
                        if (in_array($key, $in_database)) {
                            if (in_array($key, ['website_reg_rqstd', 'website_reg_agreement'])) {
                                $value = implode(",", $value);
                            }
                            $settings[$key] = $value;
                        }
                    }
                    $settings['website_reg_rqstd'] = $settings['website_reg_rqstd'] ?: '';
                    $settings['website_reg_agreement'] = $settings['website_reg_agreement'] ?: '';
                    if (isset($env)) {
                        modifyEnv($env);
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
                default :
                    return ["status" => 40000, "msg" => "Method does not exist"];
            }
            return ["status" => 200, "msg" => "更新成功"];
        } else {
            return ["status" => 40000, "msg" => "method error,must post method"];
        }

    }

}
