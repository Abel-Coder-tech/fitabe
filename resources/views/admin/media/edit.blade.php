@extends('layouts.admin')

@section('title', 'Modifier un média')

@section('content')
<h1 class="mb-4">Modifier le média</h1>

<form method="POST" action="{{ route('admin.medias.update', $media) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Titre</label>
            <input type="text" name="titre" class="form-control @error('titre') is-invalid @enderror" value="{{ old('titre', $media->titre) }}">
            @error('titre') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Type <span class="text-danger">*</span></label>
            <input type="text" name="type" class="form-control @error('type') is-invalid @enderror" value="{{ old('type', $media->type) }}" required>
            @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Fichier</label>
        <input type="file" name="fichier" class="form-control @error('fichier') is-invalid @enderror">
        @error('fichier') <div class="invalid-feedback">{{ $message }}</div> @enderror
        @if ($media->url)
            <div class="mt-2">
                @if (in_array($media->type, ['image/jpeg', 'image/png', 'image/gif', 'image/webp']))
                    <img src="{{ asset('storage/' . $media->url) }}" height="80">
                @else
                    <span class="text-muted">Fichier actuel : {{ $media->url }}</span>
                @endif
            </div>
        @endif
    </div>
    <div class="mb-3">
        <label class="form-label">Candidat</label>
        <select name="candidat_id" class="form-select @error('candidat_id') is-invalid @enderror">
            <option value="">— Aucun —</option>
            @foreach ($candidats as $candidat)
                <option value="{{ $candidat->id }}" {{ old('candidat_id', $media->candidat_id) == $candidat->id ? 'selected' : '' }}>{{ $candidat->nom }}</option>
            @endforeach
        </select>
        @error('candidat_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <button type="submit" class="btn btn-primary">Mettre à jour</button>
    <a href="{{ route('admin.medias.index') }}" class="btn btn-secondary">Annuler</a>
</form>
@endsection
