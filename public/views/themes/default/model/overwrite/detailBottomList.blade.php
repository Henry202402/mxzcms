<div id="content">
    <div class="container">
        <div class="layout with-right-sidebar js-layout">
            <div class="row">
                <div class="col-md-12">
                    <div class="main-content">
                        <!-- Article -->
                        <article class="article">

                            <!-- Article Title -->
                            <h2 class="article-title">
                                {{$data['title']}}
                            </h2>
                            <!-- End of Article Title -->

                            <!-- Article Meta -->
                            <ul class="article-meta">
                                <li>
                                    <span class="article-meta-date article-meta-item">{{$data['created_at']}}</span>
                                </li>
                                {{--<li>
                                    <span class="article-meta-author article-meta-item"><a
                                                href="#">John Doe</a></span>
                                </li>
                                <li>
                                    <span class="article-meta-category article-meta-item"><a href="blog-grid.html">Startups</a></span>
                                </li>
                                <li>
                                    <span class="article-meta-views article-meta-item">966</span>
                                </li>
                                <li>
                                    <span class="article-meta-likes article-meta-item">15</span>
                                </li>
                                <li>
                                    <span class="article-meta-comments article-meta-item"><a href="#"
                                                                                             class="js-scroll-to"
                                                                                             data-target="#article-comments"
                                                                                             data-speed="600">50</a></span>
                                </li>--}}
                            </ul>
                            <!-- End of Article Meta -->

                            <!-- Article Content -->
                            <div class="article-content">
                                {!! $data['content'] !!}
                            </div>
                            <!-- End of Article Content -->

                            <!-- Article Navigation -->
                            @include("themes.default.public.articleNavigation")
                            <!-- End of Article Navigation -->

                            <!-- Article Widget Related Article -->
                            <div class="article-widget">
                                <h3 class="article-widget-title">
                                    相关文章
                                </h3>
                                <ul class="article-related-articles">
                                    @foreach($list as $key=>$l)
                                        <li>
                                            <a href="{{url("detail/{$param['model']}/{$l['id']}")}}">
                                                {{$key+1}}、{{$l['title']}}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <!-- End of Article Widget Related Article -->
                        </article>
                        <!-- End of Article -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
