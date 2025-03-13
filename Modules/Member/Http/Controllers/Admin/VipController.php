<?php

namespace Modules\Member\Http\Controllers\Admin;


use Modules\Formtools\Http\Controllers\Admin\FormTool;
use Modules\Main\Http\Controllers\Admin\FuncController;
use Modules\Main\Services\ServiceModel;
use Modules\Member\Http\Controllers\Common\CommonController;
use Modules\Member\Models\Vip;
use Modules\System\Helper\Func;

class VipController extends CommonController {
    public $controller = 'Level';
    public $action = 'level/vipList';

    public function vipList() {
        $pageData = getURIByRoute($this->request);
        $all = $this->request->all();
        $pageData['controller'] = $this->controller;
        $pageData['action'] = $this->action;

        $data = Vip::query()
            ->where(function ($q) use ($all) {
                if ($all['keyword']) $q->where('title', 'like', '%' . $all['keyword'] . '%');
                if (isset($all['status'])) $q->where('status', $all['status']);
            })
            ->orderByDesc("status")
            ->orderByDesc("sort")
            ->latest()
            ->paginate(getLen($all));

        foreach ($data as &$d) {
            $d['statusCssClass'] = $d['status'] == 1 ? FormTool::label_success : FormTool::label_danger;
            $d['is_only_buy_oneCssClass'] = $d['is_only_buy_one'] == 2 ? FormTool::label_success : FormTool::label_danger;
            $d['typeCssClass'] = $d['type'] == 1 ? FormTool::label_danger : ($d['type'] == 2 ? FormTool::label_success : FormTool::label_info);
            $d['price'] *= 1;
            $d['discount_price'] *= 1;
        }

        $statusList = getFormRadioList(Vip::status());

        //print_r($pcate);exit();
        return FormTool::create()
            ->field("id", "ID")
            ->field("name", "名称")
            ->field("type", ['name' => "类型", 'datas' => Vip::type()])
            ->field("number", "数量")
            ->field("price", "原价格")
            ->field("discount_price", "折扣价格")
            ->field("tig", "标签")
            ->field("is_only_buy_one", ['name' => '限购购买', 'datas' => Vip::is_only_buy_one()])
            ->field("sort", "排序")
            ->field("status", ['name' => "状态", 'datas' => Vip::status()])
            ->field("created_at", ['name' => "时间"])
            ->rightAction([
                ['actionName' => '编辑', 'actionUrl' => moduleAdminJump($this->moduleName, 'level/vipEdit'), 'cssClass' => "btn-info", 'param' => ['page' => $_GET['page']]],
                [
                    'actionName' => '删除', 'actionUrl' => moduleAdminJump($this->moduleName, 'level/vipDelete'), 'cssClass' => "btn-danger", "confirm" => true, 'param' => ['page' => $_GET['page']]
                ]
            ], "id")
            ->pageTitle("等级管理")
            ->formTitle("vip列表")
            ->searchField("keyword", "品牌名", $all['keyword'])
            ->searchField("status", "状态", $all['status'], 'select', $statusList)
            ->listAction([
                ['actionName' => '添加', 'actionUrl' => moduleAdminJump($this->moduleName, 'level/vipAdd'), 'cssClass' => "bg-info btn-xs"],
            ])
            ->linkAppend($all)
            ->listView($pageData, $data);
    }

    public function vipAdd() {
        $all = $this->request->all();
        if ($this->request->isMethod("post")) {

            if ($if = ifCondition([
                'name' => '名称不能为空',
                'type' => '类型不能为空',
                'number' => '数量不能为空',
                'price' => '价格不能为空',
                'discount_price' => '折扣价格不能为空',
            ], $all)) return $if;

            if ($all['discount_price'] <= 0) $all['discount_price'] = $all['price'];
            if ($all['discount_price'] > $all['price']) return returnArr(0, '折扣价格不能大于原价');

            $add = [
                'name' => trim($all['name']),
                'type' => intval($all['type']),
                'number' => trim($all['number']),
                'price' => Func::numberFormat($all['price']),
                'discount_price' => Func::numberFormat($all['discount_price']),
                'describe' => trim($all['describe']),
                'tig' => trim($all['tig']),
                'sort' => intval($all['sort']),
                'is_only_buy_one' => $all['is_only_buy_one'] == 1 ? 1 : 2,
                'status' => intval($all['status']),
            ];
            $res = ServiceModel::add(Vip::TABLE_NAME, $add);
            if ($res) {
                return returnArr(200, '添加成功', ['jumpUrl' => $all['jumpUrl']]);
            } else {
                return returnArr(0, '添加失败');
            }
        }

        $typeArr = Func::dealArrayToTwoArray(Vip::type());
        $statusArr = Func::dealArrayToTwoArray(Vip::status());
        $is_only_buy_oneArr = Func::dealArrayToTwoArray(Vip::is_only_buy_one());

        $pageData = getURIByRoute($this->request);
        $pageData['controller'] = $this->controller;
        $pageData['action'] = $this->action;
        return FormTool::create()
            ->field("name", ['name' => '名称'])
            ->field("type", ['name' => '类型', 'formtype' => 'radio', 'datas' => $typeArr, 'value' => 2])
            ->field("number", ['name' => '数量', 'notes' => '选年，1代表1年；选月，1代表1个月；选日，1代表1天；', 'value' => 1])
            ->field("price", ['name' => '价格'])
            ->field("discount_price", ['name' => '折扣价格'])
            ->field("describe", ['name' => '描述', 'formtype' => 'editor'])
            ->field("tig", ['name' => '标签'])
            ->field("sort", ['name' => '排序', 'notes' => '降序排序', 'value' => 0])
            ->field("is_only_buy_one", ['name' => '限购购买','notes' => '限制只能购买一次', 'formtype' => 'radio', 'datas' => $is_only_buy_oneArr, 'value' => 2])
            ->field("status", ['name' => '状态', 'formtype' => 'radio', 'datas' => $statusArr, 'value' => 1])
            ->csrf_field()
            ->pageTitle("等级管理")
            ->formTitle("添加vip")
            ->formAction(moduleAdminJump($this->moduleName, 'level/vipAdd'))
            ->actionType('ajax')
            ->jumpPrevUrl()
            ->formView($pageData);
    }

