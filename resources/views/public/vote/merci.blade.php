@extends('layouts.public')

@section('title', 'Merci pour votre vote - ' . config('app.name', 'Fitabe'))

@push('styles')
<style>
    .merci-icon {
        font-size: 4rem;
        color: #198754;
    }
</style>
@endpush

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center py-5">
                <div class="merci-icon mb-3">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <h1 class="fw-bold mb-3" style="color: #9B4D07;">Merci pour votre vote !</h1>
                <p class="lead text-muted mb-4">
                    Votre soutien compte énormément pour les artistes du FITAB.
                </p>

                @if($vote)
                    <div class="card shadow-sm border-0 bg-light mb-4">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                @if($vote->candidat && $vote->candidat->photo)
                                    <img src="{{ $vote->candidat->photo_url }}" alt="" class="rounded-circle" width="64" height="64" style="object-fit:cover;">
                                @endif
                                <div class="text-start">
                                    <strong class="d-block">{{ $vote->candidat?->display_name ?? 'Candidat' }}</strong>
                                    <small class="text-muted">{{ $vote->quantite }} vote(s) • {{ number_format($vote->montant, 0, ',', ' ') }} FCFA</small>
                                </div>
                            </div>
                            <hr>
                            <div class="small text-muted">
                                <div>Nom : <strong>{{ $vote->votant_nom }}</strong></div>
                                <div>Email : <strong>{{ $vote->votant_email }}</strong></div>
                                @if($vote->transaction_id)
                                    <div>Transaction : <strong>{{ $vote->transaction_id }}</strong></div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('public.vote') }}" class="btn btn-fitab-outline fw-semibold px-4 rounded-pill">
                        <i class="bi bi-arrow-left me-1"></i> Retour au vote
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-fitab fw-semibold px-4 rounded-pill">
                        Accueil
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
