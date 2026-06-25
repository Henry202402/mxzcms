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
                            <h4 class="card-title mb-1">账单流水</h4>
                            <p>按时间查看资金与积分的每一笔变化记录。</p>
                        </div>
                    </div>

                    <div class="mx-member-table-wrap">
                        <table class="table mx-member-table">
                            <thead>
                            <tr>
                                <th>模块订单号</th>
                                <th>模块</th>
                                <th>操作类型</th>
                                <th>额度</th>
                                <th>单位</th>
                                <th>备注</th>
                                <th>时间</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($data as $d)
                                <tr>
                                    <th>{{ $d['order_num'] }}</th>
                                    <td>{{ $d['module_name'] ?: $d['module'] }}</td>
                                    <td>{{ $d['amount_type'] }}</td>
                                    <td>
                                        @if($d['type']==1)
                                            <span class="is-income">+{{ $d['amount'] * 1 }}</span>
                                        @else
                                            <span class="is-expense">-{{ $d['amount'] * 1 }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $d['unit'] }}</td>
                                    <td>{{ $d['remark'] }}</td>
                                    <td>{{ $d['created_at'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">暂无账单数据</td>
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
<script src="{{moduleHomeResource($moduleName,'home/assets/js/user.js')}}"></script>
</body>

</html>
