@include("member::home.public.head")

<body data-layout="detached" data-topbar="colored">

<!-- <body data-layout="horizontal" data-topbar="dark"> -->

<div class="container-fluid">
    <!-- Begin page -->
    <div id="layout-wrapper">

        @include("member::home.public.header")

        <!-- ========== Left Sidebar Start ========== -->
        @include("member::home.public.leftnav")
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                            <h4 class="page-title mb-0 font-size-18">修改资料</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="{{url("member")}}">会员中心</a></li>
                                    <li class="breadcrumb-item active">个人资料</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">

                                <h4 class="card-title">Textual inputs</h4>
                                <p class="card-title-desc">Here are examples of <code>.form-control</code> applied
                                    to each textual HTML5 <code>&lt;input&gt;</code> <code>type</code>.</p>

                                <div class="mb-3">
                                    <label class="form-label">Required</label>
                                    <input type="text" class="form-control" required="" placeholder="Type something">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Required</label>
                                    <input type="text" class="form-control" required="" placeholder="Type something">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Required</label>
                                    <input type="text" class="form-control" required="" placeholder="Type something">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Required</label>
                                    <input type="text" class="form-control" required="" placeholder="Type something">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Required</label>
                                    <input type="text" class="form-control" required="" placeholder="Type something">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Required</label>
                                    <div class="input-group">
                                        <input type="file" class="form-control" id="inputGroupFile02">
                                        <label class="input-group-text" for="inputGroupFile02">Upload</label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Required</label>
                                    <input type="text" class="form-control" required="" placeholder="Type something">
                                </div>

                                <div class="mb-3 ">
                                    <button type="button" class="btn btn-info waves-effect waves-light col-md-1 col-form-label">确认</button>
                                    <button type="button" class="btn btn-danger waves-effect waves-light col-md-1 col-form-label">返回</button>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->

            </div>
            <!-- End Page-content -->

            @include("member::home.public.footer")
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

</div>
<!-- end container-fluid -->

<!-- Right bar overlay-->
<div class="rightbar-overlay"></div>

@include("member::home.public.js")

</body>

</html>
