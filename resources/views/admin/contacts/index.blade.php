@extends('layouts.admin')

@section('title', 'Contacts')

@section('content')
{{-- Titre --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Contacts</h1>
    <a href="{{ route('admin.export.contacts') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-download me-1"></i> CSV</a>
</div>

{{-- Liste des messages --}}
<div class="table-responsive">
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Sujet</th>
            <th>Date</th>
            <th>Lu</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($contacts as $contact)
            <tr>
                <td data-label="ID">{{ $contact->id }}</td>
                <td data-label="Nom">{{ $contact->nom }}</td>
                <td data-label="Email">{{ $contact->email }}</td>
                <td data-label="Sujet">{{ $contact->sujet ?: 'Sans sujet' }}</td>
                <td data-label="Date">{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                <td data-label="Statut">
                    @if ($contact->lu)
                        <span class="badge bg-success">Lu</span>
                    @else
                        <span class="badge bg-warning text-dark">Non lu</span>
                    @endif
                </td>
                <td data-label="Actions">
                    <div class="d-flex gap-1">
                        <a href="{{ route('admin.contacts.show', $contact) }}" class="btn btn-sm btn-info">Voir</a>
                        <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Supprimer</button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center py-4 text-muted">Aucun message.</td></tr>
        @endforelse
    </tbody>
</table>
</div>
{{-- Pagination --}}
{{ $contacts->links() }}
@endsection
