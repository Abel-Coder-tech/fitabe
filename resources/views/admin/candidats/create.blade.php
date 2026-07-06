@extends('layouts.admin')

@section('title', 'Nouveau candidat')

@section('content')
{{-- Titre --}}
<h1 class="mb-4">Nouveau candidat</h1>

{{-- Formulaire de création --}}
<form method="POST" action="{{ route('admin.candidats.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Nom</label>
            <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror" value="{{ old('nom') }}" required>
            @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Catégorie</label>
            <input type="text" name="categorie" class="form-control @error('categorie') is-invalid @enderror" value="{{ old('categorie') }}" required>
            @error('categorie') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Numéro de scène</label>
            <input type="number" name="numero_scene" class="form-control" value="{{ old('numero_scene') }}">
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Photo</label>
        <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror">
        @error('photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Biographie</label>
        <textarea name="biographie" rows="5" class="form-control">{{ old('biographie') }}</textarea>
    </div>
    <button type="submit" class="btn btn-primary">Enregistrer</button>
    <a href="{{ route('admin.candidats.index') }}" class="btn btn-secondary">Annuler</a>
</form>
@endsection
