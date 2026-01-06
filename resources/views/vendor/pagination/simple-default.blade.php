{{--
    Simple Pagination View

    Laravel এর built-in pagination এর জন্য custom view।
    $paginator variable automatically inject হয়।

    @see https://laravel.com/docs/pagination#customizing-the-pagination-view
--}}
@if ($paginator->hasPages())
    <nav>
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li><span>&laquo; আগের</span></li>
            @else
                <li><a href="{{ $paginator->previousPageUrl() }}">&laquo; আগের</a></li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}">পরের &raquo;</a></li>
            @else
                <li><span>পরের &raquo;</span></li>
            @endif
        </ul>
    </nav>
@endif
