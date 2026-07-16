@extends('layouts.admin')

@section('title', 'Utilisateurs')

@section('content')
{{-- En-tête --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Utilisateurs</h1>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Nouvel éditeur</a>
</div>

<div class="alert alert-info py-2 px-3 mb-3" style="font-size: 0.85rem; border-radius: 8px;">
    <i class="bi bi-info-circle me-1"></i>
    Vous pouvez uniquement créer des <strong>éditeurs</strong> avec un accès limité à la gestion des contenus.
    Le compte administrateur principal est unique et non modifiable.
</div>

{{-- Liste des utilisateurs --}}
<div class="table-responsive">
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($users as $user)
            <tr>
                <td data-label="ID">{{ $user->id }}</td>
                <td data-label="Nom">{{ $user->name }}</td>
                <td data-label="Email">{{ $user->email }}</td>
                <td data-label="Rôle">
                    @if($user->role === 'super_admin')
                        <span class="badge rounded-pill" style="background: #dc3545; font-size: 0.7rem;">Admin</span>
                    @else
                        <span class="badge rounded-pill" style="background: #0d6efd; font-size: 0.7rem;">Éditeur</span>
                    @endif
                </td>
                <td data-label="Date">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                <td data-label="Actions">
                    <div class="d-flex gap-1">
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning">Modifier</a>
                        @if (auth()->id() !== $user->id && $user->role !== 'super_admin')
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Supprimer</button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center py-4 text-muted">Aucun utilisateur.</td></tr>
        @endforelse
    </tbody>
</table>
</div>
{{-- Pagination --}}
{{ $users->links() }}
@endsection
