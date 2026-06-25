<?php

namespace Modules\Formtools\Http\Controllers\Admin;

/**
 * FormTool 是后台表单、列表、详情页的轻量构建器。
 *
 * 设计目标：
 * 1. 保持旧接口兼容，避免影响现有模块控制器；
 * 2. 统一字段、搜索项和操作按钮的默认结构，减少数组拼装出错；
 * 3. 为二次开发提供更清晰的快捷方法、批量写法和导出能力。
 */
class FormTool
{
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

    const FORM_TYPES = [
        "text", "textarea", "editor", "password", "radio", "checkbox", "checkboxList",
        "select", "selectMore", "multipleSelect", "number", "color",
        "upload", "uploadAjax", "image", "imageAjax",
        "date", "dateMonth", "dateYear", "time", "datetime", "dateRange", "datetimeRange",
        "hidden", "readonly", "word", "links", "legend", "button", "section", "switch",
        "json", "code", "tags"
    ];

    const FORM_TYPE_ALIASES = [
        'file' => 'upload',
        'img' => 'image',
    ];

    const FIELD_ATTRIBUTES = [
        "name", "placeholder", "value", "datas", "required", "rule", "regex", "maxlength",
        "formtype", "disabled", "cssClass", "callback", "jsfunction", "notes", "aInfo",
        "showtype", "width", "template", "relate", "relateaction", "actionby", "rows"
    ];

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
    private $tips = ''; // 列表/表单提示
    private $inlineScripts = []; // 页面内联脚本
    private $formtype = self::FORM_TYPES;
    private $formattr = self::FIELD_ATTRIBUTES;
    private $fieldDefaultStack = [];

    private static $instance = null;
    private $currentField = null;

    private function __construct()
    {
        // 私有化构造函数，防止外部创建实例
    }

