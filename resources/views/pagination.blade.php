@if($paginator->hasPages())
    <nav class="pagination" data-cy="pagination" aria-label="Paginação">
        <a class="btn secondary" data-cy="page-previous" href="{{ $paginator->previousPageUrl() ?? '#' }}" @if($paginator->onFirstPage()) aria-disabled="true" @endif>Anterior</a>
        <span data-cy="current-page">Página {{ $paginator->currentPage() }} de {{ $paginator->lastPage() }}</span>
        <a class="btn secondary" data-cy="page-next" href="{{ $paginator->nextPageUrl() ?? '#' }}" @unless($paginator->hasMorePages()) aria-disabled="true" @endunless>Próxima</a>
    </nav>
@endif
