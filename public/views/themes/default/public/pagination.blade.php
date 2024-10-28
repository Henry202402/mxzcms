@if ($paginator->hasPages())
    <style>
        .h-page-style .pagination {
            text-align: center;
            line-height: 35px;
            font-size: 16px;
            margin: 0;
        }

        .h-page-style .pagination li {
            float: left;
            width: 35px;
            height: 35px;
            background-color: #F2F2F2;
            margin: 0 3px;
            border-radius: 2px;
            list-style: none;
            cursor: pointer;
        }

        .h-page-style .pageDiv {
            float: right;
            margin-top: 20px;
        }

        .h-page-style .pagination li a {
            width: 90%;
            height: 90%;
            color: #000;
            text-decoration-line: none;
            display: inline-block;
        }

        .h-page-style .active {
            color: #fff !important;
            background-color:#28c !important;
        }

        .h-page-style .active a {
            color: #fff !important;
        }

        .h-page-style li:hover {
            background-color: #28c;
        }

        .h-page-style li:hover a {
            color: #fff !important;
        }

        .h-page-style .disabled {
            font-size: 25px;
        }

        .h-page-style .center {
            display: flex;
            justify-content: center;
        }

        .h-page-style .left {
            display: flex;
            justify-content: left;
        }

        .h-page-style .right {
            display: flex;
            justify-content: right;
        }
    </style>
    <nav class="h-page-style">
        <ul class="pagination {{$data['page_position']}}">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="page-link" aria-hidden="true">&lsaquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev"
                       aria-label="@lang('pagination.previous')">&lsaquo;</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span>
                            </li>
                        @elseif($data['side_num']&&$page>=($paginator->currentPage()-$data['side_num']) && $page<=($paginator->currentPage()+$data['side_num']))
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @elseif(!$data['side_num'])
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next"
                       aria-label="@lang('pagination.next')">&rsaquo;</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="page-link" aria-hidden="true">&rsaquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
