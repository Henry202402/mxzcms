<?php

namespace Modules\Member\Http\Controllers\Admin;


use Illuminate\Support\Facades\Cache;
use Modules\Formtools\Http\Controllers\Admin\FormTool;
use Modules\Main\Models\Member;
use Modules\Main\Services\ServiceModel;
use Modules\Member\Http\Controllers\Common\CommonController;
use Modules\Member\Models\Wallet;
use Modules\Member\Models\WalletRecord;
use Modules\System\Helper\Func;
use Mxzcms\Modules\cache\CacheKey;

class WalletController extends CommonController {
    public $controller = 'Finance';
    public $action = 'finance/walletList';

    //钱包列表
    public function walletList() {
        $all = $this->request->all();
        $pageData = getURIByRoute($this->request);
        $pageData['controller'] = $this->controller;
        $pageData['action'] = $this->action;

        $data = Member::query()
            ->where('uid', '<>', 1)
            ->where(function ($q) use ($all) {
                if ($all['keyword']) $q->where('title', 'like', '%' . $all['keyword'] . '%');
                if ($all['type']) $q->where('type', $all['type']);
                if ($all['amount_type']) $q->where('amount_type', $all['amount_type']);
            })
            ->with(['wallet'])
            ->latest()
            ->paginate(getLen($all));
        $addWalletNot = [];
        foreach ($data as &$d) {
            if ($d['wallet']['wallet_id'] > 0) {
                $addWalletNot[] = $d['uid'];
                $d['withdrawable'] = $d['wallet']['withdrawable'] * 1;
                $d['balance'] = $d['wallet']['balance'] * 1;
                $d['integral'] = $d['wallet']['integral'] * 1;
            }
        }
        $typeList = Func::dealArrayToTwoArray(WalletRecord::type());
        $amount_typeList = Func::dealArrayToTwoArray(WalletRecord::amount_type());

        foreach (Cache::get(CacheKey::ModulesActive) ?: [] as $m) {
            $moduleList[$m['identification']] = $m['name'];
        }

        $signInConfig = \Modules\Member\Helper\Func::getBaseConfig('signInConfig');

        return FormTool::create()
            ->field("uid", "ID")
            ->field("username", "用户名称")
            ->field("nickname", "用户昵称")
            ->field("phone", "用户手机")
            ->field("withdrawable", ['name' => "可提现余额"])
            ->field("balance", ['name' => "余额"])
            ->field("integral", ['name' => $signInConfig['integral_alias']])
            ->field("created_at", ['name' => "时间"])
            ->rightAction([
                ['actionName' => '添加钱包', 'actionUrl' => moduleAdminJump($this->moduleName, 'finance/walletAdd'), 'cssClass' => "btn-info", "confirm" => true, 'notIdArray' => $addWalletNot],
            ], "uid")
            ->pageTitle("对账中心")
            ->formTitle("流水记录")
            ->searchField("type", "类型", $all['type'], 'select', $typeList)
            ->searchField("amount_type", "对象类型", $all['amount_type'], 'select', $amount_typeList)
            ->linkAppend($all)
            ->listView($pageData, $data);
    }

    //添加钱包
    public function walletAdd() {
        $all = $this->request->all();
        if (!Member::query()->find($all['uid'])) return oneFlash([0, '用户不存在']);
        $check = ServiceModel::apiGetOneArray(WalletRecord::TABLE_NAME, ['uid' => $all['uid']]);
        if ($check) return oneFlash([0, '钱包已添加']);
        $res = Wallet::add(['uid' => $all['uid']]);
        if ($res) {
            return oneFlash([200, '添加成功']);
        } else {
            return oneFlash([0, '添加失败']);
        }
    }
}
