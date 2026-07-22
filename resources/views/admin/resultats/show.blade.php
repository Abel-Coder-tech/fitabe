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
            <i class="bi bi-trophy-fill me-2" style="color: #9B4D07;"></i> Résultats {{ $annee }}
        </h1>
    </div>
    <div class="d-flex gap-2">
        @php
            $hasResults = $resultats->isNotEmpty();
            $allPublished = $hasResults ? $resultats->flatten()->every(fn($r) => $r->publie) : false;
        @endphp
        @auth
            @if(auth()->user()?->role === 'super_admin')
                @if($hasResults)
                <form action="{{ route('admin.resultats.publier', $annee) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm {{ $allPublished ? 'btn-success' : 'btn-outline-success' }}">
                        <i class="bi bi-{{ $allPublished ? 'check-circle-fill' : 'globe2' }} me-1"></i>
                        {{ $allPublished ? 'Publié' : 'Publier' }}
                    </button>
                </form>
                @endif
                <form action="{{ route('admin.resultats.regenerer', $annee) }}" method="POST" class="d-inline" onsubmit="return confirm('Régénérer les résultats pour {{ $annee }} ? Les notes jury seront perdues.')">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-repeat me-1"></i> {{ $hasResults ? 'Régénérer' : 'Générer' }}
                    </button>
                </form>
            @endif
        @endauth
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show py-2">{{ session('success') }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Résultats par catégorie --}}
@if($hasResults)
    @foreach ($resultats as $categorie => $items)
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
                            <th>Ovations</th>
                            <th>Technique</th>
                            <th>Originalité</th>
                            <th>Présence</th>
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
                                            <img src="{{ $r->candidat_photo_url }}" alt="{{ $r->candidat_nom }}" width="36" height="36" class="rounded-circle" style="object-fit: cover;">
                                        @endif
                                        <span class="fw-medium">{{ $r->candidat_nom }}</span>
                                    </div>
                                </td>
                                <td>{{ $r->nombre_votes }}</td>
                                <td>{{ $r->note_technique ?? '-' }}</td>
                                <td>{{ $r->note_originalite ?? '-' }}</td>
                                <td>{{ $r->note_presence ?? '-' }}</td>
                                <td>{{ $r->score_public ?? '-' }}</td>
                                <td><strong>{{ $r->score_final ?? '-' }}</strong></td>
                                <td>
                                    @if(auth()->user()?->role === 'super_admin')
                                    <a href="{{ route('admin.resultats.edit', $r) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil-fill"></i> Noter
                                    </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
@else
    <div class="text-center py-5">
        <i class="bi bi-inbox" style="font-size: 3rem; color: #CA7B05;"></i>
        <h5 class="mt-3" style="color: #3E1E05;">Aucun résultat pour l'édition {{ $annee }}</h5>
        <p class="text-muted mb-3">Les résultats n'ont pas encore été générés pour cette édition.</p>
        @if(auth()->user()?->role === 'super_admin')
        <form action="{{ route('admin.resultats.regenerer', $annee) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-arrow-repeat me-1"></i> Générer les résultats
            </button>
        </form>
        @endif
    </div>
@endif
@endsection
