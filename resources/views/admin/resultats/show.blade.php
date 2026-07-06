@extends('layouts.admin')

@section('title', 'Résultats ' . $annee)

@section('content')
{{-- En-tête --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('admin.resultats.index') }}" class="text-muted text-decoration-none small">
            <i class="bi bi-arrow-left me-1"></i> Toutes les éditions
        </a>
        <h1 style="font-size: 1.3rem; color: #3E1E05; margin-top: 4px;">
            Résultats {{ $annee }}
        </h1>
    </div>
    <form action="{{ route('admin.resultats.regenerer', $annee) }}" method="POST" class="d-inline" onsubmit="return confirm('Régénérer tous les résultats pour {{ $annee }} ? Les notes jury seront perdues.')">
        @csrf
        <button type="submit" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-repeat me-1"></i> Régénérer
        </button>
    </form>
</div>

{{-- Résultats par catégorie --}}
@forelse ($resultats as $categorie => $items)
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
        <div class="px-4 py-3" style="background: linear-gradient(135deg, #3E1E05, #9B4D07);">
            <h6 class="fw-bold mb-0 text-white">
                <i class="bi bi-tag-fill me-2" style="color: #E3D5AD;"></i> {{ $categorie }}
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Candidat</th>
                        <th>Votes</th>
                        <th>Note Jury</th>
                        <th>Score Public</th>
                        <th>Score Final</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $r)
                        <tr>
                            <td><span class="badge fs-6 px-2 py-1" style="background: {{ $r->prix === 1 ? '#FFD700' : ($r->prix === 2 ? '#C0C0C0' : '#CD7F32') }}; color: #3E1E05;">{{ $r->prix_label }}</span></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if ($r->candidat_photo)
                                        <img src="{{ $r->candidat_photo_url }}" alt="" width="36" height="36" class="rounded-circle" style="object-fit: cover;">
                                    @endif
                                    <span class="fw-medium">{{ $r->candidat_nom }}</span>
                                </div>
                            </td>
                            <td>{{ $r->nombre_votes }}</td>
                            <td>{{ $r->note_jury ?? '-' }}</td>
                            <td>{{ $r->score_public ?? '-' }}</td>
                            <td><strong>{{ $r->score_final ?? '-' }}</strong></td>
                            <td>
                                <a href="{{ route('admin.resultats.edit', $r) }}" class="btn btn-sm btn-warning">Noter</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
{{-- Aucun résultat --}}
@empty
    <div class="text-center py-5 text-muted">
        <i class="bi bi-inbox fs-1 d-block mb-2" style="color: #CA7B05;"></i>
        <p class="mb-0">Aucun résultat pour cette édition.</p>
    </div>
@endforelse
@endsection
