@extends('layouts.public')

@php
    $nomPartage = $candidatPartage?->display_name;
@endphp

@section('title', $candidatPartage ? "Votez pour {$nomPartage} — {$site['edition_nom']}" : 'Ovation - ' . config('app.name', 'FITAB'))

@php
    $description = $candidatPartage
        ? "Soutenez {$nomPartage} en catégorie {$candidatPartage->categorie} au Festival International des Talents Artistiques du Bénin. 1 ovation = {$site['prix_ovation']} {$site['devise']}"
        : 'Soutenez vos artistes préférés au FITAB. Théâtre, Danse, Musique, Percussion et Art visuel.';
    $ogImage = $candidatPartage?->photo ? url('storage/' . $candidatPartage->photo) : asset('images/hero.jpg');
@endphp

@section('description', $description)
@section('og_title', $candidatPartage ? "Votez pour {$nomPartage} — {$site['edition_nom']}" : 'Ovation - FITAB')
@section('og_description', $description)
@section('og_image', $ogImage)
@section('og_url', url('/vote' . ($candidatPartage ? '?candidat=' . $candidatPartage->id : '')))

@push('scripts')
<script src="https://cdn.fedapay.com/checkout.js?v=1.1.3"></script>
@endpush

@push('styles')
<style>
    html, body {
        overflow-x: hidden;
        width: 100%;
    }
    :root {
        --vote-brown: #3E1E05;
        --vote-gold: #9B4D07;
        --vote-gold-light: #CA7B05;
        --vote-cream: #E3D5AD;
    }

    .candidate-card {
        border: none;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
        border-radius: 14px;
        overflow: hidden;
        width: 100%;
        display: flex;
        flex-direction: column;
    }
    @media (max-width: 575.98px) {
        .candidate-card .candidat-cover {
            height: 180px;
        }
    }
    .candidate-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(62,30,5,0.18);
    }
    .shared-highlight .candidate-card {
        box-shadow: 0 0 0 4px var(--vote-gold), 0 12px 32px rgba(62,30,5,0.25);
        transform: translateY(-4px);
    }
    .candidate-card .candidat-num {
        position: absolute;
        top: 12px;
        left: 12px;
        background: rgba(62,30,5,0.85);
        color: #fff;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
        z-index: 2;
        letter-spacing: 0.3px;
    }
    .candidate-card .candidat-cover {
        height: 240px;
        background: linear-gradient(135deg, #3E1E05, #9B4D07);
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .candidate-card .candidat-cover img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .candidate-card .candidat-cover .photo-principale {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 1;
    }
    .candidate-card .card-body {
        flex: 1;
        padding: 0.6rem 0.65rem 0.7rem;
    }
    .candidate-card .vote-count {
        font-size: 1rem;
        font-weight: 700;
        color: var(--vote-brown);
    }
    .candidate-card .categorie-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 3px 10px;
        border-radius: 20px;
        color: #fff;
        z-index: 2;
    }

    .result-card {
        display: flex;
        flex-direction: column;
    }
    .result-cover {
        flex: 0 0 60%;
        max-height: 60%;
        background: linear-gradient(135deg, #3E1E05, #9B4D07);
        position: relative;
        overflow: hidden;
    }
    .result-cover img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .result-body {
        flex: 0 0 40%;
        max-height: 40%;
        padding: 0.4rem 0.5rem;
    }

    .filter-btn {
        border: 2px solid var(--vote-gold-light);
        color: var(--vote-gold-light);
        background: transparent;
        transition: all 0.2s;
        font-weight: 600;
        font-size: 0.85rem;
        border-radius: 50px;
    }
    .filter-btn:hover,
    .filter-btn.active {
        background: var(--vote-gold-light);
        color: #fff;
    }

    .vote-step {
        display: none;
        animation: fadeIn 0.35s ease;
    }
    .vote-step.active {
        display: block;
    }
    @media (min-width: 768px) {
        #step-1.active {
            display: flex !important;
            flex-direction: row !important;
        }
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .payment-option {
        border: 2px solid #dee2e6;
        border-radius: 12px;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.25s;
        text-align: center;
    }
    .payment-option:hover {
        border-color: var(--vote-gold);
        background: #fefbf5;
    }
    .payment-option.selected {
        border-color: var(--vote-gold);
        background: #fefbf5;
    }
    .payment-option img {
        height: 40px;
        margin-bottom: 0.5rem;
    }

    .countdown-item {
        background: rgba(255,255,255,0.10);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 12px;
        padding: 0.65rem 0.9rem;
        min-width: 64px;
        text-align: center;
    }
    .countdown-item .num {
        font-size: 1.5rem;
        font-weight: 800;
        line-height: 1.2;
    }
    .countdown-item .label {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.7;
    }

    .hero-vote {
        position: relative;
        overflow: hidden;
        width: 100%;
        padding: 5rem 0;
        background: linear-gradient(135deg, rgba(62,30,5,0.88) 0%, rgba(62,30,5,0.65) 50%, rgba(62,30,5,0.4) 100%), url('{{ asset('images/hero.jpg') }}') no-repeat center center;
        background-size: cover;
    }
    .hero-vote h1 {
        color: #fff;
    }
    .hero-vote .hero-sub {
        color: rgba(227,213,173,0.85);
    }
    .hero-vote .price-badge {
        display: inline-block;
        background: var(--vote-gold);
        border-radius: 50px;
        padding: 0.5rem 1.5rem;
        font-weight: 600;
        font-size: 1rem;
        color: #fff;
    }
    .hero-vote .btn-vote {
        background: transparent;
        border: 2px solid #E3D5AD;
        color: #E3D5AD;
        font-weight: 700;
        transition: all 0.25s;
    }
    .hero-vote .btn-vote:hover {
        background: #E3D5AD;
        color: #3E1E05;
    }
    .hero-vote .countdown-wrap {
        background: var(--vote-brown);
        border-radius: 16px;
        padding: 1rem 1.25rem;
        color: var(--vote-cream);
    }
    .hero-vote .countdown-wrap .label-text {
        color: rgba(243,234,206,0.6);
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    @media (max-width: 575.98px) {
        .hero-vote .countdown-wrap span {
            font-size: 0.82rem;
        }
    }
</style>
@endpush

@section('content')

{{-- ==================== HERO ==================== --}}
<section class="hero-vote">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <span class="badge fw-semibold px-3 py-2 mb-3 fs-6" style="background-color: #CA7B05; color: #fff;">
                    <i class="bi bi-music-note-beamed me-1"></i> FITAB {{ date('Y') }}
                </span>
                <h1 class="display-4 fw-bold mb-3">
                    Ovationnez votre<br>
                    <span style="color: #CA7B05;">candidat préféré</span>
                </h1>
                @php
                    $now = time();
                    $ouverture = $dateDebut ? strtotime($dateDebut) : 0;
                    $cloture   = $dateFin ? strtotime($dateFin) : 0;
                    $dateDebutFormatted = $dateDebut ? \Carbon\Carbon::parse($dateDebut)->locale('fr')->isoFormat('D MMM YYYY [à] HH:mm') : '';
                @endphp

                @if($voteMode === 'cloture')
                {{-- ÉTAT 3 : Après clôture --}}
                <p class="hero-sub mb-3 mx-auto" style="max-width: 540px;">
                    Les ovations sont closes. Rendez-vous le <strong>28 novembre</strong> <br>pour la Grande Finale.
                </p>
                <a href="{{ route('public.resultats') }}" class="btn btn-vote fw-semibold px-4 py-2 rounded-pill">
                    <i class="bi bi-trophy me-2"></i>Découvrez les finalistes et les résultats
                </a>

                @elseif($voteMode === 'off' || $now < $ouverture)
                {{-- ÉTAT 1 : Avant ouverture --}}
                <p class="hero-sub mb-4 mx-auto" style="max-width: 540px;">
                    Soutenez les talents du FITAB.<br>Chaque voix compte pour aider votre artiste favori à remporter le concours.
                </p>
                <div class="d-flex flex-column align-items-center gap-3">
                    <div class="countdown-wrap d-inline-flex align-items-center gap-2 px-4 py-3">
                        <i class="bi bi-calendar-event" style="color: #CA7B05; font-size: 1.2rem;"></i>
                        <span style="color: #E3D5AD;">Les ovations ouvrent le <strong>{{ $dateDebutFormatted }}</strong>.<br>Revenez soutenir vos artistes !</span>
                    </div>
                </div>

                @else
                {{-- ÉTAT 2 : Pendant la période d'ovations --}}
                <p class="hero-sub mb-4 mx-auto" style="max-width: 540px;">
                    Soutenez les talents du FITAB.<br>Chaque voix compte pour aider votre artiste favori à remporter le concours.
                </p>
                <div class="d-flex flex-wrap align-items-center justify-content-center gap-3 mb-4">
                    <span class="price-badge">
                        <i class="bi bi-ticket-perforated me-2"></i>
                        <strong>{{ number_format($prixDuVote, 0, ',', ' ') }} FCFA</strong> l'ovation
                    </span>
                    <a href="#candidats" class="btn btn-vote fw-semibold px-4 py-2 rounded-pill">
                        Voir les candidats <i class="bi bi-arrow-down ms-2"></i>
                    </a>
                </div>
                <div class="d-flex flex-column align-items-center gap-3">
                    <div class="countdown-wrap d-inline-flex flex-column align-items-center gap-2 px-4 py-3">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-clock" style="color: #CA7B05;"></i>
                            <span style="color: #E3D5AD;">Les ovations ferment dans :</span>
                        </div>
                        <div class="d-flex gap-1" id="countdown">
                            <div class="countdown-item"><div class="num" id="cd-jours">00</div><div class="label">Jours</div></div>
                            <div class="countdown-item"><div class="num" id="cd-heures">00</div><div class="label">Hrs</div></div>
                            <div class="countdown-item"><div class="num" id="cd-minutes">00</div><div class="label">Min</div></div>
                            <div class="countdown-item"><div class="num" id="cd-secondes">00</div><div class="label">Sec</div></div>
                        </div>
                        <span style="color: #CA7B05; font-weight: 600; font-size: 0.85rem;">Ovationnez maintenant</span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- ==================== BARRE D'ÉTAPES ==================== --}}
<section class="py-4" style="background: #fdfaf5;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center mb-3">
                <h6 class="fw-bold mb-1" style="color: #3E1E05;"><i class="bi bi-info-circle me-1" style="color: #9B4D07;"></i> Comment ça marche ?</h6>
                <p class="small text-muted mb-0">Ovationnez votre artiste préféré en 3 étapes simples</p>
            </div>
            <div class="col-lg-6 col-md-8">
                {{-- Cercles avec traits centrés --}}
                <div class="d-flex align-items-center mb-1">
                    <div class="text-center" style="flex: 1;">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle fw-bold text-white" style="width: 36px; height: 36px; background: #9B4D07; font-size: 0.85rem;">1</div>
                    </div>
                    <div style="flex: 0 0 auto; width: 40px; height: 2px; background: #E3D5AD; margin: 0 -4px;"></div>
                    <div class="text-center" style="flex: 1;">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle fw-bold text-white" style="width: 36px; height: 36px; background: #9B4D07; font-size: 0.85rem;">2</div>
                    </div>
                    <div style="flex: 0 0 auto; width: 40px; height: 2px; background: #E3D5AD; margin: 0 -4px;"></div>
                    <div class="text-center" style="flex: 1;">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle fw-bold text-white" style="width: 36px; height: 36px; background: #9B4D07; font-size: 0.85rem;">3</div>
                    </div>
                </div>
                {{-- Labels alignés sous chaque cercle --}}
                <div class="d-flex">
                    <div class="text-center" style="flex: 1;">
                        <div class="small fw-semibold mt-0" style="color: #9B4D07; font-size: 0.75rem;">Choisir</div>
                        <div class="small text-muted" style="font-size: 0.65rem;">votre candidat</div>
                    </div>
                    <div style="flex: 0 0 auto; width: 40px;"></div>
                    <div class="text-center" style="flex: 1;">
                        <div class="small fw-semibold mt-0" style="color: #9B4D07; font-size: 0.75rem;">Entrer</div>
                        <div class="small text-muted" style="font-size: 0.65rem;">le nombre d'ovations</div>
                    </div>
                    <div style="flex: 0 0 auto; width: 40px;"></div>
                    <div class="text-center" style="flex: 1;">
                        <div class="small fw-semibold mt-0" style="color: #9B4D07; font-size: 0.75rem;">Payer</div>
                        <div class="small text-muted" style="font-size: 0.65rem;">Mobile Money</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ==================== FILTRES ==================== --}}
<section class="py-4 bg-light" id="candidats">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center gap-2">
            <span class="fw-semibold me-2" style="color: var(--vote-gold);">Catégorie :</span>
            <button class="btn filter-btn active" data-filter="all">Tous</button>
            @foreach($categories as $cat)
                <button class="btn filter-btn" data-filter="{{ Str::slug($cat) }}">{{ $cat }}</button>
            @endforeach
        </div>
    </div>
</section>

{{-- ==================== GRILLE CANDIDATS ==================== --}}
<section class="py-5">
    <div class="container">
        @if($voteMode === 'off')
            <div class="alert alert-warning text-center py-4 mb-4" role="alert">
                <h4 class="alert-heading mb-2"><i class="bi bi-clock-history"></i> Ovation bientôt disponible</h4>
                <p class="mb-0">Les ovations ne sont pas encore ouvertes. Revenez bientôt pour soutenir vos candidats préférés !</p>
                <hr>
                <p class="mb-0">En attendant, découvrez nos <a href="{{ route('public.medias') }}" class="alert-link">médias</a>.</p>
            </div>
        @elseif($voteMode === 'cloture')
            <div class="alert alert-secondary text-center py-4 mb-4" role="alert">
                <h4 class="alert-heading mb-2"><i class="bi bi-trophy-fill"></i> Ovations clôturées</h4>
                <p class="mb-0">Les ovations sont maintenant terminées.
                    @if($resultatsPublies) Consultez les résultats ci-dessous.
                    @else Les résultats seront bientôt publiés. Revenez plus tard.
                    @endif
                </p>
            </div>
        @endif

        @if($voteMode === 'cloture' && $resultatsPublies && $resultats->isNotEmpty())
            {{-- ==================== RÉSULTATS PUBLIÉS ==================== --}}
            @foreach($resultats as $categorie => $items)
                <h5 class="fw-bold mb-3 mt-4" style="color: #3E1E05;">
                    <i class="bi bi-tag-fill me-1" style="color: #9B4D07;"></i>
                    {{ $categorie }}
                </h5>
                <div class="row g-4 mb-5">
                    @foreach($items as $r)
                    <div class="col-sm-6 col-lg-3">
                        <div class="card candidate-card result-card h-100 shadow-sm">
                            <div class="result-cover">
                                @if($r->candidat_photo)
                                    <img src="{{ $r->candidat_photo_url }}" class="photo-principale" alt="{{ $r->candidat_nom }}" loading="lazy">
                                @endif
                                <span class="candidat-num" style="background: {{ $r->prix === 1 ? '#FFD700' : ($r->prix === 2 ? '#C0C0C0' : '#CD7F32') }}; color: #3E1E05;">
                                    {{ $r->prix_label }}
                                </span>
                            </div>
                            <div class="card-body result-body d-flex flex-column px-2 py-2 text-center">
                                <h6 class="fw-bold mb-0" style="color: var(--vote-brown); font-size: 0.95rem;">{{ $r->candidat_nom }}</h6>
                                <span class="text-muted" style="font-size: 0.75rem;">{{ $r->categorie }}</span>
                                <div class="mt-1 mb-1" style="font-size: 0.78rem;">
                                    <div class="d-flex justify-content-between px-1">
                                        <span style="color: #9B4D07;">Ovations</span>
                                        <span class="fw-bold" style="color: #3E1E05;">{{ $r->nombre_votes }}</span>
                                    </div>
                                    @if($r->note_jury !== null)
                                    <div class="d-flex justify-content-between px-1">
                                        <span style="color: #9B4D07;">Note jury</span>
                                        <span class="fw-bold" style="color: #3E1E05;">{{ $r->note_jury }}/20</span>
                                    </div>
                                    @endif
                                </div>
                                <div class="mt-auto">
                                    <span class="badge fw-semibold px-2 py-1" style="background: #9B4D07; color: #fff; font-size: 0.85rem;">
                                        {{ $r->score_final ?? $r->score_public ?? '-' }}/20
                                    </span>
                                    <small class="d-block text-muted" style="font-size: 0.65rem;">Score final</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endforeach
        @elseif($candidats->isEmpty())
            <div class="alert alert-info text-center py-5">
                <i class="bi bi-people fs-1 d-block mb-3"></i>
                Aucun candidat inscrit pour le moment.
            </div>
        @else
            <div class="row g-4 align-items-stretch" id="candidatsGrid">
                @foreach($candidats as $candidat)
                    <div class="col-8 col-md-6 col-lg-3 offset-2 offset-md-0 candidat-col" data-candidat-id="{{ $candidat->id }}" data-categorie="{{ Str::slug($candidat->categorie ?? '') }}">
                        <div class="card candidate-card shadow-sm">
                            <div class="candidat-cover">
                                @if($candidat->photo)
                                    <img src="{{ $candidat->photo_url }}" class="photo-principale" alt="{{ $candidat->display_name }}" loading="lazy">
                                @endif
                                @if($candidat->categorie)
                                    @php $catColor = \App\Models\Candidats::CATEGORIES[$candidat->categorie] ?? '#9B4D07'; @endphp
                                    <span class="categorie-badge" style="background: {{ $catColor }};">{{ $candidat->categorie }}</span>
                                @endif
                                @if($candidat->numero_scene)
                                    <span class="candidat-num">N°{{ $candidat->numero_scene }}</span>
                                @endif
                            </div>
                            <div class="card-body d-flex flex-column text-center">
                                <h6 class="fw-bold mb-1" style="color: var(--vote-brown); font-size: 0.9rem; line-height: 1.2;">{{ $candidat->display_name }}</h6>
                                @if($candidat->biographie)
                                    <p class="mb-1 text-center" style="font-size: 0.75rem; color: #777; line-height: 1.35; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ Str::limit($candidat->biographie, 80) }}
                                    </p>
                                @endif
                                @if($afficherCompteur)
                                    <p class="vote-count text-center mb-1" style="font-size: 0.85rem;">
                                        <i class="bi bi-heart-fill" style="color: var(--vote-gold);"></i>
                                        {{ $candidat->votes_sum_quantite ?? 0 }} ovation(s)
                                    </p>
                                @endif
                                <hr class="my-1" style="border-color: #f0e6d6; opacity: 0.6;">
                                <div class="d-flex flex-column gap-1 mt-auto">
                                    @if($voteMode === 'active')
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn text-white fw-semibold btn-sm flex-fill vote-btn" style="background: var(--vote-gold); border-radius: 50px; font-size: 0.8rem;"
                                                    data-id="{{ $candidat->id }}"
                                                    data-nom="{{ $candidat->display_name }}"
                                                    data-photo="{{ $candidat->photo_url }}"
                                                    data-votes="{{ $candidat->votes_sum_quantite ?? 0 }}"
                                                    data-categorie="{{ $candidat->categorie ?? '' }}"
                                                    data-bio="{{ Str::limit($candidat->biographie ?? '', 120) }}"
                                                    data-numero="{{ $candidat->numero_scene }}">
                                                Ovationner <i class="bi bi-check-circle ms-1"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm fw-semibold share-btn" style="border: 2px solid var(--vote-gold-light); color: var(--vote-gold-light); border-radius: 50px; flex: 0 0 auto; padding: 0.25rem 0.8rem;"
                                                    data-id="{{ $candidat->id }}"
                                                    data-nom="{{ $candidat->display_name }}"
                                                    data-photo="{{ $candidat->photo_url }}">
                                                <i class="bi bi-share-fill"></i>
                                            </button>
                                        </div>
                                    @else
                                        <button type="button" class="btn btn-secondary w-100 btn-sm" disabled style="border-radius: 50px;">
                                            Ovation fermée
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
</div>
</section>

@include('public.vote.modal')

{{-- ==================== MODAL PARTAGE ==================== --}}
<div class="modal fade" id="shareModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 380px;">
        <div class="modal-content" style="border-radius: 18px; overflow: hidden; border: none; box-shadow: 0 10px 40px rgba(62,30,5,0.25);">
            <div class="modal-body p-0">
                <div class="text-center position-relative">
                    <button type="button" class="position-absolute top-0 end-0 m-2" data-bs-dismiss="modal" aria-label="Fermer" style="background: rgba(0,0,0,0.5); border-radius: 50%; width: 28px; height: 28px; border: none; color: #fff; font-size: 0.8rem; display: flex; align-items: center; justify-content: center; z-index: 10; cursor: pointer;">
                        <i class="bi bi-x-lg"></i>
                    </button>
                    <img id="sharePhoto" src="{{ asset('images/default-user.png') }}" alt="Photo du candidat" class="w-100" style="height: 220px; object-fit: cover;">
                    <div class="p-3">
                        <h6 class="fw-bold mb-1" id="shareName" style="color: #3E1E05;"></h6>
                        <p class="text-muted mb-3" style="font-size: 0.78rem;">Partagez ce candidat sur vos réseaux</p>
                        <div class="d-flex flex-column gap-2">
                            <a id="shareWhatsApp" href="#" target="_blank" class="btn text-white fw-semibold w-100" style="background: #25D366; border-radius: 50px; font-size: 0.85rem;">
                                <i class="bi bi-whatsapp me-2"></i>Partager sur WhatsApp
                            </a>
                            <a id="shareFacebook" href="#" target="_blank" class="btn text-white fw-semibold w-100" style="background: #1877F2; border-radius: 50px; font-size: 0.85rem;">
                                <i class="bi bi-facebook me-2"></i>Partager sur Facebook
                            </a>
                            <a id="shareTelegram" href="#" target="_blank" class="btn text-white fw-semibold w-100" style="background: #0088CC; border-radius: 50px; font-size: 0.85rem;">
                                <i class="bi bi-telegram me-2"></i>Partager sur Telegram
                            </a>
                            <a id="shareTwitter" href="#" target="_blank" class="btn text-white fw-semibold w-100" style="background: #000; border-radius: 50px; font-size: 0.85rem;">
                                <i class="bi bi-twitter-x me-2"></i>Partager sur X (Twitter)
                            </a>
                            <a id="shareEmail" href="#" class="btn fw-semibold w-100" style="border: 2px solid #3E1E05; color: #3E1E05; border-radius: 50px; font-size: 0.85rem;">
                                <i class="bi bi-envelope me-2"></i>Envoyer par e-mail
                            </a>
                            <button type="button" id="shareCopyLink" class="btn fw-semibold w-100" style="background: #f0e6d6; color: #3E1E05; border-radius: 50px; font-size: 0.85rem;">
                                <i class="bi bi-link-45deg me-2"></i>Copier le lien
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ==================== RÈGLEMENT ==================== --}}
<section class="py-4" style="background: #fdfaf5;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center">
                    <h6 class="fw-bold mb-1" style="color: #9B4D07;"><i class="bi bi-file-text me-1"></i> Règlement de l'ovation</h6>
                    <p class="small text-muted mb-3">Consultez les conditions générales de participation et d'ovation du FITAB 2026.</p>
                    <div class="d-flex justify-content-center gap-2">
                        <button class="btn btn-sm px-3 fw-semibold" style="background: #9B4D07; color: #fff;" type="button" data-bs-toggle="collapse" data-bs-target="#reglementCollapse">
                            <i class="bi bi-eye me-1"></i> Lire
                        </button>
                        <a href="{{ asset('documents/reglement-fitab_2026.pdf') }}" class="btn btn-sm px-3 fw-semibold" style="border: 2px solid #9B4D07; color: #9B4D07;" target="_blank">
                            <i class="bi bi-download me-1"></i> Télécharger (PDF)
                        </a>
                    </div>
                    <div class="collapse mt-3 text-start" id="reglementCollapse">
                        <div class="p-4 rounded-3" style="background: #fff; border: 1px solid #E3D5AD;">
                            <h6 class="fw-bold mb-3" style="color: #9B4D07;">Conditions de participation</h6>
                            <p class="small mb-2">1. L'ovation est ouverte à toute personne physique âgée d'au moins 18 ans.</p>
                            <p class="small mb-2">2. Chaque ovation est payante au tarif de <strong>{{ number_format($prixDuVote, 0, ',', ' ') }} FCFA</strong> l'unité.</p>
                            <p class="small mb-2">3. Le nombre d'ovations par personne n'est pas limité.</p>
                            <p class="small mb-2">4. Les ovations sont définitives et non remboursables.</p>
                            <p class="small mb-2">5. Le paiement s'effectue via Fedapay (MTN Mobile Money, Moov Flooz, Orange Money).</p>
                            <p class="small mb-0">6. Toute ovation frauduleuse entraîne l'annulation des ovations concernées.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Section appel à l'action --}}
