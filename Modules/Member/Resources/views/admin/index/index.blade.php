@include(moduleAdminTemplate($moduleName)."public.header")
<body>
@include(moduleAdminTemplate($moduleName)."public.nav")
<div class="page-container">
    <div class="page-content">
    @include(moduleAdminTemplate($moduleName)."public.left")
        <div class="content-wrapper">
            <div class="content">
            @include(moduleAdminTemplate($moduleName)."public.page",['breadcrumb'=>[$pageData['title'],$pageData['subtitle']]])
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        这里是内容，左侧菜单请到Config目录下menus.php补充修改
                    </div>
                </div>
                @include(moduleAdminTemplate($moduleName)."public.footer")
            </div>
        </div>
    </div>
</div>
@include(moduleAdminTemplate($moduleName)."public.js")
</body>
</html>
