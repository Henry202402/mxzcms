<?php

namespace Modules\Formtools\Http\Controllers\Admin;

class FormTool {

    //列表状态底色
    const label_success = 'label-success';//绿色
    const label_danger = 'label-danger';//红色
    const label_info = 'label-info';//天蓝色
    const label_primary = 'label-primary';//蓝色
    const label_warning = 'label-warning';//橘色
    const label_default = 'label-default';//灰色

    //按钮底色
    const btn_success = 'btn-success';//绿色
    const btn_danger = 'btn-danger';//红色
    const btn_info = 'btn-info';//天蓝色
    const btn_primary = 'btn-primary';//蓝色
    const btn_warning = 'btn-warning';//橘色
    const btn_default = 'btn-default';//默认/无色

    private $fields = []; //字段信息
    private $searchFields = [];//搜索字段
    private $searchClearEmpty = '';//搜索清空跳转
    private $formaction = ''; //表单提交地址
    private $method = 'post'; //提交方式
    private $formTitle = ''; // 表单标题
    private $formTitleUrl = ''; // 表单标题跳转
    private $pageTitle = ""; // 页面标题
    private $pageTitleUrl = ""; // 页面标题跳转
    private $actionName = "提交"; //按钮名称
    private $actionType = "form"; //提交方式，ajax或者form
    private $backName = "返回"; //返回名称
    private $formid = "myForm"; //表单ID
    private $listActions = []; //列表右侧操作
    private $leftListActions = []; //列表左侧操作
    private $isShowMoreCheckbox = false; //列表操作 false不显示，显示=id 的 key name
    private $popup = false; //是否以弹窗方式显示
    private $linkAppend = []; //追加链接参数
    private $formtype = [ //表单类型
        "text", "textarea", "editor", "password", "radio", "checkbox", "select", "file", "image", "date", "time", "datetime", "dateRange", "datetimeRange", "hidden", "readonly", "section"
    ];

    private $formattr = [  //字段属性
        "name", "placeholder", "value", "datas", "required", "rule", "regex", "maxlength", "formtype", "disabled", "cssClass", "callback", "jsfunction", "notes"
    ];

    private static $instance = null;
    private $currentField = null;

    private function __construct() {
        // 私有化构造函数，防止外部创建实例
    }

    static function create() {  //创建实例
//        return new FormTool();
        if (self::$instance === null) {
            self::$instance = new FormTool();
        }
        return self::$instance;
    }

    /*
     * 字段信息
     * identification  字段标识
     * name  字段名称，显示在页面上的
     * placeholder 输入框显示的提示
     * formtype  表单类型
     * value  值
     * datas 其他数据
     * required 是否必填
     * rule 验证规则
     * regex 正则验证
     * maxlength 最大长度，0不限制
     * disabled 是否禁用
     * cssClass 样式，数组形式，["div class","input class1 class2"]
     * callback php回调函数
     * jsfunction js函数
     * notes 备注信息
     * actionby 操作字段
     * relate 关联字段
     * relateaction 关联字段的操作 ShowAndHide显示隐藏 linkage多级联动
     * template 自定义模板 , 返回自定义html，优先callback
     * showtype 多行 row 多列 column
     * */
    public function field($identification, $name, $value = '', $formtype = "text", $datas = []) {

        if (!$identification) return $this;

        if (is_string($name)) {
            $this->fields[$identification] = [
                'identification' => $identification,
                'name' => $name,
                'value' => $value,
                'formtype' => $formtype,
                'datas' => $datas,
                'notes' => "",
                'required' => '',
                'placeholder' => '',
                'rule' => 'string',
                'regex' => '',
                'maxlength' => 0,
                'disabled' => '',
                'cssClass' => '',
                'aInfo' => '',
                'showtype' => 'row',
            ];
        } else if (is_array($name)) {
            $this->fields[$identification] = [
                'identification' => $identification,
                'name' => $name['name'] ?: '',
                'value' => isset($name['value']) ? $name['value'] : '',
                'formtype' => $name['formtype'] ?: 'text',
                'datas' => $name['datas'] ?: [],
                'notes' => $name['notes'] ?: "",
                'required' => $name['required'],
                'placeholder' => $name['placeholder'] ?: '',
                'rule' => $name['rule'] ?: 'string',
                'regex' => $name['regex'] ?: '',
                'maxlength' => $name['maxlength'] ?: 255,
                'disabled' => $name['disabled'] ?: '',
                'cssClass' => $name['cssClass'] ?: '',
                'aInfo' => $name['aInfo'] ?: '',
                'showtype' => $name['showtype'] ?: 'row',
            ];
        }
        $this->currentField = $identification;
        return $this;
    }

    //csrf字段
    public function csrf_field() {
        $this->fields['_token'] = [
            'identification' => '_token',
            'name' => '',
            'formtype' => 'hidden',
            'value' => csrf_token(),
            'required' => 'required',
        ];
        return $this;
    }