    public static function create()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        self::$instance->reset();
        return self::$instance;
    }

    public static function make()
    {
        return self::create();
    }

    public static function supportedFormTypes(): array
    {
        return self::FORM_TYPES;
    }

    public static function supportedFieldAttributes(): array
    {
        return self::FIELD_ATTRIBUTES;
    }

    public static function supportsFormType($formtype): bool
    {
        return in_array(self::normalizeFormTypeName($formtype), self::FORM_TYPES, true);
    }

    public static function option($value, $name, array $extra = []): array
    {
        return array_merge([
            'value' => $value,
            'name' => $name,
        ], $extra);
    }

    public static function optionsFromMap(array $map): array
    {
        $options = [];
        foreach ($map as $value => $name) {
            $options[] = self::option($value, $name);
        }
        return $options;
    }

    public static function action(string $name, string $url, string $cssClass = self::btn_default, array $options = []): array
    {
        return array_merge([
            'actionName' => $name,
            'actionUrl' => $url,
            'cssClass' => $cssClass,
        ], $options);
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
    public function field($identification, $name, $value = '', $formtype = "text", $datas = [])
    {
        $identification = trim((string) $identification);
        if ($identification === '') {
            return $this;
        }

        if (is_string($name)) {
            $this->fields[$identification] = $this->normalizeFieldDefinition(array_merge($this->currentFieldDefaults(), [
                'identification' => $identification,
                'name' => $name,
                'value' => $value,
                'formtype' => $formtype,
                'datas' => $datas,
            ]));
        } elseif (is_array($name)) {
            $this->fields[$identification] = $this->normalizeFieldDefinition(array_merge($this->currentFieldDefaults(), $name, [
                'identification' => $identification,
            ]));
        }

        $this->currentField = $identification;
        return $this;
    }

    public function fieldsBatch(array $fields)
    {
        foreach ($fields as $identification => $definition) {
            if (is_array($definition) && isset($definition['identification'])) {
                $this->field($definition['identification'], $definition);
                continue;
            }

            if (is_string($identification) && is_array($definition)) {
                $this->field($identification, $definition);
            }
        }

        return $this;
    }

    public function setFieldAttributes($identification, array $attributes)
    {
        if (!$this->hasField($identification)) {
            return $this;
        }

        foreach ($attributes as $attribute => $value) {
            if (!in_array($attribute, $this->formattr, true)) {
                continue;
            }
            $this->fields[$identification][$attribute] = $this->normalizeFieldAttributeValue($attribute, $value);
        }

        $this->currentField = $identification;
        return $this;
    }

    public function fill(array $values)
    {
        foreach ($values as $identification => $value) {
            if ($this->hasField($identification)) {
                $this->fields[$identification]['value'] = $value;
            }
        }

        return $this;
    }

    public function hasField($identification): bool
    {
        return isset($this->fields[$identification]);
    }

    public function getField($identification, $default = null)
    {
        return $this->fields[$identification] ?? $default;
    }

    public function removeField($identification)
    {
        unset($this->fields[$identification]);
        if ($this->currentField === $identification) {
            $this->currentField = null;
        }
        return $this;
    }

    public function useField($identification)
    {
        if ($this->hasField($identification)) {
            $this->currentField = $identification;
        }
        return $this;
    }

    public function text($identification, $name, $value = '', array $attributes = [])
    {
        return $this->quickField('text', $identification, $name, $value, [], $attributes);
    }

    public function textarea($identification, $name, $value = '', array $attributes = [])
    {
        return $this->quickField('textarea', $identification, $name, $value, [], $attributes);
    }

    public function editor($identification, $name, $value = '', array $attributes = [])
    {
        return $this->quickField('editor', $identification, $name, $value, [], $attributes);
    }

    public function hidden($identification, $value = '', array $attributes = [])
    {
        return $this->quickField('hidden', $identification, '', $value, [], $attributes);
    }

    public function number($identification, $name, $value = '', array $attributes = [])
    {
        return $this->quickField('number', $identification, $name, $value, [], $attributes);
    }

    public function password($identification, $name, $value = '', array $attributes = [])
    {
        return $this->quickField('password', $identification, $name, $value, [], $attributes);
    }

    public function select($identification, $name, $value = '', array $datas = [], array $attributes = [])
    {
        return $this->quickField('select', $identification, $name, $value, $datas, $attributes);
    }

    public function radio($identification, $name, $value = '', array $datas = [], array $attributes = [])
    {
        return $this->quickField('radio', $identification, $name, $value, $datas, $attributes);
    }

    public function checkbox($identification, $name, $value = '', array $datas = [], array $attributes = [])
    {
        return $this->quickField('checkbox', $identification, $name, $value, $datas, $attributes);
    }

    public function checkboxList($identification, $name, $value = '', array $datas = [], array $attributes = [])
    {
        return $this->quickField('checkboxList', $identification, $name, $value, $datas, $attributes);
    }

    public function selectMore($identification, $name, $value = '', array $datas = [], array $attributes = [])
    {
        return $this->quickField('selectMore', $identification, $name, $value, $datas, $attributes);
    }

    public function multipleSelect($identification, $name, $value = '', array $datas = [], array $attributes = [])
    {
        return $this->quickField('multipleSelect', $identification, $name, $value, $datas, $attributes);
    }

    public function upload($identification, $name, $value = '', array $attributes = [])
    {
        return $this->quickField('upload', $identification, $name, $value, [], $attributes);
    }

    public function file($identification, $name, $value = '', array $attributes = [])
    {
        return $this->upload($identification, $name, $value, $attributes);
    }

    public function uploadAjax($identification, $name, $value = '', array $attributes = [])
    {
        return $this->quickField('uploadAjax', $identification, $name, $value, [], $attributes);
    }

    public function image($identification, $name, $value = '', array $attributes = [])
    {
        return $this->quickField('image', $identification, $name, $value, [], $attributes);
    }

    public function img($identification, $name, $value = '', array $attributes = [])
    {
        return $this->image($identification, $name, $value, $attributes);
    }

    public function imageAjax($identification, $name, $value = '', array $attributes = [])
    {
        return $this->quickField('imageAjax', $identification, $name, $value, [], $attributes);
    }

    public function switchField($identification, $name, $value = 1, array $datas = [], array $attributes = [])
    {
        return $this->quickField('switch', $identification, $name, $value, $datas, $attributes);
    }

    public function color($identification, $name, $value = '', array $attributes = [])
    {
        return $this->quickField('color', $identification, $name, $value, [], $attributes);
    }

    public function readonly($identification, $name, $value = '', array $attributes = [])
    {
        return $this->quickField('readonly', $identification, $name, $value, [], $attributes);
    }

    public function word($identification, $name, $value = '', array $attributes = [])
    {
        return $this->quickField('word', $identification, $name, $value, [], $attributes);
    }

    public function jsonField($identification, $name, $value = '', array $attributes = [])
    {
        return $this->quickField('json', $identification, $name, $value, [], $attributes);
    }

    public function json($identification, $name, $value = '', array $attributes = [])
    {
        return $this->jsonField($identification, $name, $value, $attributes);
    }

    public function codeField($identification, $name, $value = '', array $attributes = [])
    {
        return $this->quickField('code', $identification, $name, $value, [], $attributes);
    }

    public function code($identification, $name, $value = '', array $attributes = [])
    {
        return $this->codeField($identification, $name, $value, $attributes);
    }

    public function tagsField($identification, $name, $value = '', array $attributes = [])
    {
        return $this->quickField('tags', $identification, $name, $value, [], $attributes);
    }

    public function tags($identification, $name, $value = '', array $attributes = [])
    {
        return $this->tagsField($identification, $name, $value, $attributes);
    }

    public function date($identification, $name, $value = '', array $attributes = [])
    {
        return $this->quickField('date', $identification, $name, $value, [], $attributes);
    }

    public function dateMonth($identification, $name, $value = '', array $attributes = [])
    {
        return $this->quickField('dateMonth', $identification, $name, $value, [], $attributes);
    }

    public function dateYear($identification, $name, $value = '', array $attributes = [])
    {
        return $this->quickField('dateYear', $identification, $name, $value, [], $attributes);
    }

    public function time($identification, $name, $value = '', array $attributes = [])
    {
        return $this->quickField('time', $identification, $name, $value, [], $attributes);
    }

    public function datetime($identification, $name, $value = '', array $attributes = [])
    {
        return $this->quickField('datetime', $identification, $name, $value, [], $attributes);
    }

    public function dateRange($identification, $name, $value = '', array $attributes = [])
    {
        return $this->quickField('dateRange', $identification, $name, $value, [], $attributes);
    }

    public function datetimeRange($identification, $name, $value = '', array $attributes = [])
    {
        return $this->quickField('datetimeRange', $identification, $name, $value, [], $attributes);
    }

    public function links($identification, $name, array $datas = [], array $attributes = [])
    {
        return $this->quickField('links', $identification, $name, '', $datas, $attributes);
    }

    public function button($identification, $name, $value = '', array $datas = [], array $attributes = [])
    {
        return $this->quickField('button', $identification, $name, $value, $datas, $attributes);
    }

    public function section($identification, $title, $notes = '')
    {
        return $this->field($identification, [
            'name' => $title,
            'formtype' => 'section',
            'notes' => $notes,
        ]);
    }

    public function legend($identification, $title, $notes = '')
    {
        return $this->field($identification, [
            'name' => $title,
            'formtype' => 'legend',
            'notes' => $notes,
        ]);
    }

    public function group($identification, $title, array $options = [], ?callable $callback = null)
    {
        $options = $this->normalizeGroupOptions($options);
        $headingMethod = $options['formtype'] === 'legend' ? 'legend' : 'section';
        $this->{$headingMethod}($identification, $title, $options['notes']);

        $defaults = $this->resolveGroupDefaults($options);
        if ($callback) {
            return $this->withFieldDefaults($defaults, $callback);
        }

        if ($defaults) {
            $this->fieldDefaultStack[] = $defaults;
        }

        return $this;
    }

    public function beginGroup($identification, $title, array $options = [])
    {
        return $this->group($identification, $title, $options);
    }

    public function endGroup()
    {
        array_pop($this->fieldDefaultStack);
        return $this;
    }

    public function row($identification = null)
    {
        return $this->setFieldShowtype($identification, 'row');
    }

    public function column($identification = null)
    {
        return $this->setFieldShowtype($identification, 'column');
    }

    public function columns(array $identifications)
    {
        foreach ($identifications as $identification) {
            $this->column($identification);
        }

        return $this;
    }

    public function width($identification, $width = null)
    {
        if ($width === null) {
            $width = $identification;
            $identification = null;
        }

        return $this->setFieldWidth($identification, $width);
    }

    public function half($identification = null)
    {
        return $this->width($identification, 6);
    }

    public function third($identification = null)
    {
        return $this->width($identification, 4);
    }

    public function quarter($identification = null)
    {
        return $this->width($identification, 3);
    }

    public function full($identification = null)
    {
        return $this->width($identification, 12);
    }

    //csrf字段
    public function csrf_field()
    {
        return $this->hidden('_token', csrf_token(), ['required' => 'required']);
    }

    //返回上一个页面
    public function jumpPrevUrl()
    {
        $referer = request()->headers->get('referer', '');
        return $this->hidden('jumpUrl', $referer);
    }

    //表单提交地址
    public function formAction($url)
    {
        $this->formaction = (string) $url;
        return $this;
    }

    //提交method
    public function method($method)
    {
        $method = strtolower(trim((string) $method));
        $this->method = in_array($method, ['get', 'post', 'put', 'patch', 'delete'], true) ? $method : 'post';
        return $this;
    }

    //表单标题
    public function formTitle($title, $url = null)
    {
        $this->formTitle = (string) $title;
        $this->formTitleUrl = $url;
        return $this;
    }

    //页面标题
    public function pageTitle($title, $url = null)
    {
        $this->pageTitle = (string) $title;
        $this->pageTitleUrl = $url;
        return $this;
    }

    //按钮显示文字
    public function actionName($name)
    {
        $this->actionName = (string) $name;
        return $this;
    }

    //按钮显示文字
    public function backName($name)
    {
        $this->backName = (string) $name;
        return $this;
    }

    //搜索表单
    public function searchField($identification, $placeholder, $value = "", $formtype = "text", $datas = [])
    {
        $identification = trim((string) $identification);
        if ($identification === '') {
            return $this;
        }

        $this->searchFields[$identification] = $this->normalizeSearchFieldDefinition([
            'identification' => $identification,
            'placeholder' => $placeholder,
            'value' => $value,
            'formtype' => $formtype,
            'datas' => $datas,
        ]);

        return $this;
    }

    public function searchText($identification, $placeholder, $value = '')
    {
        return $this->searchField($identification, $placeholder, $value, 'text');
    }

    public function searchSelect($identification, $placeholder, $value = '', array $datas = [])
    {
        return $this->searchField($identification, $placeholder, $value, 'select', $datas);
    }

    public function searchRadio($identification, $placeholder, $value = '', array $datas = [])
    {
        return $this->searchField($identification, $placeholder, $value, 'radio', $datas);
    }

    public function searchDate($identification, $placeholder, $value = '')
    {
        return $this->searchField($identification, $placeholder, $value, 'date');
    }

    public function searchDateRange($identification, $placeholder, $value = '')
    {
        return $this->searchField($identification, $placeholder, $value, 'dateRange');
    }

    public function searchDatetime($identification, $placeholder, $value = '')
    {
        return $this->searchField($identification, $placeholder, $value, 'datetime');
    }

    public function searchDatetimeRange($identification, $placeholder, $value = '')
    {
        return $this->searchField($identification, $placeholder, $value, 'datetimeRange');
    }

    public function searchFieldsBatch(array $fields)
    {
        foreach ($fields as $identification => $definition) {
            if (is_array($definition) && isset($definition['identification'])) {
                $this->searchField(
                    $definition['identification'],
                    $definition['placeholder'] ?? '',
                    $definition['value'] ?? '',
                    $definition['formtype'] ?? 'text',
                    $definition['datas'] ?? []
                );
                continue;
            }

            if (is_string($identification) && is_array($definition)) {
                $this->searchField(
                    $identification,
                    $definition['placeholder'] ?? '',
                    $definition['value'] ?? '',
                    $definition['formtype'] ?? 'text',
                    $definition['datas'] ?? []
                );
            }
        }

        return $this;
    }

    //搜索表单清空按钮
    public function searchClearEmpty($url)
    {
        $this->searchClearEmpty = $url ?: null;
        return $this;
    }

    //form表单绑定id
    public function formid($formid)
    {
        $this->formid = trim((string) $formid) ?: 'myForm';
        return $this;
    }

    public function tips($tip)
    {
        $this->tips = (string) $tip;
        return $this;
    }

    public function appendTips($tip, string $glue = PHP_EOL)
    {
        $tip = trim((string) $tip);
        if ($tip === '') {
            return $this;
        }

        $this->tips = $this->tips === '' ? $tip : $this->tips . $glue . $tip;
        return $this;
    }

    public function inlineScript($script)
    {
        $script = is_string($script) ? trim($script) : '';
        if ($script !== '' && !in_array($script, $this->inlineScripts, true)) {
            $this->inlineScripts[] = $script;
        }
        return $this;
    }

    public function inlineScripts(array $scripts)
    {
        foreach ($scripts as $script) {
            $this->inlineScript($script);
        }
        return $this;
    }

    //是否已弹窗方式显示
    public function popup($popup = false)
    {
        if (!$popup && request()->input('popup')) {
            $popup = true;
        }
        $this->popup = (bool) $popup;
        return $this;
    }

    //form表单提交使用方式提交 默认form
    public function actionType($actionType = 'ajax')
    {
        $actionType = strtolower(trim((string) $actionType));
        $this->actionType = in_array($actionType, ['ajax', 'form'], true) ? $actionType : 'form';
        return $this;
    }

    //列表模板的操作按钮
    /*[
       ['actionName'=>'添加','actionUrl'=>url("admin/formtools/testadd"),'cssClass'=>"bg-info"],
       ['actionName'=>'添加','actionUrl'=>url("admin/formtools/testadd"),'cssClass'=>"bg-info"]
    ]*/
    public function listAction($datas = [])
    {
        $this->listActions = $this->normalizeListActionCollection($datas);
        return $this;
    }

    public function appendListAction(array $action)
    {
        $this->listActions[] = $this->normalizeButtonAction($action);
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
    public function leftListAction($datas = [])
    {
        $this->leftListActions = $this->normalizeLeftListActionCollection($datas);
        return $this;
    }

    public function appendLeftListActionGroup(array $actions)
    {
        $this->leftListActions[] = $this->normalizeLeftActionGroup($actions);
        return $this;
    }

    //是否显示多选checkbox
    public function isShowMoreCheckbox($filed = false)
    {
        $this->isShowMoreCheckbox = $filed;
        return $this;
    }

    //只获取数据
    public function getData()
    {
        return $this->buildPageData([]);
    }

    public function toArray(array $pageData = [])
    {
        return $this->buildAllPageData($pageData);
    }

    //追加链接参数
    public function linkAppend($value = [])
    {
        $this->linkAppend = is_array($value) ? $value : [];
        return $this;
    }

    //数据的操作按钮 ['notIdArray'=>[],'actionName'=>'编辑','actionUrl'=>url("admin/formtools/testedit"),'cssClass'=>"btn-success",'param'=>['page'=>$_GET['page']]],
    // ['notIdArray'=>[],'actionName'=>'删除','actionUrl'=>url('admin/formtools/testdel'),'cssClass'=>"btn-danger","confirm"=>true,'param'=>['page'=>$_GET['page']]
    // @$datas  actionName操作名称，actionUrl操作地址，cssClass样式 string，param参数，confirm是否需要确认
    // @$field 操作的字段标识
    // @$not_ids 数组内的id不显示所有操作
    public function rightAction($datas, $field = "", $notIdArray = [])
    {
        $field = trim((string) $field);
        $this->fields['rightaction'] = [
            'identification' => "rightaction",
            'name' => "操作",
            'value' => "",
            'formtype' => 'text',
            'datas' => $this->normalizeRightActionCollection($datas ?: []),
            'actionby' => $field,
            'notIdArray' => is_array($notIdArray) ? array_values($notIdArray) : [],
        ];

        return $this;
    }

    public function appendRightAction(array $action)
    {
        if (!isset($this->fields['rightaction'])) {
            $this->rightAction([], 'id');
        }

        $this->fields['rightaction']['datas'][] = $this->normalizeRightButtonAction($action);
        return $this;
    }

    //表单模板,分并列，和垂直
    public function formView($pageData = [], $view = "add&edit")
    {
        if (!is_string($view)) {
            $view = "add&edit";
        }
        $pageData = $this->buildPageData($pageData);
        return view($this->resolveFormtoolView($view, 'add&edit'), compact('pageData'));
    }

    //list列表，table列表, 树形列表
    public function listView($pageData = [], $datas = [], $view = 'list')
    {
        $pageData = $this->buildListPageData($pageData, $datas);
        return view($this->resolveFormtoolView($view, 'list'), compact('pageData'));
    }

    public function listTreeView($pageData = [], $datas = [], $view = 'listTree')
    {
        $pageData = $this->buildListPageData($pageData, $datas);
        return view($this->resolveFormtoolView($view, 'listTree'), compact('pageData'));
    }

    //详情页面模板
    public function detailView($pageData = [], $view = "detail")
    {
        $pageData = $this->buildPageData($pageData);
        return view($this->resolveFormtoolView($view, 'detail'), compact('pageData'));
    }

    public function __call(string $name, $value)
    {
        if (in_array($name, $this->formattr, true)) { //设置字段属性 , $this->属性('标识',"值"), @$name="属性",@value=['标识',"值"]
            if (count($value) == 2 && isset($this->fields[$value[0]])) {
                $this->fields[$value[0]][$name] = $this->normalizeFieldAttributeValue($name, $value[1]);
                $this->currentField = $value[0];
            } elseif (count($value) == 1 && $this->currentField && isset($this->fields[$this->currentField])) {
                $this->fields[$this->currentField][$name] = $this->normalizeFieldAttributeValue($name, $value[0]);
            }
            return $this;
        }
        //抛出异常
        throw new \Exception("不存在的方法 :: " . $name . "()");
    }

    private function reset()
    {
        $this->fields = [];
        $this->searchFields = [];
        $this->searchClearEmpty = '';
        $this->formaction = '';
        $this->method = 'post';
        $this->formTitle = '';
        $this->formTitleUrl = '';
        $this->pageTitle = '';
        $this->pageTitleUrl = '';
        $this->actionName = '提交';
        $this->actionType = 'form';
        $this->backName = '返回';
        $this->formid = 'myForm';
        $this->listActions = [];
        $this->leftListActions = [];
        $this->isShowMoreCheckbox = false;
        $this->popup = false;
        $this->linkAppend = [];
        $this->tips = '';
        $this->inlineScripts = [];
        $this->currentField = null;
        $this->fieldDefaultStack = [];
    }

    private function quickField(string $formtype, $identification, $name, $value = '', array $datas = [], array $attributes = [])
    {
        return $this->field($identification, array_merge($attributes, [
            'name' => $name,
            'value' => $value,
            'formtype' => $formtype,
            'datas' => $datas,
        ]));
    }

    private function normalizeFieldDefinition(array $field): array
    {
        $identification = trim((string) ($field['identification'] ?? ''));
        $showtype = trim((string) ($field['showtype'] ?? 'row'));
        $width = $this->normalizeColumnWidth($field['width'] ?? 0);
        if (!in_array($showtype, ['row', 'column'], true)) {
            $showtype = 'row';
        }
        if ($width > 0) {
            $showtype = 'column';
        }

        return [
            'identification' => $identification,
            'name' => $field['name'] ?? '',
            'value' => $field['value'] ?? '',
            'formtype' => self::normalizeFormTypeName($field['formtype'] ?? 'text'),
            'datas' => is_array($field['datas'] ?? null) ? $field['datas'] : [],
            'notes' => (string) ($field['notes'] ?? ''),
            'required' => $this->normalizeRequiredValue($field['required'] ?? ''),
            'placeholder' => (string) ($field['placeholder'] ?? ''),
            'rule' => (string) ($field['rule'] ?? 'string'),
            'regex' => (string) ($field['regex'] ?? ''),
            'maxlength' => max(0, intval($field['maxlength'] ?? 0)),
            'disabled' => $field['disabled'] ?? '',
            'cssClass' => $field['cssClass'] ?? '',
            'callback' => $field['callback'] ?? null,
            'jsfunction' => (string) ($field['jsfunction'] ?? ''),
            'aInfo' => is_array($field['aInfo'] ?? null) ? $field['aInfo'] : '',
            'showtype' => $showtype,
            'width' => $width,
            'template' => (string) ($field['template'] ?? ''),
            'relate' => $field['relate'] ?? '',
            'relateaction' => $field['relateaction'] ?? '',
            'actionby' => $field['actionby'] ?? '',
            'rows' => max(1, intval($field['rows'] ?? 5)),
        ];
    }

    private function normalizeSearchFieldDefinition(array $field): array
    {
        $formtype = self::normalizeFormTypeName($field['formtype'] ?? 'text');
        $searchView = 'formtools::admin.formsearchtemplates.' . $formtype;
        if (!view()->exists($searchView)) {
            $formtype = 'text';
        }

        return [
            'identification' => trim((string) ($field['identification'] ?? '')),
            'placeholder' => (string) ($field['placeholder'] ?? ''),
            'value' => $field['value'] ?? '',
            'formtype' => $formtype,
            'datas' => is_array($field['datas'] ?? null) ? $field['datas'] : [],
        ];
    }

    private function normalizeFieldAttributeValue(string $name, $value)
    {
        if ($name === 'formtype') {
            return self::normalizeFormTypeName($value);
        }

        if ($name === 'datas') {
            return is_array($value) ? $value : [];
        }

        if ($name === 'maxlength' || $name === 'rows') {
            $normalized = intval($value);
            return $name === 'rows' ? max(1, $normalized ?: 5) : max(0, $normalized);
        }

        if ($name === 'required') {
            return $this->normalizeRequiredValue($value);
        }

        if ($name === 'showtype') {
            return in_array($value, ['row', 'column'], true) ? $value : 'row';
        }

        if ($name === 'width') {
            return $this->normalizeColumnWidth($value);
        }

        if ($name === 'aInfo') {
            return is_array($value) ? $value : '';
        }

        return $value;
    }

    private function normalizeRequiredValue($value)
    {
        if ($value === true) {
            return 'required';
        }

        if ($value === false || $value === null) {
            return '';
        }

        return $value;
    }

    private function normalizeGroupOptions(array $options): array
    {
        return [
            'notes' => (string) ($options['notes'] ?? ''),
            'columns' => max(0, intval($options['columns'] ?? 0)),
            'formtype' => (($options['formtype'] ?? 'section') === 'legend') ? 'legend' : 'section',
            'defaults' => is_array($options['defaults'] ?? null) ? $options['defaults'] : [],
        ];
    }

    private function resolveGroupDefaults(array $options): array
    {
        $defaults = [];
        foreach ($options['defaults'] as $attribute => $value) {
            if (!in_array($attribute, $this->formattr, true)) {
                continue;
            }
            $defaults[$attribute] = $this->normalizeFieldAttributeValue($attribute, $value);
        }

        if ($options['columns'] > 0 && !isset($defaults['showtype'])) {
            $defaults['showtype'] = $options['columns'] > 1 ? 'column' : 'row';
        }

        if ($options['columns'] > 1 && !isset($defaults['width'])) {
            $calculatedWidth = intval(12 / $options['columns']);
            if ($calculatedWidth > 0 && $calculatedWidth * $options['columns'] === 12) {
                $defaults['width'] = $calculatedWidth;
            }
        }

        return $defaults;
    }

    private function withFieldDefaults(array $defaults, callable $callback)
    {
        if ($defaults) {
            $this->fieldDefaultStack[] = $defaults;
        }

        try {
            $callback($this);
        } finally {
            if ($defaults) {
                array_pop($this->fieldDefaultStack);
            }
        }

        return $this;
    }

    private function currentFieldDefaults(): array
    {
        if (!$this->fieldDefaultStack) {
            return [];
        }

        $defaults = [];
        foreach ($this->fieldDefaultStack as $stackDefaults) {
            $defaults = array_merge($defaults, $stackDefaults);
        }

        return $defaults;
    }

    private function setFieldShowtype($identification, string $showtype)
    {
        if ($identification === null || $identification === '') {
            if ($this->currentField && $this->hasField($this->currentField)) {
                $this->fields[$this->currentField]['showtype'] = $showtype;
            }
            return $this;
        }

        return $this->setFieldAttributes($identification, ['showtype' => $showtype]);
    }

    private function setFieldWidth($identification, $width)
    {
        $normalizedWidth = $this->normalizeColumnWidth($width);
        if ($identification === null || $identification === '') {
            if ($this->currentField && $this->hasField($this->currentField)) {
                $this->fields[$this->currentField]['width'] = $normalizedWidth;
                $this->fields[$this->currentField]['showtype'] = 'column';
            }
            return $this;
        }

        return $this->setFieldAttributes($identification, [
            'width' => $normalizedWidth,
            'showtype' => 'column',
        ]);
    }

    private function normalizeColumnWidth($value): int
    {
        $width = intval($value);
        if ($width <= 0) {
            return 0;
        }

        return min(12, $width);
    }

    private static function normalizeFormTypeName($formtype): string
    {
        $formtype = trim((string) $formtype);
        if ($formtype === '') {
            return 'text';
        }

        if (isset(self::FORM_TYPE_ALIASES[$formtype])) {
            $formtype = self::FORM_TYPE_ALIASES[$formtype];
        }

        return in_array($formtype, self::FORM_TYPES, true) ? $formtype : 'text';
    }

    private function normalizeListActionCollection($datas): array
    {
        if (!is_array($datas)) {
            return [];
        }

        return array_values(array_map(function ($action) {
            return $this->normalizeButtonAction(is_array($action) ? $action : []);
        }, $datas));
    }

    private function normalizeLeftListActionCollection($datas): array
    {
        if (!is_array($datas) || !$datas) {
            return [];
        }

        $isSingleGroup = isset($datas[0]) && is_array($datas[0]) && array_key_exists('actionName', $datas[0]);
        if ($isSingleGroup) {
            return [$this->normalizeLeftActionGroup($datas)];
        }

        $groups = [];
        foreach ($datas as $group) {
            if (!is_array($group)) {
                continue;
            }
            $groups[] = $this->normalizeLeftActionGroup($group);
        }

        return $groups;
    }

    private function normalizeLeftActionGroup(array $actions): array
    {
        return array_values(array_map(function ($action) {
            return $this->normalizeButtonAction(is_array($action) ? $action : []);
        }, $actions));
    }

    private function normalizeRightActionCollection($datas): array
    {
        if (!is_array($datas)) {
            return [];
        }

        return array_values(array_map(function ($action) {
            return $this->normalizeRightButtonAction(is_array($action) ? $action : []);
        }, $datas));
    }

    private function normalizeButtonAction(array $action): array
    {
        return [
            'actionName' => (string) ($action['actionName'] ?? ''),
            'actionUrl' => (string) ($action['actionUrl'] ?? ''),
            'cssClass' => (string) ($action['cssClass'] ?? self::btn_default),
            'param' => is_array($action['param'] ?? null) ? $action['param'] : [],
            'confirm' => $action['confirm'] ?? false,
            'popup' => (bool) ($action['popup'] ?? false),
            'target' => (string) ($action['target'] ?? ''),
            'isMoreSelect' => (bool) ($action['isMoreSelect'] ?? false),
            'noNeedId' => (bool) ($action['noNeedId'] ?? false),
            'show' => is_array($action['show'] ?? null) ? $action['show'] : [],
            'notIdArray' => is_array($action['notIdArray'] ?? null) ? array_values($action['notIdArray']) : [],
            'actionType' => (string) ($action['actionType'] ?? 'link'),
            'titleField' => (string) ($action['titleField'] ?? 'id'),
            'field' => (string) ($action['field'] ?? ''),
        ];
    }

    private function normalizeRightButtonAction(array $action): array
    {
        return $this->normalizeButtonAction($action);
    }

    private function buildPageData(array $pageData): array
    {
        $pageData["fields"] = $pageData["fields"] ?? $this->fields;
        $pageData['fieldGroups'] = $pageData['fieldGroups'] ?? $this->buildFieldGroups($pageData["fields"]);
        $pageData['title'] = $pageData["title"] ?? $this->pageTitle;
        $pageData['titleUrl'] = $pageData["titleUrl"] ?? $this->pageTitleUrl;
        $pageData['subtitle'] = $pageData["subtitle"] ?? $this->formTitle;
        $pageData['subtitleUrl'] = $pageData["subtitleUrl"] ?? $this->formTitleUrl;
        $pageData['formaction'] = $pageData['formaction'] ?? $this->formaction;
        $pageData['method'] = $pageData['method'] ?? $this->method;
        $pageData['actionName'] = $pageData['actionName'] ?? $this->actionName;
        $pageData['backName'] = $pageData['backName'] ?? $this->backName;
        $pageData['formid'] = $pageData['formid'] ?? $this->formid;
        $pageData['actionType'] = $pageData['actionType'] ?? $this->actionType;
        $pageData['listActions'] = $pageData['listActions'] ?? $this->listActions;
        $pageData['leftListActions'] = $pageData['leftListActions'] ?? $this->leftListActions;
        $pageData['popup'] = $pageData['popup'] ?? $this->popup;
        $pageData['tips'] = $pageData['tips'] ?? $this->tips;
        $pageData['inlineScripts'] = $pageData['inlineScripts'] ?? $this->inlineScripts;
        return $pageData;
    }

    private function buildFieldGroups(array $fields): array
    {
        $groups = [];
        $currentGroup = [
            'heading' => null,
            'fields' => [],
        ];

        foreach ($fields as $field) {
            if (!is_array($field)) {
                continue;
            }

            $formtype = $field['formtype'] ?? 'text';
            if (in_array($formtype, ['section', 'legend'], true)) {
                if ($currentGroup['heading'] || $currentGroup['fields']) {
                    $groups[] = $currentGroup;
                }
                $currentGroup = [
                    'heading' => $field,
                    'fields' => [],
                ];
                continue;
            }

            $currentGroup['fields'][] = $field;
        }

        if ($currentGroup['heading'] || $currentGroup['fields']) {
            $groups[] = $currentGroup;
        }

        return $groups;
    }

    private function buildListPageData(array $pageData, $datas): array
    {
        $pageData = $this->buildPageData($pageData);
        $pageData['searchFields'] = $pageData['searchFields'] ?? $this->searchFields;
        $pageData['searchClearEmpty'] = $pageData['searchClearEmpty'] ?? $this->searchClearEmpty;
        $pageData['isShowMoreCheckbox'] = $pageData['isShowMoreCheckbox'] ?? $this->isShowMoreCheckbox;
        $pageData['linkAppend'] = $pageData['linkAppend'] ?? $this->linkAppend;
        $pageData['datas'] = $pageData['datas'] ?? $datas;
        return $pageData;
    }

    private function buildAllPageData(array $pageData): array
    {
        return $this->buildListPageData($pageData, $pageData['datas'] ?? []);
    }

    private function resolveFormtoolView($view, string $fallback): string
    {
        $view = trim((string) $view);
        if ($view === '') {
            $view = $fallback;
        }

        $candidate = "formtools::admin.formtooltemp." . $view;
        $fallbackView = "formtools::admin.formtooltemp." . $fallback;

        return view()->exists($candidate) ? $candidate : $fallbackView;
    }
}
