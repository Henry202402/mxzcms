@include(moduleAdminTemplate($moduleName)."public.header")
@php($moduleInfo = $pageData['moduleInfo'] ?? [])
@php($isModuleTask = !empty($moduleInfo))
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
        grid-template-columns: repeat(3, minmax(0, 1fr));
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
        word-break: break-all;
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

    .task-form-item--full,
    .task-form-item--cycle {
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

    @media (max-width: 1200px) {
        .task-form-overview,
        .task-form-layout,
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
                    <h2 class="task-form-hero__title">新增定时任务</h2>
                    <div class="task-form-hero__desc">
                        支持添加模块 Hook 任务、URL 任务或脚本任务。建议先确认执行周期和执行内容，再保存到统一任务列表中。
                    </div>

                    <div class="task-form-overview">
                        <div class="task-form-overview__card">
                            <div class="task-form-overview__name">任务来源</div>
                            <div class="task-form-overview__value">{{ $isModuleTask ? '模块 Hook' : '手动创建' }}</div>
                            <div class="task-form-overview__desc">{{ $isModuleTask ? '当前任务来自模块暴露的方法，可直接纳入后台统一调度。' : '可创建 URL 请求或脚本执行任务，适合补充站点自动化需求。' }}</div>
                        </div>
                        <div class="task-form-overview__card">
                            <div class="task-form-overview__name">默认执行周期</div>
                            <div class="task-form-overview__value">每天 1:30</div>
                            <div class="task-form-overview__desc">新增页默认提供一组可直接调整的周期参数，保存前可按业务需要修改。</div>
                        </div>
                        <div class="task-form-overview__card">
                            <div class="task-form-overview__name">创建目标</div>
                            <div class="task-form-overview__value">{{ $isModuleTask ? ($moduleInfo[1] . '@' . $moduleInfo[2]) : '自定义执行内容' }}</div>
                            <div class="task-form-overview__desc">{{ $isModuleTask ? '任务目标已自动带入，无需重复填写路径方法。' : '根据任务类型填写 URL 地址或脚本内容。' }}</div>
                        </div>
                    </div>
                </div>

                <div class="task-form-layout">
                    <div class="task-form-panel">
                        <form method="post" id="myForm">
                            {{csrf_field()}}

                            @if($isModuleTask)
                                <input type="hidden" name="task_type" value="1">
                                <input type="hidden" name="module" value="{{$moduleInfo[0]}}">
                                <input type="hidden" name="module_class" value="{{$moduleInfo[1]}}">
                                <input type="hidden" name="module_class_method" value="{{$moduleInfo[2]}}">
                            @endif

                            <div class="task-form-section">
                                <h3 class="task-form-section__title">基础信息</h3>
                                <div class="task-form-section__desc">先定义任务名称和来源，方便后续在任务列表中搜索和管理。</div>

                                <div class="task-form-grid">
                                    @if(!$isModuleTask)
                                        <div class="task-form-item task-form-item--full">
                                            <label>任务类型</label>
                                            <select class="form-control" name="task_type">
                                                @foreach(\Modules\System\Services\ServiceModel::task_type() as $ttk => $task_type)
                                                    <option value="{{$ttk}}">{{$task_type}}</option>
                                                @endforeach
                                            </select>
                                            <div class="task-form-help">URL 任务适合触发接口或页面访问，脚本任务适合执行自定义代码片段。</div>
                                        </div>
                                    @endif

                                    @if($isModuleTask)
                                        <div class="task-form-item task-form-item--full">
                                            <label>路径方法</label>
                                            <input type="text" value="{{$moduleInfo[1] . '@' . $moduleInfo[2]}}" disabled>
                                            <div class="task-form-help">该任务来源于模块 Hook，保存后会直接作为模块任务执行。</div>
                                        </div>
                                    @endif

                                    <div class="task-form-item task-form-item--full">
                                        <label>任务名称</label>
                                        <input type="text" name="name" class="form-control" placeholder="请输入计划任务名称" value="{{$moduleInfo[3] ?? ''}}">
                                    </div>
                                </div>
                            </div>

                            <div class="task-form-section">
                                <h3 class="task-form-section__title">执行周期</h3>
                                <div class="task-form-section__desc">执行周期会决定任务的触发频率，周、天、小时参数会根据周期类型自动显示。</div>

                                <div class="task-cycle-grid">
                                    <div class="task-form-item">
                                        <label>周期类型</label>
                                        <select class="form-control" name="type">
                                            @foreach(\Modules\System\Services\ServiceModel::type() as $tk => $type)
                                                <option value="{{$tk}}">{{$type}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="task-form-item h-week">
                                        <label>星期</label>
                                        <select class="select-search" name="week">
                                            @foreach(\Modules\System\Services\ServiceModel::taskWeek() as $tk => $type)
                                                <option value="{{$tk}}">{{$type}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="task-form-item h-day">
                                        <label>天数</label>
                                        <select class="select-search" name="day">
                                            @for($d=1;$d<=31;$d++)
                                                <option value="{{$d}}">{{$d}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="task-form-item h-hour">
                                        <label>小时</label>
                                        <select class="select-search" name="hour">
                                            @for($h=0;$h<=23;$h++)
                                                <option value="{{$h}}" @if($h==1) selected @endif>{{$h}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="task-form-item">
                                        <label>分钟</label>
                                        <select class="select-search" name="minute">
                                            @for($m=0;$m<=60;$m++)
                                                <option value="{{$m}}" @if($m==30) selected @endif>{{$m}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>

                            @if(!$isModuleTask)
                                <div class="task-form-section">
                                    <h3 class="task-form-section__title">执行内容</h3>
                                    <div class="task-form-section__desc">根据任务类型填写对应内容，切换任务类型时会自动显示对应输入区。</div>

                                    <div class="task-form-grid">
                                        <div class="task-form-item task-form-item--full h-content1">
                                            <label>URL 地址</label>
                                            <input type="text" name="content1" class="form-control" value="http://">
                                            <div class="task-form-help">建议填写完整的 `http://` 或 `https://` 地址。</div>
                                        </div>
                                        <div class="task-form-item task-form-item--full h-content2">
                                            <label>脚本内容</label>
                                            <textarea name="content2" class="form-control" rows="6" placeholder="请输入脚本内容"></textarea>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="task-form-section">
                                <h3 class="task-form-section__title">备注信息</h3>
                                <div class="task-form-section__desc">建议补充任务用途、依赖说明或执行注意事项，便于后续维护。</div>

                                <div class="task-form-grid">
                                    <div class="task-form-item task-form-item--full">
                                        <label>备注</label>
                                        <input type="text" name="remark" class="form-control" placeholder="备注" value="{{$moduleInfo[3] ?? ''}}">
                                    </div>
                                </div>
                            </div>

                            <div class="task-form-actions">
                                <button type="button" class="btn btn-info h-sub">保存任务</button>
                                <button type="button" class="btn btn-default" onclick="history.go(-1)">返回列表</button>
                            </div>
                        </form>
                    </div>

                    <div>
                        <div class="task-form-sidecard">
                            <h4 class="task-form-sidecard__title">创建建议</h4>
                            <div class="task-form-sidecard__desc">优先把模块 Hook 任务接入后台管理，便于统一启停、查看日志和排查执行状态。</div>
                        </div>

                        <div class="task-form-sidecard">
                            <h4 class="task-form-sidecard__title">当前来源</h4>
                            @if($isModuleTask)
                                <div class="task-form-sidecard__meta"><strong>模块：</strong>{{$moduleInfo[0]}}</div>
                                <div class="task-form-sidecard__meta"><strong>方法：</strong>{{$moduleInfo[1] . '@' . $moduleInfo[2]}}</div>
                                <div class="task-form-sidecard__meta"><strong>备注：</strong>{{$moduleInfo[3] ?? '暂无说明'}}</div>
                            @else
                                <div class="task-form-sidecard__desc">当前为手动新增任务，可自由选择 URL 请求或脚本执行方式。</div>
                            @endif
                        </div>

                        <div class="task-form-sidecard">
                            <h4 class="task-form-sidecard__title">保存后可做什么</h4>
                            <div class="task-form-sidecard__desc">保存成功后可直接在列表页执行一次、查看执行日志、启停任务或继续编辑参数。</div>
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
            url: "{{moduleAdminJump($moduleName,'secure/scheduledTasksAdd')}}",
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
