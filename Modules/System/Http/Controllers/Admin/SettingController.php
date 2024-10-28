<?php

namespace Modules\System\Http\Controllers\Admin;


use Modules\System\Http\Controllers\Common\CommonController;
use Illuminate\Http\Request;
use Modules\System\Models\ModuleBindDomain;
use Modules\Main\Models\Modules;

class SettingController extends CommonController {

    public function __construct(Request $request) {
        parent::__construct($request);
    }


    public function moduleBindDomain(Request $request) {
        $pageData = [
            'subtitle' => '系统设置',
            'title' => '模块绑定域名',
            'controller' => 'Setting',
            'action' => 'moduleBindDomain',
        ];
        $all = $request->all();
        $data = \Modules\System\Services\ServiceModel::getModuleList(["domain" => "y"]);

        return $this->adminView('setting.moduleBindDomain', [
            'pageData' => $pageData,
            'data' => $data,
        ]);
    }

    public function moduleBindDomainSubmit(Request $request) {
        $all = $request->all();

        $findModule = \Modules\System\Services\ServiceModel::apiGetOne(Modules::TABLE_NAME, ['id' => $all['module_id']]);
        if (!$findModule) return returnArr(0, '模块不存在');

        $findRecord = \Modules\System\Services\ServiceModel::apiGetOne(ModuleBindDomain::TABLE_NAME, ['module_id' => $all['module_id']]);

        //手机列表
        $domain = str_replace(' ', '', $all['domain']);
        $domain = explode("\n", $domain);
        $domain = array_filter($domain);
        $domain = array_unique($domain);

        $up = [
            'domain' => implode(',', $domain),
            'num' => count($domain),
        ];

        if ($findRecord) {
            $res = \Modules\System\Services\ServiceModel::whereUpdate(ModuleBindDomain::TABLE_NAME, ['id' => $findRecord['id']], $up);
        } else {
            $up['module_id'] = $all['module_id'];
            $up['module'] = $findModule['identification'];
            $up['created_at'] = getDay();
            $up['updated_at'] = getDay();
            $res = \Modules\System\Services\ServiceModel::add(ModuleBindDomain::TABLE_NAME, $up);
        }

        if ($res) {
            return ['status' => 200, 'msg' => '编辑成功'];
        } else {
            return ['status' => 0, 'msg' => '编辑失败'];
        }
    }
}
