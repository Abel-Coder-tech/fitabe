@extends('layouts.public')

@section('title', 'Vote - ' . config('app.name', 'Fitabe'))

@push('styles')
<style>
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
        border-radius: 12px;
        overflow: hidden;
    }
    .candidate-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 8px 28px rgba(160,132,92,0.12);
    }
    .candidate-card .card-img-top {
        height: 260px;
        object-fit: cover;
    }
    .candidate-card .vote-count {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--vote-brown);
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

    .step-indicator {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }
    .step-dot {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
        border: 2px solid #dee2e6;
        color: #adb5bd;
        background: #fff;
        transition: all 0.3s;
    }
    .step-dot.active {
        border-color: var(--vote-gold);
        background: var(--vote-gold);
        color: #fff;
    }
    .step-dot.done {
        border-color: #198754;
        background: #198754;
        color: #fff;
    }

    .vote-step {
        display: none;
        animation: fadeIn 0.35s ease;
    }
    .vote-step.active {
        display: block;
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
        padding: 5rem 0;
        background: linear-gradient(135deg, rgba(62,30,5,0.88) 0%, rgba(62,30,5,0.65) 50%, rgba(62,30,5,0.4) 100%), url('{{ asset('images/votes/hero_votant.jpg') }}') no-repeat center center;
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
</style>
@endpush

@section('content')

{{-- ==================== HERO ==================== --}}
<section class="hero-vote">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <p class="small fw-semibold mb-2" style="color: #CA7B05; letter-spacing: 2px; text-transform: uppercase;">
                    <i class="bi bi-star-fill me-1"></i> Édition {{ date('Y') }}
                </p>
                <h1 class="display-4 fw-bold mb-3">
                    Votez pour votre<br>
                    <span style="color: #CA7B05;">candidat préféré</span>
                </h1>
                <p class="lead hero-sub mb-4 mx-auto" style="max-width: 540px;">
                    Soutenez les talents du FITAB. Chaque voix compte pour aider votre artiste favori à remporter le concours.
                </p>
                <div class="d-flex flex-wrap align-items-center justify-content-center gap-3 mb-4">
                    <span class="price-badge">
                        <i class="bi bi-ticket-perforated me-2"></i>
                        <strong>{{ number_format($prixDuVote, 0, ',', ' ') }} FCFA</strong> le vote
                    </span>
                    <a href="#candidats" class="btn btn-vote fw-semibold px-4 py-2 rounded-pill">
                        Voir les candidats <i class="bi bi-arrow-down ms-2"></i>
                    </a>
                </div>
                @if($afficherCompteur && $voteDeadline)
                <div class="countdown-wrap d-inline-flex align-items-center gap-3">
                    <span class="label-text"><i class="bi bi-clock me-1"></i> Fin</span>
                    <div class="d-flex gap-1" id="countdown">
                        <div class="countdown-item">
                            <div class="num" id="cd-jours">00</div>
                            <div class="label">Jours</div>
                        </div>
                        <div class="countdown-item">
                            <div class="num" id="cd-heures">00</div>
                            <div class="label">Hrs</div>
                        </div>
                        <div class="countdown-item">
                            <div class="num" id="cd-minutes">00</div>
                            <div class="label">Min</div>
                        </div>
                        <div class="countdown-item">
                            <div class="num" id="cd-secondes">00</div>
                            <div class="label">Sec</div>
                        </div>
                    </div>
                    <div class="small" id="cd-expired" style="display:none;color:#CA7B05;">
                        <i class="bi bi-hourglass-split me-1"></i> Clos
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
            <div class="col-lg-6 col-md-8">
                <div class="d-flex align-items-center">
                    <div class="text-center flex-fill">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle fw-bold text-white mb-1" style="width: 36px; height: 36px; background: #9B4D07; font-size: 0.85rem;">1</div>
                        <div class="small fw-semibold mt-1" style="color: #9B4D07;">Choisissez</div>
                        <div class="small text-muted" style="font-size: 0.75rem;">votre candidat</div>
                    </div>
                    <div class="flex-grow-1 mx-2" style="height: 2px; background: #E3D5AD;"></div>
                    <div class="text-center flex-fill">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle fw-bold text-white mb-1" style="width: 36px; height: 36px; background: #9B4D07; font-size: 0.85rem;">2</div>
                        <div class="small fw-semibold mt-1" style="color: #9B4D07;">Entrez</div>
                        <div class="small text-muted" style="font-size: 0.75rem;">le nombre de votes</div>
                    </div>
                    <div class="flex-grow-1 mx-2" style="height: 2px; background: #E3D5AD;"></div>
                    <div class="text-center flex-fill">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle fw-bold text-white mb-1" style="width: 36px; height: 36px; background: #9B4D07; font-size: 0.85rem;">3</div>
                        <div class="small fw-semibold mt-1" style="color: #9B4D07;">Payez</div>
                        <div class="small text-muted" style="font-size: 0.75rem;">Mobile Money / Carte</div>
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
                <h4 class="alert-heading mb-2"><i class="bi bi-clock-history"></i> Vote bientôt disponible</h4>
                <p class="mb-0">Les votes ne sont pas encore ouverts. Revenez bientôt pour soutenir vos candidats préférés !</p>
                <hr>
                <p class="mb-0">En attendant, découvrez nos <a href="{{ route('public.medias') }}" class="alert-link">médias</a>.</p>
            </div>
        @endif

        @if($candidats->isEmpty())
            <div class="alert alert-info text-center py-5">
                <i class="bi bi-people fs-1 d-block mb-3"></i>
                Aucun candidat inscrit pour le moment.
            </div>
        @else
            @foreach($candidats as $categorie => $candidatsCategorie)
            <div class="categorie-group mb-5" data-categorie="{{ Str::slug($categorie) }}">
                <h3 class="h4 mb-4" style="color: var(--vote-gold); border-left: 4px solid var(--vote-gold); padding-left: 1rem;">
                    {{ $categorie }}
                </h3>
                <div class="row g-4">
                    @foreach($candidatsCategorie as $candidat)
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="card candidate-card h-100 shadow-sm">
                                @if($candidat->photo)
                                    <img src="{{ $candidat->photo_url }}" class="card-img-top" alt="{{ $candidat->display_name }}">
                                @else
                                    <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center text-white" style="height:260px;">
                                        <i class="bi bi-person fs-1"></i>
                                    </div>
                                @endif
                                <div class="card-body text-center d-flex flex-column">
                                    <h5 class="fw-bold mb-1">{{ $candidat->display_name }}</h5>
                                    @if($candidat->numero_scene)
                                        <span class="small text-muted mb-2">#{{ $candidat->numero_scene }}</span>
                                    @endif
                                    <p class="vote-count mt-auto mb-2">
                                        <i class="bi bi-heart-fill" style="color: var(--vote-gold);"></i>
                                        {{ $candidat->votes_count ?? 0 }} vote(s)
                                    </p>
                                    @if($voteMode === 'active')
                                        <button type="button" class="btn w-100 text-white fw-semibold" style="background: var(--vote-gold);"
                                                onclick="ouvrirVote({{ $candidat->id }}, '{{ addslashes($candidat->display_name) }}', '{{ $candidat->photo_url }}')">
                                            Voter <i class="bi bi-check-circle ms-1"></i>
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-secondary w-100" disabled>
                                            Vote fermé
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        @endif
    </div>
</section>

{{-- ==================== MODAL VOTE (3 étapes) ==================== --}}
@if($voteMode === 'active')
<div class="modal fade" id="voteModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 overflow-hidden">

            {{-- En-tête --}}
            <div class="modal-header border-bottom-0 pb-0" style="background: var(--vote-cream);">
                <div class="w-100 text-center">
                    <div class="step-indicator">
                        <div class="step-dot active" id="step-dot-1">1</div>
                        <div class="step-dot" id="step-dot-2">2</div>
                        <div class="step-dot" id="step-dot-3">3</div>
                    </div>
                    <h5 class="fw-bold mb-0" style="color: var(--vote-gold);" id="voteModalTitle">
                        Voter pour <span id="candidatNameDisplay"></span>
                    </h5>
                    <p class="small text-muted mt-1" id="voteModalSubtitle">Étape 1 — Quantité &amp; informations</p>
                </div>
                <button type="button" class="btn-close position-absolute top-0 end-0 mt-3 me-3" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">

                {{-- Candidat résumé --}}
                <div class="d-flex align-items-center gap-3 mb-4 p-3 rounded-3 bg-light">
                    <img id="candidatPhotoPreview" src="" alt="" class="rounded-circle" width="56" height="56" style="object-fit:cover;">
                    <div>
                        <strong id="candidatNameMini" class="d-block"></strong>
                        <small class="text-muted" id="candidatPriceInfo"></small>
                    </div>
                </div>

                <form id="voteForm" method="POST">
                    @csrf
                    <input type="hidden" name="candidat_id" id="voteCandidatId">
                    <input type="hidden" name="payment_method" id="votePaymentMethod">

                    {{-- STEP 1 : Quantité + infos votant --}}
                    <div class="vote-step active" id="step-1">
                        <div class="mb-3">
                            <label for="quantite" class="form-label fw-semibold">Nombre de votes</label>
                            <div class="input-group">
                                <button class="btn btn-outline-secondary" type="button" onclick="changerQte(-1)">−</button>
                                <input type="number" class="form-control text-center fw-bold" id="quantite" name="quantite" value="1" min="1" max="100" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="changerQte(1)">+</button>
                            </div>
                            <div class="form-text mt-2">
                                Prix unitaire : <strong>{{ number_format($prixDuVote, 0, ',', ' ') }} FCFA</strong> &mdash;
                                Total : <strong id="totalDisplay">{{ number_format($prixDuVote, 0, ',', ' ') }} FCFA</strong>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <label for="votant_nom" class="form-label fw-semibold">Nom complet <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="votant_nom" name="votant_nom" required placeholder="Votre nom et prénom">
                            </div>
                            <div class="col-md-6">
                                <label for="votant_email" class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="votant_email" name="votant_email" required placeholder="vous@exemple.com">
                            </div>
                            <div class="col-md-6">
                                <label for="votant_telephone" class="form-label fw-semibold">Téléphone <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="votant_telephone" name="votant_telephone" required placeholder="+229 61 00 00 00">
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="button" class="btn text-white px-4 fw-semibold" style="background: var(--vote-gold);" onclick="allerStep(2)">
                                Suivant <i class="bi bi-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </div>

                    {{-- STEP 2 : Choix du mode de paiement --}}
                    <div class="vote-step" id="step-2">
                        <p class="text-center text-muted mb-4">Choisissez votre moyen de paiement</p>
                        <div class="row g-3 justify-content-center">
                            <div class="col-6 col-md-4">
                                <div class="payment-option" data-method="kkiapay" onclick="choisirPaiement(this, 'kkiapay')">
                                    <img src="https://kkiapay.me/images/logo.png" alt="Kkiapay" onerror="this.style.display='none'">
                                    <div class="fw-semibold small">Kkiapay</div>
                                    <small class="text-muted">Mobile Money, Carte</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="payment-option" data-method="fedapay" onclick="choisirPaiement(this, 'fedapay')">
                                    <img src="https://fedapay.com/assets/logo.svg" alt="Fedapay" onerror="this.style.display='none'">
                                    <div class="fw-semibold small">Fedapay</div>
                                    <small class="text-muted">Mobile Money, Carte</small>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-secondary px-3" onclick="allerStep(1)">
                                <i class="bi bi-arrow-left me-1"></i> Retour
                            </button>
                            <button type="button" class="btn text-white px-4 fw-semibold" style="background: var(--vote-gold);" onclick="allerStep(3)" id="btnStep2">
                                Payer <i class="bi bi-credit-card ms-1"></i>
                            </button>
                        </div>
                    </div>

                    {{-- STEP 3 : Paiement --}}
                    <div class="vote-step" id="step-3">
                        <div class="text-center py-3">
                            <div class="spinner-border mb-3 text-primary" role="status" id="paymentSpinner">
                                <span class="visually-hidden">Chargement...</span>
                            </div>
                            <p class="fw-semibold" id="paymentStepText">Initialisation du paiement...</p>
                            <p class="small text-muted" id="paymentStepDetail">Montant à payer : <strong id="paymentMontant"></strong></p>
                        </div>

                        <div id="fedapayRedirectNotice" class="alert alert-info text-center" style="display:none;">
                            <i class="bi bi-box-arrow-up-right me-1"></i>
                            Redirection vers Fedapay...
                        </div>

                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-outline-secondary px-3" onclick="allerStep(2)" id="btnStep3Back">
                                <i class="bi bi-arrow-left me-1"></i> Retour
                            </button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
@endif

{{-- ==================== RÈGLEMENT ==================== --}}
<section class="py-4" style="background: #fdfaf5;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center">
                    <h6 class="fw-bold mb-1" style="color: #9B4D07;"><i class="bi bi-file-text me-1"></i> Règlement du vote</h6>
                    <p class="small text-muted mb-3">Consultez les conditions générales de participation et de vote du FITAB 2026.</p>
                    <div class="d-flex justify-content-center gap-2">
                        <button class="btn btn-sm px-3 fw-semibold" style="background: #9B4D07; color: #fff;" type="button" data-bs-toggle="collapse" data-bs-target="#reglementCollapse">
                            <i class="bi bi-eye me-1"></i> Lire
                        </button>
                        <a href="{{ asset('storage/documents/reglement-fitab-2026.pdf') }}" class="btn btn-sm px-3 fw-semibold" style="border: 2px solid #9B4D07; color: #9B4D07;" target="_blank">
                            <i class="bi bi-download me-1"></i> Télécharger (PDF)
                        </a>
                    </div>
                    <div class="collapse mt-3 text-start" id="reglementCollapse">
                        <div class="p-4 rounded-3" style="background: #fff; border: 1px solid #E3D5AD;">
                            <h6 class="fw-bold mb-3" style="color: #9B4D07;">Conditions de participation</h6>
                            <p class="small mb-2">1. Le vote est ouvert à toute personne physique âgée d'au moins 18 ans.</p>
                            <p class="small mb-2">2. Chaque vote est payant au tarif de <strong>{{ number_format($prixDuVote, 0, ',', ' ') }} FCFA</strong> l'unité.</p>
                            <p class="small mb-2">3. Le nombre de votes par personne n'est pas limité.</p>
                            <p class="small mb-2">4. Les votes sont définitifs et non remboursables.</p>
                            <p class="small mb-2">5. Le paiement s'effectue via Kkiapay ou Fedapay (Mobile Money ou Carte bancaire).</p>
                            <p class="small mb-0">6. Tout vote frauduleux entraîne l'annulation des votes concernés.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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

// ==================== OUVERTURE ====================
function ouvrirVote(id, nom, photo) {
    etat.candidatId = id;
    etat.candidatNom = nom;
    etat.candidatPhoto = photo;
    etat.step = 1;
    etat.paymentMethod = null;
    etat.voteId = null;

    document.getElementById('voteCandidatId').value = id;
    document.getElementById('candidatNameDisplay').textContent = nom;
    document.getElementById('candidatNameMini').textContent = nom;
    document.getElementById('candidatPriceInfo').textContent = '{{ number_format($prixDuVote, 0, ",", " ") }} FCFA / vote';
    document.getElementById('candidatPhotoPreview').src = photo || '{{ asset("images/default-user.png") }}';
    document.getElementById('quantite').value = 1;
    document.getElementById('votant_nom').value = '';
    document.getElementById('votant_email').value = '';
    document.getElementById('votant_telephone').value = '';
    document.getElementById('votePaymentMethod').value = '';
    majTotal();

    document.querySelectorAll('.payment-option').forEach(el => el.classList.remove('selected'));

    const modal = new bootstrap.Modal('#voteModal');
    modal.show();

    reinitSteps();
    allerStep(1, true);
}

// ==================== QUANTITÉ ====================
function changerQte(delta) {
    const input = document.getElementById('quantite');
    let val = parseInt(input.value) + delta;
    if (val < 1) val = 1;
    if (val > 100) val = 100;
    input.value = val;
    majTotal();
}

function majTotal() {
    const qte = parseInt(document.getElementById('quantite').value) || 1;
    const total = qte * etat.prixUnitaire;
    document.getElementById('totalDisplay').textContent = total.toLocaleString('fr-FR') + ' FCFA';
}

// ==================== ÉTAPES ====================
function reinitSteps() {
    document.querySelectorAll('.vote-step').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.step-dot').forEach(el => { el.classList.remove('active', 'done'); });
}

function allerStep(n, skipValidation) {
    if (!skipValidation) {
        if (n === 2) {
            // Step 1 → 2: validate step 1 fields
            const nom = document.getElementById('votant_nom').value.trim();
            const email = document.getElementById('votant_email').value.trim();
            const tel = document.getElementById('votant_telephone').value.trim();
            if (!nom || !email || !tel) {
                alert('Veuillez remplir tous les champs (Nom, Email, Téléphone).');
                return;
            }
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                alert('Veuillez entrer un email valide.');
                return;
            }
        }
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

    for (let i = 1; i <= 3; i++) {
        const dot = document.getElementById('step-dot-' + i);
        dot.classList.remove('active', 'done');
        if (i < n) dot.classList.add('done');
        else if (i === n) dot.classList.add('active');
    }

    const subtitles = {
        1: 'Étape 1 — Quantité & informations',
        2: 'Étape 2 — Choix du moyen de paiement',
        3: 'Étape 3 — Paiement sécurisé',
    };
    document.getElementById('voteModalSubtitle').textContent = subtitles[n] || '';

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
    formData.set('votant_nom', document.getElementById('votant_nom').value);
    formData.set('votant_email', document.getElementById('votant_email').value);
    formData.set('votant_telephone', document.getElementById('votant_telephone').value);
    formData.set('payment_method', etat.paymentMethod);
    formData.set('candidat_id', etat.candidatId);

    document.getElementById('paymentSpinner').style.display = 'inline-block';
    document.getElementById('paymentStepText').textContent = 'Enregistrement de votre vote...';
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

        const quantite = data.quantite;
        const montant = data.montant;
        document.getElementById('paymentMontant').textContent = montant.toLocaleString('fr-FR') + ' FCFA';

        if (etat.paymentMethod === 'kkiapay') {
            document.getElementById('paymentStepText').textContent = 'Ouverture de Kkiapay...';
            ouvrirKkiapay(data.vote_id, montant);
        } else if (etat.paymentMethod === 'fedapay') {
            document.getElementById('paymentStepText').textContent = 'Redirection vers Fedapay...';
            document.getElementById('fedapayRedirectNotice').style.display = 'block';
            ouvrirFedapay(data.vote_id, montant);
        }

    } catch (err) {
        document.getElementById('paymentSpinner').style.display = 'none';
        document.getElementById('paymentStepText').textContent = 'Erreur : ' + err.message;
        document.getElementById('paymentStepText').className = 'text-danger fw-semibold';
        document.getElementById('btnStep3Back').disabled = false;
    }
}

