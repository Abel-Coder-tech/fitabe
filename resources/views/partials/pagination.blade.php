{{-- Pagination FITAB : "10 par page" + numéros de page à gauche, Précédent/Suivant à droite --}}
@php
    $pageCourante = $paginator->currentPage();
    $dernierePage = $paginator->lastPage();
    $libelle = $label ?? 'élément(s)';
    if (! isset($elements)) {
        $elements = [];
        if ($dernierePage <= 7) {
            $elements[] = $paginator->getUrlRange(1, $dernierePage);
        } else {
            $elements[] = $paginator->getUrlRange(1, 1);
            if ($pageCourante > 3) $elements[] = '…';
            $debut = max(2, $pageCourante - 1);
            $fin = min($dernierePage - 1, $pageCourante + 1);
            if ($debut <= $fin) $elements[] = $paginator->getUrlRange($debut, $fin);
            if ($pageCourante < $dernierePage - 2) $elements[] = '…';
            $elements[] = $paginator->getUrlRange($dernierePage, $dernierePage);
        }
    }
@endphp

<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-3 px-2">
    <div class="d-flex flex-wrap align-items-center gap-2">
        <select class="form-select form-select-sm" style="width:auto; border-color:#E3D5AD; border-radius:8px;"
                aria-label="Nombre d'éléments par page"
                onchange="var u=new URL(window.location.href);u.searchParams.set('per_page',this.value);u.searchParams.set('page','1');window.location.href=u.toString();">
            @foreach ([10, 20, 50, 100] as $taille)
                <option value="{{ $taille }}" @selected((int) $paginator->perPage() === $taille)>{{ $taille }} par page</option>
            @endforeach
        </select>

        @if ($paginator->hasPages())
            <div class="d-flex align-items-center gap-1">
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="small text-muted px-1">{{ $element }}</span>
                    @else
                        @foreach ($element as $page => $url)
                            <a href="{{ $url }}" class="text-decoration-none px-2 py-1 small fw-semibold"
                               style="{{ $page === $pageCourante ? 'background:#9B4D07; color:#fff; border-radius:8px;' : 'border:1px solid #E3D5AD; color:#3E1E05; border-radius:8px;' }}">{{ $page }}</a>
                        @endforeach
                    @endif
                @endforeach
            </div>
        @endif

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
