@extends('layouts.public')

@section('title', 'Vote - ' . config('app.name', 'Fitabe'))

@section('content')
<div class="container">
    <h1 class="mb-4">Votez pour vos candidats</h1>

    @forelse ($candidats as $categorie => $candidatsCategorie)
        <h2 class="h4 mb-3 text-secondary">{{ $categorie }}</h2>
        <div class="row mb-5">
            @foreach ($candidatsCategorie as $candidat)
                <div class="col-md-4 col-lg-3 mb-4">
                    @include('components.candidate-card', ['candidat' => $candidat])
                    <div class="text-center mt-2">
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#voteModal{{ $candidat->id }}">
                            Voter
                        </button>
                    </div>
                    @include('components.vote-modal', ['candidat' => $candidat])
                </div>
            @endforeach
        </div>
    @empty
        <div class="alert alert-info">Aucun candidat pour le moment.</div>
    @endforelse
</div>
@endsection
