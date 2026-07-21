@extends('layouts.admin')

@section('title', 'Message de ' . $contact->nom)

@section('content')
<h1 class="mb-4">Message de {{ $contact->nom }}</h1>

<div class="card border-0 rounded-4 shadow-sm mb-4">
    <div class="card-body p-4">
        <dl class="row mb-0">
            <dt class="col-sm-2">Nom</dt>
            <dd class="col-sm-10">{{ $contact->nom }}</dd>

            <dt class="col-sm-2">Email</dt>
            <dd class="col-sm-10"><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></dd>

            <dt class="col-sm-2">Sujet</dt>
            <dd class="col-sm-10">{{ $contact->sujet ?: 'Sans sujet' }}</dd>

            <dt class="col-sm-2">Date</dt>
            <dd class="col-sm-10">{{ $contact->created_at->format('d/m/Y H:i') }}</dd>

            <dt class="col-sm-2">Message</dt>
            <dd class="col-sm-10">{{ nl2br(e($contact->message)) }}</dd>
        </dl>
    </div>
</div>

<div class="card border-0 rounded-4 shadow-sm mb-4">
    <div class="card-header bg-transparent border-bottom px-4 py-3">
        <h5 class="fw-semibold mb-0" style="color: #3E1E05;">Répondre</h5>
    </div>
    <div class="card-body p-4">
        <form method="POST" action="{{ route('admin.contacts.respond', $contact) }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Votre réponse</label>
                <textarea name="reponse" rows="6" class="form-control @error('reponse') is-invalid @enderror" placeholder="Écrivez votre réponse ici..." required></textarea>
                @error('reponse') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i> Envoyer la réponse</button>
        </form>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary">Retour</a>
    <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?')">
        @csrf @method('DELETE')
        <button class="btn btn-danger">Supprimer</button>
    </form>
</div>
@endsection