<section class="py-5 bg-light text-center">
    <div class="container">
        <h2 class="fw-bold mb-3" style="color: #3E1E05;">Prêt à vivre l'expérience FITAB ?</h2>
        <p class="mb-4" style="color: #5F2B0C;">Rejoignez-nous pour célébrer la culture, la créativité et le talent du Grand Porto-Novo.</p>
        <a href="{{ route('public.contact') }}" class="btn btn-fitab btn-lg px-4 py-3 border-0 shadow-sm">
            <i class="bi bi-calendar-event me-2"></i>Dévenir partenaire ou sponsor
        </a>
    </div>
</section>

@endsection

@push('scripts')
<script>
// ==================== ÉTAT ====================
let etat = {
    candidatId: null,
    candidatNom: '',
    candidatPhoto: '',
    step: 1,
    paymentMethod: null,
    voteId: null,
    mode: @json($voteMode),
    prixUnitaire: {{ $prixDuVote }},
};

// ==================== COULEURS CATÉGORIES ====================
const categoryColors = {
    'théâtre': '#8B0000',
    'danse': '#6B3FA0',
    'musique': '#1E6EB5',
    'percussion': '#B8860B',
    'arts visuels': '#C2185B',
    'stylisme/modélisme': '#E67E00',
};

// ==================== OUVERTURE ====================
function ouvrirVote(id, nom, photo, votesCount, categorie, bio, numeroScene) {
    try {
        etat.candidatId = id;
        etat.candidatNom = nom;
        etat.candidatPhoto = photo;
        etat.step = 1;
        etat.paymentMethod = null;
        etat.voteId = null;

        document.getElementById('voteCandidatId').value = id;
        const nameEl = document.getElementById('candidatNameDisplay');
        if (nameEl) nameEl.textContent = nom;
        document.getElementById('candidatNameMini').textContent = nom;
        document.getElementById('candidatCategoryInfo').textContent = categorie || '';

        const catBadge = document.getElementById('candidatCategoryInfo');
        const color = categoryColors[categorie] || '#9B4D07';
        catBadge.style.background = color;

        const numEl = document.getElementById('candidatNumero');
        if (numEl) {
            numEl.textContent = numeroScene ? 'N°' + numeroScene : '';
            numEl.style.display = numeroScene ? '' : 'none';
        }

        const countEl = document.getElementById('candidatVoteCount');
        if (countEl) {
            @if($afficherCompteur)
            countEl.innerHTML = '<i class="bi bi-heart-fill" style="color: #9B4D07;"></i> ' + (votesCount || 0) + ' ovation' + ((votesCount || 0) > 1 ? 's' : '');
            countEl.style.display = '';
            @else
            countEl.style.display = 'none';
            @endif
        }

        document.getElementById('candidatBio').textContent = bio || '';
        document.getElementById('candidatPhotoPreview').src = photo || '{{ asset("images/default-user.png") }}';
        document.getElementById('candidatPhotoPreview').alt = nom || 'Photo du candidat';
        const mobilePreview = document.getElementById('candidatPhotoPreviewMobile');
        if (mobilePreview) {
            mobilePreview.src = photo || '{{ asset("images/default-user.png") }}';
            mobilePreview.alt = nom || 'Photo du candidat';
        }
        document.getElementById('step2CandidatNom').textContent = nom;
        document.getElementById('step2CandidatPhoto').src = photo || '{{ asset("images/default-user.png") }}';
        document.getElementById('step2CandidatPhoto').alt = nom || 'Photo du candidat';

        document.getElementById('quantite').value = 1;
        setTimeout(function() { changerQte(0); }, 10);
        document.getElementById('votePaymentMethod').value = '';
        majTotal();

        document.querySelectorAll('.payment-option').forEach(el => el.classList.remove('selected'));

        const modalEl = document.getElementById('voteModal');
        if (!modalEl) {
            console.error('Modal #voteModal introuvable dans le DOM');
            return;
        }
        const modal = new bootstrap.Modal(modalEl);
        modal.show();

        reinitSteps();
        allerStep(1, true);
    } catch (e) {
        console.error('Erreur ouvrirVote:', e);
    }
}

