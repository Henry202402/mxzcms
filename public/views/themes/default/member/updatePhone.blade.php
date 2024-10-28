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
                                                <li class="h-comment-form-list-item">
                                                    <input type="text" class="form-control" placeholder="当前手机号" value="{{$userInfo['phone']}}" disabled>
                                                </li>
                                                <li class="h-comment-form-list-item">
                                                    <input type="number" class="form-control" placeholder="新手机号"
                                                           name="phone" value="">
                                                    <input type="hidden" id="phone_code_type" value="4">
                                                    <input type="hidden" name="phone_key" value="">
                                                </li>
                                                <li class="h-comment-form-list-item display-flex">
                                                    <input type="text" class="form-control" placeholder="手机验证码"
                                                           name="phone_captcha" value="">
                                                    <button type="button" class="h-user-info-update-btn phone_captcha_btn">
                                                        发送
                                                    </button>
                                                </li>

                                                <li class="comment-form-list-item">
                                                    <button type="button" class="btn updateUserPhoneInfo">更新</button>
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
<script src="{{HOME_ASSET}}default/assets/js/loginRegister.js"></script>
</body>
</html>
