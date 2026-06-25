@include("admin.public.header")
<body class="horizontal">
@include("admin.public.themeMenuNav")
<div class="row page-header" style="margin-bottom: -15px;">
    <div class="col-lg-6 align-self-center ">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url("admin/index")}}">{{getTranslateByKey("common_home_page")}}</a></li>
            <li class="breadcrumb-item"><a href="{{url("admin/theme")}}">{{getTranslateByKey("theme_list")}}</a></li>
            <li class="breadcrumb-item active">{{getTranslateByKey("menu_edit")}}</li>
        </ol>
    </div>
</div>
<section class="main-content mt-20">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <section class="main-content">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <form method="post" action="" id="post_form" enctype="multipart/form-data">
                                        {{csrf_field()}}

                                        <div class="form-group">
                                            <label>
                                                {{getTranslateByKey("parent_menu")}}
                                            </label>
                                            <select name="pid" class="form-control m-b">
                                                <option value="0">{{getTranslateByKey("top_level")}}</option>
                                                @include('admin.func.partials.themeMenuParentOptions', ['menus' => $menuList, 'selectedId' => $data['pid'], 'excludeId' => $data['id'], 'langList' => $langList])
                                            </select>
                                            <small class="form-text text-muted">父级菜单必须与当前位置、语言范围一致，避免前台切换语言后树结构混乱。</small>
                                        </div>


                                        <div class="form-group ">
                                            <label>
                                                {{getTranslateByKey("position")}}
                                            </label>

                                            <div class="form-inline">
                                                <div class="radio radio-inline radio-inverse">
                                                    <input id="position" name="position" type="radio" value="top"
                                                           @if($data['position']=='top') checked @endif>
                                                    <label for="position">
                                                        {{getTranslateByKey("top_menu")}}
                                                    </label>
                                                </div>

                                                <div class=" radio radio-inline radio-inverse">
                                                    <input id="position1" name="position" type="radio" value="bottom"
                                                           @if($data['position']=='bottom') checked @endif>
                                                    <label for="position1">
                                                        {{getTranslateByKey("bottom_menu")}}
                                                    </label>
                                                </div>

                                                <div class=" radio radio-inline radio-inverse">
                                                    <input id="position2" name="position" type="radio" value="footer"
                                                           @if($data['position']=='footer') checked @endif>
                                                    <label for="position2">
                                                        {{getTranslateByKey("footer_menu")}}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label>语言范围</label>
                                            <select name="lang" class="form-control form-control-rounded" id="menuLangScope">
                                                @foreach($langList as $langCode => $langName)
                                                    <option value="{{$langCode}}" @if(($data['lang'] ?? '') === $langCode) selected @endif>{{$langName}}</option>
                                                @endforeach
                                            </select>
                                            <small class="form-text text-muted">全局共享菜单会在所有语言下使用；指定语言菜单只在对应语言启用，并在该语言存在菜单时优先显示。</small>
                                        </div>

                                        <div class="form-group">
                                            <label>
                                                {{getTranslateByKey("name")}}
                                            </label>
                                            <input type="text" required name="name" value="{{$data['name']}}"
                                                   class="form-control form-control-rounded">
                                        </div>

                                        <div class="form-group ">
                                            <label>
                                                {{getTranslateByKey("jump_url")}}
                                            </label>
                                            <input type="text" required name="url" value="{{$data['url']}}"
                                                   class="form-control form-control-rounded">
                                        </div>
                                        <div class="form-group ">
                                            <label>打开方式</label>
                                            <select name="target" class="form-control form-control-rounded">
                                                <option value="_self" @if(($data['target'] ?? '_self') === '_self') selected @endif>当前窗口</option>
                                                <option value="_blank" @if(($data['target'] ?? '_self') === '_blank') selected @endif>新窗口</option>
                                            </select>
                                        </div>
                                        <div class="form-group ">
                                            <label>菜单来源</label>
                                            <select name="menu_type" class="form-control form-control-rounded">
                                                <option value="manual" @if(($data['menu_type'] ?? 'manual') === 'manual') selected @endif>手工菜单</option>
                                                <option value="module" @if(($data['menu_type'] ?? 'manual') === 'module') selected @endif>模块菜单</option>
                                                <option value="model" @if(($data['menu_type'] ?? 'manual') === 'model') selected @endif>模型菜单</option>
                                                <option value="page" @if(($data['menu_type'] ?? 'manual') === 'page') selected @endif>页面菜单</option>
                                                <option value="search" @if(($data['menu_type'] ?? 'manual') === 'search') selected @endif>搜索菜单</option>
                                            </select>
                                        </div>
                                        <div class="form-group ">
                                            <label>来源模块</label>
                                            <input type="text" name="source_module" value="{{$data['source_module'] ?? ''}}"
                                                   class="form-control form-control-rounded" placeholder="如 Formtools / News / Main">
                                        </div>
                                        <div class="form-group ">
                                            <label>来源标识</label>
                                            <input type="text" name="source_value" value="{{$data['source_value'] ?? ''}}"
                                                   class="form-control form-control-rounded" placeholder="如 news、feedback、article_search">
                                            <small class="form-text text-muted">用于记录模型入口、模块入口或搜索入口来源，方便后续菜单同步和模板扩展。</small>
                                        </div>
                                        <div class="form-group ">
                                            <label>
                                                icon
                                            </label>
                                            <div class="input-group">
                                                <input type="text" name="icon" value="{{$data['icon']}}"
                                                       class="form-control form-control-rounded">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-info" id="recommendIconButton">推荐图标</button>
                                                </span>
                                            </div>
                                            <small class="form-text text-muted">支持 Font Awesome，例如 `fa fa-home`、`fa fa-envelope`。</small>
                                        </div>

                                        <div class="form-group ">
                                            <label>
                                                {{getTranslateByKey("sort_desc")}}
                                            </label>
                                            <input type="text" name="sort" value="{{$data['sort']*1}}"
                                                   class="form-control form-control-rounded">
                                        </div>


                                        <div class="form-group ">
                                            <label>
                                                {{getTranslateByKey("icon_text")}}
                                            </label>
                                            <input type="text" name="icon_character" value="{{$data['icon_character']}}"
                                                   class="form-control form-control-rounded">
                                        </div>
                                        <div class="form-group ">
                                            <label>
                                                {{getTranslateByKey("common_image")}}
                                            </label>
                                            <div class="fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-preview" data-trigger="fileinput"
                                                     style="width: 50px; height:50px;">
                                                </div>
                                                <span class="btn btn-success  btn-file">
                                                        <span class="fileinput-new">
                                                            {{getTranslateByKey("common_select")}}
                                                        </span>
                                                        <span class="fileinput-exists">
                                                            {{getTranslateByKey("common_change")}}
                                                        </span>
                                                        <input type="file" id="images"
                                                               name="cover">
                                                    </span>
                                                <a href="#" class="btn btn-danger fileinput-exists"
                                                   data-dismiss="fileinput">
                                                    {{getTranslateByKey("common_delete")}}
                                                </a>
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label>
                                                {{getTranslateByKey("common_account_status")}}
                                            </label>

                                            <div class="form-inline">
                                                <div class="radio radio-inline radio-inverse">
                                                    <input id="status" name="status" type="radio" value="1"
                                                           @if($data['status']=='1') checked @endif>
                                                    <label for="status">
                                                        {{getTranslateByKey("common_enable")}}
                                                    </label>
                                                </div>

                                                <div class=" radio radio-inline radio-inverse">
                                                    <input id="status1" name="status" type="radio" value="2"
                                                           @if($data['status']=='2') checked @endif>
                                                    <label for="status1">
                                                        {{getTranslateByKey("common_disable")}}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="id" value="{{$data['id']}}">
                                        <input type="hidden" name="module" value="{{$data['module']}}">
                                        <button type="button" id="postButton"
                                                class="btn btn-primary margin-l-5 mx-sm-3">
                                            {{getTranslateByKey("common_submit")}}
                                        </button>
                                        <button type="button" id="ton" onclick="history.go(-1);"
                                                class="btn btn-default ">
                                            {{getTranslateByKey("common_back")}}
                                        </button>

                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body" style="min-height: 570px;">
                                    <div class="alert alert-info">
                                        可直接按模型重建当前菜单入口，适合把普通链接快速切换成列表页、留言页或表单页。
                                    </div>
                                    <div class="form-group">
                                        <label>模型入口构造器</label>
                                        <select class="form-control m-b" id="modelEntryModel">
                                            @foreach(($modelMenu['models'] ?? []) as $m)
                                                <option value="{{$m['access_identification']}}"
                                                        @if(($data['source_module'] ?? '') === 'Formtools' && str_starts_with((string) ($data['source_value'] ?? ''), $m['access_identification'] . ':')) selected @endif>
                                                    【{{$moduleArray[$modelMenu['identification']]}}】{{$m['name']}} / {{$m['access_identification']}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>页面类型</label>
                                        <select class="form-control m-b" id="modelEntryType"></select>
                                    </div>
                                    <button type="button" id="buildModelEntryButton"
                                            class="btn btn-primary margin-l-5 mx-sm-3">
                                        重新生成模型入口
                                    </button>

                                    <div class="form-group">
                                        <label>
                                            {{getTranslateByKey("module_menu")}}
                                        </label>
                                        <select class="form-control m-b" id="moduleMenu">
                                            @foreach($moduleMenu as $module)
                                                @foreach($module['menuList'] as $m)
                                                    <option
                                                        value="{{$module['identification']}}__{{$m['name']}}__{{$m['url']}}"
                                                        data-module="{{$module['identification']}}"
                                                        data-name="{{$m['name']}}"
                                                        data-url="{{$m['url']}}"
                                                        data-menu-type="module"
                                                        data-source-module="{{$module['identification']}}"
                                                        data-source-value="{{$m['url']}}"
                                                    >
                                                        【{{$moduleArray[$module['identification']]}}
                                                        】{{$m['name']}}</option>
                                                @endforeach
                                            @endforeach

                                        </select>
                                    </div>
                                    <button type="button" id="addMenuButton"
                                            class="btn btn-success margin-l-5 mx-sm-3">
                                        {{getTranslateByKey("add_to_menu")}}
                                    </button>

                                    <div class="form-group mt-5">
                                        <label>
                                            {{getTranslateByKey("model_menu")}}
                                        </label>
                                        <select class="form-control m-b" id="modelMenu">
                                            @foreach($modelMenu['menuList'] as $m)
                                                @php($sourceValue = trim(str_replace('list/', '', $m['url']), '/'))
                                                <option
                                                    value="{{$modelMenu['identification']}}__{{$m['name']}}__{{$m['url']}}"
                                                    data-module="{{$modelMenu['identification']}}"
                                                    data-name="{{$m['name']}}"
                                                    data-url="{{$m['url']}}"
                                                    data-menu-type="model"
                                                    data-source-module="{{$modelMenu['identification']}}"
                                                    data-source-value="{{$sourceValue}}"
                                                >
                                                    【{{$moduleArray[$modelMenu['identification']]}}
                                                    】{{$m['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="button" id="addModelMenuButton"
                                            class="btn btn-success margin-l-5 mx-sm-3">
                                        {{getTranslateByKey("add_to_menu")}}
                                    </button>

                                    <div class="form-group mt-5">
                                        <label>页面菜单</label>
                                        <select class="form-control m-b" id="pageMenu">
                                            @foreach($pageMenu as $page)
                                                @php($pageMenuLabel = '【页面】' . $page['name'] . ' / ' . ($page['slug'] ?: '未设置路径') . (!$page['status'] ? ' / 已停用' : '') . ($page['is_nav'] ? ' / 导航' : ''))
                                                <option
                                                    value="Formtools__{{$page['name']}}__{{$page['url']}}"
                                                    data-module="Formtools"
                                                    data-name="{{$page['name']}}"
                                                    data-url="{{$page['url']}}"
                                                    data-menu-type="page"
                                                    data-source-module="Formtools"
                                                    data-source-value="{{$page['source_value']}}"
                                                    @if(($data['source_module'] ?? '') === 'Formtools' && ($data['source_value'] ?? '') === $page['source_value']) selected @endif
                                                >
                                                    {{$pageMenuLabel}}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">快速把页面管理里的页面切换成菜单入口，默认写入正式地址 `/p/...`。</small>
                                    </div>
                                    <button type="button" id="addPageMenuButton"
                                            class="btn btn-success margin-l-5 mx-sm-3">
                                        添加页面到菜单
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body" style="min-height: 570px;">
                                    <div class="form-group">
                                        <label>
                                            {{getTranslateByKey("search_menu")}}
                                        </label>
                                        <select class="form-control m-b" id="menuModule">
                                            @foreach($moduleMenu as $module)
                                                @foreach($module['modelList'] as $m)
                                                    <option value="{{$module['identification']}}__{{$m['table']}}">
                                                        【{{$moduleArray[$module['identification']]}}
                                                        】{{$m['name']}}</option>
                                                @endforeach
                                            @endforeach

                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>
                                            {{getTranslateByKey("menu_title")}}
                                        </label>
                                        <input type="text" id="menuTitle"
                                               class="form-control form-control-rounded">
                                    </div>

                                    <button type="button" id="searchMenuButton"
                                            class="btn btn-info margin-l-5 mx-sm-3">
                                        {{getTranslateByKey("search_menu")}}
                                    </button>

                                    <div class="form-group mt-3">
                                        <label>
                                            {{getTranslateByKey("search_menu_list")}}
                                        </label>
                                        <select class="form-control m-b" id="searchModuleMenuList">
                                        </select>
                                    </div>

                                    <button type="button" id="searchAddMenuButton"
                                            class="btn btn-success margin-l-5 mx-sm-3">
                                        {{getTranslateByKey("add_to_menu")}}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @include('admin.public.footer')
                </section>
            </div>
        </div>
    </div>
</section>
@include('admin.public.js',['load'=> ["custom"]])
</body>
</html>
<script>
    $(function () {
        const themeMenuEditLang = {
            requesting: @json(getTranslateByKey('requesting')),
            pleaseSelectModule: @json(getTranslateByKey('please_select_module')),
            pleaseEnterTitle: @json(getTranslateByKey('please_enter_title'))
        };
        const modelBuilderData = @json($modelMenu['models'] ?? []);
        const currentSourceValue = @json($data['source_value'] ?? '');
        function recommendIcon(sourceValue, name) {
            const value = `${sourceValue || ''} ${name || ''}`.toLowerCase();
            if (value.includes('about') || value.includes('关于')) return 'fa fa-building-o';
            if (value.includes('contact') || value.includes('contacts') || value.includes('联系')) return 'fa fa-phone';
            if (value.includes('feedback') || value.includes('留言')) return 'fa fa-commenting-o';
            if (value.includes('agreement') || value.includes('协议')) return 'fa fa-file-text-o';
            if (value.includes('news') || value.includes('资讯') || value.includes('动态')) return 'fa fa-newspaper-o';
            if (value.includes('page:') || value.includes('页面') || value.includes('专题')) return 'fa fa-file-o';
            if (value.includes('milestone') || value.includes('历程')) return 'fa fa-flag-checkered';
            if (value.includes('handle') || value.includes('投稿') || value.includes('提交')) return 'fa fa-edit';
            return 'fa fa-circle-o';
        }
        function applyMenuPreset(option, menuType) {
            if (!option.length) {
                return;
            }
            $('input[name=module]').val(option.data('module') || 'Main');
            $('input[name=name]').val(option.data('name') || '');
            $('input[name=url]').val(option.data('url') || '#');
            $('select[name=menu_type]').val(option.data('menu-type') || menuType || 'manual');
            $('input[name=source_module]').val(option.data('source-module') || '');
            $('input[name=source_value]').val(option.data('source-value') || '');
            $('input[name=icon]').val(recommendIcon(option.data('source-value') || option.data('url') || '', option.data('name') || ''));
        }
        function getCurrentEntryKey() {
            if (!currentSourceValue || currentSourceValue.indexOf(':') === -1) {
                return null;
            }
            return currentSourceValue.split(':')[1] || null;
        }
        function populateModelEntryTypes(selectedAccess, selectedEntryKey) {
            const model = modelBuilderData.find(item => item.access_identification === selectedAccess);
            let options = '';
            if (!model) {
                $('#modelEntryType').html(options);
                return;
            }
            (model.entries || []).forEach(function (entry, index) {
                const selected = selectedEntryKey ? entry.key === selectedEntryKey : index === 0;
                options += `<option value="${entry.key}" ${selected ? 'selected' : ''}>${entry.label}</option>`;
            });
            $('#modelEntryType').html(options);
        }
        function buildModelEntry() {
            const access = $('#modelEntryModel').val();
            const entryKey = $('#modelEntryType').val();
            const model = modelBuilderData.find(item => item.access_identification === access);
            if (!model) {
                return;
            }
            const entry = (model.entries || []).find(item => item.key === entryKey) || model.entries[0];
            if (!entry) {
                return;
            }
            $('input[name=module]').val('Formtools');
            $('input[name=name]').val(entry.name || model.name);
            $('input[name=url]').val(entry.url || ('list/' + access));
            $('select[name=menu_type]').val('model');
            $('input[name=source_module]').val('Formtools');
            $('input[name=source_value]').val(`${access}:${entry.key}`);
            $('input[name=icon]').val(recommendIcon(`${access}:${entry.key}`, entry.name || model.name));
        }
        function filterParentOptions() {
            const selectedLang = $('select[name=lang]').val() || '';
            const selectedPosition = $('input[name=position]:checked').val() || 'top';
            const parentSelect = $('select[name=pid]');
            const currentValue = parentSelect.val();
            let keepCurrent = false;
            parentSelect.find('option').each(function (index) {
                if (index === 0) {
                    this.hidden = false;
                    $(this).prop('disabled', false);
                    return;
                }
                const optionLang = ($(this).data('lang') || '').toString();
                const optionPosition = ($(this).data('position') || '').toString();
                const visible = optionLang === selectedLang && optionPosition === selectedPosition;
                this.hidden = !visible;
                $(this).prop('disabled', !visible);
                if (visible && $(this).val() === currentValue) {
                    keepCurrent = true;
                }
            });
            if (!keepCurrent) {
                parentSelect.val('0');
            }
        }
        $("#postButton").click(function () {

            popup({
                type: 'load', msg: themeMenuEditLang.requesting, delay: 800, callBack: function () {

                    $.ajax({
                        "method": "post",
                        "url": "{{url('admin/theme/themeMenuEdit?m='.$_GET['m'])}}",
                        "data": new FormData($('#post_form')[0]),                    //$("#post_form").serialize(),
                        "dataType": 'json',
                        "cache": false,
                        "processData": false,
                        "contentType": false,
                        "success": function (res) {
                            if (res.status == 200) {
                                popup({type: "success", msg: res.msg, delay: 2000});
                                setTimeout(function () {
                                    location.href = "{{url('admin/theme/themeMenuList?m='.$_GET['m'])}}";
                                }, 2000);
                            } else {
                                popup({type: "error", msg: res.msg, delay: 2000});
                            }
                        },
                        "error": function (res) {
                            console.log(res);
                        }
                    })
                }
            });
        })

        $('#addMenuButton').click(function () {
            var option = $('#moduleMenu option:selected');
            if (option.length) {
                applyMenuPreset(option, 'module');
            }
        });

        $('#addModelMenuButton').click(function () {
            var option = $('#modelMenu option:selected');
            if (option.length) {
                applyMenuPreset(option, 'model');
            }
        });
        $('#addPageMenuButton').click(function () {
            var option = $('#pageMenu option:selected');
            if (option.length) {
                applyMenuPreset(option, 'page');
            }
        });
        $('#modelEntryModel').change(function () {
            populateModelEntryTypes($(this).val());
        });
        $('select[name=lang]').change(filterParentOptions);
        $('input[name=position]').change(filterParentOptions);
        $('#buildModelEntryButton').click(function () {
            buildModelEntry();
        });
        $('#recommendIconButton').click(function () {
            const sourceValue = $('input[name=source_value]').val() || $('input[name=url]').val();
            const name = $('input[name=name]').val();
            $('input[name=icon]').val(recommendIcon(sourceValue, name));
        });

        $('#searchMenuButton').click(function () {
            var table = $('#menuModule').val();
            var title = $('#menuTitle').val();
            if (!table) return layer.msg(themeMenuEditLang.pleaseSelectModule, {icon: 2});
            if (!title) return layer.msg(themeMenuEditLang.pleaseEnterTitle, {icon: 2});
            var arr = table.split('__');
            popup({
                type: 'load', msg: themeMenuEditLang.requesting, delay: 800, callBack: function () {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        },
                        "method": "post",
                        "url": "{{url('admin/theme/themeMenuSearchModuleMenu')}}",
                        "data": {
                            module: arr[0],
                            table: arr[1],
                            title: title
                        },
                        "dataType": 'json',
                        "cache": false,
                        "success": function (res) {
                            if (res.status == 200) {
                                // popup({type: "success", msg: res.msg, delay: 2000});
                                var option =``;
                                var data = res.data;
                                data.menuList.forEach(function (val) {
                                    option +=`<option value="${data.identification}__${val.name}__${val.url}" data-module="${data.identification}" data-name="${val.name}" data-url="${val.url}" data-menu-type="search" data-source-module="${data.identification}" data-source-value="${arr[1]}:${title}">${val.name}</option>`;
                                });
                                $('#searchModuleMenuList').html(option);
                            } else {
                                popup({type: "error", msg: res.msg, delay: 2000});
                            }
                        },
                        "error": function (res) {
                            console.log(res);
                        }
                    })
                }
            });
        });
        $('#searchAddMenuButton').click(function () {
            var option = $('#searchModuleMenuList option:selected');
            if (option.length) {
                applyMenuPreset(option, 'search');
            }
        });
        populateModelEntryTypes($('#modelEntryModel').val(), getCurrentEntryKey());
        filterParentOptions();
    })
</script>