// ==================== QUANTITÉ ====================
function changerQte(delta) {
    const input = document.getElementById('quantite');
    let val = parseInt(input.value) + delta;
    if (val < 1) val = 1;
    if (val > 1000) val = 1000;
    input.value = val;
    updateQteButtons(val);
    majTotal();
}

function saisirQte(input) {
    let val = parseInt(input.value);
    if (isNaN(val) || val < 1) { input.value = 1; val = 1; }
    if (val > 1000) { input.value = 1000; val = 1000; }
    updateQteButtons(val);
    majTotal();
}

function updateQteButtons(val) {
    const btnMoins = document.getElementById('btnMoins');
    if (btnMoins) {
        btnMoins.style.opacity = val <= 1 ? '0.3' : '1';
        btnMoins.style.pointerEvents = val <= 1 ? 'none' : 'auto';
    }
    const btnPlus = document.getElementById('btnPlus');
    if (btnPlus) {
        btnPlus.style.opacity = val >= 1000 ? '0.3' : '1';
        btnPlus.style.pointerEvents = val >= 1000 ? 'none' : 'auto';
    }
}

function majTotal() {
    const qte = parseInt(document.getElementById('quantite').value) || 1;
    const total = qte * etat.prixUnitaire;
    document.getElementById('totalDisplay').textContent = total.toLocaleString('fr-FR');
    const step2Total = document.getElementById('step2Total');
    if (step2Total) step2Total.textContent = total.toLocaleString('fr-FR') + ' FCFA';
    const step2Qte = document.getElementById('step2Quantite');
    if (step2Qte) step2Qte.textContent = qte + ' ovation' + (qte > 1 ? 's' : '');
}

