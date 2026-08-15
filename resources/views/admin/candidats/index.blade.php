@extends('layouts.admin')

@section('title', 'Candidats')

@push('styles')
<style>
    .candidat-btn::after { display: none; }
    .candidat-btn { transition: background-color .2s ease; }
    .candidat-btn:not(.collapsed) { background-color: #fff !important; }
    .candidat-chevron { color: #9B4D07; transition: transform .3s ease; font-size: .9rem; }
    .candidat-btn.collapsed .candidat-chevron { transform: rotate(-90deg); }
</style>
@endpush

@section('content')
{{-- En-tête --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Candidats</h1>
    <a href="{{ route('admin.candidats.create') }}" class="btn btn-primary">Nouveau candidat</a>
</div>

@if ($errors->any())
    <div class="alert alert-danger py-2">
        <ul class="mb-0 small">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Accordéons par catégorie --}}
@if ($total === 0)
    <div class="text-center py-5 text-muted">
        <i class="bi bi-people fs-1 d-block mb-2" style="color: #CA7B05;"></i>
        <p class="mb-0">Aucun candidat inscrit pour le moment.</p>
        <small>Cliquez sur « Nouveau candidat » pour ajouter votre premier candidat.</small>
    </div>
@else
    <div class="accordion" id="candidatsAccordion">
        @foreach ($categories as $i => $cat)
            @php
                $catColor = \App\Models\Candidats::CATEGORY_COLORS[$cat->categorie] ?? '#9B4D07';
                $count = $cat->candidats->count();
                $complet = $count >= $cat->places;
            @endphp
            <div class="accordion-item border-0 mb-3 rounded-3 overflow-hidden shadow-sm"
                 style="border-left: 4px solid {{ $catColor }} !important; border-radius: 8px !important;">
                <h2 class="accordion-header" id="heading{{ $i }}">
                    <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }} fw-bold py-3 candidat-btn"
                            style="background: #fff; color: #3E1E05; box-shadow: none; padding-left: 48px; position: relative;"
                            type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $i }}"
                            aria-expanded="{{ $i === 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $i }}">
                        <i class="bi bi-tag-fill" style="color: {{ $catColor }}; position: absolute; left: 16px; top: 50%; transform: translateY(-50%);"></i>
                        <span>{{ $cat->categorie }}</span>
                        <span class="ms-auto d-flex align-items-center gap-3">
                            <span class="badge rounded-pill px-3"
                                  style="{{ $complet ? 'background:#8b1a1a; color:#fff;' : 'background:' . $catColor . '1F; color:' . $catColor . ';' }}">
                                {{ $count }}/{{ $cat->places }} places
                            </span>
                            <i class="bi bi-chevron-down candidat-chevron"></i>
                        </span>
                    </button>
                </h2>
                <div id="collapse{{ $i }}" class="accordion-collapse collapse {{ $i === 0 ? 'show' : '' }}"
                     aria-labelledby="heading{{ $i }}" data-bs-parent="#candidatsAccordion">
                    <div class="accordion-body p-0">
                        <div class="d-flex justify-content-between align-items-center px-3 py-2" style="background:#fdfaf5; border-bottom:1px solid #f0e6d6;">
                            <span class="small text-muted">
                                <i class="bi bi-people me-1" style="color:#9B4D07;"></i>
                                {{ $count }} candidat(s) inscrit(s)
                            </span>
                            @if(auth()->user()?->isSuperAdmin())
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3"
                                    onclick="ouvrirPlaces('{{ $cat->categorie }}', {{ $cat->places }})">
                                <i class="bi bi-sliders me-1"></i> Modifier les places
                            </button>
                            @endif
                        </div>
                        @if ($cat->candidats->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-striped table-hover mb-0">
                                    <thead style="background:#fff;">
                                        <tr>
                                            <th>N° passage</th>
                                            <th>Nom</th>
                                            <th>Nom de scène</th>
                                            <th>Ovations</th>
                                            <th class="text-end pe-3">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cat->candidats as $candidat)
                                            <tr>
                                                <td>
                                                    @if ($candidat->numero_scene)
                                                        <span class="badge px-2 py-1" style="background:#9B4D07; color:#fff;">N°{{ $candidat->numero_scene }}</span>
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>
                                                <td>{{ $candidat->nom }}</td>
                                                <td>{{ $candidat->nom_scene ?? '-' }}</td>
                                                <td>{{ $candidat->nombre_votes }}</td>
                                                <td class="text-end pe-3">
                                                    <div class="d-flex gap-1 justify-content-end">
                                                        <button type="button" class="btn btn-sm btn-fitab-info" title="Voir"
                                                                data-bs-toggle="modal" data-bs-target="#voirCandidatModal"
                                                                onclick="voirCandidat({{ json_encode($candidat) }})">
                                                            <i class="bi bi-eye-fill"></i>
                                                        </button>
                                                        <a href="{{ route('admin.candidats.edit', $candidat) }}" class="btn btn-sm btn-warning" title="Modifier">
                                                            <i class="bi bi-pencil-fill"></i>
                                                        </a>
                                                        @if(auth()->user()?->isSuperAdmin())
                                                        <form action="{{ route('admin.candidats.destroy', $candidat) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?')">
                                                            @csrf @method('DELETE')
                                                            <button class="btn btn-sm btn-danger" title="Supprimer"><i class="bi bi-trash-fill"></i></button>
                                                        </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4 text-muted">
                                <p class="mb-2">Aucun candidat dans cette catégorie.</p>
                                <a href="{{ route('admin.candidats.create') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="bi bi-plus-lg me-1"></i> Ajouter un candidat
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

