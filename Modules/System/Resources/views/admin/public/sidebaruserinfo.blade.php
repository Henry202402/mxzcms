<div class="sidebar-user">
    <div class="category-content">
        <div class="media">
            <a href="#" class="media-left">
                <img class="img-circle img-sm" alt="" src="
                        @if($userInfo['avatar'])
                {{GetUrlByPath($userInfo['avatar'])}}
                @else
                {{asset("assets/module")}}/images/placeholder.jpg
                        @endif">
            </a>
            <div class="media-body">
                        <span class="media-heading text-semibold">
                            {{$userInfo['username']}} <br />
                            @if($userInfo['type']=='admin')
                                超级管理员
                            @else
                                用户
                            @endif
                        </span>

            </div>

        </div>
    </div>
</div>
