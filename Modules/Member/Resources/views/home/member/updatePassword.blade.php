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
                                <div class="mx-member-side-panel">
                                    <h5>密码建议</h5>
                                    <ul class="mx-member-tips">
                                        <li>密码建议至少 8 位，并同时包含字母和数字。</li>
                                        <li>不要使用生日、手机号、姓名拼音等容易猜中的信息。</li>
                                        <li>修改后请在常用设备重新确认登录状态。</li>
                                    </ul>
                                    <div class="mx-member-safe-actions">
                                        <a href="{{ url('member/mine') }}" class="btn btn-outline-secondary waves-effect">返回资料页</a>
                                        <a href="{{ url('member') }}" class="btn btn-outline-primary waves-effect">回到首页</a>
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
                                        <h4 class="card-title mb-1">更新登录密码</h4>
                                        <p class="card-title-desc mb-0">修改完成后，新密码会立即生效。</p>
                                    </div>
                                </div>

                                <form id="myForm" class="mx-member-form" method="post">
                                    {{csrf_field()}}

                                    <div class="mb-3">
                                        <label class="form-label">原密码</label>
                                        <input type="password" class="form-control" name="old_password" placeholder="请输入当前密码">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">新密码</label>
                                        <input type="password" class="form-control" name="new_password" placeholder="请输入新的登录密码">
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label">确认密码</label>
                                        <input type="password" class="form-control" name="confirm_password" placeholder="请再次输入新密码">
                                    </div>

                                    <div class="mx-member-form-actions">
                                        <button type="button" class="btn btn-info waves-effect waves-light updateUserPassword">保存新密码</button>
                                        <a href="{{ url('member') }}" class="btn btn-light waves-effect">取消</a>
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
<script src="{{moduleHomeResource($moduleName,'home/assets/js/user.js')}}"></script>
</body>

</html>