    //返回上一个页面
    public function jumpPrevUrl() {
        $this->fields['jumpUrl'] = [
            'identification' => 'jumpUrl',
            'formtype' => 'hidden',
            'value' => $_SERVER['HTTP_REFERER'],
        ];
        return $this;
    }

    //表单提交地址
    public function formAction($url) {
        $this->formaction = $url;
        return $this;
    }

    //提交method
    public function method($method) {
        $this->method = $method;
        return $this;
    }

    //表单标题
    public function formTitle($title, $url = null) {
        $this->formTitle = $title;
        $this->formTitleUrl = $url;
        return $this;
    }

    //页面标题
    public function pageTitle($title, $url = null) {
        $this->pageTitle = $title;
        $this->pageTitleUrl = $url;
        return $this;
    }

    //按钮显示文字
    public function actionName($name) {
        $this->actionName = $name;
        return $this;
    }

    //按钮显示文字
    public function backName($name) {
        $this->backName = $name;
        return $this;
    }

    //搜索表单
    public function searchField($identification, $placeholder, $value = "", $formtype = "text", $datas = []) {
        if (!$identification) return $this;
        $this->searchFields[$identification] = [
            'identification' => $identification,
            'placeholder' => $placeholder,
            'value' => $value,
            'formtype' => $formtype,
            'datas' => $datas,
        ];

        return $this;
    }

    //搜索表单清空按钮
    public function searchClearEmpty($url) {
        $this->searchClearEmpty = $url ?: null;
        return $this;
    }

    //form表单绑定id
    public function formid($formid) {
        $this->formid = $formid;
        return $this;
    }

    //是否已弹窗方式显示
    public function popup($popup = false) {
        if (!$popup && \Request()->popup) $popup = true;
        $this->popup = $popup;
        return $this;
    }

    //form表单提交使用方式提交 默认form
    public function actionType($actionType = 'ajax') {
        $this->actionType = $actionType;
        return $this;
    }

    //列表模板的操作按钮
    /*[
       ['actionName'=>'添加','actionUrl'=>url("admin/formtools/testadd"),'cssClass'=>"bg-info"],
       ['actionName'=>'添加','actionUrl'=>url("admin/formtools/testadd"),'cssClass'=>"bg-info"]
    ]*/
    public function listAction($datas = []) {
        $this->listActions = $datas;
        return $this;
    }

    //列表模板的操作按钮三维数组
    /*[
        [
            ['actionName'=>'添加','actionUrl'=>url("admin/formtools/testadd"),'cssClass'=>"bg-info",
                "confirm" => true, 'isMoreSelect' => true,"noNeedId" => false,popup=>false, 'param' => ['popup' => 0]],
        ]
    ]
     *  actionName 按钮名称 actionUrl 跳转路径 cssClass 类样式
     *  confirm 是否弹出确认框 isMoreSelect 是否可以多选 noNeedId 跳转是否需要id popup是否是弹窗
     *  popup.popup=1 是否需要【头部/左侧】菜单
     */
    public function leftListAction($datas = []) {
        $this->leftListActions = $datas;
        return $this;
    }

    //是否显示多选checkbox
    public function isShowMoreCheckbox($filed = false) {
        $this->isShowMoreCheckbox = $filed;
        return $this;
    }

    //只获取数据
    public function getData() {
        $pageData = [];
        $pageData["fields"] = $this->fields;
        $pageData['title'] = $pageData["title"] ?: $this->pageTitle;
        $pageData['titleUrl'] = $pageData["titleUrl"] ?: $this->pageTitleUrl;
        $pageData['subtitle'] = $pageData["subtitle"] ?: $this->formTitle;
        $pageData['subtitleUrl'] = $pageData["subtitleUrl"] ?: $this->formTitleUrl;
        $pageData['formaction'] = $this->formaction;
        $pageData['method'] = $this->method;
        $pageData['actionName'] = $this->actionName;
        $pageData['backName'] = $this->backName;
        $pageData['formid'] = $this->formid;
        $pageData['actionType'] = $this->actionType;
        $pageData['listActions'] = $this->listActions;
        $pageData['leftListActions'] = $this->leftListActions;
        $pageData['popup'] = $this->popup;
        return $pageData;
    }

    //追加链接参数
    public function linkAppend($value = []) {
        $this->linkAppend = $value;
        return $this;
    }

