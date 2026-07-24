<style>
    /* === STYLES COMMUNS (desktop + mobile) === */
    #voteModal .btn-close-modal {
        position: absolute;
        top: 0.75rem;
        right: 0.75rem;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: 1px solid #ddd;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        color: #666;
        cursor: pointer;
        transition: all 0.2s;
        z-index: 10;
    }
    #voteModal .btn-close-modal:hover { background: #f0f0f0; color: #333; }
    #voteModal .candidat-name {
        font-size: 1.15rem;
        font-weight: 700;
        color: #3E1E05;
        margin-bottom: 0.3rem;
        padding-right: 2rem;
    }
    #voteModal .candidat-bio {
        font-size: 0.78rem;
        color: #6c757d;
        line-height: 1.4;
        margin-bottom: 0.75rem;
    }
    #voteModal .ovations-label {
        font-size: 0.78rem;
        font-weight: 600;
        color: #3E1E05;
        margin-bottom: 0.4rem;
    }
    #voteModal .qte-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: 2px solid #E3D5AD;
        background: #fef0e0;
        color: #9B4D07;
        font-weight: 700;
        font-size: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        line-height: 1;
    }
    #voteModal .qte-btn:hover { background: #fde4c4; border-color: #CA7B05; }
    #voteModal .qte-btn.disabled { opacity: 0.3; pointer-events: none; }
    #voteModal .qte-input {
        width: 55px;
        text-align: center;
        font-weight: 700;
        font-size: 1.2rem;
        color: #3E1E05;
        border: none;
        background: transparent;
        outline: none;
        padding: 0.25rem;
    }
    #voteModal .total-block {
        background: #fdfaf5;
        border: 1px solid #E3D5AD;
        border-radius: 0.5rem;
        padding: 0.6rem 0.75rem;
        margin-bottom: 0.75rem;
    }
    #voteModal .total-block .price-label { font-size: 0.72rem; color: #6c757d; }
    #voteModal .total-block .price-total { font-size: 1.3rem; font-weight: 700; color: #9B4D07; }
    #voteModal .btn-payer {
        width: 100%;
        border-radius: 50px;
        padding: 0.6rem;
        font-weight: 700;
        font-size: 0.9rem;
        background: #9B4D07;
        color: #fff;
        border: none;
        transition: all 0.2s;
    }
    #voteModal .btn-payer:hover { background: #7B3D05; }
    #voteModal .note-preselection {
        font-size: 0.68rem;
        color: #6c757d;
        text-align: center;
        margin-top: auto;
        padding-top: 0.5rem;
    }
    #voteModal .note-preselection i { color: #CA7B05; }

    /* === DESKTOP === */
    @media (min-width: 768px) {
        #voteModal .modal-dialog { max-width: 700px !important; }
        #voteModal .modal-dialog.modal-narrow { max-width: 420px !important; }
        #voteModal .modal-content { min-height: 480px; }
        #voteModal .step1-photo {
            flex: 0 0 45%;
            max-width: 45%;
            position: relative;
            overflow: hidden;
            border-radius: 1rem 0 0 1rem;
        }
        #voteModal .step1-photo img { width: 100%; height: 100%; object-fit: cover; }
        #voteModal .step1-info {
            flex: 0 0 55%;
            max-width: 55%;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            position: relative;
        }
        #voteModal .step1-photo-mobile { display: none; }
        #voteModal .candidat-name { font-size: 1.25rem; margin-bottom: 0.5rem; }
    }

    /* === MOBILE === */
    @media (max-width: 767.98px) {
        #voteModal .modal-dialog { max-width: 100% !important; margin: 0.5rem !important; }
        #voteModal .modal-content { display: block !important; border-radius: 16px !important; }
        #voteModal .step1-photo { display: none !important; }
        #voteModal .step1-photo-mobile { display: block !important; }
        #voteModal .step1-photo-mobile img {
            width: calc(100% - 2rem) !important;
            margin: 0 1rem !important;
            height: 140px !important;
            object-fit: cover !important;
            border-radius: 10px !important;
        }
        #voteModal .step1-info {
            flex: unset !important;
            max-width: 100% !important;
            padding: 0.75rem 1rem 1rem !important;
            position: relative !important;
        }
        #voteModal .candidat-name { font-size: 0.95rem; margin-bottom: 0.25rem; padding-right: 2.5rem; }
        #voteModal .candidat-bio { font-size: 0.72rem; margin-bottom: 0.5rem; }
        #voteModal .qte-btn { width: 34px; height: 34px; font-size: 0.95rem; }
        #voteModal .qte-input { width: 50px; font-size: 1.1rem; }
        #voteModal .total-block { padding: 0.5rem 0.6rem; margin-bottom: 0.6rem; }
        #voteModal .total-block .price-total { font-size: 1.1rem; }
        #voteModal .btn-payer { padding: 0.65rem; font-size: 0.95rem; }
        #voteModal .note-preselection { font-size: 0.65rem; }
        #voteModal #step-2, #voteModal #step-3 { padding: 1rem !important; }
        #voteModal #step-2 .payment-option { min-width: 140px !important; }
    }