// ==================== PAIEMENT DIRECT (1 agrégateur) ====================
function payerDirect(method) {
    etat.paymentMethod = method;
    document.getElementById('votePaymentMethod').value = method;
    allerStep(3);
}

// ==================== ÉTAPES ====================
function reinitSteps() {
    document.querySelectorAll('.vote-step').forEach(el => el.classList.remove('active'));
}

function allerStep(n, skipValidation) {
    if (!skipValidation) {
        if (n === 3) {
            const method = etat.paymentMethod;
            if (!method) {
                alert('Veuillez choisir un moyen de paiement.');
                return;
            }
        }
    }

    reinitSteps();
    etat.step = n;
    document.getElementById('step-' + n).classList.add('active');

    const dialog = document.querySelector('#voteModal .modal-dialog');
    if (dialog) {
        dialog.classList.toggle('modal-narrow', n === 2 || n === 3);
    }

    if (n === 3) {
        lancerPaiement();
    }
}

// =================== PAIEMENT ====================
function choisirPaiement(el, method) {
    document.querySelectorAll('.payment-option').forEach(e => e.classList.remove('selected'));
    el.classList.add('selected');
    etat.paymentMethod = method;
    document.getElementById('votePaymentMethod').value = method;
}

async function lancerPaiement() {
    const form = document.getElementById('voteForm');
    const formData = new FormData(form);
    formData.set('quantite', document.getElementById('quantite').value);
    formData.set('payment_method', etat.paymentMethod);
    formData.set('candidat_id', etat.candidatId);

    document.getElementById('paymentSpinner').style.display = 'inline-block';
    document.getElementById('paymentStepText').textContent = 'Enregistrement de votre ovation...';
    document.getElementById('btnStep3Back').disabled = true;

    try {
        const response = await fetch('{{ route("public.vote.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: formData,
        });

        const data = await response.json();

        if (!data.success) {
            throw new Error(data.message || 'Erreur lors de l\'enregistrement');
        }

        etat.voteId = data.vote_id;
        document.getElementById('paymentSpinner').style.display = 'none';

        const montant = data.montant;
        document.getElementById('paymentMontant').textContent = montant.toLocaleString('fr-FR') + ' FCFA';

        document.getElementById('paymentStepText').textContent = 'Initialisation du paiement...';
        ouvrirFedapay(data.vote_id, montant);

    } catch (err) {
        document.getElementById('paymentSpinner').style.display = 'none';
        document.getElementById('paymentStepText').textContent = 'Erreur : ' + err.message;
        document.getElementById('paymentStepText').className = 'text-danger fw-semibold';
        document.getElementById('btnStep3Back').disabled = false;
    }
}

