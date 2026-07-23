@extends('layouts.admin')

@section('title', 'Nouvel utilisateur')

@section('content')
<h1 class="mb-4">Nouvel utilisateur</h1>

<form method="POST" action="{{ route('admin.users.store') }}" novalidate>
    @csrf
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Nom <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Mot de passe <span class="text-danger">*</span></label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Confirmer le mot de passe <span class="text-danger">*</span></label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Rôle</label>
        <input type="hidden" name="role" value="editor">
        <div class="form-control bg-light" style="cursor: default;">
            <span class="badge rounded-pill" style="background: #0d6efd; font-size: 0.75rem;">Éditeur</span>
            <small class="text-muted ms-2">Accès limité à la gestion des contenus</small>
        </div>
        @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <button type="submit" class="btn btn-primary">Enregistrer</button>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Annuler</a>
</form>
@endsection
