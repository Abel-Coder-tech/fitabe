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
        <form action="{{ route('admin.soutiens.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf

            <div class="mb-3">
                <label for="nom" class="form-label">Nom complet <span class="text-danger">*</span></label>
                <input type="text" name="nom" id="nom" class="form-control @error('nom') is-invalid @enderror" value="{{ old('nom') }}" placeholder="Ex: Jean Dupont">
                @error('nom')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="photo" class="form-label">Photo <span class="text-danger">*</span></label>
                <input type="file" name="photo" id="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/*">
                @error('photo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="titre" class="form-label">Titre / Fonction</label>
                    <input type="text" name="titre" id="titre" class="form-control @error('titre') is-invalid @enderror" value="{{ old('titre') }}" placeholder="Ex: Ministre de la Culture">
                    @error('titre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Poste ou qualité du soutien</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="role_parrain" class="form-label">Rôle événement</label>
                    <input type="text" name="role_parrain" id="role_parrain" class="form-control @error('role_parrain') is-invalid @enderror" value="{{ old('role_parrain') }}" placeholder="Ex: Parrain, Marraine, Mécène">
                    @error('role_parrain')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Son rôle lors de l'événement FITAB</small>
                </div>
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
