@extends('layouts.admin')

@section('title', 'Message de ' . $contact->nom)

@section('content')
<h1 class="mb-4">Message de {{ $contact->nom }}</h1>

<div class="card">
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-2">Nom</dt>
            <dd class="col-sm-10">{{ $contact->nom }}</dd>

            <dt class="col-sm-2">Email</dt>
            <dd class="col-sm-10">{{ $contact->email }}</dd>

            <dt class="col-sm-2">Sujet</dt>
            <dd class="col-sm-10">{{ $contact->sujet ?: 'Sans sujet' }}</dd>

            <dt class="col-sm-2">Date</dt>
            <dd class="col-sm-10">{{ $contact->created_at->format('d/m/Y H:i') }}</dd>

            <dt class="col-sm-2">Message</dt>
            <dd class="col-sm-10">{{ nl2br(e($contact->message)) }}</dd>
        </dl>
    </div>
</div>

<div class="mt-3 d-flex gap-2 flex-wrap">
    <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary">Retour</a>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#replyModal">
        <i class="bi bi-reply-fill me-1"></i> Répondre
    </button>
    <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?')">
        @csrf @method('DELETE')
        <button class="btn btn-danger">Supprimer</button>
    </form>
</div>

{{-- Modal Réponse --}}
<div class="modal fade" id="replyModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">
            <form method="POST" action="{{ route('admin.contacts.reply', $contact) }}">
                @csrf
                <div class="modal-header border-0" style="background: linear-gradient(135deg, #3E1E05, #9B4D07);">
                    <h6 class="fw-bold text-white mb-0">Répondre à {{ $contact->nom }}</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="small text-muted mb-3">
                        <strong>Sujet original :</strong> {{ $contact->sujet ?: 'Sans sujet' }}<br>
                        <strong>Message :</strong><br>
                        {{ Str::limit($contact->message, 200) }}
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small" style="color: #3E1E05;">Votre réponse</label>
                        <textarea name="reponse" rows="6" class="form-control" placeholder="Écrivez votre réponse..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send-fill me-1"></i> Envoyer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
