@extends('layouts.admin')

@section('title', $programme->titre)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>{{ $programme->titre }}</h1>
    <div>
        <a href="{{ route('admin.programmes.edit', $programme) }}" class="btn btn-warning">Modifier</a>
        <a href="{{ route('admin.programmes.index') }}" class="btn btn-secondary">Retour</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Icône :</strong>
                @if ($programme->icone)
                    <i class="bi {{ $programme->icone }}" style="color: {{ $programme->couleur_bordure ?? '#9B4D07' }};"></i>
                @else
                    —
                @endif
            </div>
            <div class="col-md-6">
                <strong>Couleur :</strong>
                <span style="display:inline-block;width:20px;height:20px;background:{{ $programme->couleur_bordure ?? '#9B4D07' }};border-radius:4px;vertical-align:middle;"></span>
                {{ $programme->couleur_bordure ?? '—' }}
            </div>
        </div>
        <p><strong>Description :</strong> {{ $programme->description ?? '—' }}</p>
        <p><strong>Date :</strong> {{ \Carbon\Carbon::parse($programme->date_programme)->format('d/m/Y H:i') }}</p>
        <p><strong>Lieu :</strong> {{ $programme->lieu ?? '—' }}</p>
        <p><strong>Catégorie :</strong> {{ $programme->categorie ?? '—' }}</p>
        <p><strong>Ordre :</strong> {{ $programme->ordre }}</p>
        <p><strong>Actif :</strong> {{ $programme->est_actif ? 'Oui' : 'Non' }}</p>

        @if ($programme->dates->count() > 0)
        <hr>
        <h5>Sous-dates ({{ $programme->dates->count() }})</h5>
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Date</th>
                    <th>Lieu</th>
                    <th>Ordre</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($programme->dates as $sd)
                <tr>
                    <td>{{ $sd->titre ?? '—' }}</td>
                    <td>{{ $sd->date->format('d/m/Y H:i') }}</td>
                    <td>{{ $sd->lieu ?? '—' }}</td>
                    <td>{{ $sd->ordre }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
@endsection