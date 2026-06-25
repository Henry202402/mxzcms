@include(moduleAdminTemplate($moduleName)."public.header")
<style>
    .task-config-hero {
        margin-bottom: 20px;
        padding: 22px 24px;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.06);
    }

    .task-config-hero__top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }

    .task-config-hero__title {
        margin: 0;
        font-size: 22px;
        font-weight: 700;
        color: #111827;
    }

    .task-config-hero__desc {
        margin-top: 8px;
        max-width: 760px;
        color: #6b7280;
        line-height: 1.7;
    }

    .task-config-overview {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
        margin-top: 18px;
    }

    .task-overview-card {
        padding: 16px 18px;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #fff;
    }

    .task-overview-card__name {
        font-size: 13px;
        color: #6b7280;
    }

    .task-overview-card__value {
        margin-top: 10px;
        font-size: 28px;
        line-height: 1.1;
        font-weight: 700;
        color: #111827;
    }

    .task-overview-card__desc {
        margin-top: 10px;
        min-height: 40px;
        color: #6b7280;
        line-height: 1.6;
    }

    .task-filter-panel,
    .task-list-panel {
        margin-bottom: 20px;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 10px 32px rgba(15, 23, 42, 0.05);
        overflow: hidden;
    }

    .task-filter-panel__header,
    .task-list-panel__header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        padding: 18px 20px;
        border-bottom: 1px solid #eef2f7;
    }

    .task-filter-panel__title,
    .task-list-panel__title {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #111827;
    }

    .task-filter-panel__desc,
    .task-list-panel__desc {
        margin-top: 6px;
        color: #6b7280;
    }

    .task-filter-form {
        padding: 20px;
    }

    .task-filter-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
    }

    .task-filter-item label {
        display: block;
        margin-bottom: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
    }

    .task-filter-item select,
    .task-filter-item input {
        width: 100%;
        height: 40px;
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        background: #fff;
    }

    .task-filter-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 16px;
    }

    .task-table {
        margin-bottom: 0;
    }

    .task-table > thead > tr > th {
        border-bottom: 1px solid #eef2f7;
        color: #6b7280;
        font-weight: 600;
        background: #f8fafc;
    }

    .task-table > tbody > tr > td {
        vertical-align: top;
        border-top: 1px solid #f1f5f9;
    }

    .task-name {
        font-weight: 700;
        color: #111827;
    }

    .task-meta,
    .task-remark,
    .task-empty {
        color: #6b7280;
        line-height: 1.7;
    }

    .task-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
    }

    .task-status-badge.is-active {
        color: #166534;
        background: #dcfce7;
    }

    .task-status-badge.is-inactive {
        color: #b91c1c;
        background: #fee2e2;
    }

    .task-toggle-link {
        display: inline-block;
        margin-top: 10px;
        color: #2563eb;
        cursor: pointer;
    }

    .task-actions .btn {
        margin: 0 6px 6px 0;
    }

    @media (max-width: 1200px) {
        .task-config-overview,
        .task-filter-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 768px) {
        .task-config-overview,
        .task-filter-grid {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }
    }
</style>
<body>

@include(moduleAdminTemplate($moduleName)."public.nav")

