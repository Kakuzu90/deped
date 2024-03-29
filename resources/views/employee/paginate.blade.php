@if ($paginator->hasPages())
<ul class="pagination pagination-md justify-content-end my-2">
    @if ($paginator->onFirstPage())
    <li class="page-item disabled">
        <a class="page-link" href="javascript: void(0);" aria-label="Previous">
            <span>Previous</span>
        </a>
    </li>
    @else
    <li class="page-item">
        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" aria-label="Previous">
            <span>Previous</span>
        </a>
    </li>
    @endif
    
    <li class="page-item active">
        <a class="page-link" href="javascript: void(0);">{{ $paginator->currentPage() }}</a>
    </li>

    @if ($paginator->hasMorePages())
    <li class="page-item">
        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" aria-label="Next">
            <span>Next</span>
        </a>
    </li>
    @else
    <li class="page-item disabled">
        <a class="page-link" href="javascript: void(0);" aria-label="Next">
            <span>Next</span>
        </a>
    </li>
    @endif
</ul>
@endif