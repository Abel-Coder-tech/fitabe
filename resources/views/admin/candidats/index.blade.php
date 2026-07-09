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
                        <a href="{{ route('admin.candidats.edit', $candidat) }}" class="btn btn-sm btn-warning">Modifier</a>
                        <form action="{{ route('admin.candidats.destroy', $candidat) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Supprimer</button>
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
@endsection
