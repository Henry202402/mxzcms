<?php
function status() {
    return [
        0 => '禁用',
        1 => '启用',
    ];
}

function pay_method() {
    return [
        'WeChat' => '微信',
        'Alipay' => '支付宝',
    ];
}

function pay_type() {
    return [
        'app' => 'APP端',
        'public' => '公众号',
        'small' => '小程序',
        'pc' => '电脑端',
    ];
}

function pay_status() {
    return [
        0 => '待支付',
        1 => '支付成功',
        2 => '支付失败',
        3 => '取消支付',
    ];
}

function pay_status_css($status) {
    switch ($status) {
        case 1:
            return \Modules\Formtools\Http\Controllers\Admin\FormTool::label_success;
            break;
        case 2:
            return \Modules\Formtools\Http\Controllers\Admin\FormTool::label_danger;
            break;
        case 3:
            return \Modules\Formtools\Http\Controllers\Admin\FormTool::label_danger;
            break;
        default:
            return \Modules\Formtools\Http\Controllers\Admin\FormTool::label_info;
            break;
    }
}

function returnArrGetThreePrefix() {
    return [
        'WeChat' => 'wx',
        'Alipay' => 'ali',
        'QQ' => 'qq',
        'Baidu' => 'bd',
    ];
}