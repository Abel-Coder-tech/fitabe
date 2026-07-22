<style>
    @media (min-width: 768px) {
        #voteModal .modal-dialog { max-width: 600px !important; }
        #voteModal .step1-grid { display: flex; gap: 1rem; align-items: flex-start; }
        #voteModal .step1-photo { flex: 0 0 200px; max-width: 200px; }
        #voteModal .step1-photo img { max-height: 240px !important; }
        #voteModal .step1-info { flex: 1; min-width: 0; }
    }
    @media (max-width: 767.98px) {
        #voteModal .step1-grid { display: block; }
        #voteModal .step1-photo, #voteModal .step1-info { flex: unset; max-width: 100%; }
    }
</style>

{{-- ==================== MODAL VOTE ==================== --}}
@if($voteMode === 'active')
<div class="modal fade" id="voteModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 400px;">
        <div class="modal-content border-0 rounded-4 overflow-hidden">

            {{-- En-tête --}}
            <div class="px-3 pt-3 pb-2 position-relative" style="background: linear-gradient(135deg, #3E1E05, #9B4D07);">
                <h6 class="fw-bold text-white mb-0 text-center" id="voteModalTitle">
                    Ovationner <span id="candidatNameDisplay"></span>
                </h6>
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 mt-2 me-2" style="font-size: 0.75rem;" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-3 pb-3 pt-2">
                <form id="voteForm" method="POST">
                    @csrf
                    <input type="hidden" name="candidat_id" id="voteCandidatId">
                    <input type="hidden" name="payment_method" id="votePaymentMethod">

                    {{-- STEP 1 : Fiche candidat + quantité --}}
                    <div class="vote-step active" id="step-1">

                        <div class="step1-grid">

                            {{-- Colonne gauche : Photo --}}
                            <div class="step1-photo text-center">
                                <img id="candidatPhotoPreview"
                                     src=""
                                     alt="Photo du candidat"
                                     class="rounded-2 shadow w-100"
                                     style="max-height: 100px; object-fit: contain;">
                            </div>

                            {{-- Colonne droite : Infos --}}
                            <div class="step1-info">
                                <div class="fw-bold" style="font-size: 1rem; color: #3E1E05;" id="candidatNameMini"></div>
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    <span class="badge px-2 py-1" id="candidatCategoryInfo"
                                          style="background: #9B4D07; font-weight: 500; font-size: 0.7rem;"></span>
                                    <span class="badge px-2 py-1" id="candidatNumero"
                                          style="background: #3E1E05; font-weight: 500; font-size: 0.7rem;"></span>
                                </div>
                                <div class="small mt-1" id="candidatVoteCount"
                                     style="color: #CA7B05; font-size: 0.75rem;"></div>
                                <div class="small text-muted mt-1" id="candidatBio"
                                     style="font-style: italic; line-height: 1.3; font-size: 0.75rem;"></div>
                            </div>
                        </div>

                        <hr class="my-2" style="border-color: #E3D5AD; opacity: 0.5;">

                        {{-- Compteur votes --}}
                        <div class="text-center mb-2">
                            <label class="fw-semibold mb-1 d-block" style="color: #3E1E05; font-size: 0.8rem;">
                                Nombre d'ovations
                            </label>
                            <div class="d-inline-flex align-items-center gap-2">
                                <button type="button" onclick="changerQte(-1)"
                                        id="btnMoins"
                                        class="btn rounded-circle fw-bold d-flex align-items-center justify-content-center"
                                        style="width: 32px; height: 32px; background: #fef0e0; color: #9B4D07; border: 2px solid #E3D5AD; font-size: 0.9rem; opacity: 0.3; pointer-events: none; line-height: 1;">
                                    −
                                </button>
                                <input type="number" id="quantite" name="quantite"
                                       value="1" min="1" max="1000"
                                       class="form-control text-center fw-bold border-0"
                                       style="width: 60px; font-size: 1.3rem; color: #3E1E05; background: transparent; padding: 0.15rem;"
                                       oninput="saisirQte(this)">
                                <button type="button" onclick="changerQte(1)"
                                        id="btnPlus"
                                        class="btn rounded-circle fw-bold d-flex align-items-center justify-content-center"
                                        style="width: 32px; height: 32px; background: #fef0e0; color: #9B4D07; border: 2px solid #E3D5AD; font-size: 0.9rem; line-height: 1;">
                                    +
                                </button>
                            </div>
                        </div>

                        {{-- Total --}}
                        <div class="text-center mb-3 p-2 rounded-3"
                             style="background: #fdfaf5; border: 1px solid #E3D5AD;">
                            <small class="text-muted d-block mb-1" style="font-size: 0.7rem;">
                                Prix unitaire : <strong>{{ number_format($prixDuVote, 0, ',', ' ') }} FCFA</strong>
                            </small>
                            <div class="fw-bold" style="font-size: 1.15rem; color: #9B4D07;">
                                <span id="totalDisplay">{{ number_format($prixDuVote, 0, ',', ' ') }}</span> FCFA
                            </div>
                        </div>

                        <button type="button"
                                class="btn w-100 fw-bold text-white rounded-pill py-1"
                                style="background: #9B4D07; font-size: 0.85rem;"
                                onclick="allerStep(2)">
                            Payer <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                    </div>

                    {{-- STEP 2 : Choix agrégateur --}}
                    <div class="vote-step" id="step-2">

                        {{-- Photo candidat --}}
                        <div class="text-center mb-2">
                            <div class="rounded-2 overflow-hidden shadow mx-auto d-block" style="width: 70px; height: 70px; border: 2px solid #E3D5AD;">
                                <img id="step2CandidatPhoto"
                                     src=""
                                     alt="Photo du candidat"
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

                {{-- Info présélections --}}
                <div class="px-1 mt-2">
                    <div class="p-1 rounded-2 text-center" style="background: #fdfaf5; border-left: 3px solid #9B4D07; font-size: 0.65rem;">
                        <i class="bi bi-megaphone-fill me-1" style="color: #CA7B05;"></i>
                        <span style="color: #5F2B0C;">L'entrée aux présélections est libre et gratuite. Les ovations sont un critère officiel de sélection.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
