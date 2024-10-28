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
                            <div class="text-align-right">
                                <button type="button" class="readUserMessage">已读</button>
                                <button type="button" class="readAllUserMessage">全部已读</button>
                                <button type="button" class="deleteUserMessage">删除</button>
                            </div>
                            <div class="category-content">
                                <form id="myForm" class="comment-form" method="post">
                                    {{csrf_field()}}
                                    <div class="row">
                                        <div class="col-md-12">
                                            <ul class="comment-form-list h-message-list-ul">
                                                <li class="h-message-list-li">
                                                    <div class="h-message-list-li-div">
                                                        <div class="h-w60">
                                                            <span>
                                                                <input type="checkbox"
                                                                       class="select-all-checkbox h-message-list-li-checkbox"
                                                                       value="1">
                                                            </span>
                                                            <span style="margin-left: 5px;">消息内容</span>
                                                        </div>
                                                        <div class="h-w15">状态</div>
                                                        <div class="h-w25">时间</div>
                                                    </div>
                                                </li>

                                                <div style="min-height: 480px;">
                                                    @foreach($data as $list)
                                                        <li class="h-message-list-li">
                                                            <div class="h-message-list-li-div">
                                                                <div class="h-w60">
                                                                <span><input type="checkbox"
                                                                             class="select-checkbox h-message-list-li-checkbox"
                                                                             name="ids[]"
                                                                             value="{{$list['id']}}"></span>
                                                                    <span style="margin-left: 5px;">
                                                                    <a href="{{url('member/message/detail?id='.$list['id'])}}"
                                                                       style="text-decoration: none;@if($list['status']==1)color: #676b6e;@endif">
                                                                        @if($list['status']==1)
                                                                            <i class="one-page-meta-list-icon fa fa-folder fa-folder-open"></i>
                                                                        @else
                                                                            <i class="one-page-meta-list-icon fa fa-folder fa-folder"></i>
                                                                        @endif
                                                                        {{$list['title']}}
                                                                    </a>
                                                                </span>
                                                                </div>
                                                                <div class="h-w15">
                                                                    @if($list['status']==1)
                                                                        <label class="text-success">已读</label>
                                                                    @else
                                                                        <label class="text-danger">未读</label>
                                                                    @endif
                                                                </div>
                                                                <div class="h-w25"
                                                                     style="font-size: 0.8rem">{{$list['created_at']}}</div>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </div>
                                                <li class="paginationTop" style="display: flex;">
                                                    {{$data->appends($_GET)->links('formtools::admin.public.pagination')}}
                                                    <nav style="margin-left: 10px;">

                                                        <button type="button"
                                                                onclick="window.location='{{url('member/message')}}'"
                                                                class="goIndexBtn">首页
                                                        </button>

                                                        <form action="{{url('member/message')}}" method="GET">
                                                            <input type="text" class="h-go-input-btn" name="page"
                                                                   value="{{$param['page']?:1}}">
                                                            <button type="submit" class="goBtn">GO</button>
                                                        </form>
                                                    </nav>
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