</style>

{{-- ==================== MODAL VOTE ==================== --}}
@if($voteMode === 'active')
<div class="modal fade" id="voteModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 400px;">
        <div class="modal-content border-0 rounded-4 overflow-hidden">

            <div class="modal-body p-0">
                <form id="voteForm" method="POST">
                    @csrf
                    <input type="hidden" name="candidat_id" id="voteCandidatId">
                    <input type="hidden" name="payment_method" id="votePaymentMethod">

                    {{-- STEP 1 : Fiche candidat + quantité --}}
                    <div class="vote-step active" id="step-1">

                        {{-- Colonne gauche : Photo (desktop) --}}
                        <div class="step1-photo">
                            <img id="candidatPhotoPreview" src="" alt="Photo du candidat">
                        </div>

                        {{-- Colonne droite : Infos --}}
                        <div class="step1-info">

                            {{-- Photo mobile --}}
                            <div class="step1-photo-mobile text-center mb-2">
                                <img id="candidatPhotoPreviewMobile" src="" alt="Photo du candidat"
                                     class="rounded-2 shadow" style="width: 100%; height: 140px; object-fit: cover;">
                            </div>

                            {{-- Bouton fermer --}}
                            <button type="button" class="btn-close-modal" data-bs-dismiss="modal">
                                <i class="bi bi-x-lg"></i>
                            </button>

                            {{-- Nom --}}
                            <div class="candidat-name" id="candidatNameMini"></div>

                            {{-- Badges --}}
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge px-2 py-1" id="candidatCategoryInfo"
                                      style="background: #9B4D07; font-weight: 500; font-size: 0.72rem;"></span>
                                <span class="badge px-2 py-1" id="candidatNumero"
                                      style="background: #3E1E05; font-weight: 500; font-size: 0.72rem;"></span>
                            </div>

                            {{-- Biographie --}}
                            <div class="candidat-bio" id="candidatBio"></div>

                            <hr class="my-2" style="border-color: #E3D5AD; opacity: 0.5;">

                            {{-- Compteur ovations --}}
                            <div class="mb-3">
                                <div class="ovations-label">Nombre d'ovations</div>
                                <div class="d-flex align-items-center gap-3">
                                    <button type="button" onclick="changerQte(-1)" id="btnMoins"
                                            class="qte-btn disabled">−</button>
                                    <input type="number" id="quantite" name="quantite"
                                           value="1" min="1" max="1000"
                                           class="qte-input"
                                           oninput="saisirQte(this)">
                                    <button type="button" onclick="changerQte(1)" id="btnPlus"
                                            class="qte-btn">+</button>
                                </div>
                            </div>

                            {{-- Total --}}
                            <div class="total-block">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="price-label">
                                        Prix unitaire : <strong>{{ number_format($prixDuVote, 0, ',', ' ') }} FCFA</strong>
                                    </span>
                                    <span class="price-total">
                                        <span id="totalDisplay">{{ number_format($prixDuVote, 0, ',', ' ') }}</span> FCFA
                                    </span>
                                </div>
                            </div>

                            {{-- Bouton payer --}}
                            <button type="button" class="btn-payer" onclick="allerStep(2)">
                                Payer <i class="bi bi-arrow-right ms-1"></i>
                            </button>

                            {{-- Note présélections --}}
                            <div class="note-preselection">
                                <i class="bi bi-megaphone-fill me-1"></i>
                                {{ $site['texte_info_vote'] }}
                            </div>
                        </div>
                    </div>

                    {{-- STEP 2 : Choix agrégateur --}}
                    <div class="vote-step" id="step-2">

                        {{-- Photo candidat --}}
                        <div class="text-center mb-2">
                            <div class="rounded-2 overflow-hidden shadow mx-auto d-block" style="width: 70px; height: 70px; border: 2px solid #E3D5AD;">
                                <img id="step2CandidatPhoto" src="" alt="Photo du candidat"
                                     style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        </div>

                        {{-- Résumé commande --}}
                        <div class="d-flex justify-content-between align-items-center p-2 mb-3 rounded-3"
                             style="background: #fdfaf5; border: 1px solid #E3D5AD;">
                            <div>
                                <small class="text-muted d-block" id="step2CandidatNom" style="font-size: 0.75rem;"></small>
                                <strong id="step2Quantite" style="color: #3E1E05; font-size: 0.85rem;">1 ovation</strong>
                            </div>
                            <strong id="step2Total" style="color: #9B4D07; font-size: 0.95rem;">
                                {{ number_format($prixDuVote, 0, ',', ' ') }} FCFA
                            </strong>
                        </div>

                        {{-- Titre moyen de paiement --}}
                        <div class="text-center mb-3">
                            <h6 class="fw-bold mb-0" style="color: #3E1E05; font-size: 0.85rem;">
                                Moyen de paiement <span class="text-danger ms-1">*</span>
                            </h6>
                            <small class="text-muted" style="font-size: 0.7rem;">Choisissez votre moyen de paiement</small>
                        </div>

                        @php
                            $fedapayActive = !empty($fedapayKey);
                        @endphp

                        @if($fedapayActive)
                            <div class="text-center py-1 mb-2">
                                <div class="payment-option d-inline-block" data-method="fedapay" style="min-width: 180px;"
                                     onclick="choisirPaiement(this, 'fedapay')">
                                    <img src="https://fedapay.com/assets/logo.svg"
                                         alt="Fedapay"
                                         onerror="this.style.display='none'"
                                         style="height: 30px;">
                                    <div class="fw-semibold small mt-1">Fedapay</div>
                                    <small class="text-muted" style="font-size: 0.65rem;">MTN · Moov · Orange Money</small>
                                </div>
                                <p class="text-muted small mt-2 mb-2" style="font-size: 0.7rem;">
                                    Paiement sécurisé via Fedapay
                                </p>
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button"
                                            class="btn btn-outline-secondary rounded-pill"
                                            style="font-size: 0.8rem; padding: 0.3rem 1rem;"
                                            onclick="allerStep(1)">
                                        <i class="bi bi-arrow-left me-1"></i> Retour
                                    </button>
                                    <button type="button"
                                            class="btn fw-bold text-white rounded-pill"
                                            style="background: #9B4D07; font-size: 0.8rem; padding: 0.3rem 1rem;"
                                            onclick="payerDirect('fedapay')">
                                        Payer <i class="bi bi-credit-card ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-3">
                                <i class="bi bi-credit-card-2-front"
                                   style="font-size: 2rem; color: #adb5bd;"></i>
                                <p class="fw-semibold mt-1 mb-1" style="color: #6c757d; font-size: 0.85rem;">
                                    Paiement temporairement indisponible
                                </p>
                                <p class="small text-muted mb-2" style="font-size: 0.7rem;">
                                    Aucun moyen de paiement n'est configuré pour le moment.
                                </p>
                                <button type="button"
                                        class="btn btn-outline-secondary rounded-pill"
                                        style="font-size: 0.8rem; padding: 0.3rem 1rem;"
                                        onclick="allerStep(1)">
                                    <i class="bi bi-arrow-left me-1"></i> Retour
                                </button>
                            </div>
                        @endif
                    </div>

                    {{-- STEP 3 : Traitement paiement --}}
                    <div class="vote-step" id="step-3">
                        <div class="text-center py-4">
                            <div class="spinner-border mb-2" role="status" id="paymentSpinner"
                                 style="width: 2.5rem; height: 2.5rem; color: #9B4D07; display: none;">
                                <span class="visually-hidden">Chargement...</span>
                            </div>
                            <div id="paymentSuccessIcon" style="display: none;">
                                <i class="bi bi-check-circle-fill"
                                   style="font-size: 2.5rem; color: #198754;"></i>
                            </div>
                            <p class="fw-semibold mt-2" id="paymentStepText" style="color: #3E1E05; font-size: 0.85rem;">
                                Préparation du paiement...
                            </p>
                            <p class="small text-muted" id="paymentStepDetail" style="font-size: 0.75rem;">
                                Montant : <strong id="paymentMontant" style="color: #9B4D07;"></strong>
                            </p>
                            <button type="button"
                                    class="btn btn-sm btn-link text-muted mt-1"
                                    onclick="allerStep(2)"
                                    id="btnStep3Back"
                                    style="font-size: 0.75rem;">
                                <i class="bi bi-arrow-left me-1"></i> Retour
                            </button>
                            <button type="button" id="btnPayerFedapay" style="display:none;">Payer</button>
                        </div>
                    </div>

                </form>

                {{-- Info présélections (mobile only) --}}
                <div class="px-3 mt-2 step1-photo-mobile">
                    <div class="p-1 rounded-2 text-center" style="background: #fdfaf5; border-left: 3px solid #9B4D07; font-size: 0.65rem;">
                        <i class="bi bi-megaphone-fill me-1" style="color: #CA7B05;"></i>
                        <span style="color: #5F2B0C;">{{ $site['texte_info_vote'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
