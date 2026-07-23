@extends('layouts.admin')

@section('title', 'Modifier un utilisateur')

@section('content')
<h1 class="mb-4">Modifier : {{ $user->name }}</h1>

<form method="POST" action="{{ route('admin.users.update', $user) }}" novalidate>
    @csrf @method('PUT')
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Nom <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}">
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}">
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Mot de passe</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <small class="text-muted">Laisser vide pour conserver l'actuel. 8 car. min, 1 maj., 1 min., 1 chiffre.</small>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Rôle</label>
        @if($user->role === 'super_admin')
            <input type="hidden" name="role" value="super_admin">
            <div class="form-control bg-light" style="cursor: default;">
                <span class="badge rounded-pill" style="background: #dc3545; font-size: 0.75rem;">Administrateur</span>
                <small class="text-muted ms-2">Rôle réservé — non modifiable</small>
            </div>
        @else
            <input type="hidden" name="role" value="editor">
            <div class="form-control bg-light" style="cursor: default;">
                <span class="badge rounded-pill" style="background: #0d6efd; font-size: 0.75rem;">Éditeur</span>
                <small class="text-muted ms-2">Accès limité à la gestion des contenus</small>
            </div>
        @endif
        @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <button type="submit" class="btn btn-primary">Mettre à jour</button>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Annuler</a>
</form>
@endsection
