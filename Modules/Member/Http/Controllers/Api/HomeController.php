<?php

namespace Modules\Member\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Main\Services\ServiceModel;
use Modules\Member\Helper\Func;
use Modules\Member\Models\Wallet;

class HomeController extends JWTController {

    //获取vip列表
    public function getVipList(Request $request) {
        $user = $this->current_user();
        $res = hook('Vip', ['moduleName' => 'Member', 'operate_type' => 'getList', 'uid' => $user['uid']])[0];
        $config = Func::getBaseConfig('vipConfig');
        $wallet = ServiceModel::apiGetOneArray(Wallet::TABLE_NAME, ['uid' => $user['uid']]);
        $data = [
            'list' => $res['data'],
            'interests' => $config['interests'],
            'vip_rule' => $config['vip_rule'],
            'vip_time' => $wallet['vip_time'] ?: null,
        ];
        return returnArr(200, '获取成功', $data);
    }

}
