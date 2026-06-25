# FormTool 开发说明

`FormTool` 用于快速生成后台表单、列表、树形列表和详情页，适合模块控制器直接链式调用，也适合系统内部统一复用。

## 设计目标

- 保持旧接口兼容，已有控制器可以继续使用
- 统一字段、搜索项、按钮的默认结构，减少手写数组出错
- 提供更清晰的快捷方法，降低新模块接入成本
- 让模板层能力和控制器能力保持一致，避免“类支持了、模板没吃到”

## 入口方法

```php
use Modules\Formtools\Http\Controllers\Admin\FormTool;

$form = FormTool::create();
// 或
$form = FormTool::make();
```

每次 `create()` / `make()` 都会重置内部状态，适合在单个控制器动作中直接开始构建。

## 项目里的真实用法

我扫描了当前项目里多个模块对 `FormTool` 的调用，现阶段最常见的模式是：

- 列表页：`field + searchField + rightAction + listAction + listView`
- 树形列表：`field + rightAction + listTreeView`
- 编辑页：`field + formtype + datas + notes + formView`
- 配置页：大量使用 `switch / json / code / readonly / word / section`
- 业务模块：大量手写选项数组、手写动作数组、手写 `legend`
- 历史兼容：真实存在 `formtype => file` 和 `formtype => img` 的旧写法

基于这些真实用法，这一版重点补了：

- 更完整的快捷字段方法和历史别名快捷方法
- 搜索快捷方法和批量定义方法
- 选项工厂、动作工厂
- `popup / target / inlineScripts` 的模板一致性
- 树形列表模板的顶部按钮、行内按钮、详情弹窗支持
- 一个可直接访问的示例控制器和示例路由

## 示例入口

示例控制器：`Modules/Formtools/Http/Controllers/Admin/TestController.php`

示例路由：

- `GET /admin/formtools/demo`
- `ANY /admin/formtools/demo/add`
- `ANY /admin/formtools/demo/edit`
- `GET /admin/formtools/demo/detail`

这几个入口主要用于查看列表、表单、详情三种页型的标准写法，不依赖真实业务表。

## 常用能力

### 1. 快速生成选项和按钮

```php
$statusOptions = FormTool::optionsFromMap([
    1 => '启用',
    0 => '停用',
]);

$addAction = FormTool::action('新增', url('admin/demo/add'), FormTool::btn_info);
```

### 2. 定义表单字段

```php
return FormTool::create()
    ->text('title', '标题', $data['title'] ?? '', ['required' => true])
    ->textarea('description', '描述', $data['description'] ?? '', ['rows' => 6])
    ->select('status', '状态', $data['status'] ?? 1, FormTool::optionsFromMap([
        1 => '启用',
        0 => '停用',
    ]))
    ->image('cover', '封面', $data['cover'] ?? '')
    ->tags('tags', '标签', $data['tags'] ?? '')
    ->csrf_field()
    ->pageTitle('文章管理')
    ->formTitle('新增文章')
    ->formAction(url('admin/demo/save'))
    ->formView();
```

### 3. 继续使用原有 `field()` 写法

```php
return FormTool::create()
    ->field('title', [
        'name' => '标题',
        'value' => $data['title'] ?? '',
        'formtype' => 'text',
        'required' => 'required',
        'placeholder' => '请输入标题',
    ])
    ->field('content', [
        'name' => '内容',
        'value' => $data['content'] ?? '',
        'formtype' => 'editor',
    ]);
```

### 4. 批量定义字段

```php
return FormTool::create()
    ->fieldsBatch([
        'title' => ['name' => '标题', 'required' => true],
        'status' => [
            'name' => '状态',
            'formtype' => 'select',
            'datas' => FormTool::optionsFromMap([
                1 => '启用',
                0 => '停用',
            ]),
        ],
    ]);
```

### 5. 批量回填值

```php
$form = FormTool::create()
    ->text('title', '标题')
    ->number('sort', '排序')
    ->fill([
        'title' => $data['title'] ?? '',
        'sort' => $data['sort'] ?? 0,
    ]);
```

### 6. 修改已定义字段

```php
$form = FormTool::create()
    ->text('title', '标题')
    ->setFieldAttributes('title', [
        'placeholder' => '请输入标题',
        'required' => true,
        'maxlength' => 120,
    ]);
```

### 7. 分组布局 DSL

