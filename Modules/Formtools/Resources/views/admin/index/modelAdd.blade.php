@include(moduleAdminTemplate($moduleName)."public.header")
<style>
    .h-invitation-list {
        cursor: pointer;
    }
</style>
<body>

@include(moduleAdminTemplate($moduleName)."public.nav")

<div class="page-container">

    <div class="page-content">

    @include(moduleAdminTemplate($moduleName)."public.left")

        <div class="content-wrapper">

            <div class="content">
                @include(moduleAdminTemplate($moduleName)."public.crumb",['breadcrumb'=>[$pageData['title'],$pageData['subtitle']]])
                <div class="panel panel-flat">
                    <div class="panel-heading">

                        <form class="form-horizontal" action="{{url("admin/formtools/modelAdd")}}" method="post"
                              id="post_form" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <fieldset class="content-group">
                                <legend class="text-bold">菜单</legend>

                                <div class="form-group">
                                    <label class="col-lg-1 control-label">
                                        一级菜单名称
                                    </label>
                                    <div class="col-lg-11">
                                        <input type="text" name="menuname" class="form-control"
                                               placeholder="菜单名称，左边导航的名称" required>
                                        <span class="help-block">左边导航一级菜单入口名称</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-1 control-label">
                                        模型名称
                                    </label>
                                    <div class="col-lg-11">
                                        <input type="text" name="name" class="form-control"
                                               placeholder="模型名称" required
                                               onkeyup="changeRemark(this)">
                                        <span class="help-block">也作左边导航二级菜单入口名称</span>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="col-lg-1 control-label">
                                        菜单图标
                                    </label>
                                    <div class="col-lg-11">
                                        <input type="text" name="icon" class="form-control"
                                               placeholder="菜单图标" required>
                                    </div>
                                </div>

                                <legend class="text-bold">模型</legend>
                                <div class="form-group">
                                    <label class="col-lg-1 control-label">
                                        模型类型
                                    </label>
                                    <div class="col-lg-11">
                                        <label class="radio-inline">
                                            <input type="radio" class="styled h-radio" value="multi" name="type" checked>
                                            <span class="h-span-val">列表</span>
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" class="styled h-radio" value="single" name="type">
                                            <span class="h-span-val">单页</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-1 control-label">
                                        模型标识
                                    </label>
                                    <div class="col-lg-11">
                                        <input type="text" id="identification" name="identification"
                                               class="form-control"
                                               placeholder="模型标识/表名" required>
                                        <span class="help-block">表名后缀，例如 test_table，会自动生成 union_module_formtools_test_table 数据表</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-1 control-label">
                                        访问标识
                                    </label>
                                    <div class="col-lg-11">
                                        <input type="text" name="access_identification"
                                               class="form-control"
                                               placeholder="访问标识" required>
                                        <span class="help-block">访问标识，前台使用此标识即可访问到该表数据</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-1 control-label">
                                        模型备注
                                    </label>
                                    <div class="col-lg-11">
                                        <input type="text" name="remark" class="form-control"
                                               placeholder="模型备注" required>
                                        <span class="help-block">表备注</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-1 control-label">
                                        追加模型到
                                    </label>
                                    <div class="col-lg-11">
                                        <select id="module" name="module" class="form-control">
                                            <option value="">不关联模块</option>

                                            @foreach($pageData['modules'] as $module)
                                                <option value="{{$module['identification']}}">{{$module['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <legend class="text-bold cursor-pointer" onclick="clickOpen('admin-setting-content')">后台（点击设置）</legend>
                                <div id="admin-setting-content" style="display: none">
                                    <div class="form-group">
                                        <label class="col-lg-1 control-label">
                                            后台表单模板
                                        </label>
                                        <div class="col-lg-11">
                                            <select name="admin_config[form_template]" class="form-control">
                                                <option value="row">并列模板</option>
                                                <option value="solo">独行模板</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>


                                <legend class="text-bold cursor-pointer" onclick="clickOpen('home-setting-content')">前台（点击设置）</legend>
                                <div id="home-setting-content" style="display: none">
                                    <div class="form-group">
                                        <label class="col-lg-1 control-label">
                                            列表模板
                                        </label>
                                        <div class="col-lg-5">
                                            <select name="home_config[list_template]" class="form-control">
                                                @foreach(\Modules\Formtools\Helper\FormFunc::listTemplate() as $key=>$value)
                                                    <option value="{{$key}}"
                                                            @if($key=='list') selected @endif >{{$value}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-5">
                                            <input type="text" value="" name="home_config[custom_list_template]"
                                                   class="form-control"
                                                   placeholder="自定义模板名称">
                                            <span class="help-block">自定义模板名称，例如 template.blade.php，只需要填写 template ，不需要后缀.blade.php</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-1 control-label">
                                            列表分页数量
                                        </label>
                                        <div class="col-lg-11">
                                            <input type="text" value="20" name="home_config[page_num]" class="form-control"
                                                   placeholder="分页数量" required>
                                            <span class="help-block">分页数量，每页显示的条数，0代表全部</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-1 control-label">
                                            列表分页样式
                                        </label>
                                        <div class="col-lg-11">
                                            <select name="home_config[list_page_template]" class="form-control">
                                                <option value="center">分页居中</option>
                                                <option value="left">分页居左</option>
                                                <option value="right">分页居右</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-1 control-label">
                                            详情页模板
                                        </label>
                                        <div class="col-lg-5">
                                            <select name="home_config[detail_template]" class="form-control">
                                                @foreach(\Modules\Formtools\Helper\FormFunc::detailTemplate() as $key=>$value)
                                                    <option value="{{$key}}"
                                                            @if($key=='detail') selected @endif>{{$value}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-5">
                                            <input type="text" name="home_config[custom_detail_template]"
                                                   class="form-control"
                                                   placeholder="自定义详情模板名称">
                                            <span class="help-block">自定义详情模板名称，例如 detail.blade.php，只需要填写 detail ，不需要后缀.blade.php</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-1 control-label">
                                            详情页面标题
                                        </label>
                                        <div class="col-lg-11">
                                            <input type="text" value=""
                                                   name="home_config[detail_page_title]" class="form-control"
                                                   placeholder="详情页面标题">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-1 control-label">
                                            详情页面简介
                                        </label>
                                        <div class="col-lg-11">
                                            <textarea name="home_config[detail_page_describe]" class="form-control" rows="5"
                                                      placeholder="详情页面简介"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-1 control-label">
                                            详情区块背景类型
                                        </label>
                                        <div class="col-lg-11">
                                            <label class="radio-inline">
                                                <input type="radio" class="styled h-radio"  checked
                                                        value="color" name="home_config[detail_page_show_type]">
                                                <span class="h-span-val">纯色</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" class="styled h-radio"  value="img" name="home_config[detail_page_show_type]">
                                                <span class="h-span-val">图片</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-1 control-label">
                                            详情区块背景颜色
                                        </label>
                                        <div class="col-lg-11">
                                            <input type="text" value=""
                                                   name="home_config[detail_page_bg_color]" class="form-control"
                                                   placeholder="详情页面区块背景颜色">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-1 control-label">
                                            详情块背景图
                                        </label>
                                        <div class="col-lg-11">
                                            <div class="media no-margin-top">
                                                <div class="media-body">
                                                    <input type="file" name="detail_page_bg_img" class="file-styled"
                                                           accept="image/*">
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="form-group">
                                        <label class="col-lg-1 control-label">
                                            是否显示在首页
                                        </label>
                                        <div class="col-lg-11">
                                            <label class="radio-inline">
                                                <input type="radio" class="styled h-radio" value="yes" name="show_home_page">
                                                <span class="h-span-val">显示</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" class="styled h-radio" value="no" name="show_home_page" checked>
                                                <span class="h-span-val">不显示</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-1 control-label">
                                            首页显示数量
                                        </label>
                                        <div class="col-lg-11">
                                            <input type="text" value="" name="home_page_num" class="form-control"
                                                   placeholder="首页显示数量，0为全部">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-1 control-label">
                                            显示在前台的顺序【升序排序】
                                        </label>
                                        <div class="col-lg-11">
                                            <input type="text" value="0" name="home_page_sort" class="form-control"
                                                   placeholder="显示在前台的顺序【升序排序】，从小到大排序，默认为0">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-1 control-label">
                                            首页页面标题
                                        </label>
                                        <div class="col-lg-3">
                                            <input type="text" value=""
                                                   name="home_config[home_page_title]" class="form-control"
                                                   placeholder="首页页面标题">
                                        </div>
                                        <label class="col-lg-1 control-label">
                                            首页页面标题大小
                                        </label>
                                        <div class="col-lg-3">
                                            <input type="text" value=""
                                                   name="home_config[home_page_title_size]" class="form-control"
                                                   placeholder="首页页面标题大小">
                                        </div>
                                        <label class="col-lg-1 control-label">
                                            首页页面标题颜色
                                        </label>
                                        <div class="col-lg-3">
                                            <input type="text" value=""
                                                   name="home_config[home_page_title_color]" class="form-control"
                                                   placeholder="首页页面标题颜色">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-1 control-label">
                                            首页页面简介
                                        </label>
                                        <div class="col-lg-11">
                                            <textarea name="home_config[home_page_describe]" class="form-control" rows="5"
                                                      placeholder="首页页面简介"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-1 control-label">
                                            首页页面简介大小
                                        </label>
                                        <div class="col-lg-5">
                                            <input type="text" value=""
                                                   name="home_config[home_page_describe_size]" class="form-control"
                                                   placeholder="首页页面标题大小">
                                            </select>
                                        </div>
                                        <label class="col-lg-1 control-label">
                                            首页页面简介颜色
                                        </label>
                                        <div class="col-lg-5">
                                            <input type="text" value=""
                                                   name="home_config[home_page_describe_color]" class="form-control"
                                                   placeholder="首页页面简介颜色">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-1 control-label">
                                            首页区块背景类型
                                        </label>
                                        <div class="col-lg-11">
                                            <label class="radio-inline">
                                                <input type="radio" class="styled h-radio"
                                                        checked
                                                        value="color" name="home_config[show_home_type]">
                                                <span class="h-span-val">纯色</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" class="styled h-radio"
                                                        value="img" name="home_config[show_home_type]">
                                                <span class="h-span-val">图片</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-1 control-label">
                                            首页区块背景颜色
                                        </label>
                                        <div class="col-lg-11">
                                            <input type="text" value=""
                                                   name="home_config[home_page_bg_color]" class="form-control"
                                                   placeholder="首页区块背景颜色">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-1 control-label">
                                            首页区块背景图
                                        </label>
                                        <div class="col-lg-11">
                                            <div class="media no-margin-top">
                                                <div class="media-body">
                                                    <input type="file" name="home_page_bg_img" class="file-styled"
                                                           accept="image/*">
                                                </div>
                                            </div>
                                            <script>
                                                $(function () {
                                                    // Primary file input
                                                    $(".file-styled").uniform({
                                                        wrapperClass: 'bg-warning',
                                                        fileButtonHtml: '<i class="icon-googleplus5"></i>'
                                                    });
                                                })
                                            </script>
                                        </div>
                                    </div>



                                </div>

                                <legend class="text-bold cursor-pointer" onclick="clickOpen('home-setting-seo')">SEO（点击设置）</legend>
                                <div id="home-setting-seo" style="display: none">

                                    <h6>列表页SEO</h6>
                                    <div class="form-group" >
                                        <label class="col-lg-1 control-label">
                                            标题
                                        </label>
                                        <div class="col-lg-11">
                                            <input type="text" name="home_seo_config[title]" class="form-control"
                                                   placeholder="SEO标题">
                                        </div>
                                    </div>

                                    <div class="form-group" >
                                        <label class="col-lg-1 control-label">
                                            关键词
                                        </label>
                                        <div class="col-lg-11">
                                            <input type="text" name="home_seo_config[keyword]" class="form-control"
                                                   placeholder="SEO关键词">
                                        </div>
                                    </div>

                                    <div class="form-group" >
                                        <label class="col-lg-1 control-label">
                                            描述
                                        </label>
                                        <div class="col-lg-11">
                                            <textarea name="home_seo_config[describe]" id="" cols="30" rows="4" placeholder="SEO描述" class="form-control"></textarea>
                                        </div>
                                    </div>

                                    <h6>详情页SEO</h6>
                                    <div class="form-group" >
                                        <label class="col-lg-1 control-label">
                                            标题
                                        </label>
                                        <div class="col-lg-11">
                                            <input type="text" name="home_seo_detail_config[title]" class="form-control"
                                                   placeholder="SEO标题">
                                        </div>
                                    </div>

                                    <div class="form-group" >
                                        <label class="col-lg-1 control-label">
                                            关键词
                                        </label>
                                        <div class="col-lg-11">
                                            <input type="text" name="home_seo_detail_config[keyword]" class="form-control"
                                                   placeholder="SEO关键词">
                                        </div>
                                    </div>

                                    <div class="form-group" >
                                        <label class="col-lg-1 control-label">
                                            描述
                                        </label>
                                        <div class="col-lg-11">
                                            <textarea name="home_seo_detail_config[describe]" id="" cols="30" rows="4" placeholder="SEO描述" class="form-control"></textarea>
                                        </div>
                                    </div>


                                </div>


                                <legend class="text-bold cursor-pointer" onclick="clickOpen('other-setting-content')">其他（点击设置）</legend>
                                <div id="other-setting-content" style="display: none">
                                    <div class="form-group">
                                        <label class="control-label col-lg-1">数据源</label>
                                        <div class="col-lg-11">

                                            <label class="radio-inline">
                                                <input type="radio" name="other_config[data_source]" class="styled h-radio"
                                                       value="local"
                                                       checked>
                                                <span class="h-span-val">本地</span>
                                            </label>

                                            <label class="radio-inline">
                                                <input type="radio" name="other_config[data_source]" class="styled h-radio"
                                                       value="api"
                                                >
                                                <span class="h-span-val">API</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group data_source_api" style="display: none;">
                                        <label class="col-lg-1 control-label">
                                            API请求列表地址
                                        </label>
                                        <div class="col-lg-11">
                                            <input type="text" name="other_config[data_source_api_url]" class="form-control"
                                                   placeholder="数据源API请求列表地址">
                                        </div>
                                    </div>

                                    <div class="form-group data_source_api" style="display: none;">
                                        <label class="col-lg-1 control-label">
                                            API请求详情地址
                                        </label>
                                        <div class="col-lg-11">
                                            <input type="text" name="other_config[data_source_api_url_detail]" class="form-control"
                                                   placeholder="数据源API请求详情地址">
                                        </div>
                                    </div>

                                    <div class="form-group data_source_api" style="display: none;">
                                        <label class="col-lg-1 control-label">
                                            API字段映射
                                        </label>
                                        <div class="col-lg-11">
                                        <textarea class="form-control" name="other_config[data_source_field_mapping]" rows="10"
                                                  placeholder="API字段映射，多个按回车键"></textarea>
                                            <span class="help-block">
                                            name=>title<br>
                                            create_at=>time<br>
                                            左边为表字段，右边为API对应字段
                                        </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-1 control-label"></label>
                                    <div class="col-lg-11">
                                        <button type="submit" class="btn btn-sm btn-info" id="post_button">
                                            提交
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger"
                                                onclick="window.location='{{moduleAdminJump($moduleName,'index')}}'">
                                            返回
                                        </button>
                                    </div>
                                </div>
                            </fieldset>

                        </form>
                    </div>
                </div>

                @include(moduleAdminTemplate($moduleName)."public.footer")

            </div>

        </div>

    </div>

</div>

@include(moduleAdminTemplate($moduleName)."public.js")

<script>
    $('input[name="other_config[data_source]"]').click(function () {
        dataSourceClick();
    });

    function dataSourceClick() {
        var data_source = $('input[name="other_config[data_source]"]:checked').val();
        if (data_source == 'api') {
            $('.data_source_api').show();
        } else {
            $('.data_source_api').hide();
        }
    }
    function clickOpen(id){
        var content = document.getElementById(id);
        if (content.style.display == 'none') {
            $('#'+id).show();
        } else {
            $('#'+id).hide();
        }
    }

    function changeRemark(obj) {
        $('input[name=remark]').val($(obj).val());
    }
</script>
</body>
</html>