function fallbackSuccess(voteId) {
    document.getElementById('paymentSpinner').style.display = 'none';
    document.getElementById('paymentSuccessIcon').style.display = 'block';
    document.getElementById('paymentStepText').textContent = 'Votre ovation a bien été enregistrée !';
    document.getElementById('paymentStepText').style.color = '#198754';
    document.getElementById('paymentStepDetail').innerHTML = 'Merci pour votre participation.';
    document.getElementById('btnStep3Back').disabled = true;
    setTimeout(function() {
        window.location.href = '{{ route("public.vote.merci") }}?vote_id=' + voteId;
    }, 1500);
}

function ouvrirFedapay(voteId, montant) {
    const apiKey = '{{ $fedapayKey }}';
    if (!apiKey) {
        fallbackSuccess(voteId);
        return;
    }

    const apiEnv = '{{ $fedapayMode }}' === 'sandbox' ? 'sandbox' : 'live';
    const btn = document.getElementById('btnPayerFedapay');
    if (!btn) return;

    const callbackUrl = '{{ route("public.vote.merci") }}?vote_id=' + voteId;

    // Fermer la modale Bootstrap avant d'ouvrir le widget Fedapay
    const modalEl = document.getElementById('voteModal');
    const modal = bootstrap.Modal.getInstance(modalEl);
    if (modal) modal.hide();

    FedaPay.init(btn, {
        public_key: apiKey,
        environment: apiEnv,
        transaction: {
            amount: montant,
            description: 'Ovation FITAB #' + voteId,
        },
        currency: { iso: 'XOF' },
        onComplete: function(data) {
            if (data.reason === 'CHECKOUT COMPLETE' && data.transaction && data.transaction.id) {
                var pm = data.transaction.payment_method || 'mobile_money';
                var ph = data.transaction.phone || '';
                window.location.href = callbackUrl + '&id=' + data.transaction.id + '&status=' + (data.transaction.status || 'approved') + '&payment_method=' + pm + '&phone=' + ph;
            } else {
                document.getElementById('paymentStepText').textContent = 'Paiement annulé ou fermé. Vous pouvez réessayer.';
                document.getElementById('paymentStepText').className = 'text-warning fw-semibold';
                document.getElementById('btnStep3Back').disabled = false;
            }
        }
    });

    document.getElementById('paymentStepText').textContent = 'Ouverture de la fenêtre Fedapay...';
    btn.click();
}

