@extends('layouts.public')

@section('title', 'Accueil - ' . config('app.name', 'Fitabe'))

@section('content')
<div class="container">
    <div class="row align-items-center min-vh-75">
        <div class="col-lg-6 mb-4 mb-lg-0">
            <h1 class="display-4 fw-bold">Bienvenue sur Fitabe</h1>
            <p class="lead text-muted">La plateforme de vote pour découvrir et soutenir vos talents préférés.</p>
            <ul class="list-unstyled mt-4">
                <li class="d-flex align-items-center gap-3 py-2">
                    <span class="badge bg-primary rounded-pill fs-6">&check;</span>
                    <span>Consultez les candidats et leurs programmes</span>
                </li>
                <li class="d-flex align-items-center gap-3 py-2">
                    <span class="badge bg-primary rounded-pill fs-6">&check;</span>
                    <span>Votez pour vos candidats favoris</span>
                </li>
                <li class="d-flex align-items-center gap-3 py-2">
                    <span class="badge bg-primary rounded-pill fs-6">&check;</span>
                    <span>Suivez les résultats en temps réel</span>
                </li>
            </ul>
            <a href="{{ route('public.vote') }}" class="btn btn-primary btn-lg mt-3">Voter maintenant</a>
        </div>
        <div class="col-lg-6 text-center">
            <div class="p-5 bg-light rounded-4">
                <p class="text-muted mb-0">Contenu à venir</p>
            </div>
        </div>
    </div>
</div>
@endsection
