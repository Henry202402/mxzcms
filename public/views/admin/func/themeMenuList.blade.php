@include("admin.public.header")
<link rel="stylesheet" href="{{url('assets/layui/css/layui.css')}}">
<style>
    .mx-menu-toolbar {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .mx-menu-toolbar__left,
    .mx-menu-toolbar__right,
    .mx-menu-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
    }

    .mx-menu-stat-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 18px;
    }

    .mx-menu-stat {
        padding: 16px 18px;
        border-radius: 16px;
        border: 1px solid #e8edf5;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.05);
    }

    .mx-menu-stat__label {
        color: #64748b;
        font-size: 13px;
        margin-bottom: 8px;
    }

    .mx-menu-stat__value {
        color: #0f172a;
        font-size: 28px;
        font-weight: 700;
        line-height: 1;
    }

    .mx-menu-panel {
        border-radius: 18px;
        border: 1px solid #e8edf5;
        background: #fff;
        box-shadow: 0 16px 30px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .mx-menu-panel .card-header {
        padding: 16px 20px;
        border-bottom: 1px solid #edf2f7;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
    }

    .mx-menu-panel .card-body {
        padding: 18px;
        overflow-x: hidden;
    }

    .mx-menu-filter-input,
    .mx-menu-filter-select {
        min-width: 160px;
        height: 36px;
        border: 1px solid #dbe3ef;
        border-radius: 10px;
        padding: 0 12px;
        background: #fff;
    }

    .mx-menu-filter-input {
        min-width: 220px;
    }

    .mx-menu-table-wrap {
        border: 1px solid #edf2f7;
        border-radius: 16px;
        overflow: hidden;
        background: #fff;
    }

    .mx-menu-summary {
        display: flex;
        flex-direction: column;
        gap: 6px;
        padding: 4px 0;
    }

    .mx-menu-summary__title {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
        color: #0f172a;
        font-weight: 700;
        line-height: 1.5;
    }

    .mx-menu-summary__meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .mx-menu-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 999px;
        background: #eef4ff;
        color: #2563eb;
        font-size: 12px;
        font-weight: 600;
    }

    .mx-menu-badge--success {
        background: #ecfdf5;
        color: #16a34a;
    }

    .mx-menu-badge--warn {
        background: #fffbeb;
        color: #d97706;
    }

    .mx-menu-badge--gray {
        background: #f1f5f9;
        color: #475569;
    }

    .mx-menu-link-block {
        display: flex;
        flex-direction: column;
        gap: 8px;
        padding: 4px 0;
        word-break: break-all;
    }

    .mx-menu-link-block__item {
        font-size: 13px;
        line-height: 1.7;
        color: #475569;
    }

    .mx-menu-link-block__label {
        display: inline-block;
        min-width: 54px;
        color: #94a3b8;
    }

    .mx-menu-icon-preview {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #0f172a;
    }

    .mx-menu-sort-group,
    .mx-menu-action-group {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .mx-menu-note {
        margin-top: 12px;
        color: #64748b;
        font-size: 12px;
    }

    #auth-table + .layui-table-view .layui-table-cell {
        height: auto;
        line-height: 1.6;
        white-space: normal;
    }

    #auth-table + .layui-table-view .layui-table-body {
        overflow-x: hidden !important;
    }

    @media (max-width: 1199px) {
        .mx-menu-stat-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 767px) {
        .mx-menu-stat-grid {
            grid-template-columns: 1fr;
        }

        .mx-menu-toolbar,
        .mx-menu-toolbar__left,
        .mx-menu-toolbar__right,
        .mx-menu-filters {
            align-items: stretch;
        }

        .mx-menu-filter-input,
        .mx-menu-filter-select {
            width: 100%;
            min-width: 0;
        }
    }
</style>
<body class="horizontal">

@include("admin.public.themeMenuNav")

