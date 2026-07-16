@extends('layouts.admin')

@section('title', $programme->titre)

@section('content')
{{-- En-tête --}}
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div class="d-flex align-items-center gap-3">
        @if ($programme->icone)
            <div style="width: 48px; height: 48px; border-radius: 14px; background: {{ ($programme->couleur_bordure ?? '#9B4D07') }}20; display: flex; align-items: center; justify-content: center;">
                <i class="bi {{ $programme->icone }}" style="color: {{ $programme->couleur_bordure ?? '#9B4D07' }}; font-size: 1.4rem;"></i>
            </div>
        @endif
        <div>
            <h1 class="h3 mb-0" style="color: #3E1E05;">{{ $programme->titre }}</h1>
            <p class="small text-muted mb-0">
                @if ($programme->categorie)
                    {{ $programme->categorie }} —
                @endif
                @if ($programme->est_actif)
                    <span class="badge rounded-pill" style="background: #e6f7e6; color: #2e7d32;">Actif</span>
                @else
                    <span class="badge rounded-pill" style="background: #f5f5f5; color: #999;">Inactif</span>
                @endif
            </p>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.programmes.edit', $programme) }}" class="btn" style="background: #fef0e0; color: #9B4D07;">
            <i class="bi bi-pencil"></i> Modifier
        </a>
        <a href="{{ route('admin.programmes.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>
</div>

<div class="row g-4">

    {{-- Colonne infos principales --}}
    <div class="col-lg-7">
        {{-- Carte description --}}
        @if ($programme->description)
        <div class="card border-0 rounded-4 shadow-sm mb-4">
            <div class="card-body p-4">
                <h6 class="fw-semibold mb-3" style="color: #3E1E05;">
                    <i class="bi bi-card-text me-2" style="color: #9B4D07;"></i>Description
                </h6>
                <p class="small mb-0" style="color: #555; line-height: 1.7;">{{ $programme->description }}</p>
            </div>
        </div>
        @endif

        {{-- Carte sous-dates --}}
        @if ($programme->dates->count() > 0)
        <div class="card border-0 rounded-4 shadow-sm">
            <div class="card-header bg-transparent border-bottom d-flex align-items-center justify-content-between px-4 py-3">
                <h6 class="fw-semibold mb-0" style="color: #3E1E05;">
                    <i class="bi bi-calendar-event me-2" style="color: #9B4D07;"></i>Sous-dates
                    <span class="badge rounded-pill ms-2" style="background: #fef0e0; color: #9B4D07; font-size: 0.65rem;">{{ $programme->dates->count() }}</span>
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @foreach ($programme->dates as $sd)
                    <div class="list-group-item px-4 py-3 d-flex align-items-center gap-3" style="border-color: #f5f5f5;">
                        <div style="width: 32px; height: 32px; border-radius: 10px; background: {{ ($programme->couleur_bordure ?? '#9B4D07') }}15; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="bi bi-clock" style="color: {{ $programme->couleur_bordure ?? '#9B4D07' }}; font-size: 0.8rem;"></i>
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <span class="fw-semibold small" style="color: #3E1E05;">{{ $sd->titre ?? 'Événement' }}</span>
                            <div class="small text-muted">
                                {{ $sd->date->format('d/m/Y H:i') }}
                                @if ($sd->lieu)
                                    — {{ $sd->lieu }}
                                @endif
                            </div>
                        </div>
                        <span class="small text-muted" style="white-space: nowrap;">Ordre {{ $sd->ordre }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Colonne infos secondaires --}}
    <div class="col-lg-5">
        <div class="card border-0 rounded-4 shadow-sm">
            <div class="card-body p-4">
                <h6 class="fw-semibold mb-3" style="color: #3E1E05;">
                    <i class="bi bi-info-circle me-2" style="color: #9B4D07;"></i>Détails
                </h6>

                {{-- Date --}}
                <div class="d-flex align-items-center gap-3 py-2 border-bottom" style="border-color: #f5f5f5 !important;">
                    <div style="width: 36px; height: 36px; border-radius: 10px; background: #fef0e0; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="bi bi-calendar3" style="color: #9B4D07; font-size: 0.9rem;"></i>
                    </div>
                    <div>
                        <span class="small text-muted d-block" style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px;">Date</span>
                        <span class="fw-semibold small" style="color: #3E1E05;">{{ \Carbon\Carbon::parse($programme->date_programme)->format('d/m/Y H:i') }}</span>
                    </div>
                </div>

                {{-- Lieu --}}
                <div class="d-flex align-items-center gap-3 py-2 border-bottom" style="border-color: #f5f5f5 !important;">
                    <div style="width: 36px; height: 36px; border-radius: 10px; background: #fef0e0; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="bi bi-geo-alt" style="color: #9B4D07; font-size: 0.9rem;"></i>
                    </div>
                    <div>
                        <span class="small text-muted d-block" style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px;">Lieu</span>
                        <span class="fw-semibold small" style="color: #3E1E05;">{{ $programme->lieu ?? 'Non renseigné' }}</span>
                    </div>
                </div>

                {{-- Catégorie --}}
                <div class="d-flex align-items-center gap-3 py-2 border-bottom" style="border-color: #f5f5f5 !important;">
                    <div style="width: 36px; height: 36px; border-radius: 10px; background: #fef0e0; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="bi bi-tag" style="color: #9B4D07; font-size: 0.9rem;"></i>
                    </div>
                    <div>
                        <span class="small text-muted d-block" style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px;">Catégorie</span>
                        <span class="fw-semibold small" style="color: #3E1E05;">{{ $programme->categorie ?? 'Non définie' }}</span>
                    </div>
                </div>

                {{-- Ordre --}}
                <div class="d-flex align-items-center gap-3 py-2 border-bottom" style="border-color: #f5f5f5 !important;">
                    <div style="width: 36px; height: 36px; border-radius: 10px; background: #fef0e0; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="bi bi-sort-numeric-up" style="color: #9B4D07; font-size: 0.9rem;"></i>
                    </div>
                    <div>
                        <span class="small text-muted d-block" style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px;">Ordre d'affichage</span>
                        <span class="fw-semibold small" style="color: #3E1E05;">{{ $programme->ordre }}</span>
                    </div>
                </div>

                {{-- Icône et couleur --}}
                <div class="d-flex align-items-center gap-3 py-2">
                    <div style="width: 36px; height: 36px; border-radius: 10px; background: {{ ($programme->couleur_bordure ?? '#9B4D07') }}20; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        @if ($programme->icone)
                            <i class="bi {{ $programme->icone }}" style="color: {{ $programme->couleur_bordure ?? '#9B4D07' }}; font-size: 0.9rem;"></i>
                        @else
                            <i class="bi bi-palette" style="color: {{ $programme->couleur_bordure ?? '#9B4D07' }}; font-size: 0.9rem;"></i>
                        @endif
                    </div>
                    <div>
                        <span class="small text-muted d-block" style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px;">Apparence</span>
                        <span class="fw-semibold small" style="color: #3E1E05;">
                            @if ($programme->icone)
                                <i class="bi {{ $programme->icone }} me-1"></i>
                            @endif
                            <span style="display: inline-block; width: 12px; height: 12px; background: {{ $programme->couleur_bordure ?? '#9B4D07' }}; border-radius: 4px; vertical-align: middle;"></span>
                            {{ $programme->couleur_bordure ?? 'Défaut' }}
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection