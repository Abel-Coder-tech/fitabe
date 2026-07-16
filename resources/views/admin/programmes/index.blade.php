@extends('layouts.admin')

@section('title', 'Programmes')

@section('content')
{{-- En-tête --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Programmes</h1>
    <a href="{{ route('admin.programmes.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Nouveau programme
    </a>
</div>

{{-- Liste des programmes --}}
<div class="card border-0 rounded-4 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="min-width: 700px;">
                <thead class="small text-muted" style="background: #f9f9fb;">
                    <tr>
                        <th class="px-4 py-3 fw-semibold" style="width: 50px;">Icône</th>
                        <th class="px-4 py-3 fw-semibold">Titre</th>
                        <th class="px-4 py-3 fw-semibold text-nowrap">Date</th>
                        <th class="px-4 py-3 fw-semibold text-nowrap">Lieu</th>
                        <th class="px-4 py-3 fw-semibold text-center" style="width: 90px;">Sous-dates</th>
                        <th class="px-4 py-3 fw-semibold text-center" style="width: 60px;">Ordre</th>
                        <th class="px-4 py-3 fw-semibold text-center" style="width: 70px;">Actif</th>
                        <th class="px-4 py-3 fw-semibold text-center" style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($programmes as $programme)
                        <tr class="border-bottom" style="border-color: #f5f5f5 !important;">
                            {{-- Icône --}}
                            <td class="px-4 py-3 text-center">
                                @if ($programme->icone)
                                    <i class="bi {{ $programme->icone }}" style="color: {{ $programme->couleur_bordure ?? '#9B4D07' }}; font-size: 1.1rem;"></i>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            {{-- Titre (tronqué si trop long) --}}
                            <td class="px-4 py-3" style="max-width: 220px;">
                                <span class="fw-semibold small" style="color: #3E1E05; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $programme->titre }}">
                                    {{ $programme->titre }}
                                </span>
                            </td>
                            {{-- Date compacte --}}
                            <td class="px-4 py-3 small text-nowrap text-muted">
                                {{ \Carbon\Carbon::parse($programme->date_programme)->format('d/m/Y') }}
                            </td>
                            {{-- Lieu (tronqué) --}}
                            <td class="px-4 py-3" style="max-width: 150px;">
                                <span style="display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $programme->lieu }}">
                                    {{ $programme->lieu ?? 'N/A' }}
                                </span>
                            </td>
                            {{-- Sous-dates --}}
                            <td class="px-4 py-3 text-center">
                                @if ($programme->dates_count > 0)
                                    <span class="badge rounded-pill" style="background: #fef0e0; color: #9B4D07; font-size: 0.7rem;">{{ $programme->dates_count }}</span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            {{-- Ordre --}}
                            <td class="px-4 py-3 text-center small text-muted">{{ $programme->ordre }}</td>
                            {{-- Actif --}}
                            <td class="px-4 py-3 text-center">
                                @if ($programme->est_actif)
                                    <span class="badge rounded-pill" style="background: #e6f7e6; color: #2e7d32; font-size: 0.7rem;">Oui</span>
                                @else
                                    <span class="badge rounded-pill" style="background: #f5f5f5; color: #999; font-size: 0.7rem;">Non</span>
                                @endif
                            </td>
                            {{-- Actions --}}
                            <td class="px-4 py-3 text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="{{ route('admin.programmes.show', $programme) }}" class="btn btn-sm" style="background: #f5f5f5; color: #3E1E05;" title="Voir">
                                        <i class="bi bi-eye"></i>
                                    <a href="{{ route('admin.programmes.edit', $programme) }}" class="btn btn-sm" style="background: #fef0e0; color: #9B4D07;" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.programmes.destroy', $programme) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce programme ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm" style="background: #ffe6e6; color: #dc3545;" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted small">Aucun programme trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Pagination --}}
<div class="d-flex justify-content-center mt-4">
    {{ $programmes->links() }}
</div>
@endsection
