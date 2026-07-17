@extends('layouts.admin')

@section('title', 'Médias')

@section('content')
{{-- En-tête --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Médias</h1>
    <a href="{{ route('admin.medias.create') }}" class="btn btn-primary">Nouveau média</a>
</div>

{{-- Liste des médias --}}
<div class="table-responsive">
<table class="table table-striped">
    <thead>
        <tr>
            <th>Ordre</th>
            <th>Légende</th>
            <th>Type</th>
            <th>Année</th>
            <th>Aperçu</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($medias as $media)
            <tr>
                <td data-label="Ordre">{{ $media->ordre_affichage ?? '-' }}</td>
                <td data-label="Légende">{{ $media->titre ?? '-' }}</td>
                <td data-label="Type">{{ $media->type === 'photo' ? 'Photo' : 'Vidéo' }}</td>
                <td data-label="Année">{{ $media->annee_edition ?? '-' }}</td>
                <td data-label="Aperçu">
                    @if ($media->type === 'photo')
                        <img src="{{ $media->thumbnail }}" alt="{{ $media->titre }}" width="60" height="60" style="object-fit:cover;border-radius:6px;" class="img-thumbnail">
                    @else
                        <span class="text-muted small">Vidéo</span>
                    @endif
                </td>
                <td data-label="Actions">
                    <div class="d-flex gap-1">
                        <a href="{{ route('admin.medias.edit', $media) }}" class="btn btn-sm btn-warning" title="Modifier"><i class="bi bi-pencil-fill"></i></a>
                        <form action="{{ route('admin.medias.destroy', $media) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" title="Supprimer"><i class="bi bi-trash-fill"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center py-4 text-muted">Aucun média.</td></tr>
        @endforelse
    </tbody>
</table>
</div>
{{-- Pagination --}}
{{ $medias->links() }}
@endsection
