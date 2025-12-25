@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="mt-4">
        {{-- Mobile pagination (visible sur petits écrans) --}}
        <div class="d-flex justify-content-between align-items-center d-md-none mb-3">
            <div>
                @if ($paginator->onFirstPage())
                    <span class="btn btn-outline-secondary disabled">
                        <i class="bi bi-chevron-left"></i> Précédent
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-outline-primary" rel="prev">
                        <i class="bi bi-chevron-left"></i> Précédent
                    </a>
                @endif
            </div>

            <div class="text-center">
                <span class="text-muted small">
                    Page <strong>{{ $paginator->currentPage() }}</strong> sur <strong>{{ $paginator->lastPage() }}</strong>
                </span>
            </div>

            <div>
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-outline-primary" rel="next">
                        Suivant <i class="bi bi-chevron-right"></i>
                    </a>
                @else
                    <span class="btn btn-outline-secondary disabled">
                        Suivant <i class="bi bi-chevron-right"></i>
                    </span>
                @endif
            </div>
        </div>

        {{-- Desktop pagination (visible sur écrans moyens et grands) --}}
        <div class="d-none d-md-flex justify-content-between align-items-center">
            <div>
                <p class="text-muted small mb-0">
                    Affichage de 
                    <strong>{{ $paginator->firstItem() }}</strong> 
                    à 
                    <strong>{{ $paginator->lastItem() }}</strong> 
                    sur 
                    <strong>{{ $paginator->total() }}</strong> 
                    résultat(s)
                </p>
            </div>

            <ul class="pagination mb-0">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                        <span class="page-link" aria-hidden="true">
                            <i class="bi bi-chevron-left"></i>
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li class="page-item disabled" aria-disabled="true">
                            <span class="page-link">{{ $element }}</span>
                        </li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="page-item active" aria-current="page">
                                    <span class="page-link">
                                        {{ $page }}
                                    </span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $url }}">
                                        {{ $page }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                        <span class="page-link" aria-hidden="true">
                            <i class="bi bi-chevron-right"></i>
                        </span>
                    </li>
                @endif
            </ul>
        </div>
    </nav>
@endif

<style>
    .pagination {
        --ceeac-primary: #003366;
        --ceeac-secondary: #0066CC;
    }

    .pagination .page-link {
        border-radius: 8px;
        margin: 0 3px;
        border: 2px solid #e9ecef;
        color: var(--ceeac-primary);
        transition: all 0.3s ease;
        font-weight: 500;
        padding: 0.5rem 0.75rem;
        min-width: 40px;
        text-align: center;
    }

    .pagination .page-link:hover:not(.disabled) {
        background: linear-gradient(135deg, var(--ceeac-primary) 0%, var(--ceeac-secondary) 100%);
        color: white;
        border-color: var(--ceeac-primary);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 51, 102, 0.2);
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, var(--ceeac-primary) 0%, var(--ceeac-secondary) 100%);
        color: white;
        border: none;
        box-shadow: 0 4px 12px rgba(0, 51, 102, 0.3);
        font-weight: 600;
    }

    .pagination .page-item.disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
        background-color: #f8f9fa;
    }

    .pagination .page-link i {
        font-size: 1.1rem;
    }
</style>
