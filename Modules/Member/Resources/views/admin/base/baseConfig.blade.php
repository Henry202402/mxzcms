@include(moduleAdminTemplate($moduleName)."public.header")
<style>
    input[type="radio"] {
        margin: 8px 10px 0px;
    }

    .h-del-btn {
        color: red;
        margin-top: 11px;
        cursor: pointer;
    }

    .mt-30 {
        margin-top: 30px !important;
    }

    .h-clear-both {
        clear: both;
    }

    .h-span-val {
        font-size: 17px;
        position: relative;
        top: 5px;
    }

    #brag_user_check_time,
    #brag_user_check_time_end,
    #brag_anchor_check_time,
    #brag_anchor_check_time_end {
        background-color: #fff;
        cursor: pointer;
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
                @include(moduleAdminTemplate($moduleName)."public.page",['breadcrumb'=>[$pageData['title'],$pageData['subtitle']]])
            </div>
            <!-- /page header -->


            <!-- Content area -->
            <div class="content" style="margin-top: 1rem;">


                <div class="panel panel-flat">
                    <div class="panel-heading">


                        {{csrf_field()}}
                        <fieldset class="content-group">
                            <ul class="nav nav-tabs">
                                <li @if(!$_GET['type'] || $_GET['type']=='vip') class="active" @endif >
                                    <a href data-toggle="tab" data-target="#vip">
                                        VIP
                                    </a>
                                </li>
                                <li @if($_GET['type']=='signIn') class="active" @endif >
                                    <a href data-toggle="tab" data-target="#signIn">
                                        签到与积分
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane @if(!$_GET['type'] || $_GET['type']=='vip') active @endif "
                                     id="vip">
                                    <form id="vip_form" class="h-mt20" role="form" method="post"
                                          enctype="multipart/form-data">
                                        <input type="hidden" name="type" value="vip">


                                        <legend class="text-bold">会员权益</legend>

                                        <div class="form-group col-md-10 mt-10 ml-5">
                                            <button type="button" class="btn btn-info btn-xs" onclick="addVipInt(this)">
                                                添加
                                            </button>
                                        </div>
                                        <div class="col-md-12 mt-20">
                                            <label class="col-md-3">名称</label>
                                            <label class="col-md-3">描述</label>
                                        </div>
                                        <div id="addVipIntDiv">
                                            @foreach($data['vip']['interests'] as $key1=>$value1)
                                                <div class="col-md-12 mt-20 addVipIntClass">
                                                    <label class="col-md-3">
                                                        <input type="text" class="form-control"
                                                               name="interests[{{$key1}}][name]"
                                                               value="{{$value1['name']}}">
                                                    </label>
                                                    <label class="col-md-3">
                                                        <input type="text" class="form-control"
                                                               name="interests[{{$key1}}][value]"
                                                               value="{{$value1['value']}}">
                                                    </label>
                                                    <div class="col-md-1">
                                                        <i class="icon-bin h-del-btn"
                                                           onclick="row_del(this)"></i>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="h-clear-both"></div>
                                        <br>

                                        <legend class="text-bold">规则</legend>
                                        <div class="col-md-12">
                                            <textarea class="form-control"
                                                      name="vip_rule"
                                                      id="vip_rule">{{$data['vip']['vip_rule']}}</textarea>
                                        </div>

                                        <div class="form-group col-md-10 mt-30 ml-5">
                                            <button type="button" onclick="formSub('vip_form','vip')"
                                                    class="btn btn-primary {{permissions('base/baseConfigSubmit')}}">
                                                提交
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane @if($_GET['type']=='signIn') active @endif " id="signIn">
                                    <form id="signIn_form" class="h-mt20" role="form" method="post"
                                          enctype="multipart/form-data">
                                        <input type="hidden" name="type" value="signIn">
                                        <div class="form-group row">
                                            <label class="col-lg-1 control-label">
                                                积分别名
                                            </label>
                                            <div class="col-lg-11">
                                                <input type="text" class="form-control"
                                                       name="integral_alias" placeholder="积分别名"
                                                       value="{{$data['signIn']['integral_alias']}}">
                                            </div>

                                        </div>
                                        <div class="form-group row">
                                            <label class="control-label col-lg-1">
                                                奖励
                                                <button type="button" class="btn btn-info btn-xs"
                                                        style="padding: 1px 5px;" onclick="addDayInt(this)">
                                                    添加
                                                </button>
                                            </label>
                                            <div class="col-lg-11" style="margin-left: -20px;">
                                                <div class="col-lg-12">
                                                    <label class="col-lg-5">签到天数</label>
                                                    <label class="col-lg-5">签到积分</label>
                                                </div>
                                                <div id="addDayIntDiv">
                                                    @foreach($data['signIn']['day_int'] as $key1=>$value1)
                                                        <div class="col-lg-12 mt-5 addDayIntClass">
                                                            <label class="col-lg-5">
                                                                <input type="number" class="form-control"
                                                                       name="day_int[{{$key1}}][key]"
                                                                       value="{{$value1['key']}}">
                                                            </label>
                                                            <label class="col-lg-5">
                                                                <input type="number" class="form-control"
                                                                       name="day_int[{{$key1}}][value]"
                                                                       value="{{$value1['value']}}">
                                                            </label>
                                                            <div class="col-lg-1">
                                                                <i class="icon-bin h-del-btn"
                                                                   onclick="row_del(this)"></i>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-20">
                                            <label>
                                                签到规则
                                            </label>
                                            <div>
                                                <textarea name="sign_in_rules" id="sign_in_rules" class="form-control"
                                                          rows="10">{!! $data['signIn']['sign_in_rules'] !!}</textarea>
                                            </div>
                                        </div>
                                        {{--                                        @include(moduleAdminTemplate("formtools")."formtooltemplates.editor",$editer['signin'])--}}
                                        <div class="form-group col-md-10 mt-10 ml-5">
                                            <button type="button" onclick="formSub('signIn_form','signIn')"
                                                    class="btn btn-primary {{permissions('base/baseConfigSubmit')}}">
                                                提交
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </fieldset>
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
<script type="text/javascript" src="{{asset("assets/module")}}/laydate/laydate.js"></script>
<script>
    var setType = false;

    function formSub(form, type) {
        layer.load(1);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            "method": "post",
            "url": "{{moduleAdminJump($moduleName,'setting/baseConfigSubmit')}}",
            "data": new FormData($('#' + form)[0]),                    //$("#post_form").serialize(),
            "dataType": 'json',
            "cache": false,
            "processData": false,
            "contentType": false,
            "success": function (res) {
                layer.closeAll();
                if (res.status == 200) {
                    layer.msg(res.msg, {icon: 1, time: 1000}, function () {
                        if (setType !== false) type = setType;
                        window.location.href = "{{moduleAdminJump($moduleName,'setting/baseConfig?type=')}}" + type;
                    });
                } else {
                    layer.msg(res.msg, {icon: 5})
                }
            },
            "error": function (res) {
                console.log(res);
            }
        })
    }

    //添加vip
    function addVipInt(obj) {
        var key_personal = document.getElementsByClassName('addVipIntClass').length;
        var contact = `<div class="col-md-12 mt-20 addVipIntClass">
                            <label class="col-md-3">
                                <input type="text" class="form-control"
                                       name="interests[${key_personal}][name]"
                                       value="">
                            </label>
                            <label class="col-md-3">
                                <input type="text" class="form-control"
                                       name="interests[${key_personal}][value]"
                                       value="">
                            </label>

                            <div class="col-md-1">
                                <i class="icon-bin h-del-btn"
                                   onclick="row_del(this)"></i>
                            </div>
                        </div>`;
        $('#addVipIntDiv').append(contact);
    }

    function row_del(obj) {
        $(obj).parent().parent().remove();
    }

    //添加签到天数
    function addDayInt(obj) {
        var key_personal = document.getElementsByClassName('addDayIntClass').length;
        var contact = `<div class="col-lg-12 mt-5 addDayIntClass">
                            <label class="col-lg-5">
                                <input type="number" class="form-control"
                                       name="day_int[${key_personal}][key]"
                                       value="">
                            </label>
                            <label class="col-lg-5">
                                <input type="number" class="form-control"
                                       name="day_int[${key_personal}][value]"
                                       value="">
                            </label>
                            <div class="col-lg-1">
                                <i class="icon-bin h-del-btn"
                                   onclick="row_del(this)"></i>
                            </div>
                        </div>`;
        $('#addDayIntDiv').append(contact);
    }
