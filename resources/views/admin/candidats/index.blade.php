@extends('layouts.admin')

@section('title', 'Candidats')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Candidats</h1>
    <a href="{{ route('admin.candidats.create') }}" class="btn btn-primary">Nouveau candidat</a>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Nom</th>
            <th>Nom de scène</th>
            <th>Catégorie</th>
            <th>Votes</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($candidats as $candidat)
            <tr>
                <td>{{ $candidat->id }}</td>
                <td>{{ $candidat->nom }}</td>
                <td>{{ $candidat->nom_scene ?? '-' }}</td>
                <td>{{ $candidat->categorie }}</td>
                <td>{{ $candidat->nombre_votes }}</td>
                <td>
                    <a href="{{ route('admin.candidats.edit', $candidat) }}" class="btn btn-sm btn-warning">Modifier</a>
                    <form action="{{ route('admin.candidats.destroy', $candidat) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Supprimer</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center">Aucun candidat.</td></tr>
        @endforelse
    </tbody>
</table>
{{ $candidats->links() }}
@endsection
