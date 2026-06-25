@include("member::home.public.head")
<body data-layout="detached" data-topbar="colored">
<div class="container-fluid">
    <div id="layout-wrapper">
        @include("member::home.public.header")
        @include("member::home.public.leftnav")

        <div class="main-content">
            <div class="page-content">
                @include("member::home.public.topnav")

                <div class="mx-member-message-card">
                    <div class="mx-member-message-toolbar">
                        <div>
                            <h4 class="card-title mb-1">消息中心</h4>
                            <p>系统通知、业务提醒和站内消息都会集中展示在这里。</p>
                        </div>
                        <div class="mx-member-safe-actions mt-0">
                            <button type="button" class="btn btn-primary btn-sm waves-effect waves-light readAllUserMessage">全部已读</button>
                            <button type="button" class="btn btn-danger btn-sm waves-effect waves-light deleteUserMessage">删除选中</button>
                        </div>
                    </div>

                    <div class="mx-member-message-list">
                        @forelse($data as $list)
                            <div class="mx-member-message-item @if($list['status']==0) is-unread @endif">
                                <div class="mx-member-message-head">
                                    <input type="checkbox" class="mx-member-message-check" name="ids[]" value="{{ $list['id'] }}">
                                    <div class="mx-member-message-meta">
                                        <span class="mx-member-message-title">{{ $list['title'] }}</span>
                                        <span class="mx-member-message-time">{{ $list['created_at'] }}</span>
                                    </div>
                                    <button class="btn btn-light btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $list['id'] }}" aria-expanded="false" aria-controls="collapse{{ $list['id'] }}">
                                        查看
                                    </button>
                                </div>
                                <div id="collapse{{ $list['id'] }}" class="collapse">
                                    <div class="mx-member-message-body">
                                        {!! $list['content'] !!}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="mx-member-empty">暂无站内信消息</div>
                        @endforelse
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
