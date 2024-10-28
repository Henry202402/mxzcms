@include(moduleAdminTemplate($moduleName)."public.header")
<style>
    input[type=checkbox] {
        width: 18px;
        height: 18px;
    }

    .h-module-title {
        font-size: 20px;
        cursor: pointer;
    }

    .h-one-title {
        position: relative;
        top: -3px;
        font-size: 20px;
        cursor: pointer;
    }

    .h-two-title {
        position: relative;
        top: 2px;
        font-size: 15px;
        cursor: pointer;
    }

    .h-checkbox-two {
        margin-left: 15px;
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


                    <!-- Bordered striped table -->
                    <div class="col-sm-12">
                        <div class="panel panel-default col-sm-12" style="padding-top: 10px;">
                            <form id="add" role="form" method="post"
                                  action="{{url('admin/auth/group/handle')}}">
                                {{csrf_field()}}
                                <dl class="permission-list">
                                    <dd>
                                        @foreach($pageData['allMenus'] as $moduleKey=>$modules)

                                            <div class="" style="text-align: center;">
                                                <label class="i-checks col-lg-12 h-checkbox-one mt-20" style="width: 100%;">
                                                    <hr style="clear: both;">
                                                    <span class="h-module-title">模块：{{$modules['name']}}</span>
                                                </label>
                                            </div>
                                            @foreach($modules['menus'] as $menuKey=>$menus)
                                                <dl class="cl permission-list2">
                                                    <dt>
                                                        <label class="i-checks col-lg-2 h-checkbox-one"
                                                               style="margin-top: 10px;font-weight: bold;">
                                                            <input type="checkbox" name="role[{{$moduleKey}}][]"
                                                                   value="{{$menus['url']?:$menus['title']}}"
                                                                    @if(in_array(($menus['url']?:$menus['title']),($pageData['group']['role_array'][$moduleKey]?:[]))) checked @endif
                                                            >
                                                            <span class="h-one-title">{{$menus['title']}}</span>
                                                        </label>
                                                    </dt>
                                                    <dd class="col-lg-12">
                                                        @foreach($menus['submenu'] as $submenu)
                                                            <label class="checkbox-inline i-checks col-lg-2 h-checkbox-two">
                                                                <input type="checkbox" name="role[{{$moduleKey}}][]"
                                                                       value="{{$submenu['url']}}"
                                                                        @if(in_array($submenu['url'],($pageData['group']['role_array'][$moduleKey]?:[]))) checked @endif
                                                                >
                                                                <span class="h-two-title">{{$submenu['title']}}</span>
                                                            </label>
                                                        @endforeach
                                                    </dd>
                                                </dl>
                                            @endforeach
                                        @endforeach
                                    </dd>
                                </dl>
                                <div class="Button_operation"
                                     style="margin: 80px auto;clear: both;position: relative;top: 52px;left: 10px">
                                    <input type="hidden" name='group_id' value="{{$_GET['group_id']}}">
                                    <input type="hidden" name='submitType' value="assignPermissions">
                                    <button class="btn btn-primary radius" type="submit"><i
                                                class="fa fa-save "></i>
                                        提交
                                    </button>
                                    <button type="button" class="btn btn-danger"
                                            onclick="window.location='{{moduleAdminJump($moduleName,'group/list')}}'">
                                        返回
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                    <!-- /bordered striped table -->


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
        /*按钮选择*/
        $(function () {
            $(".permission-list dt input:checkbox").click(function () {
                $(this).closest("dl").find("dd input:checkbox").prop("checked", $(this).prop("checked"));
            });
            $(".permission-list2 dd input:checkbox").click(function () {
                var l = $(this).parent().parent().find("input:checked").length;
                var l2 = $(this).parents(".permission-list").find(".permission-list2 dd").find("input:checked").length;
                if ($(this).prop("checked")) {
                    $(this).closest("dl").find("dt input:checkbox").prop("checked", true);
                    //$(this).parents(".permission-list").find("dt").first().find("input:checkbox").prop("checked", true);
                } else {
                    if (l == 0) {
                        $(this).closest("dl").find("dt input:checkbox").prop("checked", false);
                    } else if (l2 == 0) {
                        $(this).parents(".permission-list").find("dt").first().find("input:checkbox").prop("checked", false);
                    }
                }

            });
        });
    </script>
</body>
</html>
