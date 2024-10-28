<div id="content">
    <div class="container">
        <div class="layout with-right-sidebar js-layout">
            <div class="row">
                <div class="col-md-12">
                    <div class="main-content">
                        <!-- Blog Page -->
                        <div class="blog">
                            @foreach($list->chunk(3) as $li)
                                <div class="row">
                                    @foreach($li as $l)
                                        <div class="col-md-4 col-sm-4">
                                            <!-- Blog Grid -->
                                            <div class="blog-grid">
                                                @if($l['cover'])
                                                    <div class="blog-grid-image">
                                                        <a href="{{url("detail/{$param['model']}/{$l['id']}")}}">
                                                            <span class="blog-grid-image-over"></span>
                                                            <img src="{{GetUrlByPath($l['cover'])}}"
                                                                 class=""
                                                                 alt="article image">
                                                        </a>
                                                    </div>
                                                @endif
                                                <h3 class="blog-grid-title">
                                                    <a href="{{url("detail/{$param['model']}/{$l['id']}")}}">{{$l['title']}}</a>
                                                </h3>
                                                <div style="font-size: 13px;color: #99a3b1;">
                                                    <span class="blog-grid-date">{{$l['created_at']}}</span>
                                                </div>
                                            </div>
                                            <!-- End of Blog Grid -->
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
                        <!-- End of Blog Page -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
