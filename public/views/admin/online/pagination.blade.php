<nav aria-label="page navigation example  text-center">
    <ul class="pagination">

        @foreach($links['links'] as $link)

            <li class="page-item @if(!$link['url']) disabled @endif  @if($link['active']) active @endif ">
                @if($link['label']=="pagination.previous")
                    <a class="page-link" href="{{url("admin/cloud?".http_build_query($_GET)."&page=".($links['current_page']-1))}}" aria-label="Previous">
                        <span aria-hidden="true">«</span>
                        <span class="sr-only">Previous</span>
                    </a>
                @elseif($link['label']=="pagination.next")
                        <a class="page-link" href="{{url("admin/cloud?".http_build_query($_GET)."&page=".($links['current_page']+1))}}" aria-label="Next">
                            <span aria-hidden="true">»</span>
                            <span class="sr-only">Next</span>
                        </a>
                @else
                    @if(!$link['active'])
                        <a class="page-link" href="{{url("admin/cloud?".http_build_query($_GET)."&page=".$link['label'])}}">{{$link['label']}}</a>
                    @else
                        <span class="page-link">{{$link['label']}}</span>
                    @endif

                @endif

            </li>

        @endforeach

    </ul>
</nav>
