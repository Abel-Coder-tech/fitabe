@extends('layouts.admin')

@section('title', 'Détail du vote')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Détail du vote</h1>
    <a href="{{ route('admin.votes.index') }}" class="btn btn-secondary">Retour</a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th style="width: 200px;">Candidat</th>
                <td>{{ $vote->candidat?->nom ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Nom du votant</th>
                <td>{{ $vote->nom_votant ?? $vote->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $vote->email }}</td>
            </tr>
            <tr>
                <th>Téléphone</th>
                <td>{{ $vote->telephone ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Statut</th>
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
            </tr>
            <tr>
                <th>IP</th>
                <td>{{ $vote->ip ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Date</th>
                <td>{{ $vote->created_at->format('d/m/Y H:i') }}</td>
            </tr>
        </table>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">Modifier le statut</div>
    <div class="card-body">
        <form action="{{ route('admin.votes.update', $vote) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="statut" class="form-label">Statut</label>
                <select name="statut" id="statut" class="form-select @error('statut') is-invalid @enderror">
                    <option value="en_attente" {{ old('statut', $vote->statut) == 'en_attente' ? 'selected' : '' }}>En attente</option>
                    <option value="confirme" {{ old('statut', $vote->statut) == 'confirme' ? 'selected' : '' }}>Confirmé</option>
                    <option value="rejete" {{ old('statut', $vote->statut) == 'rejete' ? 'selected' : '' }}>Rejeté</option>
                </select>
                @error('statut')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
    </div>
</div>
@endsection
