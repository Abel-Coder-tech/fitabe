@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Modifier le paramètre</h1>
    <a href="{{ route('admin.parametres.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.parametres.update', $parametre) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="cle" class="form-label">Clé</label>
                <input type="text" name="cle" id="cle" class="form-control @error('cle') is-invalid @enderror" value="{{ old('cle', $parametre->cle) }}" readonly>
                @error('cle')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="valeur" class="form-label">Valeur</label>
                <textarea name="valeur" id="valeur" rows="5" class="form-control @error('valeur') is-invalid @enderror">{{ old('valeur', $parametre->valeur) }}</textarea>
                @error('valeur')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Mettre à jour
                </button>
                <a href="{{ route('admin.parametres.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
