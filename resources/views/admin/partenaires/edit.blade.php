@extends('layouts.admin')

@section('content')
{{-- En-tête --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Modifier le partenaire</h1>
    <a href="{{ route('admin.partenaires.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>

{{-- Formulaire de modification --}}
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.partenaires.update', $partenaire) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                <input type="text" name="nom" id="nom" class="form-control @error('nom') is-invalid @enderror" value="{{ old('nom', $partenaire->nom) }}" required>
                @error('nom')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="logo" class="form-label">Logo</label>

                @if ($partenaire->logo)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $partenaire->logo) }}" alt="{{ $partenaire->nom }}" width="100" class="img-thumbnail">
                    </div>
                @endif

                <input type="file" name="logo" id="logo" class="form-control @error('logo') is-invalid @enderror">
                @error('logo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="site_web" class="form-label">Site web</label>
                <input type="url" name="site_web" id="site_web" class="form-control @error('site_web') is-invalid @enderror" value="{{ old('site_web', $partenaire->site_web) }}">
                @error('site_web')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $partenaire->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Mettre à jour
                </button>
                <a href="{{ route('admin.partenaires.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