// ==================== COMPTEUR (état 2 uniquement) ====================
(function() {
    const el = document.getElementById('countdown');
    if (!el) return;
    const CLOTURE = new Date('{{ $dateFin ? date('Y-m-d\TH:i:s', strtotime($dateFin)) : '2026-11-22T23:59:00' }}').getTime();

    function maj() {
        const now = new Date().getTime();
        const diff = CLOTURE - now;
        if (diff <= 0) {
            document.getElementById('cd-jours').textContent = '00';
            document.getElementById('cd-heures').textContent = '00';
            document.getElementById('cd-minutes').textContent = '00';
            document.getElementById('cd-secondes').textContent = '00';
            return;
        }
        const jours = Math.floor(diff / (1000 * 60 * 60 * 24));
        const heures = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const secondes = Math.floor((diff % (1000 * 60)) / 1000);

        document.getElementById('cd-jours').textContent = String(jours).padStart(2, '0');
        document.getElementById('cd-heures').textContent = String(heures).padStart(2, '0');
        document.getElementById('cd-minutes').textContent = String(minutes).padStart(2, '0');
        document.getElementById('cd-secondes').textContent = String(secondes).padStart(2, '0');
    }

    maj();
    setInterval(maj, 1000);
})();

// ==================== PARTAGER ====================
function partagerCandidat(id, nom, photo) {
    const url = window.location.origin + '/vote?candidat=' + id;
    const text = 'Ovationnez ' + nom + ' au FITAB ! Votez pour votre candidat préféré.';

    document.getElementById('sharePhoto').src = photo || '{{ asset("images/default-user.png") }}';
    document.getElementById('shareName').textContent = nom;
    document.getElementById('shareWhatsApp').href = 'https://wa.me/?text=' + encodeURIComponent(text + ' ' + url);
    document.getElementById('shareFacebook').href = 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(url);
    document.getElementById('shareTelegram').href = 'https://t.me/share/url?url=' + encodeURIComponent(url) + '&text=' + encodeURIComponent(text);
    document.getElementById('shareTwitter').href = 'https://twitter.com/intent/tweet?text=' + encodeURIComponent(text) + '&url=' + encodeURIComponent(url);
    document.getElementById('shareEmail').href = 'mailto:?subject=' + encodeURIComponent(nom + ' — FITAB') + '&body=' + encodeURIComponent(text + '\n' + url);

    const copyBtn = document.getElementById('shareCopyLink');
    copyBtn.onclick = function() {
        navigator.clipboard.writeText(url).then(function() {
            copyBtn.innerHTML = '<i class="bi bi-check-lg me-2"></i>Lien copié !';
            setTimeout(function() {
                copyBtn.innerHTML = '<i class="bi bi-link-45deg me-2"></i>Copier le lien';
            }, 2000);
        });
    };

    const shareModal = new bootstrap.Modal(document.getElementById('shareModal'));
    shareModal.show();
}

