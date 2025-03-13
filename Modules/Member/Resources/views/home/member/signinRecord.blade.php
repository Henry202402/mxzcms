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
            @include("member::home.public.topnav")
            <!-- end page title -->
                <!-- start row -->
                <div class="row">
                    <div class="col-md-12 col-xl-12">

                        <div class="card" style="height: 640px;">
                            <div class="card-body">
                                <a href="{{url('member/signIn')}}">
                                    <button class="btn btn-danger">
                                        返回
                                    </button>
                                </a>
                                <!-- Tab panes -->
                                <div class="tab-content p-3 text-muted">
                                    <div class="tab-pane active" id="experience" role="tabpanel">
                                        <div class="table-responsive" style="min-height: 500px;">
                                            <table class="table table-striped">
                                                <thead>
                                                <tr>
                                                    <th scope="col">订单号</th>
                                                    <th scope="col">签到日期</th>
                                                    <th scope="col">签到积分</th>
                                                    <th scope="col">单位</th>
                                                    <th scope="col">备注</th>
                                                    <th scope="col">创建时间</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @forelse($data as $d)
                                                    <tr>
                                                        <td>{{$d['order_num']}}</td>
                                                        <td>{{$d['day']}}</td>
                                                        <td>{{$d['point']*1}}</td>
                                                        <td>积分</td>
                                                        <td>{{$d['remark']}}</td>
                                                        <td>{{$d['created_at']}}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center">暂无数据</td>
                                                    </tr>
                                                @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="mt-3">
                                            @include("member::home.public.pagination",['pageDataArray'=>$data])
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>


                    </div>


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
