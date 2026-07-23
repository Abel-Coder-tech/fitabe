@extends('layouts.admin')

@section('title', 'Modifier un candidat')

@section('content')
{{-- Titre --}}
<h1 class="mb-4">Modifier : {{ $candidat->nom }}</h1>

{{-- Formulaire de modification --}}
<form method="POST" action="{{ route('admin.candidats.update', $candidat) }}" enctype="multipart/form-data" novalidate>
    @csrf @method('PUT')
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Nom</label>
            <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror" value="{{ old('nom', $candidat->nom) }}" required>
            @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Nom de scène</label>
            <input type="text" name="nom_scene" class="form-control @error('nom_scene') is-invalid @enderror" value="{{ old('nom_scene', $candidat->nom_scene) }}">
            @error('nom_scene') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Catégorie</label>
            <select name="categorie" class="form-select @error('categorie') is-invalid @enderror" required>
                <option value="">— Sélectionner —</option>
                @foreach (\App\Models\Candidats::CATEGORIES as $cat)
                    <option value="{{ $cat }}" {{ old('categorie', $candidat->categorie) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            @error('categorie') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Numéro de scène</label>
            <input type="number" name="numero_scene" class="form-control @error('numero_scene') is-invalid @enderror" value="{{ old('numero_scene', $candidat->numero_scene) }}">
            @error('numero_scene') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Photo</label>
        <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror">
        @error('photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
        @if ($candidat->photo)
            <div class="mt-2"><img src="{{ $candidat->photo_url }}" height="80"></div>
        @endif
    </div>
    <div class="mb-3">
        <label class="form-label">Biographie</label>
        <textarea name="biographie" rows="5" class="form-control @error('biographie') is-invalid @enderror">{{ old('biographie', $candidat->biographie) }}</textarea>
        @error('biographie') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <button type="submit" class="btn btn-primary">Mettre à jour</button>
    <a href="{{ route('admin.candidats.index') }}" class="btn btn-secondary">Annuler</a>
</form>
@endsection