// ==================== FILTRES CATÉGORIES ====================
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.vote-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            ouvrirVote(
                parseInt(this.dataset.id),
                this.dataset.nom,
                this.dataset.photo,
                parseInt(this.dataset.votes) || 0,
                this.dataset.categorie,
                this.dataset.bio,
                this.dataset.numero
            );
        });
    });

    document.querySelectorAll('.share-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            partagerCandidat(parseInt(this.dataset.id), this.dataset.nom, this.dataset.photo);
        });
    });

    const filterBtns = document.querySelectorAll('.filter-btn');
    const colonnes = document.querySelectorAll('.candidat-col');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const filter = this.dataset.filter;
            colonnes.forEach(col => {
                if (filter === 'all' || col.dataset.categorie === filter) {
                    col.style.display = '';
                } else {
                    col.style.display = 'none';
                }
            });
        });
    });

    // ==================== CANDIDAT PARTAGÉ (auto-scroll + highlight) ====================
    @if ($candidatPartage)
    const targetBtn = document.querySelector('[data-candidat-id="{{ $candidatPartage->id }}"]');
    if (targetBtn) {
        const card = targetBtn.closest('.candidat-col');
        if (card) {
            const cat = card.dataset.categorie;
            const filterBtn = document.querySelector('.filter-btn[data-filter="' + cat + '"]');
            if (filterBtn) filterBtn.click();
            setTimeout(function() {
                card.classList.add('shared-highlight');
                card.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 300);
        }
    }
    @endif
});
</script>
@endpush
