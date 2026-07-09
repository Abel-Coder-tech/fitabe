@extends('layouts.admin')

@section('title', 'Programmes')

@section('content')
{{-- En-tête --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Programmes</h1>
    <a href="{{ route('admin.programmes.create') }}" class="btn btn-primary">Nouveau programme</a>
</div>

{{-- Liste des programmes --}}
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>Icône</th>
                <th>Titre</th>
                <th>Date</th>
                <th>Lieu</th>
                <th>Sous-dates</th>
                <th>Ordre</th>
                <th>Actif</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($programmes as $programme)
                <tr>
                    <td data-label="Icône" class="text-center">
                        @if ($programme->icone)
                            <i class="bi {{ $programme->icone }}" style="color: {{ $programme->couleur_bordure ?? '#9B4D07' }}; font-size: 1.2rem;"></i>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td data-label="Titre">
                        {{ $programme->titre }}
                       
                    </td>
                    <td data-label="Date">{{ \Carbon\Carbon::parse($programme->date_programme)->format('d/m/Y H:i') }}</td>
                    <td data-label="Lieu">{{ $programme->lieu ?? 'N/A' }}</td>
                    <td data-label="Sous-dates">
                        @if ($programme->dates_count > 0)
                            <span class="badge bg-info">{{ $programme->dates_count }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td data-label="Ordre">{{ $programme->ordre }}</td>
                    <td data-label="Actif">
                        @if ($programme->est_actif)
                            <span class="badge bg-success">Oui</span>
                        @else
                            <span class="badge bg-secondary">Non</span>
                        @endif
                    </td>
                    <td data-label="Actions">
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.programmes.edit', $programme) }}" class="btn btn-sm btn-warning" title="Modifier"><i class="bi bi-pencil-fill"></i></a>
                            <form action="{{ route('admin.programmes.destroy', $programme) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce programme ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Supprimer"><i class="bi bi-trash-fill"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">Aucun programme trouvé.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
{{ $programmes->links() }}
@endsection
