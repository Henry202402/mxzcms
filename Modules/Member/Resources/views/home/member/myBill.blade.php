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

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">

                                <h4 class="card-title">列表</h4>
                                {{--<p class="card-title-desc">This is an</p>--}}

                                <div class="table-rep-plugin">
                                    <div class="table-responsive mb-0" data-pattern="priority-columns"
                                         style="min-height: 500px;">
                                        <table id="tech-companies-1" class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>模块订单号</th>
                                                <th data-priority="1">模块</th>
                                                <th data-priority="2">操作类型</th>
                                                <th data-priority="1">额度</th>
                                                <th data-priority="1">单位</th>
                                                <th data-priority="3">备注</th>
                                                <th data-priority="2">时间</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @forelse($data as $d)
                                                <tr>
                                                    <th>{{$d['order_num']}}</th>
                                                    <th>{{$d['module_name']?:$d['module']}}</th>
                                                    <th>{{$d['amount_type']}}</th>
                                                    <th>
                                                        @if($d['type']==1)
                                                            <label class="text-success">+{{$d['amount']*1}}</label>
                                                        @else
                                                            <label class="text-danger">-{{$d['amount']*1}}</label>
                                                        @endif
                                                    </th>
                                                    <th>{{$d['unit']}}</th>
                                                    <th>{{$d['remark']}}</th>
                                                    <th>{{$d['created_at']}}</th>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center">暂无数据</td>
                                                </tr>
                                            @endforelse


                                            </tbody>
                                        </table>
                                    </div>

                                </div>

                                @include("member::home.public.pagination",['pageDataArray'=>$data])
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
<script src="{{moduleHomeResource($moduleName,'home/assets/js/user.js')}}"></script>
</body>

</html>
