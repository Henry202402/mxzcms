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
                            <h4 class="card-title mb-1">我的会员</h4>
                            <p>展示归属到你名下的会员用户基础信息。</p>
                        </div>
                    </div>

                    <div class="mx-member-table-wrap">
                        <table class="table mx-member-table">
                            <thead>
                            <tr>
                                <th>UID</th>
                                <th>头像</th>
                                <th>用户名</th>
                                <th>昵称</th>
                                <th>邮箱</th>
                                <th>状态</th>
                                <th>注册时间</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($data as $d)
                                <tr>
                                    <th>{{ $d['uid'] }}</th>
                                    <td><img src="{{ $d['avatar'] }}" width="36" style="border-radius: 50%;"></td>
                                    <td>{{ $d['username'] }}</td>
                                    <td>{{ $d['nickname'] }}</td>
                                    <td>{{ $d['email'] ?: '-' }}</td>
                                    <td>{{ $d['status'] == 1 ? '启用' : '禁用' }}</td>
                                    <td>{{ $d['created_at'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">暂无会员数据</td>
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
