<div id="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12 dataList">
                @foreach($list as $l)
                    <!-- Note -->
                    <div class="note js-note {{['','green','blue','blue-light','purple','red'][rand(0,5)]}}">
                        <a href="{{url("detail/{$param['model']}/{$l['id']}")}}">
                            <h4 class="note-title">
                                {{$l['title']}}
                            </h4>
                        </a>
                        <div class="note-description"
                             style="-webkit-line-clamp: 2;overflow: hidden;height: 40px;line-height: 20px;">
                            {!! strip_tags($l['content']) !!}
                        </div>

                        <div style="font-size: 13px;color: #99a3b1;margin-top: 5px;">
                            <span class="blog-grid-date">{{$l['created_at']}}</span>
                        </div>
                    </div>
                    <!-- End of Note -->
                @endforeach
            </div>
            <div class="col-md-12">
                @if ($model['page_num'] > 0 && $data_source=="local")
                    {{$list->appends($_GET)->links('themes.default.public.pagination',['data'=>['side_num'=>2,'page_position'=>$model['list_page_template']]])}}
                @endif
            </div>
        </div>
    </div>
</div>
