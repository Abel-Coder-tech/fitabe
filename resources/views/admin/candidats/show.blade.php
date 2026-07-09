@extends('layouts.admin')

@section('title', $candidat->nom)

@section('content')
<h1>{{ $candidat->display_name }}</h1>

<div class="row mt-4">
    <div class="col-md-4">
        @if ($candidat->photo)
            <img src="{{ $candidat->photo_url }}" class="img-fluid rounded" alt="{{ $candidat->nom }}">
        @endif
    </div>
    <div class="col-md-8">
        <table class="table">
            <tr><th>Nom</th><td>{{ $candidat->nom }}</td></tr>
            <tr><th>Nom de scène</th><td>{{ $candidat->nom_scene ?? '-' }}</td></tr>
            <tr><th>Catégorie</th><td>{{ $candidat->categorie }}</td></tr>
            <tr><th>Numéro de scène</th><td>{{ $candidat->numero_scene ?? '-' }}</td></tr>
            <tr><th>Ovations</th><td>{{ $candidat->nombre_votes }}</td></tr>
            <tr><th>Classement</th><td>{{ $candidat->rank ?? '-' }}</td></tr>
            <tr><th>Biographie</th><td>{{ $candidat->biographie ?? '-' }}</td></tr>
        </table>
        <a href="{{ route('admin.candidats.edit', $candidat) }}" class="btn btn-warning">Modifier</a>
        <a href="{{ route('admin.candidats.index') }}" class="btn btn-secondary">Retour</a>
    </div>
</div>
@endsection
