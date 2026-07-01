@extends('layouts.admin')

@section('title', 'Contacts')

@section('content')
<h1 class="mb-4">Contacts</h1>

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
                <td>{{ $contact->id }}</td>
                <td>{{ $contact->nom }}</td>
                <td>{{ $contact->email }}</td>
                <td>{{ $contact->sujet }}</td>
                <td>{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    @if ($contact->lu)
                        <span class="badge bg-success">Lu</span>
                    @else
                        <span class="badge bg-warning text-dark">Non lu</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.contacts.show', $contact) }}" class="btn btn-sm btn-info">Voir</a>
                    <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Supprimer</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center">Aucun message.</td></tr>
        @endforelse
    </tbody>
</table>
{{ $contacts->links() }}
@endsection