{{-- Modal Voir Candidat --}}
<div class="modal fade" id="voirCandidatModal" tabindex="-1" aria-labelledby="voirCandidatLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, #3E1E05, #9B4D07); border: none;">
                <h6 class="modal-title fw-bold text-white" id="voirCandidatLabel">
                    <i class="bi bi-person-fill me-2"></i>Détails du candidat
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    <div class="col-md-4 text-center">
                        <img id="voirPhoto" src="" alt="" class="rounded-circle mb-3" style="width: 140px; height: 140px; object-fit: cover; border: 4px solid #E3D5AD;">
                        <div id="voirBadge" class="badge px-3 py-1" style="background: #CA7B05; color: #fff;"></div>
                    </div>
                    <div class="col-md-8">
                        <table class="table table-borderless mb-0">
                            <tr><th style="width: 140px; color: #9B4D07;">Nom</th><td id="voirNom"></td></tr>
                            <tr><th style="color: #9B4D07;">Nom de scène</th><td id="voirScene"></td></tr>
                            <tr><th style="color: #9B4D07;">N° passage</th><td id="voirNumero"></td></tr>
                            <tr><th style="color: #9B4D07;">Ovations</th><td><span class="fw-bold" style="color: #3E1E05;" id="voirOvations"></span></td></tr>
                            <tr><th style="color: #9B4D07;">Biographie</th><td id="voirBio" style="white-space: pre-wrap;"></td></tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <a id="voirEditLink" href="#" class="btn btn-warning">
                    <i class="bi bi-pencil-fill me-1"></i> Modifier
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Modifier les places --}}
<div class="modal fade" id="placesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 380px;">
        <div class="modal-content" style="border-radius: 16px;">
            <div class="modal-header" style="background: linear-gradient(135deg, #3E1E05, #9B4D07); border: none;">
                <h6 class="modal-title fw-bold text-white" id="placesModalLabel">
                    <i class="bi bi-sliders me-2"></i>Modifier les places
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form method="POST" action="{{ route('admin.candidats.places') }}" novalidate>
                @csrf
                <div class="modal-body p-4">
                    <input type="hidden" name="categorie" id="placesCategorie">
                    <label class="form-label fw-semibold small" for="placesInput">Nombre de places (numéros de scène)</label>
                    <input type="number" name="places" id="placesInput" min="1" max="100" class="form-control @error('places') is-invalid @enderror">
                    @include('partials.field-error', ['field' => 'places'])
                    <small class="text-muted d-block mt-2">Chaque candidat occupe un numéro de scène entre 1 et ce nombre.</small>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4">
                    <button type="submit" class="btn text-white fw-semibold border-0 rounded-pill px-4" style="background:#9B4D07;">
                        <i class="bi bi-check-lg me-1"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function voirCandidat(c) {
    document.getElementById('voirPhoto').src = c.photo_url || '{{ asset("images/hero.jpg") }}';
    document.getElementById('voirPhoto').alt = c.nom || 'Photo';
    document.getElementById('voirNom').textContent = c.nom || '—';
    document.getElementById('voirScene').textContent = c.nom_scene || '—';
    document.getElementById('voirNumero').textContent = c.numero_scene || '—';
    document.getElementById('voirOvations').textContent = c.nombre_votes || 0;
    document.getElementById('voirBio').textContent = c.biographie || '—';
    document.getElementById('voirBadge').textContent = c.categorie || '';
    document.getElementById('voirEditLink').href = '{{ url("admin/candidats") }}/' + c.id + '/edit';
}

function ouvrirPlaces(categorie, places) {
    document.getElementById('placesCategorie').value = categorie;
    document.getElementById('placesInput').value = places;
    document.getElementById('placesModalLabel').textContent = 'Modifier les places — ' + categorie;
    new bootstrap.Modal(document.getElementById('placesModal')).show();
}
</script>
@endpush
@endsection