</script>
<script type="text/javascript" src="{{url('views/modules/editor/assets/tinymce/tinymce.min.js')}}"></script>
<script>
    var vip = tinymce.init({
        selector: '#vip_rule',
        elem: "#edit-vip_rule",
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            })
        },
        language: 'zh_CN',
        style_formats: [
            {
                title: '首行缩进',
                block: 'p',
                styles: {
                    'text-indent': '2em'
                },
            },
            {
                title: '段落距离',
                block: 'p',
                items: [
                    {title: '0em', block: 'p', styles: {"margin-bottom": '0em'}},
                    {title: '0.5em', block: 'p', styles: {"margin-bottom": '0.5em'}},
                    {title: '1em', block: 'p', styles: {"margin-bottom": '1em'}},
                    {title: '1.5em', block: 'p', styles: {"margin-bottom": '1.5em'}},
                    {title: '2em', block: 'p', styles: {"margin-bottom": '2em'}},
                ]
            },
        ],
        // 解决粘贴图片后，不自动上传，而是使用base64编码。
        urlconverter_callback: (url, node, onSave, name) => {
            if (node === 'img' && url.startsWith('blob:')) {
                // Do some custom URL conversion
                console.log('urlConverter:', url, node, onSave, name)
                tinymce.activeEditor && tinymce.activeEditor.uploadImages()
            }
            // Return new URL
            return url
        },
        images_upload_handler: function (blobInfo, succFun, failFun) {
            var xhr, formData;
            var file = blobInfo.blob();//转化为易于理解的file对象
            xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', "{{moduleAdminJump('formtools',"model?_token=".csrf_token())}}");
            xhr.onload = function () {
                var json;
                if (xhr.status != 200) {
                    failFun('HTTP Error: ' + xhr.status);
                    return;
                }
                json = JSON.parse(xhr.responseText);
                if (!json || typeof json.location != 'string') {
                    failFun(json.msg);
                    return;
                }
                succFun(json.location);
            };
            formData = new FormData();
            formData.append('file', file, file.name);//此处与源文档不一样
            formData.append('moduleName', '{{$_GET['moduleName']}}');//此处与源文档不一样
            formData.append('action', 'uploadImg');//此处与源文档不一样
            formData.append('model', '{{$_GET['model']}}');//此处与源文档不一样
            xhr.send(formData);
        },
        style_formats_merge: true,
        nonbreaking_force_tab: true,
        style_formats_autohide: true,
        content_style: "p {margin: 0px; border:0px ; padding: 0px}",
        menu: {
            file: {
                title: "文件",
                items: "fullscreen | preview | print ",
            },
            edit: {
                title: '编辑',
                items: 'undo redo | cut copy paste pastetext | selectall'
            },
            insert: {
                title: '插入',
                // items: 'image media link | template hr'
                items: ''
            },
            view: {
                title: '查看',
                items: 'visualaid'
            },

            table: {
                title: '表格',
                items: 'inserttable tableprops deletetable | cell row column'
            },
            tools: {
                title: '工具',
                items: 'spellchecker code'
            }
        },
        // toolbar: "undo redo  styleselect | bold italic | alignleft | aligncenter | alignright | numlist | lineheight | link | image ",
        plugins: " uickbars print nonbreaking preview searchreplace autolink fullscreen image link media codesample table charmap hr advlist lists wordcount imagetools indent2em code codesample",
        toolbar: "undo redo styleselect | bold italic | alignleft | aligncenter | alignright | lineheight | codesample | code",
        width: "100%",
        quickbars_insert_toolbar: '',
        height: "600px",
        convert_urls: false
    });
    var signin = tinymce.init({
        selector: '#sign_in_rules',
        elem: "#edit-sign_in_rules",
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            })
        },
        language: 'zh_CN',
        style_formats: [
            {
                title: '首行缩进',
                block: 'p',
                styles: {
                    'text-indent': '2em'
                },
            },
            {
                title: '段落距离',
                block: 'p',
                items: [
                    {title: '0em', block: 'p', styles: {"margin-bottom": '0em'}},
                    {title: '0.5em', block: 'p', styles: {"margin-bottom": '0.5em'}},
                    {title: '1em', block: 'p', styles: {"margin-bottom": '1em'}},
                    {title: '1.5em', block: 'p', styles: {"margin-bottom": '1.5em'}},
                    {title: '2em', block: 'p', styles: {"margin-bottom": '2em'}},
                ]
            },
        ],
        // 解决粘贴图片后，不自动上传，而是使用base64编码。
        urlconverter_callback: (url, node, onSave, name) => {
            if (node === 'img' && url.startsWith('blob:')) {
                // Do some custom URL conversion
                console.log('urlConverter:', url, node, onSave, name)
                tinymce.activeEditor && tinymce.activeEditor.uploadImages()
            }
            // Return new URL
            return url
        },
        images_upload_handler: function (blobInfo, succFun, failFun) {
            var xhr, formData;
            var file = blobInfo.blob();//转化为易于理解的file对象
            xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', "{{moduleAdminJump('formtools',"model?_token=".csrf_token())}}");
            xhr.onload = function () {
                var json;
                if (xhr.status != 200) {
                    failFun('HTTP Error: ' + xhr.status);
                    return;
                }
                json = JSON.parse(xhr.responseText);
                if (!json || typeof json.location != 'string') {
                    failFun(json.msg);
                    return;
                }
                succFun(json.location);
            };
            formData = new FormData();
            formData.append('file', file, file.name);//此处与源文档不一样
            formData.append('moduleName', '{{$_GET['moduleName']}}');//此处与源文档不一样
            formData.append('action', 'uploadImg');//此处与源文档不一样
            formData.append('model', '{{$_GET['model']}}');//此处与源文档不一样
            xhr.send(formData);
        },
        style_formats_merge: true,
        nonbreaking_force_tab: true,
        style_formats_autohide: true,
        content_style: "p {margin: 0px; border:0px ; padding: 0px}",
        menu: {
            file: {
                title: "文件",
                items: "fullscreen | preview | print ",
            },
            edit: {
                title: '编辑',
                items: 'undo redo | cut copy paste pastetext | selectall'
            },
            insert: {
                title: '插入',
                // items: 'image media link | template hr'
                items: ''
            },
            view: {
                title: '查看',
                items: 'visualaid'
            },

            table: {
                title: '表格',
                items: 'inserttable tableprops deletetable | cell row column'
            },
            tools: {
                title: '工具',
                items: 'spellchecker code'
            }
        },
        // toolbar: "undo redo  styleselect | bold italic | alignleft | aligncenter | alignright | numlist | lineheight | link | image ",
        plugins: " uickbars print nonbreaking preview searchreplace autolink fullscreen image link media codesample table charmap hr advlist lists wordcount imagetools indent2em code codesample",
        toolbar: "undo redo styleselect | bold italic | alignleft | aligncenter | alignright | lineheight | codesample | code",
        width: "100%",
        quickbars_insert_toolbar: '',
        height: "600px",
        convert_urls: false
    });
