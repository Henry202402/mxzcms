<style>
    .navbar-brand > img {
        margin-top: -8px;
        height: 35px;
    }

    .sidebar.sidebar-main {
        position: relative;
        z-index: 30;
    }

    .sidebar.sidebar-main .sidebar-content {
        position: relative;
        z-index: 31;
        pointer-events: auto;
    }

    .sidebar.sidebar-main .sidebar-category.sidebar-category-visible {
        position: relative;
        z-index: 32;
        pointer-events: auto;
    }

    @media (min-width: 769px) {
        .sidebar-fixed .sidebar-content {
            z-index: 1002;
        }

        .sidebar-fixed-expanded .sidebar-fixed.sidebar-main .sidebar-content {
            z-index: 1003;
        }
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
