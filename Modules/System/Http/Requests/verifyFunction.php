<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2023/6/25
 * Time: 16:54
 */

namespace Modules\System\Http\Requests;


trait verifyFunction {
    //获取域名绑定模块
    public static function domainGetBindModule($request) {
        $domain = $request->server('SERVER_NAME');
        $find = \Modules\System\Services\ServiceModel::apiGetModule([
            ['domain', 'LIKE', "%$domain%"]
        ]);
        return $find['module_data'] ?: false;
    }
}