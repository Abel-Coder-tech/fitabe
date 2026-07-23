@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Ajouter un soutien</h1>
    <a href="{{ route('admin.soutiens.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.soutiens.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                <input type="text" name="nom" id="nom" class="form-control @error('nom') is-invalid @enderror" value="{{ old('nom') }}" required>
                @error('nom')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="photo" class="form-label">Photo <span class="text-danger">*</span></label>
                <input type="file" name="photo" id="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/*" required>
                @error('photo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="citation" class="form-label">Citation / Phrase</label>
                <textarea name="citation" id="citation" rows="3" class="form-control @error('citation') is-invalid @enderror" placeholder="La phrase ou le mot de ce soutien...">{{ old('citation') }}</textarea>
                @error('citation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="titre" class="form-label">Titre / Fonction</label>
                    <input type="text" name="titre" id="titre" class="form-control @error('titre') is-invalid @enderror" value="{{ old('titre') }}" placeholder="Ex: Président, Directeur...">
                    @error('titre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="organisation" class="form-label">Organisation</label>
                    <input type="text" name="organisation" id="organisation" class="form-control @error('organisation') is-invalid @enderror" value="{{ old('organisation') }}" placeholder="Ex: Fondation X, Ministère...">
                    @error('organisation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="ordre_affichage" class="form-label">Ordre d'affichage</label>
                <input type="number" name="ordre_affichage" id="ordre_affichage" class="form-control @error('ordre_affichage') is-invalid @enderror" value="{{ old('ordre_affichage', 0) }}" min="0">
                @error('ordre_affichage')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Enregistrer
                </button>
                <a href="{{ route('admin.soutiens.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