</script>
<script>
    var selectHtml = '';
    @foreach($data['taskList'] as $tkk=>$taskList)
        selectHtml += `<option value = "{{$tkk}}">{{$taskList}}</option>`;
    @endforeach

    //添加任务
    function addTaskInt(obj) {
        var key_personal = document.getElementsByClassName('addTaskIntClass').length;
        var contact = `<div class="col-md-12 mt-20 addTaskIntClass">
                            <label class="col-md-1">
                                <input type="checkbox" class="styled h-radio"
                                       name="list[${key_personal}][is_show]"
                                       value="1">
                            </label>

                            <label class="col-md-5">
                                <select  class="form-control"
                                       name="list[${key_personal}][type]">
                                ${selectHtml}
                                </select>
                            </label>
                            <label class="col-md-1">
                                <input type="text" class="form-control"
                                       name="list[${key_personal}][coin]"
                                       value="">
                            </label>
                            <div class="col-md-1">
                                <i class="icon-bin h-del-btn"
                                   onclick="task_del(this)"></i>
                            </div>
                        </div>`;
        $('#addTaskIntDiv').append(contact);
    }

    function task_del(obj) {
        $(obj).parent().parent().remove();
    }

</script>


<script type="text/javascript"
        src="{{asset("assets/module")}}/js/core/libraries/jquery_ui/core.min.js"></script>
<script type="text/javascript"
        src="{{asset("assets/module")}}/js/plugins/forms/selects/selectboxit.min.js"></script>
<script type="text/javascript" src="{{asset("assets/module")}}/js/pages/form_selectbox.js"></script>

<script type="text/javascript"
        src="{{asset("assets/module")}}/js/plugins/forms/selects/select2.min.js"></script>
<script type="text/javascript" src="{{asset("assets/module")}}/js/pages/form_select2.js"></script>
</body>
</html>
