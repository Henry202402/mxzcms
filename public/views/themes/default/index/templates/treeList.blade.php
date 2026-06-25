@php $home_config = json_decode($data->home_config,true) ?: []; @endphp
<div @if(($home_config['show_home_type'] ?? '')=="color" && !empty($home_config['home_page_bg_color']))
         style='background-color:{{$home_config['home_page_bg_color']}} !important'
     @elseif(($home_config['show_home_type'] ?? '')=="img" && !empty($home_config['home_page_bg_img']))
         style='background-image: url({{GetUrlByPath($home_config['home_page_bg_img'])}});background-repeat: no-repeat'
   @endif >
    <div class="container">
        @include('themes.default.public.homeSectionHeader', [
            'sectionData' => $data,
            'sectionConfig' => $home_config,
            'sectionMoreUrl' => url('list/' . $data->access_identification),
        ])

        @php
            $items = collect(getListByModel($data, $data->home_page_num ?: null))
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

        @if($roots->isEmpty())
            <div class="mx-empty">当前模型还没有可展示的目录数据，请先在后台新增内容。</div>
        @else
            <div class="mx-tree-board">
                @foreach($roots as $root)
                    @php($rootTitle = $root['title'] ?? ($root['name'] ?? ('内容 #' . $root['id'])))
                    @php($branchItems = $flattenBranch($root))
                    <section class="mx-tree-column">
                        <div class="mx-tree-column__header">
                            <div>
                                <h3 class="mx-tree-column__title">{{$rootTitle}}</h3>
                                <p class="mx-tree-column__desc">{{ \Illuminate\Support\Str::limit(trim(strip_tags($root['description'] ?? $root['content'] ?? '')), 78) ?: '适合协议目录、帮助中心、知识分类等层级结构内容。' }}</p>
                            </div>
                            <a href="{{url('detail/'.$data->access_identification.'/'.$root['id'])}}" class="mx-tree-column__action">查看</a>
                        </div>

                        @if($branchItems)
                            <div class="mx-tree-list">
                                @foreach($branchItems as $branchItem)
                                    @php($branchTitle = $branchItem['title'] ?? ($branchItem['name'] ?? ('内容 #' . $branchItem['id'])))
                                    <a href="{{url('detail/'.$data->access_identification.'/'.$branchItem['id'])}}"
                                       class="mx-tree-item"
                                       style="--mx-tree-depth: {{ (int) ($branchItem['_depth'] ?? 0) }};">
                                        <span class="mx-tree-item__line"></span>
                                        <span class="mx-tree-item__title">{{$branchTitle}}</span>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="mx-empty">当前目录下还没有子级内容。</div>
                        @endif
                    </section>
                @endforeach
            </div>
        @endif
    </div>
</div>
