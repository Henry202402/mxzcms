@include("themes.default.public.head")
@include("themes.default.public.preloader")

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
                                会员中心
                            </h5>

                            <div class="note js-note green">

                                <h4 class="note-title">
                                    温馨提示
                                </h4>

                                <p class="note-description">
                                    欢迎您，{{session("home_info")['username']}}，
{{--                                    你上次登录IP：1111.1111.111.111 登录时间：2024.21.24 12:12:58 不是我登录?，--}}
                                    <a href="{{url("member/password")}}">修改密码</a>
                                </p>

                            </div>

                            <div class="category-content">

                                <div class="">
                                    <h5 class="category-title">
                                        模块入口
                                    </h5>
                                    <section class="panel-content">
{{--                                        <p class="helper mb30">--}}
{{--                                            Specifically, we support the latest versions of the following browsers and platforms. On Windows, we support Internet Explorer 9+.--}}
{{--                                            More specific support information is provided below.--}}
{{--                                        </p>--}}
                                        <div class="row ">
                                            @foreach(hook("GetMemberEntry",[]) as $val)
                                                @if($val)
                                                    <a href="{{$val['url']}}" target="_blank">
                                                        <div class="col-md-2" style="margin-top: 20px;">
                                                            <div class="box helper center">
                                                                @if($val['icontype']=="imgage")
                                                                    <img src="{{$val['icon']}}" width="100" height="100" alt="">
                                                                @else
                                                                    <i class="{{$val['icon']}} box-icon-large"></i>
                                                                @endif

                                                                <h5 class="">{{$val['name']}}</h5>
                                                            </div>
                                                        </div>
                                                    </a>
                                                @else
                                                    <div class="col-md-12">
                                                        <div class="note js-note ">
                                                            <h4 class="note-title">
                                                                温馨提示
                                                            </h4>
                                                            <p class="note-description">
                                                                暂无其他模块入口
                                                            </p>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </section>
                                </div>
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

</body>
</html>
