<?php

namespace Modules\Member\Http\Controllers\Admin;


use Illuminate\Support\Facades\Cache;
use Modules\Formtools\Http\Controllers\Admin\FormTool;
use Modules\Main\Models\Member;
use Modules\Main\Models\SystemMessage;
use Modules\Member\Http\Controllers\Common\CommonController;
use Modules\Member\Models\WalletRecord;
use Modules\System\Helper\Func;
use Mxzcms\Modules\cache\CacheKey;

class MessageController extends CommonController {
    public $controller = 'Setting';
    public $action = 'setting/messageList';

    //站内信列表
    public function messageList() {
        $all = $this->request->all();
        $pageData = getURIByRoute($this->request);
        $pageData['controller'] = $this->controller;
        $pageData['action'] = $this->action;

        $all['moduleName'] = 'System';
        $all['operate_type'] = 7;
        $data = hook('UpdateUserMessage', $all)[0]['data'];

        foreach ($data as &$d) {
            $d['username'] = $d['user']['username'];
            $d['nickname'] = $d['user']['nickname'];
            $d['phone'] = $d['user']['phone'];
            $d['statusCssClass'] = $d['status'] == 1 ? FormTool::label_success : FormTool::label_danger;
        }

        return FormTool::create()
            ->field("id", "ID")
            ->field("username,nickname,phone", "用户信息")
            ->field("module", ['name' => "模块"])
            ->field("title", ['name' => "标题"])
            ->field("status", ['name' => '状态', 'datas' => SystemMessage::status()])
            ->field("created_at", ['name' => "时间"])
            ->pageTitle("系统管理")
            ->formTitle("站内信")
            ->searchField("username", "用户名", $all['username'])
            ->searchField("title", "标题", $all['title'])
            ->searchField("rang", "时间", $all['rang'], 'dateRange')
            ->rightAction([
                ['actionName' => '详情', 'actionUrl' => moduleAdminJump($this->moduleName, 'setting/messageDetail'), 'cssClass' => "btn-info", 'param' => ['page' => $_GET['page']]],
            ], "id")
            ->linkAppend($all)
            ->listView($pageData, $data);
    }

    public function messageDetail() {
        $all = $this->request->all();
        $pageData = getURIByRoute($this->request);
        $pageData['controller'] = $this->controller;
        $pageData['action'] = $this->action;

        $all['moduleName'] = 'System';
        $all['operate_type'] = 8;
        $data = hook('UpdateUserMessage', $all)[0]['data'];
        return FormTool::create()
            ->field("id", ['name' => "ID", 'formtype' => 'word', 'value' => $data['id']])
            ->field("username", ['name' => "名称", 'formtype' => 'word', 'value' => $data['user']['username']])
            ->field("nickname", ['name' => "昵称", 'formtype' => 'word', 'value' => $data['user']['nickname']])
            ->field("phone", ['name' => "手机号", 'formtype' => 'word', 'value' => $data['user']['phone']])
            ->field("module", ['name' => "模块", 'formtype' => 'word', 'value' => $data['module']])
            ->field("title", ['name' => "标题", 'formtype' => 'word', 'value' => $data['title']])
            ->field("content", ['name' => "内容", 'formtype' => 'word', 'value' => $data['content']])
            ->field("created_at", ['name' => "时间", 'formtype' => 'word', 'value' => $data['created_at']])
            ->field("status", ['name' => '状态', 'formtype' => 'radio', 'datas' => \Modules\Member\Helper\Func::dealArrayToTwoArray(SystemMessage::status()), 'value' => $data['status'], 'disabled' => 'disabled'])
            ->pageTitle("系统管理")
            ->formTitle("站内信详情")
            ->linkAppend($all)
            ->formView($pageData);
    }
}
