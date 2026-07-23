@extends('layouts.admin')

@section('title', 'Modifier un média')

@section('content')
{{-- Titre --}}
<h1 class="mb-4">Modifier le média</h1>

{{-- Formulaire de modification --}}
<form method="POST" action="{{ route('admin.medias.update', $media) }}" enctype="multipart/form-data" novalidate>
    @csrf @method('PUT')

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Type <span class="text-danger">*</span></label>
            <select name="type" id="mediaType" class="form-select @error('type') is-invalid @enderror" required>
                <option value="">— Sélectionner —</option>
                <option value="photo" {{ old('type', $media->type) === 'photo' ? 'selected' : '' }}>Photo</option>
                <option value="video" {{ old('type', $media->type) === 'video' ? 'selected' : '' }}>Vidéo</option>
            </select>
            @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Légende (titre)</label>
            <input type="text" name="titre" class="form-control @error('titre') is-invalid @enderror" value="{{ old('titre', $media->titre) }}">
            @error('titre') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3" id="fieldFichier" style="{{ old('type', $media->type) === 'photo' ? '' : 'display:none;' }}">
            <label class="form-label">Fichier photo</label>
            <input type="file" name="fichier" class="form-control @error('fichier') is-invalid @enderror">
            @error('fichier') <div class="invalid-feedback">{{ $message }}</div> @enderror
            @if ($media->url)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $media->url) }}" height="80">
                    <small class="text-muted d-block">Fichier actuel</small>
                </div>
            @endif
        </div>
        <div class="col-md-6 mb-3" id="fieldYoutube" style="{{ old('type', $media->type) === 'video' ? '' : 'display:none;' }}">
            <label class="form-label">Lien YouTube</label>
            <input type="url" name="lien_youtube" class="form-control @error('lien_youtube') is-invalid @enderror" value="{{ old('lien_youtube', $media->lien_youtube) }}" placeholder="https://www.youtube.com/watch?v=...">
            @error('lien_youtube') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $media->description) }}</textarea>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Année d'édition</label>
            <select name="annee_edition" class="form-select @error('annee_edition') is-invalid @enderror">
                <option value="">—</option>
                @foreach(range(date('Y'), 2020) as $annee)
                    <option value="{{ $annee }}" {{ old('annee_edition', $media->annee_edition) == $annee ? 'selected' : '' }}>{{ $annee }}</option>
                @endforeach
            </select>
            @error('annee_edition') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Mettre à jour</button>
    <a href="{{ route('admin.medias.index') }}" class="btn btn-secondary">Annuler</a>

</form>

@if ($errors->any())
    <div class="alert alert-danger mt-3">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Script : affichage conditionnel champs photo/vidéo --}}
@push('scripts')
<script>
document.getElementById('mediaType')?.addEventListener('change', function() {
    const val = this.value;
    document.getElementById('fieldFichier').style.display = val === 'photo' ? '' : 'none';
    document.getElementById('fieldYoutube').style.display = val === 'video' ? '' : 'none';
});
</script>
@endpush
@endsection
