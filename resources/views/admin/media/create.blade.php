@extends('layouts.admin')

@section('title', 'Nouveau média')

@section('content')
{{-- Titre --}}
<h1 class="mb-4">Nouveau média</h1>

{{-- Formulaire de création --}}
<form method="POST" action="{{ route('admin.medias.store') }}" enctype="multipart/form-data" novalidate>
    @csrf

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Type <span class="text-danger">*</span></label>
            <select name="type" id="mediaType" class="form-select @error('type') is-invalid @enderror" required>
                <option value="">— Sélectionner —</option>
                <option value="photo" {{ old('type') === 'photo' ? 'selected' : '' }}>Photo</option>
                <option value="video" {{ old('type') === 'video' ? 'selected' : '' }}>Vidéo</option>
            </select>
            @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Légende (titre)</label>
            <input type="text" name="titre" class="form-control @error('titre') is-invalid @enderror" value="{{ old('titre') }}">
            @error('titre') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3" id="fieldFichier" style="display:none;">
            <label class="form-label">Fichier photo <span class="text-danger">*</span></label>
            <input type="file" name="fichier" class="form-control @error('fichier') is-invalid @enderror">
            @error('fichier') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6 mb-3" id="fieldYoutube" style="display:none;">
            <label class="form-label">Lien YouTube <span class="text-danger">*</span></label>
            <input type="url" name="lien_youtube" class="form-control @error('lien_youtube') is-invalid @enderror" value="{{ old('lien_youtube') }}" placeholder="https://www.youtube.com/watch?v=...">
            @error('lien_youtube') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Année d'édition</label>
            <select name="annee_edition" class="form-select @error('annee_edition') is-invalid @enderror">
                <option value="">—</option>
                @foreach(range(date('Y'), 2020) as $annee)
                    <option value="{{ $annee }}" {{ old('annee_edition') == $annee ? 'selected' : '' }}>{{ $annee }}</option>
                @endforeach
            </select>
            @error('annee_edition') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Enregistrer</button>
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
    document.querySelector('#fieldFichier input').required = val === 'photo';
    document.querySelector('#fieldYoutube input').required = val === 'video';
});
</script>
@endpush
@endsection
