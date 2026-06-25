@include("member::home.public.head")

<body data-layout="detached" data-topbar="colored">
<div class="container-fluid">
    <div id="layout-wrapper">
        @include("member::home.public.header")
        @include("member::home.public.leftnav")

        <div class="main-content">
            <div class="page-content">
                @include("member::home.public.topnav")

                <div class="row">
                    <div class="col-xl-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="mx-member-profile-card">
                                    <img src="{{ GetUrlByPath($user['avatar']) }}" alt="avatar" class="mx-member-profile-card__avatar">
                                    <h4>{{ $user['nickname'] ?: $user['username'] }}</h4>
                                    <p>{{ $user['email'] ?: ($user['phone'] ?: '建议补充常用联系方式') }}</p>
                                    <div class="mx-member-profile-card__meta">
                                        <div>
                                            <span>资料完整度</span>
                                            <strong>{{ $overview['profile_completion'] }}%</strong>
                                        </div>
                                        <div>
                                            <span>实名认证</span>
                                            <strong>{{ $overview['auth_status'] }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="mx-member-side-panel">
                                    <h5>资料建议</h5>
                                    <ul class="mx-member-tips">
                                        <li>昵称尽量使用便于识别的公开名称。</li>
                                        <li>邮箱建议填写常用邮箱，便于通知与找回密码。</li>
                                        <li>签名建议简短明确，展示个人特色即可。</li>
                                    </ul>
                                    <div class="mx-member-safe-actions">
                                        <a href="{{ url('member/password') }}" class="btn btn-outline-primary waves-effect">修改密码</a>
                                        <a href="{{ url('member/myRealName') }}" class="btn btn-outline-secondary waves-effect">查看实名</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="mx-member-section-head">
                                    <div>
                                        <h4 class="card-title mb-1">编辑个人资料</h4>
                                        <p class="card-title-desc mb-0">修改后的资料会立即同步到会员中心展示。</p>
                                    </div>
                                </div>

                                <form method="post" autocomplete="off" id="myForm" class="mx-member-form">
                                    {{csrf_field()}}

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">用户名</label>
                                                <input type="text" class="form-control" value="{{ $user['username'] }}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">昵称</label>
                                                <input type="text" class="form-control" name="nickname" value="{{ $user['nickname'] }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">邮箱</label>
                                                <input type="text" class="form-control" name="email" value="{{ $user['email'] }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">手机号码</label>
                                                <input type="text" class="form-control" value="{{ $user['phone'] }}" disabled>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">个性签名</label>
                                        <input type="text" class="form-control" name="signature" value="{{ $user['signature'] }}" placeholder="介绍一下你自己，最多一句话即可">
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label">头像</label>
                                        <div class="mx-member-avatar-upload">
                                            <img src="{{ GetUrlByPath($user['avatar']) }}" alt="avatar preview">
                                            <div class="input-group">
                                                <input type="file" class="form-control" id="inputGroupFile02" name="avatar">
                                                <label class="input-group-text" for="inputGroupFile02">上传头像</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mx-member-form-actions">
                                        <button type="button" class="btn btn-info waves-effect waves-light h-sub">保存资料</button>
                                        <a href="{{ url('member') }}" class="btn btn-light waves-effect">返回首页</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include("member::home.public.footer")
        </div>
    </div>
</div>
<div class="rightbar-overlay"></div>

@include("member::home.public.js")
<script>
    $('.h-sub').click(function () {
        ajaxForm('myForm', function (data) {
            layer.closeAll();
            if (data.status == 200) {
                layer.msg(data.msg, {icon: 1, time: 500}, function () {
                    window.location.reload();
                })
            } else {
                layer.msg(data.msg, {icon: 2})
            }
        });
    });
</script>
</body>

</html>
