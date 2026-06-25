<?php

namespace Modules\System\Listeners;

use Modules\Formtools\Services\ServiceModel;

class GetHomeBasicConfig {
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
        $web['maintenance'] = [
            'pc_status' => $this->resolveMaintenanceStatus('website_pc_status'),
            'pc_message' => $this->resolveMaintenanceMessage('website_pc_status_when'),
            'api_status' => $this->resolveMaintenanceStatus('website_api_status'),
            'api_message' => $this->resolveMaintenanceMessage('website_api_status_when'),
        ];


        //登录注册
        //会员登录
        $login_register['open_login'] = cacheGlobalSettingsByKey('website_open_login') == 1 ? true : false;
        //会员注册
        $login_register['open_register'] = cacheGlobalSettingsByKey('website_open_reg') == 1 ? true : false;

        //验证码
        $website_reg_rqstd = array_values(array_filter(explode(",", (string) __E('website_reg_rqstd'))));
        $registerFieldsRaw = trim((string) __E('website_reg_fields'));
        $registerRequiredRaw = trim((string) __E('website_reg_required'));
        $registerFields = $registerFieldsRaw === '' ? [] : array_values(array_filter(explode(",", $registerFieldsRaw)));
        $registerRequired = $registerRequiredRaw === '' ? [] : array_values(array_filter(explode(",", $registerRequiredRaw)));
        //手机验证码
        $login_register['open_phone_verify'] = in_array('phone', $website_reg_rqstd) ? true : false;
        //邮件验证码
        $login_register['open_email_verify'] = in_array('email', $website_reg_rqstd) ? true : false;
        //提交验证码
        $login_register['open_code_verify'] = cacheGlobalSettingsByKey('open_captcha') == 1 ? true : false;
        $login_register['register_fields'] = $registerFields;
        $login_register['register_required'] = $registerRequired;
        $login_register['show_nickname'] = in_array('nickname', $registerFields, true);
        $login_register['required_nickname'] = in_array('nickname', $registerRequired, true);
        $login_register['show_email'] = in_array('email', $registerFields, true) || $login_register['open_email_verify'];
        $login_register['required_email'] = in_array('email', $registerRequired, true) || $login_register['open_email_verify'];
        $login_register['show_phone'] = in_array('phone', $registerFields, true) || $login_register['open_phone_verify'];
        $login_register['required_phone'] = in_array('phone', $registerRequired, true) || $login_register['open_phone_verify'];

        //注册协议
        $idArr = array_values(array_filter(array_map('intval', explode(',', (string) cacheGlobalSettingsByKey('website_reg_agreement')))));
        $agreementList = $idArr ? ServiceModel::getEnableAgreementList($idArr) : [];
        foreach ($agreementList as &$agree) {
            $agree['detail_url'] = url('detail/agreement/'.$agree['id']);
        }
        $login_register['agreement_ids'] = $idArr;
        $login_register['agreementList'] = $agreementList;

        return ['web' => $web, 'login_register' => $login_register];
    }

}
