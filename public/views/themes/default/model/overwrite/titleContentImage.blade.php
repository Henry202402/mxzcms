<div id="content">
    <div class="container">
        <div class="layout with-right-sidebar js-layout">
            <div class="row">
                <div class="col-md-12">
                    <div class="main-content">
                        <div class="blog">
                            @foreach($list->chunk(2) as $li)
                                <div class="row">
                                    @foreach($li as $l)
                                        <div class="col-md-12 col-sm-12">
                                            <!-- Blog List -->
                                            <div class="blog-grid">
                                                <div class="row">
                                                    @if($l['cover'])
                                                        <div class="col-md-2 col-sm-3">
                                                            <div class="blog-grid-image">
                                                                <a href="{{url("detail/{$param['model']}/{$l['id']}")}}">
                                                                    <span class="blog-grid-image-over"></span>
                                                                    <img src="{{GetUrlByPath($l['cover'])}}"
                                                                         alt="article image">

                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-10 col-sm-9">
                                                    @else
                                                        <div class="col-md-12 col-sm-12">
                                                    @endif
                                                            <h3 class="blog-grid-title">
                                                                <a href="{{url("detail/{$param['model']}/{$l['id']}")}}">{{$l['title']}}</a>
                                                            </h3>
                                                            <p class="blog-grid-excerpt"
                                                               style="-webkit-line-clamp: 2;overflow: hidden;height: 40px;line-height: 20px;">
                                                                {!! strip_tags($l['content']) !!}
                                                            </p>

                                                            <div class="blog-grid-meta">
                                                                <span class="blog-grid-date">{{$l['created_at']}}</span>
                                                            </div>
                                                        </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>

                        <div class="col-md-12">
                            @if ($model['page_num'] > 0)
                                {{$list->appends($_GET)->links('themes.default.public.pagination',['data'=>['side_num'=>2,'page_position'=>$model['list_page_template']]])}}
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
