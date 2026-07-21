@extends('layouts.admin')

@section('title', 'Résultats')

@section('content')
{{-- En-tête --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 style="font-size: 1.3rem; color: #3E1E05;">
        <i class="bi bi-trophy-fill me-2" style="color: #9B4D07;"></i> Résultats
    </h1>
</div>

{{-- Grille des éditions --}}
@if ($editions->count())
    <div class="row g-4">
        @foreach ($editions as $annee)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100">
                    <div class="mb-2">
                        <i class="bi bi-trophy-fill" style="font-size: 2rem; color: #CA7B05;"></i>
                    </div>
                    <h5 class="fw-bold mb-2" style="color: #3E1E05;">{{ $annee }}</h5>
                    <div class="d-flex justify-content-center gap-2 mt-auto">
                        <a href="{{ route('admin.resultats.show', $annee) }}"
                           class="btn btn-sm btn-outline-secondary rounded-pill px-3"
                           title="Voir les résultats">
                            <i class="bi bi-eye me-1"></i>
                        </a>
                        <form action="{{ route('admin.resultats.destroy', $annee) }}" method="POST"
                              onsubmit="return confirm('Supprimer tous les résultats de {{ $annee }} ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                    title="Supprimer les résultats">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
{{-- Aucun résultat --}}
@else
    <div class="text-center py-5 text-muted">
        <i class="bi bi-inbox fs-1 d-block mb-2" style="color: #CA7B05;"></i>
        <p class="mb-0">Aucun résultat pour le moment.</p>
        <small>Passez le vote en mode <strong>cloturé</strong> depuis le panneau de contrôle pour générer les résultats.</small>
    </div>
@endif
@endsection
