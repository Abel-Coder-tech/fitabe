@extends('layouts.admin')

@section('title', 'Votes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Votes</h1>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Candidat</th>
                <th>Votant</th>
                <th>Email</th>
                <th>Statut</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($votes as $vote)
                <tr>
                    <td>{{ $vote->id }}</td>
                    <td>{{ $vote->candidat?->nom ?? 'N/A' }}</td>
                    <td>{{ $vote->nom_votant ?? $vote->name ?? 'N/A' }}</td>
                    <td>{{ $vote->email }}</td>
                    <td>
                        @php
                            $badgeClass = match($vote->statut) {
                                'confirme' => 'success',
                                'rejete' => 'danger',
                                default => 'warning',
                            };
                        @endphp
                        <span class="badge bg-{{ $badgeClass }}">
                            {{ match($vote->statut) {
                                'confirme' => 'Confirmé',
                                'rejete' => 'Rejeté',
                                default => 'En attente',
                            } }}
                        </span>
                    </td>
                    <td>{{ $vote->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.votes.show', $vote) }}" class="btn btn-sm btn-info">Voir</a>
                        <form action="{{ route('admin.votes.destroy', $vote) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce vote ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Aucun vote trouvé.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $votes->links() }}
@endsection
