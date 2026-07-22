@extends('layouts.admin')

@section('title', 'Candidats')

@section('content')
{{-- En-tête --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Candidats</h1>
    <a href="{{ route('admin.candidats.create') }}" class="btn btn-primary">Nouveau candidat</a>
</div>

{{-- Liste des candidats --}}
<div class="table-responsive">
<table class="table table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Nom</th>
            <th>Nom de scène</th>
            <th>N° passage</th>
            <th>Catégorie</th>
            <th>Ovations</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($candidats as $candidat)
            <tr>
                <td data-label="#">{{ $candidat->id }}</td>
                <td data-label="Nom">{{ $candidat->nom }}</td>
                <td data-label="Nom de scène">{{ $candidat->nom_scene ?? '-' }}</td>
                <td data-label="N° passage">{{ $candidat->numero_scene ?? '-' }}</td>
                <td data-label="Catégorie">{{ $candidat->categorie }}</td>
                <td data-label="Ovations">{{ $candidat->nombre_votes }}</td>
                <td data-label="Actions">
                    <div class="d-flex gap-1">
                        <button type="button" class="btn btn-sm btn-fitab-info" title="Voir"
                                data-bs-toggle="modal" data-bs-target="#voirCandidatModal"
                                onclick="voirCandidat({{ json_encode($candidat) }})">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                        <a href="{{ route('admin.candidats.edit', $candidat) }}" class="btn btn-sm btn-warning" title="Modifier">
                            <i class="bi bi-pencil-fill"></i>
                        </a>
                        <form action="{{ route('admin.candidats.destroy', $candidat) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" title="Supprimer"><i class="bi bi-trash-fill"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center py-4 text-muted">Aucun candidat.</td></tr>
        @endforelse
    </tbody>
</table>
</div>
{{-- Pagination --}}
{{ $candidats->links() }}

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

@push('scripts')
<script>
function voirCandidat(c) {
    document.getElementById('voirPhoto').src = c.photo ? '/storage/' + c.photo : '{{ asset("images/default-user.png") }}';
    document.getElementById('voirPhoto').alt = c.nom || 'Photo';
    document.getElementById('voirNom').textContent = c.nom || '—';
    document.getElementById('voirScene').textContent = c.nom_scene || '—';
    document.getElementById('voirNumero').textContent = c.numero_scene || '—';
    document.getElementById('voirOvations').textContent = c.nombre_votes || 0;
    document.getElementById('voirBio').textContent = c.biographie || '—';
    document.getElementById('voirBadge').textContent = c.categorie || '';
    document.getElementById('voirEditLink').href = '{{ url("admin/candidats") }}/' + c.id + '/edit';
}
</script>
@endpush
@endsection