    public function vipEdit() {
        $all = $this->request->all();
        $find = Vip::query()->find($all['id']);

        if ($this->request->isMethod("post")) {
            if (!$find) return returnArr(0, '记录不存在');

            if ($if = ifCondition([
                'name' => '名称不能为空',
                'type' => '类型不能为空',
                'number' => '数量不能为空',
                'price' => '价格不能为空',
                'discount_price' => '折扣价格不能为空',
            ], $all)) return $if;

            if ($all['discount_price'] <= 0) $all['discount_price'] = $all['price'];
            if ($all['discount_price'] > $all['price']) return returnArr(0, '折扣价格不能大于原价');

            $add = [
                'name' => trim($all['name']),
                'type' => intval($all['type']),
                'number' => trim($all['number']),
                'price' => Func::numberFormat($all['price']),
                'discount_price' => Func::numberFormat($all['discount_price']),
                'describe' => trim($all['describe']),
                'tig' => trim($all['tig']),
                'sort' => intval($all['sort']),
                'is_only_buy_one' => $all['is_only_buy_one'] == 1 ? 1 : 2,
                'status' => intval($all['status']),
            ];
            $res = ServiceModel::whereUpdate(Vip::TABLE_NAME, ['id' => $find['id']], $add);
            if ($res) {
                return returnArr(200, '编辑成功', ['jumpUrl' => $all['jumpUrl']]);
            } else {
                return returnArr(0, '编辑失败');
            }
        }

        if (!$find) return oneFlash([0, '记录不存在']);

        $typeArr = Func::dealArrayToTwoArray(Vip::type());
        $statusArr = Func::dealArrayToTwoArray(Vip::status());
        $is_only_buy_oneArr = Func::dealArrayToTwoArray(Vip::is_only_buy_one());

        $pageData = getURIByRoute($this->request);
        $pageData['controller'] = $this->controller;
        $pageData['action'] = $this->action;
        return FormTool::create()
            ->field("name", ['name' => '名称', 'value' => $find['name']])
            ->field("type", ['name' => '类型', 'formtype' => 'radio', 'datas' => $typeArr, 'value' => $find['type']])
            ->field("number", ['name' => '数量', 'notes' => '选年，1代表1年；选月，1代表1个月；选日，1代表1天；', 'value' => $find['number']])
            ->field("price", ['name' => '价格', 'value' => $find['price'] * 1])
            ->field("discount_price", ['name' => '折扣价格', 'value' => $find['discount_price'] * 1])
            ->field("describe", ['name' => '描述', 'formtype' => 'editor', 'value' => $find['describe']])
            ->field("tig", ['name' => '标签', 'value' => $find['tig']])
            ->field("sort", ['name' => '排序', 'notes' => '降序排序', 'value' => $find['sort']])
            ->field("is_only_buy_one", ['name' => '限购购买','notes' => '限制只能购买一次', 'formtype' => 'radio', 'datas' => $is_only_buy_oneArr, 'value' => $find['is_only_buy_one']])
            ->field("status", ['name' => '状态', 'formtype' => 'radio', 'datas' => $statusArr, 'value' => $find['status']])
            ->field("id", ['formtype' => 'hidden', 'value' => $find['id']])
            ->csrf_field()
            ->pageTitle("等级管理")
            ->formTitle("添加vip")
            ->formAction(moduleAdminJump($this->moduleName, 'level/vipEdit'))
            ->actionType('ajax')
            ->jumpPrevUrl()
            ->formView($pageData);
    }

    public function vipDelete() {
        $all = $this->request->all();
        $check = Vip::query()->find($all['id']);
        if (!$check) return oneFlash([0, '记录不存在']);

        $res = Vip::destroy($all['id']);
        if ($res) return oneFlash([200, '删除成功']);

        return oneFlash([0, '删除失败']);
    }
}
