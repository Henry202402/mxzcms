@include("themes.default.public.head")
@include("themes.default.public.preloader")
<style>
    .h-comment-form-list-item {
        margin-bottom: 20px;
    }

    .h-comment-form-list-item input {
        height: 45px;
    }
</style>
<div class="page js-page ">

    @include("themes.default.member.nav")

    <div class="one-page-content">
        <div id="content">
            <div class="container-fluid container-spaced">
                <div class="row">
                    <div class="col-md-12">
                        <div class="js-prepend-mobile-menu">
                            <!-- The mobile menu will be prepended here -->
                        </div>

                        <!-- Category Info -->
                        <div class="category-info helper pt0">

                            <h5 class="category-title">
                                个人资料
                            </h5>

                            <div class="category-content">
                                <form id="myForm" class="comment-form" method="post">
                                    {{csrf_field()}}
                                    <div class="row">
                                        <div class="col-md-12">
                                            <ul class="comment-form-list">
                                                <li class="comment-form-list-item">
                                                    <input type="text" class="form-control" placeholder="名称"
                                                           name="username" value="{{$userInfo['username']}}">
                                                </li>
                                                <li class="h-comment-form-list-item display-flex">
                                                    <input type="text" class="form-control" placeholder="昵称"
                                                           name="nickname" value="{{$userInfo['nickname']}}">
                                                    <button type="button" class="h-user-info-update-btn">
                                                        <a href="{{url('member/password')}}">修改密码</a>
                                                    </button>

                                                </li>
                                                <li class="h-comment-form-list-item display-flex">
                                                    <input type="text" class="form-control" placeholder="邮箱"
                                                           value="{{$userInfo['email']}}" disabled>
                                                    <button type="button" class="h-user-info-update-btn">
                                                        <a href="{{url('member/email')}}">修改邮箱</a>
                                                    </button>
                                                </li>
                                                <li class="h-comment-form-list-item display-flex">
                                                    <input type="text" class="form-control" placeholder="手机"
                                                           value="{{$userInfo['phone']}}" disabled>
                                                    <button type="button" class="h-user-info-update-btn">
                                                        <a href="{{url('member/phone')}}">修改手机号</a>
                                                    </button>
                                                </li>

                                                {{--<li class="comment-form-list-item">
                                                    <textarea name="" id="" cols="30" rows="10" class="form-control" placeholder="Comment"></textarea>
                                                </li>--}}
                                                <li class="comment-form-list-item">
                                                    <button type="button" class="btn updateUserInfo">更新</button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                        <!-- End of Caregory Info -->


                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End of One Page Content -->

</div>

@include("themes.default.public.js")
<script src="{{HOME_ASSET}}default/assets/js/common.js"></script>
<script src="{{HOME_ASSET}}default/assets/js/user.js"></script>
</body>
</html>
