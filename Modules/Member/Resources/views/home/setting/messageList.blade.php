@include("member::home.public.head")
<style>
    .h-accordion-header {
        position: sticky;
    }

    .system-message-title {
        width: 90%;
    }

    .system-message-time {
        font-size: 12px;
        width: 80px;
        display: block;
        text-align: center;
        margin-left: auto;
    }

    .h-new-message {
        color: #000;
        font-weight: bold;
    }

    .text-align-right {
        margin: 0 0 10px 0;
    }

    .h-input-checkbox {
        margin: 0 5px 0 0;
        width: 16px;
        height: 16px;
        cursor: pointer;
    }
</style>
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
                        <div class="card" style="height: 600px;min-width: 327px;">
                            <div class="card-body" style="height: 500px;">
                                <div class="text-align-right">
                                    {{--<button type="button" class="btn btn-info btn-sm waves-effect waves-light readUserMessage">已读</button>--}}
                                    <button type="button"
                                            class="btn btn-primary btn-sm waves-effect waves-light readAllUserMessage">
                                        全部已读
                                    </button>
                                    <button type="button"
                                            class="btn btn-danger btn-sm waves-effect waves-light deleteUserMessage">删除
                                    </button>
                                </div>
                                {{--<h4 class="card-title">列表</h4>--}}
                                {{--<p class="card-title-desc"></p>--}}

                                <div class="accordion" id="accordionExample">
                                    @forelse($data as $list)

                                        <div class="accordion-item">
                                            <h2 class="accordion-header h-accordion-header" id="heading{{$list['id']}}"
                                                data-id="{{$list['id']}}">
                                                <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapse{{$list['id']}}"
                                                        aria-expanded="true" aria-controls="collapse{{$list['id']}}">
                                                    <span><input type="checkbox" class="h-input-checkbox" name="ids[]" value="{{$list['id']}}"></span>
                                                    <span class="system-message-title @if($list['status']==0) h-new-message @endif ">{{$list['title']}}</span>
                                                    <span class="system-message-time">{{$list['created_at']}}</span>
                                                </button>
                                            </h2>
                                            <div id="collapse{{$list['id']}}" class="accordion-collapse collapse"
                                                 aria-labelledby="heading{{$list['id']}}"
                                                 data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    {!! $list['content'] !!}
                                                </div>
                                            </div>
                                        </div>

                                    @empty
                                        <div class="center">
                                            暂无数据
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                            <div class="">
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
