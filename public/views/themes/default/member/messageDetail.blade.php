@include("themes.default.public.head")
@include("themes.default.public.preloader")
<style>

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
                                站内信
                            </h5>
                            <div class="h-message-detail-top">
                                <a href="{{url('member/message')}}" style="padding: 0 5px;"><i
                                            class="fa fa-angle-left f-fz-20"></i></a>
                                {{$data['title']}}
                            </div>
                            <div class="col-md-12" style="margin-top: 10px;">

                                <div class="col-md-1">

                                </div>
                                <div class="col-md-10" style="">
                                    <div class="h-message-title">{{$data['title']}}</div>

                                    <div class="h-message-content">
                                        <div>
                                            {!! $data['content'] !!}
                                        </div>
                                        <div class="h-message-time">{{$data['created_at']}}</div>

                                    </div>
                                </div>
                                <div class="col-md-1">

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
<script src="{{HOME_ASSET}}default/assets/js/common.js"></script>
<script src="{{HOME_ASSET}}default/assets/js/user.js"></script>
</body>
</html>
