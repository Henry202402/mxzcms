<style>
    .navbar-brand > img {
        margin-top: -8px;
        height: 35px;
    }
</style>
<!-- Main sidebar -->
<div class="sidebar sidebar-main">
    <div class="sidebar-content">

        <!-- User menu -->
        {{hook('AdminSidebarUserInfo',['moduleName'=>$moduleName])[0]}}
        <!-- /user menu -->

        <!-- Main navigation -->
        {{hook('AdminSidebarMenu',['moduleName'=>$moduleName,'pageData'=>$pageData])[0]}}
        <!-- /main navigation -->

    </div>
</div>
<!-- /main sidebar -->
