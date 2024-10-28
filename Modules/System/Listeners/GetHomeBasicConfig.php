<?php

namespace Modules\System\Listeners;

use Modules\Formtools\Services\ServiceModel;

class GetHomeBasicConfig {
    public function handle(\Modules\System\Events\GetHomeBasicConfig $event) {
        //事件逻辑 ...
        $moduleName = $event->data['moduleName'];

        //网站基本信息
        $web['website_name'] = cacheGlobalSettingsByKey('website_name') ?: '';
        $web['website_keywords'] = cacheGlobalSettingsByKey('website_keys') ?: '';
        $web['website_description'] = cacheGlobalSettingsByKey('website_desc') ?: '';
        $web['website_icp'] = cacheGlobalSettingsByKey('website_icp') ?: '';
        $web['website_copyright'] = cacheGlobalSettingsByKey('website_copyright') ?: '';
        $web['website_logo'] = GetLocalFileByPath(cacheGlobalSettingsByKey('weblogo')) ?: '';
        $web['website_ico'] = GetLocalFileByPath(cacheGlobalSettingsByKey('webicon')) ?: '';


        //登录注册
        //会员登录
        $login_register['open_login'] = cacheGlobalSettingsByKey('website_open_login') == 1 ? true : false;
        //会员注册
        $login_register['open_register'] = cacheGlobalSettingsByKey('website_open_reg') == 1 ? true : false;

        //验证码
        $website_reg_rqstd = explode(",", cacheGlobalSettingsByKey('website_reg_rqstd'));
        //手机验证码
        $login_register['open_phone_verify'] = in_array('phone', $website_reg_rqstd) ? true : false;
        //邮件验证码
        $login_register['open_email_verify'] = in_array('email', $website_reg_rqstd) ? true : false;
        //提交验证码
        $login_register['open_code_verify'] = cacheGlobalSettingsByKey('open_captcha') == 1 ? true : false;

        //注册协议
        $idArr = explode(',', cacheGlobalSettingsByKey('website_reg_agreement'));
        if (!$idArr) return [];
        $agreementList = ServiceModel::getEnableAgreementList($idArr);
        foreach ($agreementList as &$agree) {
            $agree['detail_url'] = url('detail/agreement/'.$agree['id']);
        }
        $login_register['agreementList'] = $agreementList;

        return ['web' => $web, 'login_register' => $login_register];
    }

}
