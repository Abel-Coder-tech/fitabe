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
        border-radius: 16px;
        overflow: hidden;
    }
    .candidate-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 8px 28px rgba(160,132,92,0.12);
    }
    .candidate-card .candidat-photo {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #fff;
        box-shadow: 0 3px 12px rgba(0,0,0,0.08);
        margin-top: -45px;
        position: relative;
        z-index: 1;
    }
    .candidate-card .candidat-num {
        position: absolute;
        top: 10px;
        left: 10px;
        background: rgba(62,30,5,0.85);
        color: #fff;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
        z-index: 2;
        letter-spacing: 0.3px;
    }
    .candidate-card .candidat-cover {
        height: 110px;
        background: linear-gradient(135deg, #3E1E05, #9B4D07);
        position: relative;
    }
    .candidate-card .candidat-cover img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.35;
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
                            <div class="card candidate-card h-100 shadow-sm text-center">
                                {{-- Cover band + "Candidat N°X" badge --}}
                                <div class="candidat-cover">
                                    
                                    @if($candidat->numero_scene)
                                        <span class="candidat-num">Candidat N°{{ $candidat->numero_scene }}</span>
                                    @endif
                                </div>
                                {{-- Circular photo --}}
                                @if($candidat->photo)
                                    <img src="{{ $candidat->photo_url }}" class="candidat-photo mx-auto" alt="{{ $candidat->display_name }}">
                                @else
                                    <div class="candidat-photo mx-auto d-flex align-items-center justify-content-center bg-light" style="border-radius:50%; width:90px; height:90px; margin-top:-45px; border:3px solid #fff; box-shadow:0 3px 12px rgba(0,0,0,0.08);">
                                        <i class="bi bi-person fs-3 text-muted"></i>
                                    </div>
                                @endif
                                <div class="card-body d-flex flex-column px-3 pt-2 pb-3">
                                    {{-- Name + stage name --}}
                                    <h6 class="fw-bold mb-0" style="color: var(--vote-brown);">{{ $candidat->display_name }}</h6>
                                    {{-- Motivation / biographie (truncated) --}}
                                    @if($candidat->biographie)
                                        <p class="small text-muted mb-2 mt-1" style="font-style: italic; line-height: 1.3; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                            {{ \Illuminate\Support\Str::limit($candidat->biographie, 80) }}
                                        </p>
                                    @endif
                                    {{-- Vote count --}}
                                    <p class="vote-count mb-2 mt-auto">
                                        <i class="bi bi-heart-fill" style="color: var(--vote-gold);"></i>
                                        {{ $candidat->votes_count ?? 0 }} vote(s)
                                    </p>
                                    {{-- Buttons --}}
                                    @if($voteMode === 'active')
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn flex-fill text-white fw-semibold btn-sm" style="background: var(--vote-gold); border-radius: 50px;"
                                                    onclick='ouvrirVote({{ $candidat->id }}, {!! json_encode($candidat->display_name) !!}, {!! json_encode($candidat->photo_url) !!}, {{ $candidat->votes_count ?? 0 }}, {!! json_encode($candidat->categorie ?? '') !!}, {!! json_encode(Str::limit($candidat->biographie ?? '', 120)) !!})'>
                                                Voter <i class="bi bi-check-circle ms-1"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm fw-semibold" style="border: 2px solid var(--vote-gold-light); color: var(--vote-gold-light); border-radius: 50px; flex: 0 0 auto; padding: 0.25rem 0.8rem;"
                                                    onclick='partagerCandidat({{ $candidat->id }}, {!! json_encode($candidat->display_name) !!})'>
                                                <i class="bi bi-share-fill"></i>
                                            </button>
                                        </div>
                                    @else
                                        <button type="button" class="btn btn-secondary w-100 btn-sm" disabled style="border-radius: 50px;">
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

@include('public.vote.modal')

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
function ouvrirVote(id, nom, photo, votesCount, categorie, bio) {
    etat.candidatId = id;
    etat.candidatNom = nom;
    etat.candidatPhoto = photo;
    etat.step = 1;
    etat.paymentMethod = null;
    etat.voteId = null;

    document.getElementById('voteCandidatId').value = id;
    document.getElementById('candidatNameDisplay').textContent = nom;
    document.getElementById('candidatNameMini').textContent = nom;
    document.getElementById('candidatCategoryInfo').textContent = categorie || '';
    document.getElementById('candidatVoteCount').innerHTML = '<i class="bi bi-heart-fill" style="color: #9B4D07;"></i> ' + (votesCount || 0) + ' vote' + ((votesCount || 0) > 1 ? 's' : '');
    document.getElementById('candidatBio').textContent = bio || '';
    document.getElementById('candidatPhotoPreview').src = photo || '{{ asset("images/default-user.png") }}';
    document.getElementById('step2CandidatNom').textContent = nom;
    // Pré-remplir les champs votant (cachés)
    document.getElementById('votant_nom').value = 'Anonyme';
    document.getElementById('votant_email').value = 'anonyme@vote.fitab';
    document.getElementById('votant_telephone').value = '0000000000';
    document.getElementById('quantite').value = 1;
    setTimeout(function() { changerQte(0); }, 10);
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
    input.value = val;
    // Désactiver le bouton − à 1
    const btnMoins = document.getElementById('btnMoins');
    if (btnMoins) {
        btnMoins.style.opacity = val <= 1 ? '0.3' : '1';
        btnMoins.style.pointerEvents = val <= 1 ? 'none' : 'auto';
    }
    majTotal();
}

function saisirQte(input) {
    let val = parseInt(input.value);
    if (isNaN(val) || val < 1) { input.value = 1; val = 1; }
    const btnMoins = document.getElementById('btnMoins');
    if (btnMoins) {
        btnMoins.style.opacity = val <= 1 ? '0.3' : '1';
        btnMoins.style.pointerEvents = val <= 1 ? 'none' : 'auto';
    }
    majTotal();
}

function majTotal() {
    const qte = parseInt(document.getElementById('quantite').value) || 1;
    const total = qte * etat.prixUnitaire;
    document.getElementById('totalDisplay').textContent = total.toLocaleString('fr-FR');
    const step2Total = document.getElementById('step2Total');
    if (step2Total) step2Total.textContent = total.toLocaleString('fr-FR') + ' FCFA';
    const step2Qte = document.getElementById('step2Quantite');
    if (step2Qte) step2Qte.textContent = qte + ' vote' + (qte > 1 ? 's' : '');
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

function fallbackSuccess(voteId) {
    document.getElementById('paymentSpinner').style.display = 'none';
    document.getElementById('paymentSuccessIcon').style.display = 'block';
    document.getElementById('paymentStepText').textContent = 'Votre vote a bien été enregistré !';
    document.getElementById('paymentStepText').style.color = '#198754';
    document.getElementById('paymentStepDetail').innerHTML = 'Merci pour votre participation.';
    document.getElementById('btnStep3Back').disabled = true;
    setTimeout(function() {
        window.location.href = '{{ route("public.vote.merci") }}?vote_id=' + voteId;
    }, 1500);
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

// ==================== PARTAGER ====================
function partagerCandidat(id, nom) {
    const url = window.location.href.split('#')[0].split('?')[0] + '?candidat=' + id;
    if (navigator.share) {
        navigator.share({ title: nom, text: 'Votez pour ' + nom + ' au FITAB !', url: url });
    } else {
        navigator.clipboard.writeText(url).then(function() {
            alert('Lien copié ! Partagez-le pour soutenir ' + nom);
        });
    }
}

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
