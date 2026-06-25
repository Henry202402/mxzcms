@include(moduleAdminTemplate($moduleName)."public.header")
<style>
    input[type=checkbox] {
        width: 18px;
        height: 18px;
    }

    .mx-auth-permission {
        display: grid;
        gap: 18px;
    }

    .mx-auth-permission__hero {
        padding: 18px 20px;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        background: linear-gradient(135deg, #ffffff 0%, #f8fbff 100%);
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
    }

    .mx-auth-permission__title {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
        color: #0f172a;
    }

    .mx-auth-permission__meta {
        margin-top: 10px;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .mx-auth-permission__badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        border-radius: 999px;
        background: #e0f2fe;
        color: #0369a1;
        font-size: 12px;
        font-weight: 700;
    }

    .mx-auth-permission__summary {
        margin-top: 12px;
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
    }

    .mx-auth-permission__stat {
        padding: 14px 16px;
        border-radius: 14px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
    }

    .mx-auth-permission__stat-label {
        color: #64748b;
        font-size: 12px;
    }

    .mx-auth-permission__stat-value {
        margin-top: 6px;
        color: #0f172a;
        font-size: 22px;
        font-weight: 700;
    }

    .mx-auth-permission__toolbar {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
        justify-content: space-between;
        padding: 16px 18px;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        background: #ffffff;
    }

    .mx-auth-permission__toolbar-left,
    .mx-auth-permission__toolbar-right {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
    }

    .mx-auth-permission__search {
        min-width: 280px;
    }

    .mx-auth-permission__cards {
        display: grid;
        gap: 16px;
    }

    .mx-auth-permission__module {
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        background: #ffffff;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.04);
        overflow: hidden;
    }

    .mx-auth-permission__module.is-hidden {
        display: none;
    }

    .mx-auth-permission__module-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 16px 18px;
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
    }

    .mx-auth-permission__module-main {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .mx-auth-permission__module-title {
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
    }

    .mx-auth-permission__module-subtitle {
        color: #64748b;
        font-size: 12px;
    }

    .mx-auth-permission__module-body {
        padding: 16px 18px 8px;
    }

    .mx-auth-permission__module-body.is-collapsed {
        display: none;
    }

    .mx-auth-permission__group {
        border: 1px solid #eef2f7;
        border-radius: 14px;
        padding: 14px 16px;
        margin-bottom: 12px;
        background: #fbfdff;
    }

    .mx-auth-permission__group-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 12px;
    }

    .mx-auth-permission__group-title {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #0f172a;
        font-size: 15px;
        font-weight: 700;
    }

    .mx-auth-permission__group-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .mx-auth-permission__chips {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .mx-auth-permission__chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-radius: 999px;
        background: #f1f5f9;
        color: #334155;
        font-size: 13px;
        border: 1px solid #e2e8f0;
    }

    .mx-auth-permission__chip.is-hidden {
        display: none;
    }

    .mx-auth-permission__footer {
        position: sticky;
        bottom: 0;
        z-index: 10;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 12px;
        margin-top: 12px;
        padding: 16px 18px;
        border-radius: 16px 16px 0 0;
        background: rgba(255, 255, 255, 0.98);
        border: 1px solid #e5e7eb;
        box-shadow: 0 -8px 24px rgba(15, 23, 42, 0.06);
    }

    .mx-auth-permission__footer-info {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        color: #64748b;
    }

    @media (max-width: 1200px) {
        .mx-auth-permission__summary {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 768px) {
        .mx-auth-permission__summary {
            grid-template-columns: 1fr;
        }

        .mx-auth-permission__search {
            min-width: 100%;
        }

        .mx-auth-permission__module-head,
        .mx-auth-permission__group-head,
        .mx-auth-permission__footer {
            flex-direction: column;
            align-items: stretch;
        }
    }
</style>
<body>

@include(moduleAdminTemplate($moduleName)."public.nav")

<!-- Page container -->
<div class="page-container">

    <!-- Page content -->
    <div class="page-content">

        <!-- Main sidebar -->
        <div class="sidebar sidebar-main">
            <div class="sidebar-content">
                @include(moduleAdminTemplate($moduleName)."public.left")
            </div>
        </div>
        <!-- /main sidebar -->


        <!-- Main content -->
        <div class="content-wrapper">

            <!-- Page header -->
            <div class="page-header">

            @include(moduleAdminTemplate($moduleName)."public.page",['breadcrumb'=>[$pageData['title'],$pageData['subtitle']]])

            <!-- Content area -->
                <div class="content" style="margin-top: 1rem;">
                    <div class="col-sm-12">
                        <form id="add" role="form" method="post" action="{{url('admin/auth/group/handle')}}">
                            {{csrf_field()}}
                            <div class="mx-auth-permission">
                                <div class="mx-auth-permission__hero">
                                    <h2 class="mx-auth-permission__title">权限组分配权限</h2>
                                    <div class="mx-auth-permission__meta">
                                        <span class="mx-auth-permission__badge">权限组：{{$pageData['group']['group_name']}}</span>
                                        <span class="mx-auth-permission__badge">已选权限：<span data-selected-count>{{$pageData['permissionStats']['selected']}}</span></span>
                                        <span class="mx-auth-permission__badge">可分配权限：{{$pageData['permissionStats']['actions']}}</span>
                                    </div>
                                    <div class="mx-auth-permission__summary">
                                        <div class="mx-auth-permission__stat">
                                            <div class="mx-auth-permission__stat-label">模块数</div>
                                            <div class="mx-auth-permission__stat-value">{{$pageData['permissionStats']['modules']}}</div>
                                        </div>
                                        <div class="mx-auth-permission__stat">
                                            <div class="mx-auth-permission__stat-label">权限组块</div>
                                            <div class="mx-auth-permission__stat-value">{{$pageData['permissionStats']['groups']}}</div>
                                        </div>
                                        <div class="mx-auth-permission__stat">
                                            <div class="mx-auth-permission__stat-label">总权限项</div>
                                            <div class="mx-auth-permission__stat-value">{{$pageData['permissionStats']['actions']}}</div>
                                        </div>
                                        <div class="mx-auth-permission__stat">
                                            <div class="mx-auth-permission__stat-label">当前已勾选</div>
                                            <div class="mx-auth-permission__stat-value" data-selected-count>{{$pageData['permissionStats']['selected']}}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mx-auth-permission__toolbar">
                                    <div class="mx-auth-permission__toolbar-left">
                                        <input type="text" class="form-control mx-auth-permission__search" id="permissionSearch" placeholder="搜索模块名、权限组块或具体权限路径">
                                        <button type="button" class="btn btn-default btn-sm" id="expandAllPermissions">展开全部</button>
                                        <button type="button" class="btn btn-default btn-sm" id="collapseAllPermissions">收起全部</button>
                                    </div>
                                    <div class="mx-auth-permission__toolbar-right">
                                        <button type="button" class="btn btn-info btn-sm" id="selectVisiblePermissions">勾选当前可见</button>
                                        <button type="button" class="btn btn-warning btn-sm" id="clearVisiblePermissions">清空当前可见</button>
                                        <button type="button" class="btn btn-default btn-sm" id="resetPermissionSearch">重置筛选</button>
                                    </div>
                                </div>

                                <div class="mx-auth-permission__cards permission-list">
                                    @foreach($pageData['allMenus'] as $moduleKey => $modules)
                                        @php($moduleSearch = strtolower($modules['name'] . ' ' . $moduleKey))
                                        <div class="mx-auth-permission__module" data-module-card data-search="{{$moduleSearch}}">
                                            <div class="mx-auth-permission__module-head">
                                                <div class="mx-auth-permission__module-main">
                                                    <label class="i-checks" style="margin:0;">
                                                        <input type="checkbox" class="module-toggle" data-module-toggle>
                                                    </label>
                                                    <div>
                                                        <div class="mx-auth-permission__module-title">{{$modules['name']}}</div>
                                                        <div class="mx-auth-permission__module-subtitle">
                                                            模块标识：{{$moduleKey}} / 已选 {{$modules['selected_count']}} / {{$modules['total_count']}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mx-auth-permission__toolbar-right">
                                                    <button type="button" class="btn btn-default btn-xs" data-module-select>全选模块</button>
                                                    <button type="button" class="btn btn-default btn-xs" data-module-clear>清空模块</button>
                                                    <button type="button" class="btn btn-default btn-xs" data-module-collapse>收起</button>
                                                </div>
                                            </div>
                                            <div class="mx-auth-permission__module-body" data-module-body>
                                                @foreach($modules['menus'] as $menuKey => $menus)
                                                    @php($groupSearch = strtolower($menus['title'] . ' ' . implode(' ', $menus['permission_values'] ?? [])))
                                                    <div class="mx-auth-permission__group permission-list2" data-group-card data-search="{{$groupSearch}}">
                                                        <div class="mx-auth-permission__group-head">
                                                            <label class="mx-auth-permission__group-title i-checks" style="margin:0;">
                                                                <input type="checkbox"
                                                                       class="group-toggle"
                                                                       name="role[{{$moduleKey}}][]"
                                                                       value="{{$menus['url'] ?: $menus['title']}}"
                                                                       data-group-parent
                                                                       @if(in_array(($menus['url'] ?: $menus['title']), ($pageData['group']['role_array'][$moduleKey] ?: []))) checked @endif>
                                                                <span>{{$menus['title']}}</span>
                                                            </label>
                                                            <div class="mx-auth-permission__group-actions">
                                                                <span class="label label-info">已选 {{$menus['selected_count']}}</span>
                                                                <span class="label label-default">共 {{$menus['total_count']}}</span>
                                                            </div>
                                                        </div>
                                                        <div class="mx-auth-permission__chips">
                                                            @foreach($menus['submenu'] as $submenu)
                                                                @php($chipSearch = strtolower($submenu['title'] . ' ' . $submenu['url']))
                                                                <label class="mx-auth-permission__chip i-checks" data-permission-chip data-search="{{$chipSearch}}">
                                                                    <input type="checkbox"
                                                                           name="role[{{$moduleKey}}][]"
                                                                           value="{{$submenu['url']}}"
                                                                           data-permission-item
                                                                           @if(in_array($submenu['url'], ($pageData['group']['role_array'][$moduleKey] ?: []))) checked @endif>
                                                                    <span>{{$submenu['title']}}</span>
                                                                    <code>{{$submenu['url']}}</code>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mx-auth-permission__footer">
                                    <div class="mx-auth-permission__footer-info">
                                        <span>当前权限组：{{$pageData['group']['group_name']}}</span>
                                        <span>已选权限：<strong data-selected-count>{{$pageData['permissionStats']['selected']}}</strong></span>
                                        <span>保存后会立即更新权限缓存</span>
                                    </div>
                                    <div class="mx-auth-permission__toolbar-right">
                                        <input type="hidden" name="group_id" value="{{$pageData['group']['group_id']}}">
                                        <input type="hidden" name="submitType" value="assignPermissions">
                                        <input type="hidden" name="jumpUrl" value="{{$pageData['jumpUrl']}}">
                                        <button class="btn btn-primary" type="submit">保存权限配置</button>
                                        <a href="{{$pageData['groupUserUrl']}}" class="btn btn-info">组成员</a>
                                        <a href="{{$pageData['groupListUrl']}}" class="btn btn-danger">返回权限组</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>


                    <!-- Footer -->
                @include(moduleAdminTemplate($moduleName)."public.footer")
                <!-- /footer -->

                </div>
                <!-- /content area -->

            </div>
            <!-- /main content -->

        </div>
    </div>

    <!-- /content -->
    <!-- 						Content End		 						-->
    <!-- ============================================================== -->
    @include(moduleAdminTemplate($moduleName)."public.js")
    <script>
        $(function () {
            function syncGroupState(groupCard) {
                var group = $(groupCard);
                var parent = group.find('[data-group-parent]').first();
                var items = group.find('[data-permission-item]');
                var total = items.length + 1;
                var checked = items.filter(':checked').length + (parent.is(':checked') ? 1 : 0);
                parent.prop('indeterminate', checked > 0 && checked < total);
                if (checked === 0) {
                    parent.prop('checked', false);
                } else if (checked === total) {
                    parent.prop('checked', true).prop('indeterminate', false);
                }
                group.find('.label-info').first().text('已选 ' + checked);
                return checked;
            }

            function syncModuleState(moduleCard) {
                var module = $(moduleCard);
                var groups = module.find('[data-group-card]');
                var total = 0;
                var checked = 0;
                groups.each(function () {
                    checked += syncGroupState(this);
                    total += $(this).find('input[type=checkbox]').length;
                });
                var moduleToggle = module.find('[data-module-toggle]').first();
                moduleToggle.prop('indeterminate', checked > 0 && checked < total);
                moduleToggle.prop('checked', total > 0 && checked === total);
                module.find('.mx-auth-permission__module-subtitle').text(function (_, text) {
                    return text.replace(/已选\s+\d+\s+\/\s+\d+/, '已选 ' + checked + ' / ' + total);
                });
            }

            function updateSelectedCount() {
                var totalSelected = $('.permission-list [data-group-parent]:checked, .permission-list [data-permission-item]:checked').length;
                $('[data-selected-count]').text(totalSelected);
            }

            function syncAll() {
                $('[data-module-card]').each(function () {
                    syncModuleState(this);
                });
                updateSelectedCount();
            }

            $(document).on('change', '[data-group-parent]', function () {
                var group = $(this).closest('[data-group-card]');
                group.find('[data-permission-item]').prop('checked', $(this).prop('checked'));
                syncAll();
            });

            $(document).on('change', '[data-permission-item]', function () {
                syncAll();
            });

            $(document).on('change', '[data-module-toggle]', function () {
                var module = $(this).closest('[data-module-card]');
                module.find('[data-group-parent], [data-permission-item]').prop('checked', $(this).prop('checked')).prop('indeterminate', false);
                syncAll();
            });

            $(document).on('click', '[data-module-select]', function () {
                var module = $(this).closest('[data-module-card]');
                module.find('[data-group-parent], [data-permission-item]').prop('checked', true).prop('indeterminate', false);
                syncAll();
            });

            $(document).on('click', '[data-module-clear]', function () {
                var module = $(this).closest('[data-module-card]');
                module.find('[data-group-parent], [data-permission-item]').prop('checked', false).prop('indeterminate', false);
                syncAll();
            });

            $(document).on('click', '[data-module-collapse]', function () {
                var module = $(this).closest('[data-module-card]');
                var body = module.find('[data-module-body]').first();
                body.toggleClass('is-collapsed');
                $(this).text(body.hasClass('is-collapsed') ? '展开' : '收起');
            });

            function applySearch() {
                var keyword = $.trim($('#permissionSearch').val()).toLowerCase();
                $('[data-module-card]').each(function () {
                    var module = $(this);
                    var moduleText = String(module.data('search') || '');
                    var visibleGroupCount = 0;

                    module.find('[data-group-card]').each(function () {
                        var group = $(this);
                        var groupText = String(group.data('search') || '');
                        var groupMatch = keyword === '' || moduleText.indexOf(keyword) >= 0 || groupText.indexOf(keyword) >= 0;
                        var visibleChipCount = 0;

                        group.find('[data-permission-chip]').each(function () {
                            var chip = $(this);
                            var chipText = String(chip.data('search') || '');
                            var showChip = keyword === '' || moduleText.indexOf(keyword) >= 0 || groupText.indexOf(keyword) >= 0 || chipText.indexOf(keyword) >= 0;
                            chip.toggleClass('is-hidden', !showChip);
                            if (showChip) {
                                visibleChipCount++;
                            }
                        });

                        var showGroup = groupMatch || visibleChipCount > 0;
                        group.toggle(showGroup);
                        if (showGroup) {
                            visibleGroupCount++;
                        }
                    });

                    module.toggleClass('is-hidden', visibleGroupCount === 0);
                });
            }

            $('#permissionSearch').on('input', applySearch);

            $('#resetPermissionSearch').on('click', function () {
                $('#permissionSearch').val('');
                applySearch();
            });

            $('#expandAllPermissions').on('click', function () {
                $('[data-module-body]').removeClass('is-collapsed');
                $('[data-module-collapse]').text('收起');
            });

            $('#collapseAllPermissions').on('click', function () {
                $('[data-module-body]').addClass('is-collapsed');
                $('[data-module-collapse]').text('展开');
            });

            $('#selectVisiblePermissions').on('click', function () {
                $('.mx-auth-permission__module:not(.is-hidden)').find('[data-group-parent], [data-permission-item]').prop('checked', true).prop('indeterminate', false);
                syncAll();
            });

            $('#clearVisiblePermissions').on('click', function () {
                $('.mx-auth-permission__module:not(.is-hidden)').find('[data-group-parent], [data-permission-item]').prop('checked', false).prop('indeterminate', false);
                syncAll();
            });

            syncAll();
        });
    </script>
</body>
</html>
