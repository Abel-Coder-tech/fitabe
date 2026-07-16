@extends('layouts.admin')

@section('title', 'Partenaires')

@section('content')
{{-- En-tête --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Partenaires</h1>
    <a href="{{ route('admin.partenaires.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Nouveau partenaire
    </a>
</div>

{{-- Liste des partenaires --}}
<div class="card border-0 rounded-4 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="min-width: 550px;">
                <thead class="small text-muted" style="background: #f9f9fb;">
                    <tr>
                        <th class="px-4 py-3 fw-semibold" style="width: 60px;">ID</th>
                        <th class="px-4 py-3 fw-semibold">Nom</th>
                        <th class="px-4 py-3 fw-semibold text-center" style="width: 100px;">Logo</th>
                        <th class="px-4 py-3 fw-semibold">Site web</th>
                        <th class="px-4 py-3 fw-semibold text-center" style="width: 110px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($partenaires as $partenaire)
                        <tr class="border-bottom" style="border-color: #f5f5f5 !important;">
                            {{-- ID --}}
                            <td class="px-4 py-3 small text-muted">{{ $partenaire->id }}</td>
                            {{-- Nom --}}
                            <td class="px-4 py-3">
                                <span class="fw-semibold small" style="color: #3E1E05;">{{ $partenaire->nom }}</span>
                            </td>
                            {{-- Logo --}}
                            <td class="px-4 py-3 text-center">
                                @if ($partenaire->logo)
                                    <img src="{{ asset('storage/' . $partenaire->logo) }}" alt="{{ $partenaire->nom }}"
                                         style="width: 44px; height: 44px; object-fit: cover; border-radius: 10px; border: 2px solid #f5f5f5;">
                                @else
                                    <div style="width: 44px; height: 44px; border-radius: 10px; background: #f5f5f5; display: inline-flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-building" style="color: #ccc; font-size: 1rem;"></i>
                                    </div>
                                @endif
                            </td>
                            {{-- Site web --}}
                            <td class="px-4 py-3" style="max-width: 250px;">
                                @if ($partenaire->site_web)
                                    <a href="{{ $partenaire->site_web }}" target="_blank" rel="noopener noreferrer"
                                       class="small" style="color: #9B4D07; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"
                                       title="{{ $partenaire->site_web }}">
                                        <i class="bi bi-box-arrow-up-right me-1" style="font-size: 0.65rem;"></i>
                                        {{ $partenaire->site_web }}
                                    </a>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            {{-- Actions --}}
                            <td class="px-4 py-3 text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="{{ route('admin.partenaires.edit', $partenaire) }}" class="btn btn-sm" style="background: #fef0e0; color: #9B4D07;" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.partenaires.destroy', $partenaire) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?')">
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
                            <td colspan="5" class="text-center py-5 text-muted small">Aucun partenaire trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Pagination --}}
<div class="d-flex justify-content-center mt-4">
    {{ $partenaires->links() }}
</div>
@endsection
