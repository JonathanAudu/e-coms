@if ($paginator->hasPages())
    <div class="pagination d-flex justify-content-center mt-5">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <a class="rounded disabled" href="#">&laquo;</a>
        @else
            <a class="rounded" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <a class="rounded disabled" href="#">{{ $element }}</a>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <a class="rounded active" href="#">{{ $page }}</a>
                    @else
                        <a class="rounded" href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a class="rounded" href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a>
        @else
            <a class="rounded disabled" href="#">&raquo;</a>
        @endif
    </div>
@endif
