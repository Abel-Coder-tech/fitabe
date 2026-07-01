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
            <dd class="col-sm-10">{{ $contact->sujet }}</dd>

            <dt class="col-sm-2">Date</dt>
            <dd class="col-sm-10">{{ $contact->created_at->format('d/m/Y H:i') }}</dd>

            <dt class="col-sm-2">Message</dt>
            <dd class="col-sm-10">{{ nl2br(e($contact->message)) }}</dd>
        </dl>
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
