{{-- ==================== MODAL VOTE ==================== --}}
@if($voteMode === 'active')
<div class="modal fade" id="voteModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
        <div class="modal-content border-0 rounded-4 overflow-hidden">

            {{-- En-tête simple --}}
            <div class="px-4 pt-4 pb-3 position-relative" style="background: linear-gradient(135deg, #3E1E05, #9B4D07);">
                <h5 class="fw-bold text-white mb-0 text-center" id="voteModalTitle">
                    Ovationner <span id="candidatNameDisplay"></span>                </h5>
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 mt-3 me-3" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4 pb-4 pt-3">
                <form id="voteForm" method="POST">
                    @csrf
                    <input type="hidden" name="candidat_id" id="voteCandidatId">
                    <input type="hidden" name="payment_method" id="votePaymentMethod">
                    <input type="hidden" name="votant_nom" id="votant_nom">
                    <input type="hidden" name="votant_email" id="votant_email">
                    <input type="hidden" name="votant_telephone" id="votant_telephone">

                    {{-- STEP 1 : Fiche candidat + quantité --}}
                    <div class="vote-step active" id="step-1">

                        {{-- Photo + infos candidat --}}
                        <div class="text-center">
                            <img id="candidatPhotoPreview"
                                 src=""
                                 alt=""
                                 class="rounded-3 shadow"
                                 style="max-width: 100%; max-height: 260px; object-fit: contain;">
                            <div class="mt-3">
                                <div class="fw-bold" style="font-size: 1.25rem; color: #3E1E05;" id="candidatNameMini"></div>
                                <div class="d-flex align-items-center justify-content-center gap-2 mt-1">
                                    <span class="badge px-3 py-1" id="candidatCategoryInfo"
                                          style="background: #9B4D07; font-weight: 500;"></span>
                                    <span class="badge px-3 py-1" id="candidatNumero"
                                          style="background: #3E1E05; font-weight: 500; letter-spacing: 0.5px;"></span>
                                </div>
                                <div class="small mt-2" id="candidatVoteCount"
                                     style="color: #CA7B05;"></div>
                                <div class="small text-muted mt-2 px-3" id="candidatBio"
                                     style="font-style: italic; line-height: 1.4;"></div>
                            </div>
                        </div>

                        <hr class="my-3" style="border-color: #E3D5AD; opacity: 0.5;">

                        {{-- Compteur votes --}}
                        <div class="text-center mb-3">
                            <label class="fw-semibold mb-2 d-block small" style="color: #3E1E05;">
                                Nombre d'ovations
                            </label>
                            <div class="d-inline-flex align-items-center gap-3">
                                <button type="button" onclick="changerQte(-1)"
                                        id="btnMoins"
                                        class="btn rounded-circle fw-bold d-flex align-items-center justify-content-center"
                                        style="width: 42px; height: 42px; background: #fef0e0; color: #9B4D07; border: 2px solid #E3D5AD; font-size: 1.3rem; opacity: 0.3; pointer-events: none;">
                                    −
                                </button>
                                <input type="number" id="quantite" name="quantite"
                                       value="1" min="1" max="1000"
                                       class="form-control text-center fw-bold border-0"
                                       style="width: 90px; font-size: 1.8rem; color: #3E1E05; background: transparent;"
                                       oninput="saisirQte(this)">
                                <button type="button" onclick="changerQte(1)"
                                        id="btnPlus"
                                        class="btn rounded-circle fw-bold d-flex align-items-center justify-content-center"
                                        style="width: 42px; height: 42px; background: #fef0e0; color: #9B4D07; border: 2px solid #E3D5AD; font-size: 1.3rem;">
                                    +
                                </button>
                            </div>
                        </div>

                        {{-- Total --}}
                        <div class="text-center mb-4 p-3 rounded-3"
                             style="background: #fdfaf5; border: 1px solid #E3D5AD;">
                            <small class="text-muted d-block mb-1">
                                Prix unitaire : <strong>{{ number_format($prixDuVote, 0, ',', ' ') }} FCFA</strong>
                            </small>
                            <div class="fw-bold" style="font-size: 1.6rem; color: #9B4D07;">
                                <span id="totalDisplay">{{ number_format($prixDuVote, 0, ',', ' ') }}</span> FCFA
                            </div>
                        </div>

                        <button type="button"
                                class="btn w-100 fw-bold text-white rounded-pill py-2"
                                style="background: #9B4D07;"
                                onclick="allerStep(2)">
                            Payer <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                    </div>

                    {{-- STEP 2 : Choix agrégateur --}}
                    <div class="vote-step" id="step-2">

                        {{-- Photo candidat --}}
                        <div class="text-center mb-3">
                            <div class="rounded-3 overflow-hidden shadow mx-auto d-block" style="width: 100px; height: 100px; border: 2px solid #E3D5AD;">
                                <img id="step2CandidatPhoto"
                                     src=""
                                     alt=""
                                     style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        </div>

                        {{-- Titre moyen de paiement --}}
                        <div class="text-center mb-4">
                            <h6 class="fw-bold mb-0" style="color: #3E1E05;">
                                Moyen de paiement
                                <span class="text-danger ms-1">*</span>
                            </h6>
                            <small class="text-muted">Choisissez votre passerelle de paiement</small>
                        </div>

                        {{-- Résumé commande --}}
                        <div class="d-flex justify-content-between align-items-center p-3 mb-4 rounded-3"
                             style="background: #fdfaf5; border: 1px solid #E3D5AD;">
                            <div>
                                <small class="text-muted d-block" id="step2CandidatNom"></small>
                                <strong id="step2Quantite" style="color: #3E1E05;">1 ovation</strong>
                            </div>
                            <strong id="step2Total" style="color: #9B4D07; font-size: 1.1rem;">
                                {{ number_format($prixDuVote, 0, ',', ' ') }} FCFA
                            </strong>
                        </div>

                        @php
                            $fedapayActive = !empty($fedapayKey);
                        @endphp

                        @if($fedapayActive)
                            <div class="text-center py-2 mb-4">
                                <div class="payment-option d-inline-block" data-method="fedapay" style="min-width: 200px;"
                                     onclick="choisirPaiement(this, 'fedapay')">
                                    <img src="https://fedapay.com/assets/logo.svg"
                                         alt="Fedapay"
                                         onerror="this.style.display='none'">
                                    <div class="fw-semibold small mt-2">Fedapay</div>
                                    <small class="text-muted">MTN · Moov · Orange Money</small>
                                </div>
                                <p class="text-muted small mt-3 mb-3">
                                    Paiement sécurisé via Fedapay
                                </p>
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button"
                                            class="btn btn-outline-secondary rounded-pill px-4"
                                            onclick="allerStep(1)">
                                        <i class="bi bi-arrow-left me-1"></i> Retour
                                    </button>
                                    <button type="button"
                                            class="btn fw-bold text-white rounded-pill px-4"
                                            style="background: #9B4D07;"
                                            onclick="payerDirect('fedapay')">
                                        Payer <i class="bi bi-credit-card ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-credit-card-2-front"
                                   style="font-size: 2.5rem; color: #adb5bd;"></i>
                                <p class="fw-semibold mt-2 mb-1" style="color: #6c757d;">
                                    Paiement temporairement indisponible
                                </p>
                                <p class="small text-muted mb-3">
                                    Aucun moyen de paiement n'est configuré pour le moment.
                                </p>
                                <button type="button"
                                        class="btn btn-outline-secondary rounded-pill px-4"
                                        onclick="allerStep(1)">
                                    <i class="bi bi-arrow-left me-1"></i> Retour
                                </button>
                            </div>
                        @endif
                    </div>

                    {{-- STEP 3 : Traitement paiement --}}
                    <div class="vote-step" id="step-3">
                        <div class="text-center py-5">
                            <div class="spinner-border mb-3" role="status" id="paymentSpinner"
                                 style="width: 3rem; height: 3rem; color: #9B4D07; display: none;">
                                <span class="visually-hidden">Chargement...</span>
                            </div>
                            <div id="paymentSuccessIcon" style="display: none;">
                                <i class="bi bi-check-circle-fill"
                                   style="font-size: 3rem; color: #198754;"></i>
                            </div>
                            <p class="fw-semibold mt-2" id="paymentStepText" style="color: #3E1E05;">
                                Préparation du paiement...
                            </p>
                            <p class="small text-muted" id="paymentStepDetail">
                                Montant : <strong id="paymentMontant" style="color: #9B4D07;"></strong>
                            </p>
                            <div id="fedapayRedirectNotice"
                                 class="alert alert-info text-center small py-2 mt-2"
                                 style="display:none; border-radius: 12px;">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Redirection vers Fedapay...
                            </div>
                            <button type="button"
                                    class="btn btn-sm btn-link text-muted mt-2"
                                    onclick="allerStep(2)"
                                    id="btnStep3Back">
                                <i class="bi bi-arrow-left me-1"></i> Retour
                            </button>
                        </div>
                    </div>

                </form>

                {{-- Info présélections --}}
                <div class="px-2 mt-2">
                    <div class="p-2 rounded-3 text-center" style="background: #fdfaf5; border-left: 3px solid #9B4D07; font-size: 0.75rem;">
                        <i class="bi bi-megaphone-fill me-1" style="color: #CA7B05;"></i>
                        <span style="color: #5F2B0C;">L'entrée aux présélections est libre et gratuite. Les ovations sont un critère officiel de sélection.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif