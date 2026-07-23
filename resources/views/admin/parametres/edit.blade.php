@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Modifier : {{ $label }}</h1>
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

            @if (in_array($cle, ['texte_info_vote', 'texte_mediatheque', 'hero_sous_titre']))
                <div class="mb-3">
                    <label class="form-label fw-semibold">{{ $label }}</label>
                    <textarea name="valeur" rows="4" class="form-control">{{ $val }}</textarea>
                </div>
            @elseif (str_contains($cle, 'social_'))
                <div class="mb-3">
                    <label class="form-label fw-semibold">{{ $label }}</label>
                    <input type="url" name="valeur" class="form-control" value="{{ $val }}" placeholder="https://...">
                </div>
            @else
                <div class="mb-3">
                    <label class="form-label fw-semibold">{{ $label }}</label>
                    <input type="text" name="valeur" class="form-control" value="{{ $val }}">
                </div>
            @endif

            @error('valeur')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Enregistrer
                </button>
                <a href="{{ route('admin.parametres.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
