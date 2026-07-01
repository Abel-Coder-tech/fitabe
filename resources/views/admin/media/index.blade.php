@extends('layouts.admin')

@section('title', 'Médias')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Médias</h1>
    <a href="{{ route('admin.medias.create') }}" class="btn btn-primary">Nouveau média</a>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Titre</th>
            <th>Type</th>
            <th>Candidat</th>
            <th>Fichier</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($medias as $media)
            <tr>
                <td>{{ $media->id }}</td>
                <td>{{ $media->titre ?? '-' }}</td>
                <td>{{ $media->type }}</td>
                <td>{{ $media->candidat?->nom ?? '-' }}</td>
                <td>
                    @if (in_array($media->type, ['image/jpeg', 'image/png', 'image/gif', 'image/webp']))
                        <img src="{{ asset('storage/' . $media->url) }}" alt="{{ $media->titre }}" width="60" class="img-thumbnail">
                    @else
                        {{ $media->url }}
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.medias.edit', $media) }}" class="btn btn-sm btn-warning">Modifier</a>
                    <form action="{{ route('admin.medias.destroy', $media) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Supprimer</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center">Aucun média.</td></tr>
        @endforelse
    </tbody>
</table>
{{ $medias->links() }}
@endsection
