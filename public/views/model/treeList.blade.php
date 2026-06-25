@php
    $items = collect($data instanceof \Illuminate\Pagination\AbstractPaginator ? $data->items() : $data)
        ->map(fn ($item) => frontendRecordData($item))
        ->filter(fn ($item) => !empty($item['id']))
        ->values();
    $itemsById = $items->keyBy('id');
    $childrenByPid = [];
    foreach ($items as $item) {
        $pid = (int) ($item['pid'] ?? 0);
        $childrenByPid[$pid][] = $item;
    }
    $roots = $items->filter(function ($item) use ($itemsById) {
        $pid = (int) ($item['pid'] ?? 0);
        return $pid <= 0 || !$itemsById->has($pid);
    })->values();
    $flattenBranch = function (array $node, int $depth = 0) use (&$flattenBranch, $childrenByPid): array {
        $branch = [];
        foreach ($childrenByPid[$node['id']] ?? [] as $child) {
            $child['_depth'] = $depth;
            $branch[] = $child;
            $branch = array_merge($branch, $flattenBranch($child, $depth + 1));
        }
        return $branch;
    };
@endphp
<div id="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @if($roots->isEmpty())
                    <div class="alert alert-info">暂无目录内容</div>
                @else
                    <div class="row">
                        @foreach($roots as $root)
                            @php($rootTitle = $root['title'] ?? ($root['name'] ?? ('#' . $root['id'])))
                            @php($branchItems = $flattenBranch($root))
                            <div class="col-md-6" style="margin-bottom: 20px;">
                                <div class="panel panel-default" style="margin-bottom: 0;">
                                    <div class="panel-heading">
                                        <strong>{{$rootTitle}}</strong>
                                        <a href="{{url("detail/{$param['model']}/{$root['id']}")}}" class="pull-right">查看</a>
                                    </div>
                                    <div class="list-group">
                                        @forelse($branchItems as $branchItem)
                                            @php($branchTitle = $branchItem['title'] ?? ($branchItem['name'] ?? ('#' . $branchItem['id'])))
                                            <a href="{{url("detail/{$param['model']}/{$branchItem['id']}")}}" class="list-group-item">
                                                <span style="display: inline-block; width: {{ 18 * ((int) ($branchItem['_depth'] ?? 0)) }}px;"></span>
                                                {{$branchTitle}}
                                            </a>
                                        @empty
                                            <div class="list-group-item text-muted">当前目录下还没有子级内容。</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if(method_exists($data, 'links') && ($model['home_config']['page_num'] ?? 0) > 0)
                        <div class="text-center">
                            {{$data->appends($_GET)->links()}}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
