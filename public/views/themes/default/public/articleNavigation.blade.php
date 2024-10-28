<div class="article-navigation">
    @if($data['prev_id'])
        <a href="{{url("detail/{$param['model']}/{$data['prev_id']}")}}" class="article-navigation-prev">上一篇</a>
    @else
        <a class="article-navigation-prev">暂无</a>
    @endif
    @if($data['last_id'])
        <a href="{{url("detail/{$param['model']}/{$data['last_id']}")}}" class="article-navigation-next">下一篇</a>
    @else
        <a class="article-navigation-next">暂无</a>
    @endif
</div>