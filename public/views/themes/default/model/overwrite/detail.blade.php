<div id="content">
    <div class="container">
        <div class="layout with-right-sidebar js-layout">
            <div class="row">
                <div class="col-md-12">
                    <div class="main-content">
                        <!-- Article -->
                        <article class="article">
                            <h2 class="article-title">
                                {{$data['title']?:$data['name']}}
                            </h2>
                            <ul class="article-meta">
                                <li>
                                    <span class="article-meta-date article-meta-item">{{$data['created_at']}}</span>
                                </li>
                            </ul>
                            <div class="article-content" style="min-height: 345px;">
                                {!! $data['content'] !!}
                            </div>
                            @include("themes.default.public.articleNavigation")
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
