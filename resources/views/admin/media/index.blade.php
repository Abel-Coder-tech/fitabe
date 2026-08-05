@extends('layouts.admin')

@section('title', 'Médias')

@section('content')
{{-- En-tête --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Médias</h1>
    <a href="{{ route('admin.medias.create') }}" class="btn btn-primary">Nouveau média</a>
</div>

{{-- ========== PHOTOS ========== --}}
<div class="d-flex align-items-center gap-2 mt-4 mb-2">
    <i class="bi bi-images" style="color: #9B4D07;"></i>
    <h5 class="fw-bold mb-0" style="color: #3E1E05;">Photos</h5>
    <span class="badge rounded-pill" style="background: #fff3e0; color: #9B4D07;">{{ $photos->total() }}</span>
</div>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Légende</th>
                <th>Année</th>
                <th>Aperçu</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($photos as $media)
                <tr>
                    <td data-label="ID">{{ $media->id }}</td>
                    <td data-label="Légende">{{ $media->titre ?? '-' }}</td>
                    <td data-label="Année">{{ $media->annee_edition ?? '-' }}</td>
                    <td data-label="Aperçu">
                        <img src="{{ $media->thumbnail }}" alt="{{ $media->titre }}" width="60" height="60" style="object-fit:cover;border-radius:6px;" class="img-thumbnail">
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
                <tr><td colspan="5" class="text-center py-4 text-muted">Aucune photo.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $photos->links('partials.pagination', ['label' => 'photo(s)']) }}

{{-- ========== VIDÉOS ========== --}}
<div class="d-flex align-items-center gap-2 mt-5 mb-2">
    <i class="bi bi-play-btn" style="color: #9B4D07;"></i>
    <h5 class="fw-bold mb-0" style="color: #3E1E05;">Vidéos</h5>
    <span class="badge rounded-pill" style="background: #fff3e0; color: #9B4D07;">{{ $videos->total() }}</span>
</div>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Légende</th>
                <th>Année</th>
                <th>Aperçu</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($videos as $media)
                <tr>
                    <td data-label="ID">{{ $media->id }}</td>
                    <td data-label="Légende">{{ $media->titre ?? '-' }}</td>
                    <td data-label="Année">{{ $media->annee_edition ?? '-' }}</td>
                    <td data-label="Aperçu">
                        @if($media->youtube_id)
                            <img src="{{ $media->thumbnail }}" alt="{{ $media->titre }}" width="90" height="60" style="object-fit:cover;border-radius:6px;" class="img-thumbnail">
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
                <tr><td colspan="5" class="text-center py-4 text-muted">Aucune vidéo.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $videos->links('partials.pagination', ['label' => 'vidéo(s)']) }}
@endsection