function ouvrirKkiapay(voteId, montant) {
    const apiKey = '{{ $kkiapayKey }}';
    if (!apiKey) {
        fallbackSuccess(voteId);
        return;
    }

    if (typeof Kkiapay === 'undefined') {
        const script = document.createElement('script');
        script.src = 'https://cdn.kkiapay.me/sdk/v2/kkiapay.js';
        script.onload = () => initKkiapay(apiKey, voteId, montant);
        document.head.appendChild(script);
    } else {
        initKkiapay(apiKey, voteId, montant);
    }
}

function initKkiapay(apiKey, voteId, montant) {
    try {
        new Kkiapay({
            public_key: apiKey,
            amount: montant,
            phone: document.getElementById('votant_telephone').value,
            email: document.getElementById('votant_email').value,
            reason: 'Vote FITAB #' + voteId,
            callback: '{{ route("public.vote.merci", ["vote_id" => "__VOTE_ID__"]) }}'.replace('__VOTE_ID__', voteId),
            data: { vote_id: voteId },
        }).open();
    } catch (e) {
        fallbackSuccess(voteId);
    }
}

function ouvrirFedapay(voteId, montant) {
    const apiKey = '{{ $fedapayKey }}';
    if (!apiKey) {
        fallbackSuccess(voteId);
        return;
    }

    const checkoutUrl = 'https://checkout.fedapay.com/v2?public_key=' + apiKey
        + '&amount=' + montant
        + '&currency=XOF'
        + '&description=Vote+FITAB+%23' + voteId
        + '&callback_url=' + encodeURIComponent('{{ route("public.vote.merci", ["vote_id" => "__VOTE_ID__"]) }}'.replace('__VOTE_ID__', voteId))
        + '&data[vote_id]=' + voteId;

    window.location.href = checkoutUrl;
}

function fallbackSuccess(voteId) {
    window.location.href = '{{ route("public.vote.merci") }}?vote_id=' + voteId;
}

// ==================== COMPTEUR ====================
(function() {
    const deadline = @json($voteDeadline ?? null);
    if (!deadline) return;
    const target = new Date(deadline.replace(' ', 'T')).getTime();
    if (isNaN(target)) return;

    function maj() {
        const now = new Date().getTime();
        const diff = target - now;
        if (diff <= 0) {
            document.getElementById('cd-jours').textContent = '00';
            document.getElementById('cd-heures').textContent = '00';
            document.getElementById('cd-minutes').textContent = '00';
            document.getElementById('cd-secondes').textContent = '00';
            document.getElementById('cd-expired').style.display = 'block';
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

// ==================== FILTRES CATÉGORIES ====================
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const groupes = document.querySelectorAll('.categorie-group');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const filter = this.dataset.filter;
            groupes.forEach(g => {
                if (filter === 'all' || g.dataset.categorie === filter) {
                    g.style.display = 'block';
                } else {
                    g.style.display = 'none';
                }
            });
        });
    });
});
</script>
@endpush
