@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Modifier : <code style="color: #9B4D07; font-size: 0.9rem;">{{ $parametre->cle }}</code></h1>
    <a href="{{ route('admin.parametres.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>

<div class="card" style="max-width: 650px;">
    <div class="card-body">
        <form action="{{ route('admin.parametres.update', $parametre) }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-semibold">Clé</label>
                <input type="text" class="form-control" value="{{ $parametre->cle }}" readonly style="background: #f8f9fa;">
            </div>

            @php
                $cle = $parametre->cle;
                $val = old('valeur', $parametre->valeur);
            @endphp

            {{-- Logo upload --}}
            @if ($cle === 'logo_url')
                <div class="mb-3">
                    <label class="form-label">Logo du festival</label>
                    @if ($val)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $val) }}" alt="Logo actuel" height="60" style="object-fit: contain;">
                        </div>
                    @endif
                    <input type="file" name="valeur_file" class="form-control" accept="image/*">
                    <input type="hidden" name="valeur" id="logoUrlHidden" value="{{ $val }}">
                    <small class="text-muted">Laisser vide pour conserver l'image actuelle.</small>
                </div>

            {{-- Date inputs --}}
            @elseif (str_contains($cle, 'date_'))
                <div class="mb-3">
                    <label class="form-label">Valeur</label>
                    <input type="date" name="valeur" class="form-control" value="{{ $val }}">
                </div>

            {{-- Number inputs --}}
            @elseif (in_array($cle, ['prix_ovation', 'seuil_publication_resultats', 'medias_par_page']))
                <div class="mb-3">
                    <label class="form-label">Valeur</label>
                    <input type="number" name="valeur" class="form-control" value="{{ $val }}" min="0">
                </div>

            {{-- URL inputs --}}
            @elseif (str_contains($cle, 'social_') || str_contains($cle, 'contact_telephone'))
                <div class="mb-3">
                    <label class="form-label">Valeur</label>
                    <input type="url" name="valeur" class="form-control" value="{{ $val }}" placeholder="https://...">
                </div>

            {{-- Textarea for long texts --}}
            @elseif (in_array($cle, ['texte_info_vote', 'texte_mediatheque', 'hero_sous_titre']))
                <div class="mb-3">
                    <label class="form-label">Valeur</label>
                    <textarea name="valeur" rows="4" class="form-control">{{ $val }}</textarea>
                </div>

            {{-- Default text input --}}
            @else
                <div class="mb-3">
                    <label class="form-label">Valeur</label>
                    <input type="text" name="valeur" class="form-control" value="{{ $val }}">
                </div>
            @endif

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Mettre à jour
                </button>
                <a href="{{ route('admin.parametres.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