如果一个表单需要明显的分区，并且一组字段默认就是双列或单列，可以直接用分组写法：

```php
return FormTool::create()
    ->group('basic_section', '基础信息', [
        'notes' => '这组字段默认按两列渲染。',
        'columns' => 2,
    ], function (FormTool $form) use ($data) {
        $form->text('title', '标题', $data['title'] ?? '');
        $form->select('status', '状态', $data['status'] ?? 1, FormTool::optionsFromMap([
            1 => '启用',
            0 => '停用',
        ]));
        $form->text('owner', '负责人', $data['owner'] ?? '');
    })
    ->beginGroup('content_group', '内容与附件', [
        'formtype' => 'legend',
        'columns' => 2,
    ])
    ->textarea('summary', '摘要', $data['summary'] ?? '')
    ->editor('content', '正文', $data['content'] ?? '')
    ->row('content')
    ->file('attachment', '附件', $data['attachment'] ?? '')
    ->group('config_section', '扩展配置', ['columns' => 2], function (FormTool $form) use ($data) {
        $form->json('seo_json', 'SEO 配置', $data['seo_json'] ?? '');
        $form->tags('tags', '标签', $data['tags'] ?? '');
        $form->width('tags', 4);
        $form->width('seo_json', 8);
    })
    ->endGroup();
```

分组相关方法：

- `group()`：创建分组并在回调内临时应用默认字段属性
- `beginGroup()` / `endGroup()`：适合链式连续书写
- `row()`：把当前字段或指定字段切回整行展示
- `column()`：把当前字段或指定字段切成半列展示
- `columns()`：批量把多个字段设成列展示
- `width()`：把字段设置为指定栅格宽度，支持 `1-12`
- `half() / third() / quarter() / full()`：常用宽度快捷方法

这一层 DSL 不只用于表单页，`detailView()` 也会按同样的字段顺序和 `showtype` 渲染，所以详情页同样可以用分组标题和列布局来组织信息。

当前默认模板会把字段先按 `section / legend` 自动切成分组，再渲染成独立分区容器，所以分组不只是标题语义，也会直接影响页面结构和视觉层次。

## 搜索区

```php
return FormTool::create()
    ->searchText('keyword', '请输入关键词', $all['keyword'] ?? '')
    ->searchSelect('status', '状态', $all['status'] ?? '', FormTool::optionsFromMap([
        1 => '启用',
        0 => '停用',
    ]))
    ->searchDateRange('range', '时间范围', $all['range'] ?? '')
    ->searchClearEmpty(url('admin/demo/index'))
    ->listView($pageData, $datas);
```

也支持批量写法：

```php
->searchFieldsBatch([
    'keyword' => ['placeholder' => '请输入关键词'],
    'status' => [
        'placeholder' => '状态',
        'formtype' => 'select',
        'datas' => FormTool::optionsFromMap([
            1 => '启用',
            0 => '停用',
        ]),
    ],
])
```

## 列表按钮

### 顶部按钮

```php
->listAction([
    FormTool::action('新增', url('admin/demo/add'), 'bg-info'),
])
```

也可以后续追加：

```php
->appendListAction(FormTool::action('导出', url('admin/demo/export'), 'bg-warning'))
```

### 左侧批量按钮

```php
->leftListAction([
    [
        [
            'actionName' => '批量删除',
            'actionUrl' => url('admin/demo/delete'),
            'cssClass' => FormTool::btn_danger,
            'confirm' => true,
            'isMoreSelect' => true,
        ],
    ],
])
```

### 行内按钮

```php
->rightAction([
    FormTool::action('编辑', url('admin/demo/edit'), FormTool::btn_info, [
        'param' => ['page' => request('page')],
    ]),
    FormTool::action('删除', url('admin/demo/delete'), FormTool::btn_danger, [
        'confirm' => true,
        'param' => ['page' => request('page')],
    ]),
], 'id')
```

也可以后续追加：

```php
->appendRightAction(FormTool::action('预览', url('admin/demo/preview'), FormTool::btn_primary, [
    'popup' => true,
]))
```

## 动作参数说明

`listAction / leftListAction / rightAction` 支持的常用参数：

