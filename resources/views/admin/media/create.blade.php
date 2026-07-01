@extends('layouts.admin')

@section('title', 'Nouveau média')

@section('content')
<h1 class="mb-4">Nouveau média</h1>

<form method="POST" action="{{ route('admin.medias.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Titre</label>
            <input type="text" name="titre" class="form-control @error('titre') is-invalid @enderror" value="{{ old('titre') }}">
            @error('titre') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Type <span class="text-danger">*</span></label>
            <input type="text" name="type" class="form-control @error('type') is-invalid @enderror" value="{{ old('type') }}" required>
            @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Fichier <span class="text-danger">*</span></label>
        <input type="file" name="fichier" class="form-control @error('fichier') is-invalid @enderror" required>
        @error('fichier') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Candidat</label>
        <select name="candidat_id" class="form-select @error('candidat_id') is-invalid @enderror">
            <option value="">— Aucun —</option>
            @foreach ($candidats as $candidat)
                <option value="{{ $candidat->id }}" {{ old('candidat_id') == $candidat->id ? 'selected' : '' }}>{{ $candidat->nom }}</option>
            @endforeach
        </select>
        @error('candidat_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <button type="submit" class="btn btn-primary">Enregistrer</button>
    <a href="{{ route('admin.medias.index') }}" class="btn btn-secondary">Annuler</a>
</form>
@endsection
