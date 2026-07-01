@extends('layouts.admin')

@section('title', 'Modifier le programme')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Modifier le programme</h1>
    <a href="{{ route('admin.programmes.index') }}" class="btn btn-secondary">Annuler</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.programmes.update', $programme) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="titre" class="form-label">Titre <span class="text-danger">*</span></label>
                <input type="text" name="titre" id="titre" class="form-control @error('titre') is-invalid @enderror" value="{{ old('titre', $programme->titre) }}" required>
                @error('titre')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" rows="5" class="form-control @error('description') is-invalid @enderror">{{ old('description', $programme->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="date_programme" class="form-label">Date <span class="text-danger">*</span></label>
                <input type="datetime-local" name="date_programme" id="date_programme" class="form-control @error('date_programme') is-invalid @enderror" value="{{ old('date_programme', $programme->date_programme instanceof \Carbon\Carbon ? $programme->date_programme->format('Y-m-d\TH:i') : $programme->date_programme) }}" required>
                @error('date_programme')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="lieu" class="form-label">Lieu</label>
                <input type="text" name="lieu" id="lieu" class="form-control @error('lieu') is-invalid @enderror" value="{{ old('lieu', $programme->lieu) }}">
                @error('lieu')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="categorie" class="form-label">Catégorie</label>
                <input type="text" name="categorie" id="categorie" class="form-control @error('categorie') is-invalid @enderror" value="{{ old('categorie', $programme->categorie) }}">
                @error('categorie')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="ordre" class="form-label">Ordre</label>
                <input type="number" name="ordre" id="ordre" class="form-control @error('ordre') is-invalid @enderror" value="{{ old('ordre', $programme->ordre ?? 0) }}">
                @error('ordre')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="est_actif" id="est_actif" class="form-check-input" value="1" {{ old('est_actif', $programme->est_actif) ? 'checked' : '' }}>
                <label for="est_actif" class="form-check-label">Actif</label>
            </div>

            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
    </div>
</div>
@endsection
