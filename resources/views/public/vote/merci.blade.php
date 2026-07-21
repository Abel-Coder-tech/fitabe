@extends('layouts.public')

@section('title', 'Merci pour votre ovation - ' . config('app.name', 'FITAB'))

@push('styles')
<style>
    .merci-icon {
        font-size: 4rem;
    }
</style>
@endpush

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center py-5">

                @if($statut === 'confirme')
                    {{-- Paiement confirmé --}}
                    <div class="merci-icon mb-3">
                        <i class="bi bi-check-circle-fill" style="color: #198754;"></i>
                    </div>
                    <h1 class="fw-bold mb-3" style="color: #9B4D07;">Merci pour votre ovation !</h1>
                    <p class="lead text-muted mb-4">
                        Votre soutien compte énormément pour les artistes du FITAB.
                    </p>

                    @if($vote)
                        <div class="card shadow-sm border-0 rounded-4 mb-4" style="background: #fdfaf5;">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    @if($vote->candidat && $vote->candidat->photo)
                                        <img src="{{ $vote->candidat->photo_url }}" alt="{{ $vote->candidat->display_name }}" class="rounded-circle" width="64" height="64" style="object-fit:cover; border: 3px solid #CA7B05;">
                                    @else
                                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:64px;height:64px;background:#fef0e0;">
                                            <i class="bi bi-person-fill" style="color: #9B4D07; font-size: 1.5rem;"></i>
                                        </div>
                                    @endif
                                    <div class="text-start">
                                        <strong class="d-block" style="color: #3E1E05;">{{ $vote->candidat?->display_name ?? 'Candidat' }}</strong>
                                        <small class="text-muted">{{ $vote->quantite }} ovation(s) • {{ number_format($vote->montant, 0, ',', ' ') }} FCFA</small>
                                    </div>
                                </div>
                                @if($vote->transaction_id)
                                    <hr style="border-color: #E3D5AD;">
                                    <div class="small text-muted">
                                        <div>Transaction : <strong style="color: #3E1E05;">{{ $vote->transaction_id }}</strong></div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                @elseif($statut === 'en_attente')
                    {{-- Paiement en attente (webhook pas encore reçu) --}}
                    <div class="merci-icon mb-3">
                        <i class="bi bi-hourglass-split" style="color: #CA7B05;"></i>
                    </div>
                    <h1 class="fw-bold mb-3" style="color: #9B4D07;">Paiement en cours...</h1>
                    <p class="lead text-muted mb-4">
                        Votre ovation est enregistrée, nous attendons la confirmation du paiement.
                        <br><span class="small">La page se mettra à jour automatiquement.</span>
                    </p>
                    <div class="spinner-border mb-3" role="status" style="color: #9B4D07;">
                        <span class="visually-hidden">Vérification du paiement...</span>
                    </div>

                @else
                    {{-- Statut inconnu ou annulé --}}
                    <div class="merci-icon mb-3">
                        <i class="bi bi-x-circle-fill" style="color: #dc3545;"></i>
                    </div>
                    <h1 class="fw-bold mb-3" style="color: #dc3545;">Paiement non abouti</h1>
                    <p class="lead text-muted mb-4">
                        Le paiement n'a pas pu être confirmé. Veuillez réessayer.
                    </p>
                    <a href="{{ route('public.vote') }}" class="btn btn-fitab fw-semibold px-4 rounded-pill">
                        <i class="bi bi-arrow-left me-1"></i> Réessayer
                    </a>
                @endif

                <div class="d-flex justify-content-center gap-3 mt-3">
                    @if($statut !== 'annule')
                        <a href="{{ route('public.vote') }}" class="btn btn-sm rounded-pill px-3" style="background: #fef0e0; color: #9B4D07; font-weight: 600;">
                            <i class="bi bi-arrow-left me-1"></i> Retour aux ovations
                        </a>
                    @endif
                    <a href="{{ route('home') }}" class="btn btn-sm rounded-pill px-3" style="border: 2px solid #9B4D07; color: #9B4D07; font-weight: 600;">
                        Accueil
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@if($statut === 'en_attente')
@push('scripts')
<script>
    // Vérifie toutes les 3 secondes si le vote a été confirmé
    var voteId = {{ $vote ? $vote->id : 'null' }};
    if (voteId) {
        var pollTimer = setInterval(function () {
            fetch('{{ route("public.vote.merci") }}?vote_id=' + voteId + '&check=1')
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (data.statut === 'confirme') {
                        clearInterval(pollTimer);
                        location.reload();
                    }
                })
                .catch(function () {});
        }, 3000);

        // Arrête le polling après 2 minutes
        setTimeout(function () { clearInterval(pollTimer); }, 120000);
    }
</script>
@endpush
@endif