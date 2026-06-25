@include(moduleAdminTemplate($moduleName)."public.header")
@php($isModuleTask = !empty($data['module_class']) && !empty($data['module_class_method']))
@php($lastExecution = !empty($data['last_execution_time']) && $data['last_execution_time'] !== '0000-00-00 00:00:00' ? $data['last_execution_time'] : '未执行')
<style>
    .task-form-hero {
        margin-bottom: 20px;
        padding: 22px 24px;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.06);
    }

    .task-form-hero__title {
        margin: 0;
        font-size: 22px;
        font-weight: 700;
        color: #111827;
    }

    .task-form-hero__desc {
        margin-top: 8px;
        max-width: 760px;
        color: #6b7280;
        line-height: 1.7;
    }

    .task-form-overview {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
        margin-top: 18px;
    }

    .task-form-overview__card,
    .task-form-panel,
    .task-form-sidecard {
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 10px 32px rgba(15, 23, 42, 0.05);
    }

    .task-form-overview__card {
        padding: 16px 18px;
    }

    .task-form-overview__name {
        font-size: 13px;
        color: #6b7280;
    }

    .task-form-overview__value {
        margin-top: 10px;
        font-size: 24px;
        line-height: 1.2;
        font-weight: 700;
        color: #111827;
        word-break: break-word;
    }

    .task-form-overview__desc {
        margin-top: 10px;
        min-height: 42px;
        color: #6b7280;
        line-height: 1.6;
    }

    .task-form-layout {
        display: grid;
        grid-template-columns: minmax(0, 2fr) minmax(320px, 1fr);
        gap: 20px;
        align-items: start;
    }

    .task-form-panel {
        padding: 20px;
    }

    .task-form-section + .task-form-section {
        margin-top: 18px;
    }

    .task-form-section {
        padding: 18px;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #fff;
    }

    .task-form-section__title {
        margin: 0 0 6px;
        font-size: 16px;
        font-weight: 700;
        color: #111827;
    }

    .task-form-section__desc {
        margin-bottom: 16px;
        color: #6b7280;
        line-height: 1.7;
    }

    .task-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .task-form-item--full {
        grid-column: 1 / -1;
    }

    .task-form-item label {
        display: block;
        margin-bottom: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
    }

    .task-form-item input,
    .task-form-item textarea,
    .task-form-item select {
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        background: #fff;
    }

    .task-form-item input,
    .task-form-item select {
        height: 42px;
        padding: 8px 12px;
    }

    .task-form-item textarea {
        min-height: 120px;
        padding: 10px 12px;
        resize: vertical;
    }

    .task-cycle-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
    }

    .task-form-help {
        margin-top: 6px;
        color: #6b7280;
        line-height: 1.6;
    }

    .task-form-actions {
        margin-top: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .task-form-sidecard {
        padding: 18px;
    }

    .task-form-sidecard + .task-form-sidecard {
        margin-top: 18px;
    }

    .task-form-sidecard__title {
        margin: 0 0 8px;
        font-size: 15px;
        font-weight: 700;
        color: #111827;
    }

    .task-form-sidecard__desc,
    .task-form-sidecard__meta {
        color: #6b7280;
        line-height: 1.7;
    }

    .task-form-sidecard__meta strong {
        color: #111827;
    }

    .task-status-badge {
        display: inline-flex;
        align-items: center;
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

    @media (max-width: 1200px) {
        .task-form-overview,
        .task-form-grid,
        .task-cycle-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .task-form-layout {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }
    }

    @media (max-width: 768px) {
        .task-form-overview,
        .task-form-grid,
        .task-cycle-grid {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }
    }
</style>
<body>

@include(moduleAdminTemplate($moduleName)."public.nav")

<div class="page-container">
    <div class="page-content">
        @include(moduleAdminTemplate($moduleName)."public.left")

        <div class="content-wrapper">
            <div class="page-header">
                @include(moduleAdminTemplate($moduleName)."public.page", ['breadcrumb'=>[$pageData['title'], $pageData['subtitle']]])
            </div>

            <div class="content" style="margin-top: 1rem;">
                <div class="task-form-hero">
                    <h2 class="task-form-hero__title">编辑定时任务</h2>
                    <div class="task-form-hero__desc">
                        调整任务名称、执行周期和执行内容后会立即写回当前任务记录，适合修正调度时间或更新执行目标。
                    </div>

                    <div class="task-form-overview">
                        <div class="task-form-overview__card">
                            <div class="task-form-overview__name">任务状态</div>
                            <div class="task-form-overview__value">
                                <span class="task-status-badge @if($data['status'] == 1) is-active @else is-inactive @endif">
                                    {{\Modules\System\Services\ServiceModel::taskStatus()[$data['status']] ?? '未知'}}
                                </span>
                            </div>
                            <div class="task-form-overview__desc">状态切换仍可在任务列表中直接完成，这里主要负责维护配置本身。</div>
                        </div>
                        <div class="task-form-overview__card">
                            <div class="task-form-overview__name">任务来源</div>
                            <div class="task-form-overview__value">{{ $isModuleTask ? '模块 Hook' : '手动创建' }}</div>
                            <div class="task-form-overview__desc">{{ $isModuleTask ? '该任务绑定模块方法，路径方法展示为只读。' : '该任务为手动创建，可继续调整执行方式与内容。' }}</div>
                        </div>
                        <div class="task-form-overview__card">
                            <div class="task-form-overview__name">最近执行</div>
                            <div class="task-form-overview__value">{{$lastExecution}}</div>
                            <div class="task-form-overview__desc">用于快速判断任务是否已经正常跑过，便于修改后再次验证。</div>
                        </div>
                        <div class="task-form-overview__card">
                            <div class="task-form-overview__name">任务编号</div>
                            <div class="task-form-overview__value">#{{$data['id']}}</div>
                            <div class="task-form-overview__desc">保存时会基于当前任务编号更新原记录，不会创建重复任务。</div>
                        </div>
                    </div>
                </div>

                <div class="task-form-layout">
                    <div class="task-form-panel">
                        <form method="post" id="myForm">
                            {{csrf_field()}}
                            <input type="hidden" name="id" value="{{$data['id']}}">

                            @if($isModuleTask)
                                <input type="hidden" name="task_type" value="1">
                            @endif

                            <div class="task-form-section">
                                <h3 class="task-form-section__title">基础信息</h3>
                                <div class="task-form-section__desc">保留任务的核心描述信息，方便列表页搜索、维护和识别。</div>

                                <div class="task-form-grid">
                                    @if(!$isModuleTask)
                                        <div class="task-form-item task-form-item--full">
                                            <label>任务类型</label>
                                            <select class="form-control" name="task_type">
                                                @foreach(\Modules\System\Services\ServiceModel::task_type() as $ttk => $task_type)
                                                    <option value="{{$ttk}}" @if($ttk == $data['task_type']) selected @endif>{{$task_type}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif

                                    @if($isModuleTask)
                                        <div class="task-form-item task-form-item--full">
                                            <label>路径方法</label>
                                            <input type="text" value="{{$data['module_class'] . '@' . $data['module_class_method']}}" disabled>
                                            <div class="task-form-help">模块任务的路径方法由系统接入决定，编辑页仅展示，不允许修改。</div>
                                        </div>
                                    @endif

                                    <div class="task-form-item task-form-item--full">
                                        <label>任务名称</label>
                                        <input type="text" name="name" class="form-control" placeholder="请输入计划任务名称" value="{{$data['name']}}">
                                    </div>
                                </div>
                            </div>

                            <div class="task-form-section">
                                <h3 class="task-form-section__title">执行周期</h3>
                                <div class="task-form-section__desc">当前周期参数会根据类型自动显示对应的星期、天数和小时设置。</div>

                                <div class="task-cycle-grid">
                                    <div class="task-form-item">
                                        <label>周期类型</label>
                                        <select class="form-control" name="type">
                                            @foreach(\Modules\System\Services\ServiceModel::type() as $tk => $type)
                                                <option value="{{$tk}}" @if($tk == $data['type']) selected @endif>{{$type}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="task-form-item h-week">
                                        <label>星期</label>
                                        <select class="select-search" name="week">
                                            @foreach(\Modules\System\Services\ServiceModel::taskWeek() as $wk => $week)
                                                <option value="{{$wk}}" @if($wk == $data['day']) selected @endif>{{$week}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="task-form-item h-day">
                                        <label>天数</label>
                                        <select class="select-search" name="day">
                                            @for($d=1;$d<=31;$d++)
                                                <option value="{{$d}}" @if($d == $data['day']) selected @endif>{{$d}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="task-form-item h-hour">
                                        <label>小时</label>
                                        <select class="select-search" name="hour">
                                            @for($h=0;$h<=23;$h++)
                                                <option value="{{$h}}" @if($h == $data['hour']) selected @endif>{{$h}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="task-form-item">
                                        <label>分钟</label>
                                        <select class="select-search" name="minute">
                                            @for($m=0;$m<=60;$m++)
                                                <option value="{{$m}}" @if($m == $data['minute']) selected @endif>{{$m}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>

                            @if(!$isModuleTask)
                                <div class="task-form-section">
                                    <h3 class="task-form-section__title">执行内容</h3>
                                    <div class="task-form-section__desc">手动任务可继续调整 URL 地址或脚本内容，系统会按任务类型读取对应字段。</div>

                                    <div class="task-form-grid">
                                        <div class="task-form-item task-form-item--full h-content1">
                                            <label>URL 地址</label>
                                            <input type="text" name="content1" class="form-control" value="{{$data['task_type'] == 1 ? $data['content'] : 'http://'}}">
                                        </div>
                                        <div class="task-form-item task-form-item--full h-content2">
                                            <label>脚本内容</label>
                                            <textarea name="content2" class="form-control" rows="6" placeholder="请输入脚本内容">@if($data['task_type'] == 2){!! $data['content'] !!}@endif</textarea>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="task-form-section">
                                <h3 class="task-form-section__title">备注信息</h3>
                                <div class="task-form-section__desc">建议记录任务用途、依赖上下文或变更说明，便于多人协作维护。</div>

                                <div class="task-form-grid">
                                    <div class="task-form-item task-form-item--full">
                                        <label>备注</label>
                                        <input type="text" name="remark" class="form-control" value="{{$data['remark']}}" placeholder="备注">
                                    </div>
                                </div>
                            </div>

                            <div class="task-form-actions">
                                <button type="button" class="btn btn-info h-sub">保存修改</button>
                                <button type="button" class="btn btn-default" onclick="history.go(-1)">返回列表</button>
                            </div>
                        </form>
                    </div>

                    <div>
                        <div class="task-form-sidecard">
                            <h4 class="task-form-sidecard__title">当前任务</h4>
                            <div class="task-form-sidecard__meta"><strong>ID：</strong>{{$data['id']}}</div>
                            <div class="task-form-sidecard__meta"><strong>名称：</strong>{{$data['name']}}</div>
                            @if($isModuleTask)
                                <div class="task-form-sidecard__meta"><strong>方法：</strong>{{$data['module_class'] . '@' . $data['module_class_method']}}</div>
                            @endif
                            <div class="task-form-sidecard__meta"><strong>最近执行：</strong>{{$lastExecution}}</div>
                        </div>

                        <div class="task-form-sidecard">
                            <h4 class="task-form-sidecard__title">编辑建议</h4>
                            <div class="task-form-sidecard__desc">如果只是短期停用任务，建议直接回任务列表切换状态；如果是时间策略变更，再在这里修改执行周期。</div>
                        </div>

                        <div class="task-form-sidecard">
                            <h4 class="task-form-sidecard__title">保存后验证</h4>
                            <div class="task-form-sidecard__desc">保存完成后可回到任务列表立即执行一次，并结合日志确认修改是否生效。</div>
                        </div>
                    </div>
                </div>

                @include(moduleAdminTemplate($moduleName)."public.footer")
            </div>
        </div>
    </div>
</div>

@include(moduleAdminTemplate($moduleName)."public.js")
<script>
    function selectType(type) {
        if (type == 6) {
            $('.h-week').show();
        } else {
            $('.h-week').hide();
        }

        if (type == 2 || type == 7) {
            $('.h-day').show();
        } else {
            $('.h-day').hide();
        }

        if (type == 1 || type == 2 || type == 4 || type == 6 || type == 7) {
            $('.h-hour').show();
        } else {
            $('.h-hour').hide();
        }
    }

    function selectTaskType(type) {
        if (type == 2) {
            $('.h-content1').hide();
            $('.h-content2').show();
        } else {
            $('.h-content1').show();
            $('.h-content2').hide();
        }
    }

    $('select[name="type"]').change(function () {
        selectType($(this).val());
    });

    $('select[name="task_type"]').change(function () {
        selectTaskType($(this).val());
    });

    $('.h-sub').click(function () {
        layer.load(1);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            method: 'post',
            url: "{{moduleAdminJump($moduleName,'secure/scheduledTasksEdit')}}",
            data: new FormData($('#myForm')[0]),
            dataType: 'json',
            cache: false,
            processData: false,
            contentType: false,
            success: function (res) {
                layer.closeAll();
                if (res.status == 200) {
                    layer.msg(res.msg, {icon: 1, time: 1000}, function () {
                        window.location.href = "{{moduleAdminJump($moduleName,'secure/scheduledTasksList')}}";
                    });
                } else {
                    layer.msg(res.msg, {icon: 2});
                }
            },
            error: function () {
                layer.closeAll();
                layer.msg('系统错误，请稍后重试', {icon: 5});
            }
        });
    });

    $(function () {
        $('.h-week').hide();
        $('.h-day').hide();
        $('.h-hour').hide();
        selectType($('select[name="type"]').val());
        @if(!$isModuleTask)
        selectTaskType($('select[name="task_type"]').val());
        @endif
    });
</script>
<script type="text/javascript" src="{{asset("assets/module")}}/js/plugins/forms/selects/select2.min.js"></script>
<script type="text/javascript" src="{{asset("assets/module")}}/js/pages/form_select2.js"></script>
</body>
</html>