| 参数 | 说明 |
| --- | --- |
| `actionName` | 按钮名称 |
| `actionUrl` | 跳转地址 |
| `cssClass` | 按钮样式类 |
| `param` | 追加到 URL 的参数 |
| `confirm` | 是否需要确认 |
| `popup` | 是否弹窗打开 |
| `target` | 链接目标，如 `_blank` |
| `actionType` | 行内按钮类型，默认 `link`，也支持 `modal` |
| `field` | `modal` 模式下展示的字段 |
| `titleField` | `modal` 模式下标题字段 |
| `show` | 满足条件才显示按钮 |
| `notIdArray` | 指定 ID 不显示按钮 |
| `isMoreSelect` | 左侧批量按钮是否允许多选 |
| `noNeedId` | 左侧批量按钮是否不依赖勾选 ID |

## 详情页

```php
return FormTool::create()
    ->field('title', '标题')
    ->field('status', ['name' => '状态', 'datas' => [1 => '启用', 0 => '禁用']])
    ->pageTitle('文章管理')
    ->formTitle('查看详情')
    ->detailView([
        'detail' => $data,
    ]);
```

## 支持的快捷字段方法

- `text()`
- `textarea()`
- `editor()`
- `hidden()`
- `number()`
- `password()`
- `select()`
- `radio()`
- `checkbox()`
- `checkboxList()`
- `selectMore()`
- `multipleSelect()`
- `upload()`
- `file()`
- `uploadAjax()`
- `image()`
- `img()`
- `imageAjax()`
- `switchField()`
- `color()`
- `readonly()`
- `word()`
- `jsonField()`
- `json()`
- `codeField()`
- `code()`
- `tagsField()`
- `tags()`
- `date()`
- `dateMonth()`
- `dateYear()`
- `time()`
- `datetime()`
- `dateRange()`
- `datetimeRange()`
- `links()`
- `button()`
- `section()`
- `legend()`
- `group()`
- `beginGroup()`
- `endGroup()`
- `row()`
- `column()`
- `columns()`

如果需要更细粒度控制，继续优先使用 `field()`。

## 搜索快捷方法

- `searchText()`
- `searchSelect()`
- `searchRadio()`
- `searchDate()`
- `searchDateRange()`
- `searchDatetime()`
- `searchDatetimeRange()`

底层仍然统一走 `searchField()`，只是把高频类型做成了更短的写法。

## 类型兼容

`FormTool` 会自动兼容部分历史别名：

- `file` 会自动转成 `upload`
- `img` 会自动转成 `image`

另外也新增了同名快捷方法：

- `file()` 直接走 `upload`
- `img()` 直接走 `image`

这能兼容旧控制器，也方便新控制器按更直觉的名字书写。

## 模板渲染

### 表单

```php
->formView($pageData)
->formView($pageData, 'add&editColumn')
```

### 列表

```php
->listView($pageData, $datas)
->listTreeView($pageData, $datas)
```

### 详情

```php
->detailView($pageData)
```

如果传入的模板不存在，类库会自动回退到默认模板，避免直接报错。

## 导出与调试

### 获取当前页面数据

```php
$pageData = FormTool::create()
    ->text('title', '标题')
    ->toArray();
```

适合用于：

- 调试字段结构
- 做二次封装
- 生成开发文档或调试输出

## 模板增强说明

这一版模板层已补齐几处历史断点：

- 列表页、树形列表、详情页都支持 `inlineScripts`
- 列表页、树形列表、详情页都识别 `popup`
- 顶部按钮不再错误引用行级变量
- 顶部按钮和行内按钮支持 `target`
- 树形列表模板补上了 `modal` 行内动作支持
- 树形列表附件列兼容 `upload / uploadAjax / image / imageAjax`
- `section / legend` 标题渲染改成共享模板，后续样式更容易统一维护
- `text / select / textarea / number / password / readonly / date / datetime` 已开始共用表单字段外壳模板

## 建议约定

- 复杂模块继续优先使用 `field()`，确保字段描述完整
- 简单后台页优先使用快捷方法，降低样板代码量
- 选项统一通过 `optionsFromMap()` 生成，减少重复数组
- 行内按钮统一通过 `FormTool::action()` 起步，再按需补参数
- 搜索字段尽量使用已有搜索模板支持的类型，避免无效配置

## 后续可继续扩展

- 字段分组和布局 DSL
- 更统一的按钮对象结构
- 可复用的工作台卡片配置
- 自动生成 API / 前台联动说明
- 更完整的 PHPDoc 和示例截图
