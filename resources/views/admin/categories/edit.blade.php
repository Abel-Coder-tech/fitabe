@extends('layouts.admin')

@section('title', 'Modifier la catégorie')

@section('content')
<h1 class="mb-4">Modifier la catégorie</h1>
<form method="POST" action="{{ route('admin.categories.update', $categorie) }}">
    @csrf @method('PUT')
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Nom <span class="text-danger">*</span></label>
            <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror" value="{{ old('nom', $categorie->nom) }}" required>
            @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Ordre</label>
            <input type="number" name="ordre" class="form-control @error('ordre') is-invalid @enderror" value="{{ old('ordre', $categorie->ordre) }}" min="0">
            @error('ordre') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $categorie->description) }}</textarea>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <button type="submit" class="btn btn-primary">Mettre à jour</button>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Annuler</a>
</form>
@endsection