    //数据的操作按钮 ['notIdArray'=>[],'actionName'=>'编辑','actionUrl'=>url("admin/formtools/testedit"),'cssClass'=>"btn-success",'param'=>['page'=>$_GET['page']]],
    // ['notIdArray'=>[],'actionName'=>'删除','actionUrl'=>url('admin/formtools/testdel'),'cssClass'=>"btn-danger","confirm"=>true,'param'=>['page'=>$_GET['page']]
    // @$datas  actionName操作名称，actionUrl操作地址，cssClass样式 string，param参数，confirm是否需要确认
    // @$field 操作的字段标识
    // @$not_ids 数组内的id不显示所有操作
    public function rightAction($datas, $field = "", $notIdArray = []) {
        $this->fields['rightaction'] = [
            'identification' => "rightaction",
            'name' => "操作",
            'value' => "",
            'formtype' => 'text',
            'datas' => $datas ?: [],
            'actionby' => $field,
            'notIdArray' => $notIdArray ?: [],
        ];

        return $this;
    }

    //表单模板,分并列，和垂直
    public function formView($pageData = [], $view = "add&edit") {
        if (!is_string($view)) $view = "add&edit";
        $pageData["fields"] = $this->fields;
        $pageData['title'] = $pageData["title"] ?: $this->pageTitle;
        $pageData['titleUrl'] = $pageData["titleUrl"] ?: $this->pageTitleUrl;
        $pageData['subtitle'] = $pageData["subtitle"] ?: $this->formTitle;
        $pageData['subtitleUrl'] = $pageData["subtitleUrl"] ?: $this->formTitleUrl;
        $pageData['formaction'] = $this->formaction;
        $pageData['method'] = $this->method;
        $pageData['actionName'] = $this->actionName;
        $pageData['backName'] = $this->backName;
        $pageData['formid'] = $this->formid;
        $pageData['actionType'] = $this->actionType;
        $pageData['listActions'] = $this->listActions;
        $pageData['leftListActions'] = $this->leftListActions;
        $pageData['popup'] = $this->popup;
        return view("formtools::admin.formtooltemp." . $view, compact('pageData'));

    }

    //list列表，table列表, 树形列表
    public function listView($pageData = [], $datas = []) {

        $pageData["fields"] = $this->fields;
        $pageData['title'] = $pageData["title"] ?: $this->pageTitle;
        $pageData['titleUrl'] = $pageData["titleUrl"] ?: $this->pageTitleUrl;
        $pageData['subtitle'] = $pageData["subtitle"] ?: $this->formTitle;
        $pageData['subtitleUrl'] = $pageData["subtitleUrl"] ?: $this->formTitleUrl;
        $pageData['searchFields'] = $this->searchFields;
        $pageData['searchClearEmpty'] = $this->searchClearEmpty;
        $pageData['listActions'] = $this->listActions;
        $pageData['leftListActions'] = $this->leftListActions;
        $pageData['isShowMoreCheckbox'] = $this->isShowMoreCheckbox;
        $pageData['linkAppend'] = $this->linkAppend;
        $pageData['popup'] = $this->popup;

        $pageData['datas'] = $datas;
        return view("formtools::admin.formtooltemp.list", compact('pageData'));
    }

    public function listTreeView($pageData = [], $datas = []) {

        $pageData["fields"] = $this->fields;
        $pageData['title'] = $pageData["title"] ?: $this->pageTitle;
        $pageData['titleUrl'] = $pageData["titleUrl"] ?: $this->pageTitleUrl;
        $pageData['subtitle'] = $pageData["subtitle"] ?: $this->formTitle;
        $pageData['subtitleUrl'] = $pageData["subtitleUrl"] ?: $this->formTitleUrl;
        $pageData['searchFields'] = $this->searchFields;
        $pageData['searchClearEmpty'] = $this->searchClearEmpty;
        $pageData['listActions'] = $this->listActions;
        $pageData['leftListActions'] = $this->leftListActions;
        $pageData['isShowMoreCheckbox'] = $this->isShowMoreCheckbox;
        $pageData['linkAppend'] = $this->linkAppend;
        $pageData['popup'] = $this->popup;

        $pageData['datas'] = $datas;
        return view("formtools::admin.formtooltemp.listTree", compact('pageData'));


    }

    //详情页面模板
    public function detailView($pageData = []) {
        $view = "detail";
        $pageData["fields"] = $this->fields;
        $pageData['title'] = $pageData["title"] ?: $this->pageTitle;
        $pageData['subtitle'] = $pageData["subtitle"] ?: $this->formTitle;
        $pageData['backName'] = $this->backName;
        return view("formtools::admin.formtooltemp." . $view, compact('pageData'));

    }


    public function __call(string $name, $value) {

        if (in_array($name, $this->formattr)) { //设置字段属性 , $this->属性('标识',"值"), @$name="属性",@value=['标识',"值"]
            if (count($value) == 2) {
                $this->fields[$value[0]][$name] = $value[1];
            } elseif (count($value) == 1) {
                if ($this->currentField) {
                    $this->fields[$this->currentField][$name] = $value[0];
                }
            }
            return $this;
        }
        //抛出异常
        throw new \Exception("不存在的方法 :: " . $name . "()");
    }


}
