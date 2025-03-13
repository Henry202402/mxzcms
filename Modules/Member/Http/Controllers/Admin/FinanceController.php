<?php

namespace Modules\Member\Http\Controllers\Admin;


use Illuminate\Support\Facades\Cache;
use Modules\Formtools\Http\Controllers\Admin\FormTool;
use Modules\Member\Http\Controllers\Common\CommonController;
use Modules\Member\Models\WalletRecord;
use Modules\System\Helper\Func;
use Mxzcms\Modules\cache\CacheKey;

class FinanceController extends CommonController {
    public $controller = 'Finance';
    public $action = 'finance/flowRecord';

    public function flowRecord() {
        $pageData = getURIByRoute($this->request);
        $all = $this->request->all();
        $pageData['controller'] = $this->controller;
        $pageData['action'] = $this->action;

        $data = WalletRecord::query()
            ->where(function ($q) use ($all) {
                if ($all['keyword']) $q->where('title', 'like', '%' . $all['keyword'] . '%');
                if ($all['type']) $q->where('type', $all['type']);
                if ($all['amount_type']) $q->where('amount_type', 'like', $all['amount_type'] . '%');
            })
            ->with(['user'])
            ->latest()
            ->paginate(getLen($all));

        foreach ($data as &$d) {
//            $d['amountCssClass'] = $d['type'] == 1 ? FormTool::label_success : FormTool::label_danger;
            $d['amount'] = ($d['type'] == 1 ? '+' : '-') . ' ' . ($d['amount'] * 1);
            $d['username'] = $d['user']['username'];
            $d['nickname'] = $d['user']['nickname'];
        }
        $typeList = Func::dealArrayToTwoArray(WalletRecord::type());

        foreach (Cache::get(CacheKey::ModulesActive) ?: [] as $m) {
            $moduleList[$m['identification']] = $m['name'];
        }

        return FormTool::create()
            ->field("id", "ID")
            ->field("module", ['name' => "模块", 'datas' => $moduleList])
            ->field("username,nickname", "用户信息")
//            ->field("type", ['name' => "类型", 'datas' => WalletRecord::type()])
            ->field("amount_type", ['name' => "操作对象类型"])
            ->field("amount", "数量")
            ->field("unit", ['name' => "单位"])
            ->field("remark", "备注")
            ->field("created_at", ['name' => "时间"])
            ->rightAction([
                ['actionName' => '更多详情', 'actionUrl' => moduleAdminJump($this->moduleName, 'finance/flowRecordDetail'), 'cssClass' => "btn-info", 'param' => ['pop' => $_GET['page']]],
            ], "id")
            ->pageTitle("对账中心")
            ->formTitle("流水记录")
            ->searchField("type", "类型", $all['type'], 'select', $typeList)
            ->searchField("amount_type", "操作对象类型", $all['amount_type'], 'text')
            ->linkAppend($all)
            ->listView($pageData, $data);
    }

    public function flowRecordDetail() {
        $all = $this->request->all();
        $pageData = getURIByRoute($this->request);
        $pageData['controller'] = $this->controller;
        $pageData['action'] = $this->action;

        $data = WalletRecord::query()->with(['user'])->find($all['id']);
        $data['amount'] *= 1;
        $data['username'] = $data['user']['username'];
        $data['nickname'] = $data['user']['nickname'];
        $data['phone'] = $data['user']['phone'];


        $typeList = Func::dealArrayToTwoArray(WalletRecord::type());
        $amount_typeList = Func::dealArrayToTwoArray(WalletRecord::amount_type());
        return FormTool::create()
            ->field("module", ['name' => "模块", 'value' => $data['module'], 'disabled' => 'disabled'])
            ->field("username", ['name' => "用户名称", 'value' => $data['username'], 'disabled' => 'disabled'])
            ->field("nickname", ['name' => "用户昵称", 'value' => $data['nickname'], 'disabled' => 'disabled'])
            ->field("phone", ['name' => "用户手机", 'value' => $data['phone'], 'disabled' => 'disabled'])
            ->field("nickname", ['name' => "用户昵称", 'value' => $data['nickname'], 'disabled' => 'disabled'])
            ->field("type", ['name' => "类型", 'formtype' => "radio", 'datas' => $typeList, 'value' => $data['type'], 'disabled' => 'disabled'])
            ->field("amount_type", ['name' => "操作对象类型", 'formtype' => "text", 'value' => $data['amount_type'], 'disabled' => 'disabled'])
            ->field("amount", ['name' => "数量", 'value' => $data['amount'], 'disabled' => 'disabled'])
            ->field("unit", ['name' => "单位", 'value' => $data['unit'], 'disabled' => 'disabled'])
            ->field("remark", ['name' => "备注", 'value' => $data['remark'], 'disabled' => 'disabled'])
            ->field("extra", ['name' => "扩展信息", 'formtype' => "textarea", 'value' => $data['extra'], 'disabled' => 'disabled'])
            ->field("created_at", ['name' => "时间", 'value' => $data['remark'], 'disabled' => 'disabled'])
            ->pageTitle("对账中心")
            ->formTitle("流水记录详情")
            ->linkAppend($all)
            ->formView($pageData);
    }
}
