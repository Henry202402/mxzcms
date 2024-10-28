@include("admin.public.header")
<body class="horizontal">
@include("admin.public.themeMenuNav")
<div class="row page-header" style="margin-bottom: -15px;">
    <div class="col-lg-6 align-self-center ">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url("admin/index")}}">{{getTranslateByKey("common_home_page")}}</a></li>
            <li class="breadcrumb-item"><a href="{{url("admin/theme")}}">主题列表</a></li>
            <li class="breadcrumb-item active">菜单编辑</li>
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
                                                上级菜单
                                            </label>
                                            <select name="pid" class="form-control m-b">
                                                <option value="">顶级</option>
                                                @foreach($menuList as $menu)
                                                    <option value="{{$menu['id']}}"
                                                            @if($data['pid']==$menu['id']) selected @endif>
                                                        【@if($menu['position']=='top')顶部@else底部@endif】
                                                        {{$menu['name']}}
                                                    </option>
                                                    @foreach($menu['child'] as $child)
                                                        <option value="{{$child['id']}}"
                                                                @if($data['pid']==$child['id']) selected @endif>　　　　
                                                            — {{$child['name']}}</option>
                                                    @endforeach
                                                @endforeach

                                            </select>
                                        </div>


                                        <div class="form-group ">
                                            <label>
                                                位置
                                            </label>

                                            <div class="form-inline">
                                                <div class="radio radio-inline radio-inverse">
                                                    <input id="position" name="position" type="radio" value="top"
                                                           @if($data['position']=='top') checked @endif>
                                                    <label for="position">
                                                        顶部菜单
                                                    </label>
                                                </div>

                                                <div class=" radio radio-inline radio-inverse">
                                                    <input id="position1" name="position" type="radio" value="bottom"
                                                           @if($data['position']=='bottom') checked @endif>
                                                    <label for="position1">
                                                        底部菜单
                                                    </label>
                                                </div>

                                                <div class=" radio radio-inline radio-inverse">
                                                    <input id="position2" name="position" type="radio" value="footer"
                                                           @if($data['position']=='footer') checked @endif>
                                                    <label for="position2">
                                                        页脚菜单
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>
                                                名称
                                            </label>
                                            <input type="text" required name="name" value="{{$data['name']}}"
                                                   class="form-control form-control-rounded">
                                        </div>

                                        <div class="form-group ">
                                            <label>
                                                跳转链接
                                            </label>
                                            <input type="text" required name="url" value="{{$data['url']}}"
                                                   class="form-control form-control-rounded">
                                        </div>
                                        <div class="form-group ">
                                            <label>
                                                icon
                                            </label>
                                            <input type="text" required name="icon" value="{{$data['icon']}}"
                                                   class="form-control form-control-rounded">
                                        </div>

                                        <div class="form-group ">
                                            <label>
                                                排序（降序）
                                            </label>
                                            <input type="text" name="sort" value="{{$data['sort']*1}}"
                                                   class="form-control form-control-rounded">
                                        </div>


                                        <div class="form-group ">
                                            <label>
                                                icon文字
                                            </label>
                                            <input type="text" name="icon_character" value="{{$data['icon_character']}}"
                                                   class="form-control form-control-rounded">
                                        </div>
                                        <div class="form-group ">
                                            <label>
                                                图片
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
                                                状态
                                            </label>

                                            <div class="form-inline">
                                                <div class="radio radio-inline radio-inverse">
                                                    <input id="status" name="status" type="radio" value="1"
                                                           @if($data['status']=='1') checked @endif>
                                                    <label for="status">
                                                        启用
                                                    </label>
                                                </div>

                                                <div class=" radio radio-inline radio-inverse">
                                                    <input id="status1" name="status" type="radio" value="2"
                                                           @if($data['status']=='2') checked @endif>
                                                    <label for="status1">
                                                        禁用
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
                                    <div class="form-group">
                                        <label>
                                            模块菜单
                                        </label>
                                        <select class="form-control m-b" id="moduleMenu">
                                            @foreach($moduleMenu as $module)
                                                @foreach($module['menuList'] as $m)
                                                    <option value="{{$module['identification']}}__{{$m['name']}}__{{$m['url']}}">
                                                        【{{$moduleArray[$module['identification']]}}
                                                        】{{$m['name']}}</option>
                                                @endforeach
                                            @endforeach

                                        </select>
                                    </div>
                                    <button type="button" id="addMenuButton"
                                            class="btn btn-success margin-l-5 mx-sm-3">
                                        添加到菜单
                                    </button>

                                    <div class="form-group mt-5">
                                        <label>
                                            模型菜单
                                        </label>
                                        <select class="form-control m-b" id="modelMenu">
                                            @foreach($modelMenu['menuList'] as $m)
                                                <option value="{{$modelMenu['identification']}}__{{$m['name']}}__{{$m['url']}}">
                                                    【{{$moduleArray[$modelMenu['identification']]}}
                                                    】{{$m['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="button" id="addModelMenuButton"
                                            class="btn btn-success margin-l-5 mx-sm-3">
                                        添加到菜单
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body" style="min-height: 570px;">
                                    <div class="form-group">
                                        <label>
                                            搜索菜单
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
                                            菜单标题
                                        </label>
                                        <input type="text" id="menuTitle"
                                               class="form-control form-control-rounded">
                                    </div>

                                    <button type="button" id="searchMenuButton"
                                            class="btn btn-info margin-l-5 mx-sm-3">
                                        搜索菜单
                                    </button>

                                    <div class="form-group mt-3">
                                        <label>
                                            搜索菜单列表
                                        </label>
                                        <select class="form-control m-b" id="searchModuleMenuList">
                                        </select>
                                    </div>

                                    <button type="button" id="searchAddMenuButton"
                                            class="btn btn-success margin-l-5 mx-sm-3">
                                        添加到菜单
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
        $("#postButton").click(function () {

            popup({
                type: 'load', msg: "正在请求", delay: 800, callBack: function () {

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
            var val = $('#moduleMenu').val();
            if (val) {
                var arr = val.split('__');
                $('input[name=module]').val(arr[0]);
                $('input[name=name]').val(arr[1]);
                $('input[name=url]').val(arr[2]);
            }
        });

        $('#addModelMenuButton').click(function () {
            var val = $('#modelMenu').val();
            if (val) {
                var arr = val.split('__');
                $('input[name=module]').val(arr[0]);
                $('input[name=name]').val(arr[1]);
                $('input[name=url]').val(arr[2]);
            }
        });

        $('#searchMenuButton').click(function () {
            var table = $('#menuModule').val();
            var title = $('#menuTitle').val();
            if (!table) return layer.msg('请选择模块', {icon: 2});
            if (!title) return layer.msg('请输入标题', {icon: 2});
            var arr = table.split('__');
            popup({
                type: 'load', msg: "正在请求", delay: 800, callBack: function () {
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
                                    option +=`<option value="${data.identification}__${val.name}__${val.url}">${val.name}</option>`;
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
            var val = $('#searchModuleMenuList').val();
            if (val) {
                var arr = val.split('__');
                $('input[name=module]').val(arr[0]);
                $('input[name=name]').val(arr[1]);
                $('input[name=url]').val(arr[2]);
            }
        });
    })
</script>
