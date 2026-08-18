{{-- Pagination FITAB : "10 par page" + numéros de page à gauche, Précédent/Suivant à droite --}}
@php
    $pageCourante = $paginator->currentPage();
    $dernierePage = $paginator->lastPage();
    $libelle = $label ?? 'élément(s)';
@endphp

<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-3 px-2">
    <div class="d-flex flex-wrap align-items-center gap-2">
        <span class="small text-muted">
            Page {{ $pageCourante }} sur {{ $dernierePage }} — {{ $paginator->total() }} {{ $libelle }} au total
        </span>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ $paginator->previousPageUrl() ?: 'javascript:void(0)' }}"
           class="btn btn-sm fw-semibold {{ $paginator->onFirstPage() ? 'disabled' : '' }}"
           style="{{ $paginator->onFirstPage() ? 'background:#eee; color:#aaa; border-radius:8px;' : 'background:#9B4D07; color:#fff; border-radius:8px;' }}">
            <i class="bi bi-chevron-left"></i> Précédent
        </a>
        <a href="{{ $paginator->nextPageUrl() ?: 'javascript:void(0)' }}"
           class="btn btn-sm fw-semibold {{ $paginator->hasMorePages() ? '' : 'disabled' }}"
           style="{{ $paginator->hasMorePages() ? 'background:#9B4D07; color:#fff; border-radius:8px;' : 'background:#eee; color:#aaa; border-radius:8px;' }}">
            Suivant <i class="bi bi-chevron-right"></i>
        </a>
    </div>
</div>
