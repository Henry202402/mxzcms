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
                                                <th>UID</th>
                                                <th data-priority="1">头像</th>
                                                <th data-priority="3">名称</th>
                                                <th data-priority="1">昵称</th>
                                                <th data-priority="3">邮箱</th>
                                                <th data-priority="3">状态</th>
                                                <th data-priority="6">时间</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @forelse($data as $d)
                                                <tr>
                                                    <th>{{$d['uid']}}</th>
                                                    <th>
                                                        <img src="{{$d['avatar']}}" width="30"
                                                             style="border-radius: 50%;">
                                                    </th>
                                                    <th>{{$d['username']}}</th>
                                                    <th>{{$d['nickname']}}</th>
                                                    <th>{{$d['email']}}</th>
                                                    <th>

                                                        <div class="square-switch">
                                                            <input type="checkbox" id="square-switch-{{$d['uid']}}"
                                                                   switch="bool"
                                                                   @if($d['status']==1) checked @endif >
                                                            <label class="form-label" for="square-switch-{{$d['uid']}}"
                                                                   data-on-label="Yes" data-off-label="No"></label>
                                                        </div>
                                                    </th>
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
