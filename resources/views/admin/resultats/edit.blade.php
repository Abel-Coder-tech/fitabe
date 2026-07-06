@extends('layouts.admin')

@section('title', 'Noter - ' . $resultat->candidat_nom)

@section('content')
{{-- Attribution note jury --}}
<div class="card border-0 shadow-sm rounded-4 mx-auto" style="max-width: 500px;">
    <div class="card-body p-4">
        <a href="{{ route('admin.resultats.show', $resultat->annee_edition) }}" class="text-muted text-decoration-none small">
            <i class="bi bi-arrow-left me-1"></i> Retour
        </a>
        <h5 class="fw-bold mt-2" style="color: #3E1E05;">
            {{ $resultat->candidat_nom }}
            <span class="badge fs-6" style="background: {{ $resultat->prix === 1 ? '#FFD700' : ($resultat->prix === 2 ? '#C0C0C0' : '#CD7F32') }}; color: #3E1E05; vertical-align: middle;">{{ $resultat->prix_label }}</span>
        </h5>
        <p class="text-muted small mb-3">{{ $resultat->categorie }} · {{ $resultat->annee_edition }} · {{ $resultat->nombre_votes }} votes</p>

        {{-- Formulaire de notation --}}
        <form method="POST" action="{{ route('admin.resultats.update', $resultat) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-semibold">Note du jury <small class="text-muted">(sur 20)</small></label>
                <input type="number" name="note_jury" class="form-control @error('note_jury') is-invalid @enderror"
                       value="{{ old('note_jury', $resultat->note_jury) }}" step="0.5" min="0" max="20">
                @error('note_jury') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Aperçu du score final --}}
            @if ($resultat->note_jury !== null && $resultat->score_public !== null)
                <div class="p-3 rounded-3 mb-3" style="background: #fdfaf5; border: 1px solid #E3D5AD;">
                    <small class="text-muted d-block">Aperçu du score final</small>
                    <div class="d-flex justify-content-between mt-1">
                        <span>Note jury (60%) : <strong>{{ $resultat->note_jury }}</strong></span>
                        <span>Score public (40%) : <strong>{{ $resultat->score_public }}</strong></span>
                        <span>Final : <strong style="color: #9B4D07;">{{ $resultat->score_final }}</strong></span>
                    </div>
                </div>
            @endif

            <button type="submit" class="btn w-100 text-white fw-semibold border-0 rounded-pill py-2" style="background: #9B4D07;">
                <i class="bi bi-check-lg me-1"></i> Enregistrer
            </button>
        </form>
    </div>
</div>
@endsection
