@include(moduleAdminTemplate($moduleName)."public.header")
<style>
    .h-invitation-list {
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
            <!-- Content area -->
            <div class="content">
                @include(moduleAdminTemplate($moduleName)."public.crumb",['breadcrumb'=>[$pageData['title'],$pageData['subtitle']]])
                @include(moduleAdminTemplate($moduleName)."public.fieldAddRemark")
                <div class="panel panel-flat">

                    <div class="panel-heading">
                        <form class="form-horizontal" action="{{url("admin/formtools/fieldAdd?id=".$pageData['id'])}}"
                              method="post" id="post_form" multiple>
                            {{csrf_field()}}
                            <fieldset class="content-group">
                                <legend class="text-bold">字段添加</legend>
                                <div class="form-group">
                                    <label class="col-lg-1 control-label">
                                        字段名称
                                    </label>
                                    <div class="col-lg-11">
                                        <input type="text" id="name" name="name" class="form-control"
                                               placeholder="字段名称，显示在数据列表上" required
                                               onkeyup="changeRemark(this)">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-1 control-label">
                                        字段标识
                                    </label>
                                    <div class="col-lg-11">
                                        <input type="text" id="identification" name="identification"
                                               class="form-control"
                                               placeholder="字段标识" required>
                                        <span class="help-block">字段名称</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-1 control-label">
                                        字段备注
                                    </label>
                                    <div class="col-lg-11">
                                        <input type="text" id="remark" name="remark" class="form-control"
                                               placeholder="字段备注，列在数据表的备注" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-1 control-label">
                                        表单类型
                                    </label>
                                    <div class="col-lg-11">
                                        <select id="formtype" name="formtype" class="form-control">
                                            @foreach(\Modules\Formtools\Helper\FormFunc::formtype() as $key=>$type)
                                                <option value="{{$key}}">{{$type}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="addFieldData" style="display: none">
                                    <div class="form-group">
                                        <label class="col-lg-1 control-label">
                                            <a class="label bg-info pull-right addFieldDataBtn">
                                                新增
                                            </a>
                                        </label>
                                        <div class="col-lg-11 addFieldDataList" style="margin-left: -20px;"></div>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-1 control-label">
                                        字段类型
                                    </label>
                                    <div class="col-lg-11">
                                        <select id="fieldtype" name="fieldtype" class="form-control">
                                            @foreach(\Modules\Formtools\Helper\FormFunc::fieldtype() as $key=>$field)
                                                <option value="{{$key}}">{{$field}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="col-lg-1 control-label">
                                        最大长度
                                    </label>
                                    <div class="col-lg-11">
                                        <input type="text" id="maxlength" name="maxlength" class="form-control"
                                               placeholder="最大长度，0表示没有限制" value="11" required>
                                    </div>
                                </div>

                                <legend class="text-bold cursor-pointer" onclick="clickOpen('more-setting-content')">
                                    更多设置（点击设置）
                                </legend>

                                <div id="more-setting-content" style="display:none;">
                                    <div class="form-group">
                                        <label class="control-label col-lg-1">是否必填</label>
                                        <div class="col-lg-11">
                                            <label class="radio-inline">
                                                <input type="radio" name="required" class="styled h-radio"
                                                       value="required"
                                                       checked>
                                                <span class="h-span-val">必填</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="required" class="styled h-radio" value=""
                                                >
                                                <span class="h-span-val">非必填</span>
                                            </label>

                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-lg-1">是否设索引</label>
                                        <div class="col-lg-11">
                                            @foreach(\Modules\Formtools\Helper\FormFunc::isindex() as $key=>$index)
                                                <label class="radio-inline">
                                                    <input type="radio" name="isindex" class="styled h-radio"
                                                           value="{{$key}}"
                                                           @if($key=='NOINDEX')   checked @endif >
                                                    <span class="h-span-val">{{$index}}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-1 control-label">
                                            字段位置
                                        </label>
                                        <div class="col-lg-11">
                                            <select id="rule" name="after_field" class="form-control">
                                                @foreach($pageData['currentModelFields'] as $currentModelFields)
                                                    <option value="{{$currentModelFields['identification']}}">{{$currentModelFields['identification']}}
                                                        之后
                                                    </option>
                                                @endforeach
                                                <option value="id">id之后</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-1 control-label">
                                            字段规则
                                        </label>
                                        <div class="col-lg-11">
                                            <select id="rule" name="rule" class="form-control">
                                                @foreach(\Modules\Formtools\Helper\FormFunc::rule() as $key=>$rule)
                                                    <option value="{{$key}}">{{$rule}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-1 control-label">
                                            正则表达式
                                        </label>
                                        <div class="col-lg-11">
                                            <input type="text" id="regex" name="regex" class="form-control"
                                                   placeholder="正则表达式">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-1 control-label">
                                            关联模型
                                        </label>
                                        <div class="col-lg-11">
                                            <select id="foreign" name="foreign" class="form-control">
                                                <option value="">非外键</option>
                                                @foreach($pageData['models'] as $v)
                                                    <option value="{{$v->identification}}-{{$v->id}}">{{$v->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group hide" id="foreign_key">
                                        <label class="control-label col-lg-1">关联字段</label>
                                        <div class="col-lg-11 foreign_key_list"></div>
                                    </div>
                                </div>

                                <legend class="text-bold cursor-pointer" onclick="clickOpen('admin-setting-content')">
                                    后台（点击设置）
                                </legend>
                                <div id="admin-setting-content" style="display:none;">
                                    <div class="form-group">
                                        <label class="control-label col-lg-1">后台数据列表</label>
                                        <div class="col-lg-11">
                                            <label class="radio-inline">
                                                <input type="radio" name="is_show_list" class="styled h-radio" value="1"
                                                       checked>
                                                <span class="h-span-val">显示</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="is_show_list" class="styled h-radio" value="2"
                                                >
                                                <span class="h-span-val">不显示</span>
                                            </label>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-1">后台列表搜索</label>
                                        <div class="col-lg-11">
                                            <label class="radio-inline">
                                                <input type="radio" name="is_show_admin_list_search"
                                                       class="styled h-radio"
                                                       value="1"
                                                >
                                                <span class="h-span-val">显示</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="is_show_admin_list_search"
                                                       class="styled h-radio"
                                                       value="2"
                                                       checked>
                                                <span class="h-span-val">不显示</span>
                                            </label>

                                        </div>
                                    </div>
                                </div>

                                <legend class="text-bold cursor-pointer" onclick="clickOpen('home-setting-content')">
                                    前台（点击设置）
                                </legend>
                                <div id="home-setting-content" style="display:none;">
                                    <div class="form-group">
                                        <label class="control-label col-lg-1">前台表单</label>
                                        <div class="col-lg-11">
                                            <label class="radio-inline">
                                                <input type="radio" name="is_show_home_form" class="styled h-radio"
                                                       value="1"
                                                       checked>
                                                <span class="h-span-val">显示</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="is_show_home_form" class="styled h-radio"
                                                       value="2"
                                                >
                                                <span class="h-span-val">不显示</span>
                                            </label>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-1">前台列表搜索</label>
                                        <div class="col-lg-11">
                                            <label class="radio-inline">
                                                <input type="radio" name="is_show_home_list_search"
                                                       class="styled h-radio"
                                                       value="1"
                                                >
                                                <span class="h-span-val">显示</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="is_show_home_list_search"
                                                       class="styled h-radio"
                                                       value="2"
                                                       checked>
                                                <span class="h-span-val">不显示</span>
                                            </label>

                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-1 control-label"></label>
                                    <div class="col-lg-11">
                                        <button type="submit" class="btn btn-sm btn-info" id="post_button">
                                            提交
                                        </button>
                                        <a href="{{url("admin/formtools/fieldList?id=".$pageData['id'])}}" type="button"
                                           class="btn btn-sm btn-danger">
                                            返回
                                        </a>
                                    </div>
                                </div>
                            </fieldset>

                        </form>
                    </div>
                </div>


                @include(moduleAdminTemplate($moduleName)."public.footer")


            </div>
            <!-- /content area -->

        </div>
        <!-- /main content -->

    </div>
    <!-- /page content -->

</div>
<!-- /page container -->

<!-- 						Content End		 						-->
<!-- ============================================================== -->
@include(moduleAdminTemplate($moduleName)."public.js")
{{--<script type="text/javascript" src="{{asset("assets/module")}}/js/pages/dashboard.js"></script>--}}
<script>
    var fieldsArr_str = "{{$pageData['modelsFields']}}".replaceAll('&quot;', '"').replaceAll('&#039;', "'").replaceAll('&lt;', '<').replaceAll('&gt;', '>');
    var fieldsArr = JSON.parse(fieldsArr_str);
    $(function () {
        $('select[name="foreign"]').change(function () {
            if (fieldsArr[$(this).val()]) {
                $('#foreign_key').removeClass('hide');
                $('.foreign_key_list').html('');
                var html = ``;
                fieldsArr[$(this).val()].forEach(function (item) {
                    console.log(item);
                    html += '<label class="radio-inline">' +
                        '<input type="radio" name="foreign_key" class="styled h-radio" value="' + item.identification + '">' +
                        '<span class="h-span-val">' + (item.name != undefined ? item.name : item.remark) + '</span>' +
                        '</label>';
                });
                $('.foreign_key_list').append(html);
            } else {
                $('#foreign_key').addClass('hide');
                $('.foreign_key_list').html('');
            }

        });
    })

    function clickOpen(id) {
        var content = document.getElementById(id);
        if (content.style.display == 'none') {
            $('#' + id).show();
        } else {
            $('#' + id).hide();
        }
    }

    function changeRemark(obj) {
        $('input[name=remark]').val($(obj).val());
    }

    var fieldKey = 0;
    $('select[name=formtype]').change(function () {
        var fieldType = ['radio', 'checkbox', 'select'];
        if (fieldType.indexOf($(this).val()) > -1) {
            $('.addFieldData').show();
        } else {
            $('.addFieldData').hide();
            fieldKey = 0;
            $('.addFieldDataList').html('');
        }
    });

    function addFieldDataBtnTemplate(value = '', name = '') {
        var deleteBtn = `<div class="col-lg-3"><i class="icon-bin" style="margin-top: 12px;" onclick="$(this).parent('div').parent('div').remove()"></i></div>`;
        var template = `<div class="col-lg-12">
                        <div class="col-lg-3">
                            <input type="text" name="datas[${fieldKey}][value]" class="form-control" placeholder="选项下标，例如：name" value="${value}">
                        </div>
                        <div class="col-lg-3">
                            <input type="text" name="datas[${fieldKey}][name]" class="form-control" placeholder="选项值，例如：小明" value="${name}">
                        </div>
                        ${deleteBtn}
                    </div>`;
        fieldKey++;
        $('.addFieldDataList').append(template);
    }

    $('.addFieldDataBtn').click(function () {
        addFieldDataBtnTemplate();
    });

</script>
</body>
</html>