<div class="page-container">
    <div class="page-content">
        <div class="sidebar sidebar-main">
            <div class="sidebar-content">
                @include(moduleAdminTemplate($moduleName)."public.left")
            </div>
        </div>

        <div class="content-wrapper">
            <div class="page-header">
                @include(moduleAdminTemplate($moduleName)."public.page", ['breadcrumb'=>[$pageData['title'],$pageData['subtitle']]])

                <div class="content" style="margin-top: 1rem;">
                    <div class="task-config-hero">
                        <div class="task-config-hero__top">
                            <div>
                                <h2 class="task-config-hero__title">定时任务概览</h2>
                                <div class="task-config-hero__desc">
                                    统一查看当前模块已启用的计划任务、待补充的 Hook 任务以及最近执行情况，方便按模块和状态快速排查。
                                </div>
                            </div>
                            <a class="btn btn-info {{permissions('secure/scheduledTasksAdd')}}"
                               href="{{moduleAdminJump($moduleName,'secure/scheduledTasksAdd')}}">
                                添加任务
                            </a>
                        </div>

                        <div class="task-config-overview">
                            @foreach($taskOverview as $overview)
                                <div class="task-overview-card">
                                    <div class="task-overview-card__name">{{$overview['name']}}</div>
                                    <div class="task-overview-card__value">{{$overview['value']}}</div>
                                    <div class="task-overview-card__desc">{{$overview['desc']}}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="task-filter-panel">
                        <div class="task-list-panel__header">
                            <div>
                                <h3 class="task-list-panel__title">Laravel 定时任务配置说明</h3>
                                <div class="task-list-panel__desc">后台这里管理的是任务定义，真正按分钟触发仍需要服务器层定时执行 `php artisan schedule:run`。</div>
                            </div>
                            <span class="label bg-success">运维说明</span>
                        </div>

                        <div style="padding: 18px 20px 4px; color: #4b5563; line-height: 1.8;">
                            <div><strong>执行原理：</strong>服务器每分钟触发一次 Laravel Scheduler，Laravel 再根据后台保存的周期配置执行启用中的任务。</div>
                            <div style="margin-top: 10px;"><strong>Linux crontab：</strong></div>
                            <pre style="margin-top: 8px; padding: 12px 14px; border-radius: 12px; background: #0f172a; color: #e2e8f0; white-space: pre-wrap;">* * * * * cd /path/to/mxzcms-2024 && php artisan schedule:run >> /dev/null 2>&1</pre>
                            <div style="margin-top: 10px;"><strong>Windows 任务计划程序：</strong>每分钟执行一次，工作目录指向项目根目录，执行命令为：</div>
                            <pre style="margin-top: 8px; padding: 12px 14px; border-radius: 12px; background: #0f172a; color: #e2e8f0; white-space: pre-wrap;">php artisan schedule:run</pre>
                            <div style="margin-top: 10px;"><strong>当前推荐：</strong>`sitemap` 建议每天凌晨或每小时执行；系统配置缓存预热建议每天执行一次即可。</div>
                            <div style="margin-top: 10px;"><strong>排查顺序：</strong>先确认服务器计划任务是否生效，再看这里的任务状态是否为“正常”，最后查看任务日志。</div>
                        </div>
                    </div>

                    <div class="task-filter-panel">
                        <div class="task-filter-panel__header">
                            <div>
                                <h3 class="task-filter-panel__title">筛选任务</h3>
                                <div class="task-filter-panel__desc">支持按模块、状态和关键词筛选已添加任务与待添加任务。</div>
                            </div>
                        </div>

                        <form class="task-filter-form" method="get" action="{{moduleAdminJump($moduleName,'secure/scheduledTasksList')}}">
                            <div class="task-filter-grid">
                                <div class="task-filter-item">
                                    <label>模块</label>
                                    <select name="module">
                                        <option value="">全部模块</option>
                                        @foreach($taskModuleOptions as $option)
                                            <option value="{{$option['value']}}" @if($taskFilters['module'] === $option['value']) selected @endif>
                                                {{$option['label']}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="task-filter-item">
                                    <label>状态</label>
                                    <select name="status">
                                        <option value="">全部状态</option>
                                        @foreach($taskStatusMap as $statusValue => $statusLabel)
                                            <option value="{{$statusValue}}" @if($taskFilters['status'] === (string) $statusValue) selected @endif>
                                                {{$statusLabel}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="task-filter-item">
                                    <label>关键词</label>
                                    <input type="text" name="keyword" value="{{$taskFilters['keyword']}}" placeholder="任务名、备注、方法名">
                                </div>
                                <div class="task-filter-item">
                                    <label>结果</label>
                                    <input type="text" value="已添加 {{count($taskList)}} 条 / 待添加 {{count($pendingTaskList)}} 条" readonly>
                                </div>
                            </div>

                            <div class="task-filter-actions">
                                <button type="submit" class="btn btn-primary">应用筛选</button>
                                <a href="{{moduleAdminJump($moduleName,'secure/scheduledTasksList')}}" class="btn btn-default">重置</a>
                            </div>
                        </form>
                    </div>

                    <div class="task-list-panel">
                        <div class="task-list-panel__header">
                            <div>
                                <h3 class="task-list-panel__title">已添加任务列表</h3>
                                <div class="task-list-panel__desc">当前模块已纳入后台管理的定时任务，可直接执行、查看日志、编辑和启停。</div>
                            </div>
                            <span class="label bg-info">共 {{count($taskList)}} 条</span>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped task-table">
                                <thead>
                                <tr>
                                    <th style="width: 70px;">ID</th>
                                    <th style="width: 220px;">任务</th>
                                    <th style="width: 180px;">状态</th>
                                    <th style="width: 220px;">执行周期</th>
                                    <th style="width: 180px;">最近执行</th>
                                    <th>任务说明</th>
                                    <th style="width: 260px;">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($taskList as $task)
                                    <tr>
                                        <td>{{$task['id']}}</td>
                                        <td>
                                            <div class="task-name">{{$task['name']}}</div>
                                            <div class="task-meta">{{$task['module_label']}}</div>
                                            <div class="task-meta">{{$task['target']}}</div>
                                        </td>
                                        <td>
                                            <span class="task-status-badge @if($task['status'] == 1) is-active @else is-inactive @endif">
                                                {{$task['status_label']}}
                                            </span>
                                            @if($task['status'] == 1)
                                                <div class="task-toggle-link"
                                                     onclick="_confirm('{{moduleAdminJump($moduleName,'secure/scheduledTasksEdit')}}',{
                                                             '_method':'PUT','_token':'{{csrf_token()}}','id':'{{$task['id']}}','update_type':1,'status':2
                                                             },'你确定要停用吗？')">
                                                    停用任务
                                                </div>
                                            @else
                                                <div class="task-toggle-link"
                                                     onclick="_confirm('{{moduleAdminJump($moduleName,'secure/scheduledTasksEdit')}}',{
                                                             '_method':'PUT','_token':'{{csrf_token()}}','id':'{{$task['id']}}','update_type':1,'status':1
                                                             },'你确定要启用吗？')">
                                                    启用任务
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="task-meta">{{$task['cycle_label']}}</div>
                                        </td>
                                        <td>
                                            <div class="task-meta">{{$task['last_execution_display']}}</div>
                                        </td>
                                        <td>
                                            <div class="task-remark">{{$task['remark']}}</div>
                                        </td>
                                        <td class="task-actions">
                                            <button type="button"
                                                    class="btn btn-success btn-xs {{permissions('secure/scheduledTasksExecute')}}"
                                                    onclick="_confirm('{{moduleAdminJump($moduleName,'secure/scheduledTasksExecute')}}',{
                                                            '_method':'PUT','_token':'{{csrf_token()}}','id':'{{$task['id']}}'
                                                            },'你确定要执行吗？')">
                                                立即执行
                                            </button>
                                            <button type="button"
                                                    class="btn btn-primary btn-xs {{permissions('secure/scheduledTasksLog')}}"
                                                    onclick="scheduledTasksLog('{{$task['id']}}')">
                                                日志
                                            </button>
                                            <a href="{{moduleAdminJump($moduleName,'secure/scheduledTasksEdit?id='.$task['id'])}}"
                                               class="{{permissions('secure/scheduledTasksEdit')}}">
                                                <button type="button" class="btn btn-info btn-xs">
                                                    编辑
                                                </button>
                                            </a>
                                            <button type="button"
                                                    class="btn btn-danger btn-xs {{permissions('secure/scheduledTasksDelete')}}"
                                                    onclick="_confirm('{{moduleAdminJump($moduleName,'secure/scheduledTasksDelete')}}',{
                                                            '_method':'DELETE','_token':'{{csrf_token()}}','id':'{{$task['id']}}'
                                                            },'你确定要删除吗？')">
                                                删除
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="20" class="task-empty">当前筛选条件下暂无已添加任务。</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="task-list-panel">
                        <div class="task-list-panel__header">
                            <div>
                                <h3 class="task-list-panel__title">待添加任务列表</h3>
                                <div class="task-list-panel__desc">这些任务已经在模块 Hook 中声明，但还没有加入后台统一调度。</div>
                            </div>
                            <span class="label bg-warning">共 {{count($pendingTaskList)}} 条</span>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped task-table">
                                <thead>
                                <tr>
                                    <th style="width: 180px;">模块</th>
                                    <th style="width: 320px;">路径方法</th>
                                    <th>说明</th>
                                    <th style="width: 140px;">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($pendingTaskList as $task)
                                    <tr>
                                        <td>{{$task['module_label']}}</td>
                                        <td>{{$task['target']}}</td>
                                        <td class="task-remark">{{$task['remark']}}</td>
                                        <td>
                                            <a href="{{moduleAdminJump($moduleName,'secure/scheduledTasksAdd?info='.$task['info'])}}"
                                               class="{{permissions('secure/scheduledTasksEdit')}}">
                                                <button type="button" class="btn btn-info btn-xs">
                                                    添加任务
                                                </button>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="20" class="task-empty">当前筛选条件下暂无待添加任务。</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @include(moduleAdminTemplate($moduleName)."public.footer")
                </div>
            </div>
        </div>
    </div>
</div>

@include(moduleAdminTemplate($moduleName)."public.js")
<script>
    function scheduledTasksLog(id) {
        layer.open({
            title: '任务执行日志',
            type: 2,
            area: ['50%', '550px'],
            content: '{{moduleAdminJump($moduleName,'secure/scheduledTasksLog?id=')}}' + id,
            closeBtn: 1,
            btn: ['关闭']
        });
    }
</script>
</body>
</html>
