@extends('layouts.admin')

@section('title', 'Soutiens')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Soutiens</h1>
    <a href="{{ route('admin.soutiens.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Nouveau soutien
    </a>
</div>

<div class="card border-0 rounded-4 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="min-width: 600px;">
                <thead class="small text-muted" style="background: #f9f9fb;">
                    <tr>
                        <th class="px-4 py-3 fw-semibold" style="width: 60px;">ID</th>
                        <th class="px-4 py-3 fw-semibold">Nom</th>
                        <th class="px-4 py-3 fw-semibold text-center" style="width: 100px;">Photo</th>
                        <th class="px-4 py-3 fw-semibold">Titre</th>
                        <th class="px-4 py-3 fw-semibold">Organisation</th>
                        <th class="px-4 py-3 fw-semibold text-center" style="width: 110px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($soutiens as $soutien)
                        <tr class="border-bottom" style="border-color: #f5f5f5 !important;">
                            <td class="px-4 py-3 small text-muted">{{ $soutien->id }}</td>
                            <td class="px-4 py-3">
                                <span class="fw-semibold small" style="color: #3E1E05;">{{ $soutien->nom }}</span>
                                @if ($soutien->citation)
                                    <br><small class="text-muted" style="font-style: italic;">{{ Str::limit($soutien->citation, 60) }}</small>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if ($soutien->photo)
                                    <img src="{{ asset('storage/' . $soutien->photo) }}" alt="{{ $soutien->nom }}"
                                         style="width: 44px; height: 44px; object-fit: cover; border-radius: 50%; border: 2px solid #f5f5f5;">
                                @else
                                    <div style="width: 44px; height: 44px; border-radius: 50%; background: #f5f5f5; display: inline-flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-person" style="color: #ccc; font-size: 1rem;"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 small">{{ $soutien->titre ?? '—' }}</td>
                            <td class="px-4 py-3 small">{{ $soutien->organisation ?? '—' }}</td>
                            <td class="px-4 py-3 text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="{{ route('admin.soutiens.edit', $soutien) }}" class="btn btn-sm" style="background: #fef0e0; color: #9B4D07;" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.soutiens.destroy', $soutien) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?')">
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
                            <td colspan="6" class="text-center py-5 text-muted small">Aucun soutien trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $soutiens->links() }}
</div>
@endsection