<div class="row page-header" style="margin-bottom: -15px;">
    <div class="col-lg-6 align-self-center ">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url("admin/index")}}">{{getTranslateByKey("common_home_page")}}</a></li>
            <li class="breadcrumb-item"><a href="{{url("admin/theme")}}">{{getTranslateByKey("theme_list")}}</a></li>
            <li class="breadcrumb-item active">{{getTranslateByKey("menu_management")}}</li>
        </ol>
    </div>
</div>

<section class="main-content mt-20" style="padding-bottom: 0px">
    <div class="row pb-5">
        <div class="col-md-12">
            <div class="card mx-menu-panel">
                <div class="card-header card-default">
                    <div class="btn-group float-left">{{getTranslateByKey("navigation_menu")}}</div>
                    <div class="btn-group float-right">
                        <button type="button" class="btn btn-default btn-sm mr-2" id="btn-expand">展开全部</button>
                        <button type="button" class="btn btn-default btn-sm mr-2" id="btn-fold">收起全部</button>
                        <a href="{{url('admin/theme/themeMenuAdd?m='.$_GET['m'])}}" class="btn btn-default btn-sm">
                            <em class="fa fa-plus"></em>
                        </a>
                    </div>
                </div>
                <div class="card-body" style="min-height: calc(100vh - 230px)">
                    <div class="mx-menu-stat-grid">
                        <div class="mx-menu-stat">
                            <div class="mx-menu-stat__label">菜单总数</div>
                            <div class="mx-menu-stat__value" id="menuStatTotal">0</div>
                        </div>
                        <div class="mx-menu-stat">
                            <div class="mx-menu-stat__label">顶部菜单</div>
                            <div class="mx-menu-stat__value" id="menuStatTop">0</div>
                        </div>
                        <div class="mx-menu-stat">
                            <div class="mx-menu-stat__label">底部菜单</div>
                            <div class="mx-menu-stat__value" id="menuStatBottom">0</div>
                        </div>
                        <div class="mx-menu-stat">
                            <div class="mx-menu-stat__label">页脚菜单</div>
                            <div class="mx-menu-stat__value" id="menuStatFooter">0</div>
                        </div>
                    </div>

                    <div class="mx-menu-toolbar">
                        <div class="mx-menu-toolbar__left">
                            <div class="mx-menu-filters">
                                <input type="text" id="menuKeyword" class="mx-menu-filter-input" placeholder="搜索菜单名称、URL、来源标识">
                                <select id="menuPositionFilter" class="mx-menu-filter-select">
                                    <option value="">全部位置</option>
                                    <option value="top">顶部</option>
                                    <option value="bottom">底部</option>
                                    <option value="footer">页脚</option>
                                </select>
                                <select id="menuTypeFilter" class="mx-menu-filter-select">
                                    <option value="">全部来源</option>
                                    <option value="manual">手工菜单</option>
                                    <option value="module">模块菜单</option>
                                    <option value="model">模型菜单</option>
                                    <option value="page">页面菜单</option>
                                    <option value="search">搜索菜单</option>
                                </select>
                                <select id="menuLangFilter" class="mx-menu-filter-select">
                                    <option value="">全部语言范围</option>
                                    @foreach($langList as $langCode => $langName)
                                        <option value="{{$langCode}}">{{$langName}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mx-menu-toolbar__right">
                            <button type="button" class="btn btn-default btn-sm" id="clearMenuFilter">清空筛选</button>
                        </div>
                    </div>

                    <div class="mx-menu-table-wrap">
                        <table id="auth-table" class="layui-table" lay-filter="auth-table"></table>
                    </div>
                    <div class="mx-menu-note">列表已压缩为摘要式展示，名称、来源、URL 和排序操作集中在同一屏内，避免出现横向滚动。</div>
                </div>

            </div>
        </div>
    </div>
    @include('admin.public.footer')
</section>

@include('admin.public.js',['load'=> ["custom"]])
<script src="{{url('assets/layui/layui.js')}}"></script>
<script>
    const themeMenuListLang = {
        menuName: @json(getTranslateByKey('menu_name')),
        menuUrl: @json(getTranslateByKey('menu_url')),
        jumpUrl: @json(getTranslateByKey('jump_url')),
        position: @json(getTranslateByKey('position')),
        top: @json(getTranslateByKey('top_section')),
        bottom: @json(getTranslateByKey('bottom_section')),
        footer: @json(getTranslateByKey('footer_section')),
        iconText: @json(getTranslateByKey('icon_text')),
        menuType: '来源',
        target: '打开方式',
        currentWindow: '当前窗口',
        newWindow: '新窗口',
        sourceManual: '手工菜单',
        sourceModule: '模块菜单',
        sourceModel: '模型菜单',
        sourceSearch: '搜索菜单',
        sourcePage: '页面菜单',
        sourceModuleLabel: '来源模块',
        sourceValueLabel: '来源标识',
        sort: @json(getTranslateByKey('sort_desc')),
        sortAction: '排序',
        sortTop: '置顶',
        sortUp: '上移',
        sortDown: '下移',
        status: @json(getTranslateByKey('common_account_status')),
        enable: @json(getTranslateByKey('common_enable')),
        disable: @json(getTranslateByKey('common_disable')),
        action: @json(getTranslateByKey('operation')),
        edit: @json(getTranslateByKey('common_edit')),
        del: @json(getTranslateByKey('common_delete')),
        sureEnable: @json(getTranslateByKey('common_sure_to_enabling')),
        sureDisable: @json(getTranslateByKey('common_sure_to_forbidden')),
        sortUpdated: '排序已更新',
        source: '来源',
        jumpMode: '打开方式',
        langScopeGlobal: '全局共享'
    };
    const themeMenuLangMap = @json($langList);

    layui.config({
        base: '../../assets/layui/module/'
    }).extend({
        treetable: 'treetable-lay/treetable'
    }).use(['table', 'treetable'], function () {
        var $ = layui.jquery;
        var treetable = layui.treetable;
        let menuData = [];

        function updateStats(rows) {
            $('#menuStatTotal').text(rows.length);
            $('#menuStatTop').text(rows.filter(item => item.position === 'top').length);
            $('#menuStatBottom').text(rows.filter(item => item.position === 'bottom').length);
            $('#menuStatFooter').text(rows.filter(item => item.position === 'footer').length);
        }

        function matchKeyword(row, keyword) {
            if (!keyword) return true;
            const text = [
                row.name,
                row.url,
                row.source_module,
                row.source_value,
                row.icon_character
            ].join(' ').toLowerCase();
            return text.indexOf(keyword) !== -1;
        }

        function buildFilteredRows() {
            const keyword = ($('#menuKeyword').val() || '').trim().toLowerCase();
            const position = $('#menuPositionFilter').val();
            const menuType = $('#menuTypeFilter').val();
            const menuLang = ($('#menuLangFilter').val() || '').toString();
            const matchedIds = new Set();

            menuData.forEach(function (row) {
                if (position && row.position !== position) return false;
                if (menuType && row.menu_type !== menuType) return false;
                if (((row.lang || '').toString()) !== menuLang && menuLang !== '') return false;
                if (!matchKeyword(row, keyword)) return false;
                matchedIds.add(row.id);
                let parentId = row.pid;
                while (parentId && parentId !== 0) {
                    matchedIds.add(parentId);
                    const parent = menuData.find(item => item.id === parentId);
                    parentId = parent ? parent.pid : 0;
                }
            });

            if (!keyword && !position && !menuType && menuLang === '') {
                return menuData;
            }

            return menuData.filter(function (row) {
                return matchedIds.has(row.id);
            });
        }

        function renderTable(rows) {
            layer.load(2);
            treetable.render({
                treeColIndex: 0,
                treeSpid: 0,
                treeIdName: 'id',
                treePidName: 'pid',
                elem: '#auth-table',
                data: rows,
                cellMinWidth: 80,
                cols: [[
                    {
                        field: 'name', minWidth: 280, title: themeMenuListLang.menuName, templet: function (d) {
                            const badges = [];
                            const langLabel = themeMenuLangMap[d.lang || ''] || d.lang || themeMenuListLang.langScopeGlobal;
                            badges.push(`<span class="mx-menu-badge mx-menu-badge--gray">${langLabel}</span>`);
                            if (d.position === 'top') badges.push(`<span class="mx-menu-badge">${themeMenuListLang.top}</span>`);
                            if (d.position === 'bottom') badges.push(`<span class="mx-menu-badge mx-menu-badge--success">${themeMenuListLang.bottom}</span>`);
                            if (d.position === 'footer') badges.push(`<span class="mx-menu-badge mx-menu-badge--warn">${themeMenuListLang.footer}</span>`);
                            if (d.menu_type === 'module') badges.push(`<span class="mx-menu-badge">${themeMenuListLang.sourceModule}</span>`);
                            if (d.menu_type === 'model') badges.push(`<span class="mx-menu-badge mx-menu-badge--success">${themeMenuListLang.sourceModel}</span>`);
                            if (d.menu_type === 'search') badges.push(`<span class="mx-menu-badge mx-menu-badge--warn">${themeMenuListLang.sourceSearch}</span>`);
                            if (d.menu_type === 'page') badges.push(`<span class="mx-menu-badge">${themeMenuListLang.sourcePage}</span>`);
                            if (d.menu_type === 'manual') badges.push(`<span class="mx-menu-badge mx-menu-badge--gray">${themeMenuListLang.sourceManual}</span>`);
                            badges.push(d.target === '_blank'
                                ? `<span class="mx-menu-badge mx-menu-badge--warn">${themeMenuListLang.newWindow}</span>`
                                : `<span class="mx-menu-badge mx-menu-badge--gray">${themeMenuListLang.currentWindow}</span>`);

                            const iconHtml = d.icon
                                ? `<span class="mx-menu-icon-preview"><i class="${d.icon}"></i>${d.icon_character ? `<span>${d.icon_character}</span>` : ''}</span>`
                                : `<span class="mx-menu-icon-preview"><i class="fa fa-circle-o"></i><span>未设置图标</span></span>`;

                            return `
                                <div class="mx-menu-summary">
                                    <div class="mx-menu-summary__title">
                                        <span>${d.name || '-'}</span>
                                        ${iconHtml}
                                    </div>
                                    <div class="mx-menu-summary__meta">${badges.join('')}</div>
                                </div>
                            `;
                        }
                    },
                    {
                        field: 'url', minWidth: 360, title: '链接信息', templet: function (d) {
                            const rawUrl = d.url || '#';
                            const jumpUrl = rawUrl && rawUrl !== '#' && !/^https?:\/\//i.test(rawUrl)
                                ? `{{url('')}}${rawUrl.charAt(0) === '/' ? '' : '/'}${rawUrl}`
                                : rawUrl;
                            return `
                                <div class="mx-menu-link-block">
                                    <div class="mx-menu-link-block__item"><span class="mx-menu-link-block__label">URL</span>${d.url || '#'}</div>
                                    <div class="mx-menu-link-block__item"><span class="mx-menu-link-block__label">跳转</span>${jumpUrl}</div>
                                    <div class="mx-menu-link-block__item"><span class="mx-menu-link-block__label">${themeMenuListLang.source}</span>${d.source_module || '-'} / ${d.source_value || '-'}</div>
                                </div>
                            `;
                        }
                    },
                    {
                        width: 220, title: themeMenuListLang.sortAction, templet: function (d) {
                            return `
                                <div class="mx-menu-sort-group">
                                    <span class="mx-menu-badge mx-menu-badge--gray">Sort ${d.sort || 0}</span>
                                    <a class="btn btn-xs btn-default" href="javascript:;" onclick="moveMenu(${d.id}, 'top')">${themeMenuListLang.sortTop}</a>
                                    <a class="btn btn-xs btn-default" href="javascript:;" onclick="moveMenu(${d.id}, 'up')">${themeMenuListLang.sortUp}</a>
                                    <a class="btn btn-xs btn-default" href="javascript:;" onclick="moveMenu(${d.id}, 'down')">${themeMenuListLang.sortDown}</a>
                                </div>
                            `;
                        }
                    },
                    {
                        width: 110, title: themeMenuListLang.status, templet: function (d) {
                            if (d.status == 1) {
                                return `<span class="btn btn-xs btn-success" onclick="changeStatus(${d.id},2)">${themeMenuListLang.enable}</span>`;
                            }
                            return `<span class="btn btn-xs btn-danger" onclick="changeStatus(${d.id},1)">${themeMenuListLang.disable}</span>`;
                        }
                    },
                    {
                        width: 150, align: 'center', title: themeMenuListLang.action, templet: function (d) {
                            var edit = `<a class="btn btn-xs btn-teal mr-2" href="{{url('admin/theme/themeMenuEdit?m='.$_GET['m'])}}&id=${d.id}">${themeMenuListLang.edit}</a>`;
                            var del = `<a class="btn btn-xs btn-danger" href="javascript:;" onclick="delData(${d.id})">${themeMenuListLang.del}</a>`;
                            return `<div class="mx-menu-action-group">${edit}${del}</div>`;
                        }
                    }
                ]],
                done: function () {
                    layer.closeAll('loading');
                }
            });
        }

        function renderFilteredTable() {
            renderTable(buildFilteredRows());
        }

        layer.load(2);
        $.get('{{url('admin/theme/themeMenuList')}}', function (res) {
            menuData = (res && res.data) ? res.data : [];
            updateStats(menuData);
            renderFilteredTable();
        });

        $('#menuKeyword').on('input', function () {
            renderFilteredTable();
        });

        $('#menuPositionFilter, #menuTypeFilter, #menuLangFilter').on('change', function () {
            renderFilteredTable();
        });

        $('#clearMenuFilter').click(function () {
            $('#menuKeyword').val('');
            $('#menuPositionFilter').val('');
            $('#menuTypeFilter').val('');
            $('#menuLangFilter').val('');
            renderFilteredTable();
        });

        $('#btn-expand').click(function () {
            treetable.expandAll('#auth-table');
        });

        $('#btn-fold').click(function () {
            treetable.foldAll('#auth-table');
        });
    });

    function moveMenu(id, direction) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            },
            method: 'post',
            url: "{{url('admin/theme/themeMenuMove?m='.$_GET['m'])}}",
            dataType: 'json',
            data: {id: id, direction: direction},
            success: function (res) {
                if (res.status == 200) {
                    popup({type: "success", msg: res.msg || themeMenuListLang.sortUpdated, delay: 1200});
                    setTimeout(function () {
                        location.reload();
                    }, 800);
                } else {
                    popup({type: "error", msg: res.msg, delay: 1800});
                }
            },
            error: function (res) {
                console.log(res);
            }
        });
    }

    function delData(id) {
        $.confirm({
            title: '{{getTranslateByKey("common_tip")}}',
            content: '{{getTranslateByKey("common_sure_to_delete")}}',
            type: 'default',
            buttons: {
                ok: {
                    text: "{{getTranslateByKey('common_ensure')}}",
                    btnClass: 'btn-primary',
                    keys: ['enter'],
                    action: function () {
                        location.href = "{{url('admin/theme/themeMenuDelete?id=')}}" + id
                    }
                },
                cancel: {
                    text: "{{getTranslateByKey('common_cancel')}}"
                }
            }
        });
    }

    function changeStatus(id, status) {
        var content = status == 1 ? themeMenuListLang.sureEnable : themeMenuListLang.sureDisable;
        $.confirm({
            title: '{{getTranslateByKey("common_tip")}}',
            content: content,
            type: 'default',
            buttons: {
                ok: {
                    text: "{{getTranslateByKey('common_ensure')}}",
                    btnClass: 'btn-primary',
                    keys: ['enter'],
                    action: function () {
                        location.href = "{{url('admin/theme/themeMenuChangeStatus?id=')}}" + id + '&status=' + status;
                    }
                },
                cancel: {
                    text: "{{getTranslateByKey('common_cancel')}}"
                }
            }
        });
    }
</script>
</body>
</html>
