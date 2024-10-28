@include(moduleAdminTemplate($moduleName)."public.header")
<style>
    .input-group {
        float: left !important;
    }
</style>
<body>

<!--                        Topbar End                              -->
<!-- ============================================================== -->


<!-- ============================================================== -->
<!-- 						Navigation Start 						-->
<!-- ============================================================== -->

@include(moduleAdminTemplate($moduleName)."public.nav")
<!-- ============================================================== -->
<!-- 						Navigation End	 						-->
<!-- ============================================================== -->

<!-- Page container -->
<div class="page-container">

    <!-- Page content -->
    <div class="page-content">


    @include(moduleAdminTemplate($moduleName)."public.left")


    <!-- Main content -->
        <div class="content-wrapper">

            <!-- Page header -->
            <div class="page-header">
                @include(moduleAdminTemplate($moduleName)."public.page",
         ['breadcrumb'=>['系统设置','菜单添加']])
            </div>
            <!-- /page header -->


            <!-- Content area -->
            <div class="content" style="margin-top: 1rem;">


                <div class="panel panel-flat">
                    <div class="panel-heading">

                        <form class="form-horizontal" method="post" id="myForm">
                            {{csrf_field()}}
                            <fieldset class="content-group">
                                <legend class="text-bold">列表</legend>
                                @if(!($data['module_class'] && $data['module_class_method']))
                                    <div class="form-group">
                                        <label class="control-label col-lg-1">任务类型</label>
                                        <div class="col-lg-11">
                                            <select class="form-control" name="task_type">
                                                @foreach(\Modules\System\Services\ServiceModel::task_type() as $ttk=>$task_type)
                                                    <option value="{{$ttk}}"
                                                            @if($ttk==$data['task_type']) selected @endif >
                                                        {{$task_type}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @else
                                    <input type="hidden" name="task_type" value="1">
                                    <div class="form-group">
                                        <label class="col-lg-1 control-label">
                                            路径方法
                                        </label>
                                        <div class="col-lg-11">
                                            <input type="text" class="form-control" value="{{$data['module_class'].'@'.$data['module_class_method']}}" disabled>
                                        </div>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label class="col-lg-1 control-label">
                                        任务名称
                                    </label>
                                    <div class="col-lg-11">
                                        <input type="text" name="name" class="form-control"
                                               placeholder="请输入计划任务名称" value="{{$data['name']}}">
                                    </div>
                                </div>

                                <div class="form-group" style="">
                                    <label class="control-label col-lg-1">执行周期</label>
                                    <div class="col-lg-2">
                                        <select class="form-control" name="type">
                                            @foreach(\Modules\System\Services\ServiceModel::type() as $tk=>$type)
                                                <option value="{{$tk}}"
                                                        @if($tk==$data['type']) selected @endif >
                                                    {{$type}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="input-group col-lg-1 h-week">
                                        <select class="select-search" name="week">
                                            @foreach(\Modules\System\Services\ServiceModel::taskWeek() as $wk=>$week)
                                                <option value="{{$wk}}"
                                                        @if($wk==$data['day']) selected @endif >
                                                    {{$week}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="input-group col-lg-2 ml-10 h-day">
                                        <select class="select-search" name="day">
                                            @for($d=1;$d<=31;$d++)
                                                <option value="{{$d}}"
                                                        @if($d==$data['day']) selected @endif >
                                                    {{$d}}
                                                </option>
                                            @endfor
                                        </select>
                                        <span class="input-group-addon">天</span>
                                    </div>
                                    <div class="input-group col-lg-2 ml-10 h-hour">
                                        <select class="select-search" name="hour">
                                            @for($h=0;$h<=23;$h++)
                                                <option value="{{$h}}"
                                                        @if($h==$data['hour']) selected @endif >
                                                    {{$h}}
                                                </option>
                                            @endfor
                                        </select>
                                        <span class="input-group-addon">小时</span>
                                    </div>
                                    <div class="input-group col-lg-2 ml-10">
                                        <select class="select-search" name="minute">
                                            @for($m=0;$m<=60;$m++)
                                                <option value="{{$m}}"
                                                        @if($m==$data['minute']) selected @endif >
                                                    {{$m}}
                                                </option>
                                            @endfor
                                        </select>
                                        <span class="input-group-addon">分钟</span>
                                    </div>
                                </div>
                                @if(!($data['module_class'] && $data['module_class_method']))
                                    <div class="form-group h-content1">
                                        <label class="col-lg-1 control-label">
                                            URL地址
                                        </label>
                                        <div class="col-lg-11">
                                            <input type="text" name="content1" class="form-control"
                                                   value="{{$data['content']}}">
                                        </div>
                                    </div>
                                    <div class="form-group h-content2">
                                        <label class="col-lg-1 control-label">
                                            脚本内容
                                        </label>
                                        <div class="col-lg-11">
                                        <textarea name="content2" class="form-control" rows="5"
                                                  placeholder="请输入脚本内容">{!! $data['content'] !!}</textarea>
                                        </div>
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label class="col-lg-1 control-label">
                                        备注
                                    </label>
                                    <div class="col-lg-11">
                                        <input type="text" name="remark" class="form-control"
                                               value="{{$data['remark']}}" placeholder="备注">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <input type="hidden" name="id" value="{{$data['id']}}">
                                    <label class="col-lg-1 control-label"></label>
                                    <div class="col-lg-11">
                                        <button type="button" class="btn btn-sm btn-info h-sub">
                                            提交
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="history.go(-1)">
                                            返回
                                        </button>
                                    </div>
                                </div>
                            </fieldset>

                        </form>
                    </div>
                </div>

                <!-- Footer -->
            @include(moduleAdminTemplate($moduleName)."public.footer")
            <!-- /footer -->

            </div>
            <!-- /content area -->

        </div>
        <!-- /main content -->

    </div>
    <!-- /page content -->

</div>

<!-- 						Content End		 						-->
<!-- ============================================================== -->
@include(moduleAdminTemplate($moduleName)."public.js")
<script>
    function selectType(type = 1) {
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

    $('select[name="type"]').change(function () {
        var type = $(this).val();
        selectType(type);
    });

    function selectTaskType(type) {
        if (type == 2) {
            $('.h-content1').hide();
            $('.h-content2').show();
        } else {
            $('.h-content1').show();
            $('.h-content2').hide();
        }
    }

    $('select[name="task_type"]').change(function () {
        var type = $(this).val();
        selectTaskType(type);
    });

    $('.h-sub').click(function () {
        layer.load(1);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            "method": "post",
            "url": "{{moduleAdminJump($moduleName,'secure/scheduledTasksEdit')}}",
            "data": new FormData($('#myForm')[0]),
            "dataType": 'json',
            "cache": false,
            "processData": false,
            "contentType": false,
            "success": function (res) {
                layer.closeAll();
                if (res.status == 200) {
                    layer.msg(res.msg, {icon: 1, time: 1000}, function () {
                        window.location.href = "{{moduleAdminJump($moduleName,'secure/scheduledTasksList')}}";
                    });
                } else {
                    layer.msg(res.msg, {icon: 2})
                }
            },
            "error": function (res) {
                layer.closeAll();
                layer.msg("系统错误，请稍后重试", {icon: 5})
            }
        });
    });

    $(function () {
        $('.h-week').hide();
        $('.h-day').hide();
        $('.h-hour').hide();
        selectTaskType('{{$data['task_type']}}');
        selectType('{{$data['type']}}');
    })
</script>
<script type="text/javascript"
        src="{{moduleAdminResource($moduleName)}}/js/plugins/forms/selects/select2.min.js"></script>
<script type="text/javascript" src="{{moduleAdminResource($moduleName)}}/js/pages/form_select2.js"></script>
</body>
</html>