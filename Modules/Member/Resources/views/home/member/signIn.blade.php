@include("member::home.public.head")
<!-- Plugin css -->
<link href="{{asset("assets/member")}}/css/main.min.css" rel="stylesheet" type="text/css">
<link href="{{asset("assets/member")}}/css/main.min1.css" rel="stylesheet" type="text/css">
<link href="{{asset("assets/member")}}/css/main.min2.css" rel="stylesheet" type="text/css">
<link href="{{asset("assets/member")}}/css/main.min3.css" rel="stylesheet" type="text/css">
<style>
    .fc-event {
        border-radius: 2px;
        border: none;
        cursor: move;
        font-size: .8125rem;
        margin: 5px 7px;
        padding: 5px 5px;
        text-align: center;
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

                        <div class="row mb-4">
                            <div class="col-lg-3">
                                <div class="card mb-0  h-100">
                                    <div class="card-body">
                                        @if($all['can_sign_in'])
                                            <button class="btn font-16 btn-primary w-100" id="btn-sign-in">
                                                <i class="mdi mdi-plus-circle-outline"></i>
                                                签到
                                            </button>
                                        @else
                                            <button class="btn font-16 btn-info w-100" disabled>
                                                已签到
                                            </button>
                                        @endif

                                        <div id="external-events" class="m-t-20">
                                            <br>
                                            {!! $all['sign_in_rules'] !!}
                                        </div>

                                    </div>
                                </div>
                            </div> <!-- end col-->

                            <div class="col-lg-9">
                                <div class="card mb-0">
                                    <div class="card-body">
                                        <div id="calendar"></div>
                                    </div>
                                </div>
                            </div> <!-- end col -->
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
<script src="{{moduleHomeResource($moduleName,'home/assets/js/user.js')}}"></script>
<!-- plugin js -->
<script src="{{asset("assets/member")}}/js/moment.min.js"></script>
<script src="{{asset("assets/member")}}/js/jquery-ui.min.js"></script>
<script src="{{asset("assets/member")}}/js/main.min.js"></script>
<script src="{{asset("assets/member")}}/js/main.min1.js"></script>
<script src="{{asset("assets/member")}}/js/main.min2.js"></script>
<script src="{{asset("assets/member")}}/js/main.min3.js"></script>
<script src="{{asset("assets/member")}}/js/main.min4.js"></script>
<script>
    var dateList = [];
    @foreach($data as $d)
    dateList.push({
        title: "签到成功",
        start: '{{$d['day']}}',
        className: "bg-success",
    });
    @endforeach

        !function (v) {
        "use strict";

        function e() {
        }

        e.prototype.init = function () {
            var a = v("#event-modal"), t = v("#modal-title"), n = v("#form-event"), l = null, i = null,
                r = document.getElementsByClassName("needs-validation"),
                events = dateList;

            document.getElementById("external-events");
            var d = document.getElementById("calendar");

            var c = new FullCalendar.Calendar(d, {
                locale: 'zh-cn',
                eventDragging: false, // 禁止拖动事件
                initialDate: '2024-12',
                plugins: ["bootstrap", "interaction", "dayGrid", "timeGrid"],
                editable: false,
                droppable: !0,
                selectable: !0,
                defaultView: "dayGridMonth",
                themeSystem: "bootstrap",
                header: {
                    left: "prev,next today",
                    // left: "today",
                    center: "title",
                    //right: "dayGridMonth,timeGridWeek,timeGridDay,listMonth"
                    right: "record"
                },
                eventClick: function (e) {

                },
                dateClick: function (e) {
                },
                customButtons: {
                    prev: {
                        text: '上月',
                        click: function (e) {
                            window.location = '{!! url('/member/signIn?type=1&prevMonth='.$all['month']) !!}';
                        },
                    },
                    next: {
                        text: '下月',
                        click: function (e) {
                            window.location = '{!! url('/member/signIn?type=2&prevMonth='.$all['month']) !!}';
                        },
                    },
                    today: {
                        text: '本月',
                        click: function (e) {
                            window.location = '{!! url('/member/signIn') !!}';
                        },
                    },
                    record: {
                        text: '签到明细',
                        click: function (e) {
                            window.location = '{!! url('/member/signinRecord') !!}';
                        },
                    }
                },

                events: events
            });

            c.render();
            c.gotoDate('{{$all['month']}}');
        };


        v.CalendarPage = new e, v.CalendarPage.Constructor = e
    }(window.jQuery), function () {
        "use strict";
        window.jQuery.CalendarPage.init();
    }();

    $('#btn-sign-in').click(function () {
        ajaxData({operate_type: 'signIn'}, function (res) {
            layer.closeAll();
            if (res.status == 200) {
                layer.msg(res.msg, {icon: 1, time: 500}, function () {
                    window.location.reload();
                });
            } else {
                layer.msg(res.msg, {icon: 2});
            }
        }, '{{url('/member/signIn')}}');
    });
</script>

</body>

</html>
