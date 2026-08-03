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
            <p class="small text-muted mb-3">Notation sur 100 points : ovation 15 pts · technique 20 pts · originalité 20 pts · présence 20 pts · perfection 25 pts.</p>
            <div class="row g-2 mb-3">
                <div class="col-4">
                    <label class="form-label fw-semibold small">Technique <small class="text-muted">/20</small></label>
                    <input type="number" name="note_technique" class="form-control form-control-sm @error('note_technique') is-invalid @enderror"
                           value="{{ old('note_technique', $resultat->note_technique) }}" step="0.5" min="0" max="20">
                    @error('note_technique')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-4">
                    <label class="form-label fw-semibold small">Originalité <small class="text-muted">/20</small></label>
                    <input type="number" name="note_originalite" class="form-control form-control-sm @error('note_originalite') is-invalid @enderror"
                           value="{{ old('note_originalite', $resultat->note_originalite) }}" step="0.5" min="0" max="20">
                    @error('note_originalite')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-4">
                    <label class="form-label fw-semibold small">Présence <small class="text-muted">/20</small></label>
                    <input type="number" name="note_presence" class="form-control form-control-sm @error('note_presence') is-invalid @enderror"
                           value="{{ old('note_presence', $resultat->note_presence) }}" step="0.5" min="0" max="20">
                    @error('note_presence')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-4">
                    <label class="form-label fw-semibold small">Perfection <small class="text-muted">/25</small></label>
                    <input type="number" name="note_perfection" class="form-control form-control-sm @error('note_perfection') is-invalid @enderror"
                           value="{{ old('note_perfection', $resultat->note_perfection) }}" step="0.5" min="0" max="25">
                    @error('note_perfection')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Aperçu du score final --}}
            @if ($resultat->score_public !== null && $resultat->note_technique !== null && $resultat->note_originalite !== null && $resultat->note_presence !== null && $resultat->note_perfection !== null)
                <div class="p-3 rounded-3 mb-3" style="background: #fdfaf5; border: 1px solid #E3D5AD;">
                    <small class="text-muted d-block">Aperçu du score final</small>
                    <div class="d-flex justify-content-between mt-1 small">
                        <span>Ovation (/15) : <strong>{{ $resultat->score_public }}</strong></span>
                        <span>Technique (/20) : <strong>{{ $resultat->note_technique }}</strong></span>
                        <span>Originalité (/20) : <strong>{{ $resultat->note_originalite }}</strong></span>
                        <span>Présence (/20) : <strong>{{ $resultat->note_presence }}</strong></span>
                        <span>Perfection (/25) : <strong>{{ $resultat->note_perfection }}</strong></span>
                    </div>
                    <div class="mt-2 text-end">
                        <span class="fw-bold" style="color: #9B4D07;">Jury : {{ $resultat->note_jury }}/80 · Final : {{ $resultat->score_final }}/100</span>
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
