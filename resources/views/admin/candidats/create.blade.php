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
            <label class="form-label">Nom de scène</label>
            <input type="text" name="nom_scene" class="form-control @error('nom_scene') is-invalid @enderror" value="{{ old('nom_scene') }}">
            @error('nom_scene') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Catégorie</label>
            <select name="categorie" class="form-select @error('categorie') is-invalid @enderror" required>
                <option value="">— Sélectionner une catégorie —</option>
                @foreach (\App\Models\Candidats::CATEGORIES as $cat)
                    <option value="{{ $cat }}" {{ old('categorie') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            @error('categorie') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Numéro de scène</label>
            <input type="number" name="numero_scene" class="form-control @error('numero_scene') is-invalid @enderror" value="{{ old('numero_scene') }}">
            @error('numero_scene') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Photo</label>
        <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror">
        @error('photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Biographie</label>
        <textarea name="biographie" rows="5" class="form-control @error('biographie') is-invalid @enderror">{{ old('biographie') }}</textarea>
        @error('biographie') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <button type="submit" class="btn btn-primary">Enregistrer</button>
    <a href="{{ route('admin.candidats.index') }}" class="btn btn-secondary">Annuler</a>
</form>
@endsection
