<?php

namespace Modules\Formtools\Services;


use Modules\Formtools\Models\FormModel;
use Modules\Main\Models\Common;

class ServiceModel {

    public static function getLeaveField() {
        $leaveArr = FormModel::query()->where('identification', 'feedback')->first();
        $leaveField = json_decode($leaveArr['fields'], true);
        foreach ($leaveField as &$field) {
            $field['datas'] = $field['datas'] ? json_decode($field['datas'], true) : null;
        }
        return $leaveField;
    }

    //获取启用的协议列表
    public static function getEnableAgreementList($ids = []) {
        $array = Common::query()
            ->from('module_formtools_agreement')
            ->where(function ($q) use ($ids) {
                if ($ids) $q->whereIn('id', $ids);
            })
            ->where('status', 1)
            ->get()->toArray();
        return $array;
    }
}