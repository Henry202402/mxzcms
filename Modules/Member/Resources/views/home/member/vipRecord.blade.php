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
                            <h4 class="card-title mb-1">VIP 订单记录</h4>
                            <p>查看会员购买、支付方式和支付时间等详细记录。</p>
                        </div>
                        <a href="{{ url('member/myVip') }}" class="btn btn-light waves-effect">返回 VIP 中心</a>
                    </div>

                    <div class="mx-member-table-wrap">
                        <table class="table mx-member-table">
                            <thead>
                            <tr>
                                <th>订单号</th>
                                <th>金额</th>
                                <th>单位</th>
                                <th>时长</th>
                                <th>支付方式</th>
                                <th>支付状态</th>
                                <th>支付时间</th>
                                <th>创建时间</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($data as $d)
                                <tr>
                                    <td>{{ $d['order_num'] }}</td>
                                    <td>{{ $d['price'] * 1 }}</td>
                                    <td>元</td>
                                    <td>{{ $d['number'] * 1 }} {{ \Modules\Member\Models\VipOrder::type()[$d['type']] }}</td>
                                    <td>{{ pay_method()[$d['pay_method']] }}</td>
                                    <td>{{ pay_status()[$d['pay_status']] }}</td>
                                    <td>{{ $d['pay_at'] }}</td>
                                    <td>{{ $d['created_at'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">暂无 VIP 订单数据</td>
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
