@include("member::home.public.head")

<body data-layout="detached" data-topbar="colored">
<div class="container-fluid">
    <div id="layout-wrapper">
        @include("member::home.public.header")
        @include("member::home.public.leftnav")

        <div class="main-content">
            <div class="page-content">
                @include("member::home.public.topnav")

                <div class="mx-member-record-card">
                    <div class="mx-member-record-toolbar">
                        <div>
                            <h4 class="card-title mb-1">签到明细</h4>
                            <p>查看每次签到获得的积分和签到时间。</p>
                        </div>
                        <a href="{{ url('member/signIn') }}" class="btn btn-light waves-effect">返回签到页</a>
                    </div>

                    <div class="mx-member-table-wrap">
                        <table class="table mx-member-table">
                            <thead>
                            <tr>
                                <th>订单号</th>
                                <th>签到日期</th>
                                <th>签到积分</th>
                                <th>单位</th>
                                <th>备注</th>
                                <th>创建时间</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($data as $d)
                                <tr>
                                    <td>{{ $d['order_num'] }}</td>
                                    <td>{{ $d['day'] }}</td>
                                    <td>{{ $d['point'] * 1 }}</td>
                                    <td>积分</td>
                                    <td>{{ $d['remark'] }}</td>
                                    <td>{{ $d['created_at'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">暂无签到记录</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    @include("member::home.public.pagination",['pageDataArray'=>$data])
                </div>
            </div>

            @include("member::home.public.footer")
        </div>
    </div>
</div>
<div class="rightbar-overlay"></div>

@include("member::home.public.js")

</body>

</html>
