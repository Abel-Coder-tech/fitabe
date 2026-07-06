@extends('layouts.admin')

@section('title', 'Votes')

@push('styles')
<style>
    .vote-control-panel {
        background: linear-gradient(135deg, #1a1a2e, #16213e, #0f3460);
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
    }
    .vote-control-panel label {
        color: rgba(255,255,255,0.5);
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: block;
        margin-bottom: 4px;
    }
    .vote-control-panel .form-control-sm {
        border-radius: 10px;
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.08);
        color: #fff;
        font-size: 0.85rem;
        padding: 0.4rem 0.7rem;
    }
    .vote-control-panel .form-control-sm:focus {
        background: rgba(255,255,255,0.1);
        border-color: #e94560;
        box-shadow: none;
    }
    .vote-control-panel .form-control-sm::placeholder {
        color: rgba(255,255,255,0.3);
    }
    .vote-toggle-btn {
        border: none;
        border-radius: 50px;
        min-width: 120px;
        font-weight: 700;
        font-size: 0.85rem;
        padding: 0.45rem 1.2rem;
        transition: all 0.3s;
    }
    .vote-toggle-btn:active {
        transform: scale(0.96);
    }
    .form-switch .form-check-input {
        width: 44px;
        height: 24px;
        cursor: pointer;
        border-radius: 50px;
        background-color: rgba(255,255,255,0.15);
        border-color: transparent;
    }
    .form-switch .form-check-input:checked {
        background-color: #198754;
        border-color: #198754;
    }
    .stats-badge {
        background: rgba(255,255,255,0.06);
        border-radius: 50px;
        padding: 0.25rem 0.85rem;
        font-size: 0.8rem;
        color: rgba(255,255,255,0.7);
    }
    .stats-badge i {
        margin-right: 4px;
    }
    @media (max-width: 767.98px) {
        .vote-control-panel .col-auto,
        .vote-control-panel .col {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }
</style>
@endpush

@section('content')

{{-- ==================== PANNEAU CONTRÔLE VOTE ==================== --}}
<div class="vote-control-panel mb-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <div>
            <h6 class="fw-bold mb-0 text-white" style="font-size: 0.95rem;">
                <i class="bi bi-sliders2 me-2" style="color: #e94560;"></i>Contrôle du vote
            </h6>
            <small class="text-white-50">Modifications instantanées — panneau administrateur.</small>
        </div>
        <div class="d-flex gap-2">
            <span class="stats-badge"><i class="bi bi-people-fill"></i>{{ \App\Models\Candidats::count() }} candidat(s)</span>
            <span class="stats-badge"><i class="bi bi-heart-fill" style="color: #e94560;"></i>{{ \App\Models\Candidats::sum('nombre_votes') }} vote(s)</span>
        </div>
    </div>

    <form id="voteSettingsForm" class="row g-3 align-items-end">
        @csrf

        <div class="col-auto">
            <label>État du vote</label>
            <button type="button" id="voteToggleBtn"
                    class="btn vote-toggle-btn d-flex align-items-center gap-2"
                    style="background: {{ $voteMode === 'active' ? '#198754' : ($voteMode === 'cloture' ? '#e94560' : '#6c757d') }}; color: #fff;">
                <span id="voteToggleDot" class="rounded-circle d-inline-block" style="width: 10px; height: 10px; background: {{ $voteMode === 'active' ? '#fff' : '#adb5bd' }}; transition: all 0.3s;"></span>
                <span id="voteToggleText">{{ $voteMode === 'active' ? 'ACTIF' : ($voteMode === 'cloture' ? 'CLÔTURÉ' : 'OFF') }}</span>
                <i class="bi bi-arrow-repeat ms-auto small"></i>
            </button>
            <input type="hidden" name="vote_mode" id="vote_mode" value="{{ $voteMode }}">
        </div>

        <div class="col">
            <label>Prix / vote (FCFA)</label>
            <input type="number" name="prix_du_vote" id="prix_du_vote"
                   class="form-control form-control-sm"
                   value="{{ $prixDuVote }}" min="50">
        </div>

        <div class="col">
            <label>Date limite</label>
            <input type="datetime-local" name="vote_deadline" id="vote_deadline"
                   class="form-control form-control-sm"
                   value="{{ $voteDeadline ? str_replace(' ', 'T', $voteDeadline) : '' }}">
        </div>

        <div class="col-auto">
            <label>Compteur</label>
            <div class="form-check form-switch ps-0 m-0 d-flex align-items-center" style="min-height: 31px;">
                <input type="hidden" name="afficher_compteur" value="0">
                <input type="checkbox" class="form-check-input" id="afficher_compteur" name="afficher_compteur"
                       value="1" role="switch" {{ $afficherCompteur ? 'checked' : '' }}>
            </div>
        </div>

        <div class="col-auto">
            <label>&nbsp;</label>
            <button type="submit" class="btn btn-sm fw-semibold border-0 rounded-pill px-3"
                    style="background: #e94560; color: #fff;" id="voteSettingsSaveBtn">
                <i class="bi bi-check-lg me-1"></i> Appliquer
            </button>
        </div>
    </form>

    <div id="voteSettingsToast" class="mt-2 small fw-semibold" style="display:none; color: #4ade80;">
        <i class="bi bi-check-circle-fill me-1"></i> Paramètres mis à jour
    </div>
</div>

{{-- ==================== LISTE DES VOTES ==================== --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 style="font-size: 1.3rem; color: #3E1E05;">Votes reçus</h1>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Candidat</th>
                <th>Votant</th>
                <th>Email</th>
                <th>Qté</th>
                <th>Montant</th>
                <th>Paiement</th>
                <th>Statut</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($votes as $vote)
                <tr>
                    <td data-label="ID">{{ $vote->id }}</td>
                    <td data-label="Candidat">{{ $vote->candidat?->nom ?? 'N/A' }}</td>
                    <td data-label="Votant">{{ $vote->nom_votant ?? $vote->name ?? \Illuminate\Support\Str::limit($vote->votant_nom, 20) ?? 'N/A' }}</td>
                    <td data-label="Email">{{ $vote->votant_email ?? $vote->email }}</td>
                    <td data-label="Qté">{{ $vote->quantite ?? 1 }}</td>
                    <td data-label="Montant">{{ $vote->montant ? number_format($vote->montant, 0, ',', ' ') . ' FCFA' : '-' }}</td>
                    <td data-label="Paiement">
                        @if($vote->payment_method)
                            <span class="badge bg-info">{{ ucfirst($vote->payment_method) }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td data-label="Statut">
                        @php
                            $badgeClass = match($vote->statut) {
                                'confirme' => 'success',
                                'rejete' => 'danger',
                                default => 'warning',
                            };
                        @endphp
                        <span class="badge bg-{{ $badgeClass }}">
                            {{ match($vote->statut) {
                                'confirme' => 'Confirmé',
                                'rejete' => 'Rejeté',
                                default => 'En attente',
                            } }}
                        </span>
                    </td>
                    <td data-label="Date">{{ $vote->created_at->format('d/m/Y H:i') }}</td>
                    <td data-label="Actions">
                        <a href="{{ route('admin.votes.show', $vote) }}" class="btn btn-sm btn-info">Voir</a>
                        <form action="{{ route('admin.votes.destroy', $vote) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce vote ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center py-4 text-muted">Aucun vote trouvé.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $votes->links() }}

@push('scripts')
<script>
(function() {
    // === RÉFÉRENCES DOM ===
    const form = document.getElementById('voteSettingsForm');
    const toggleBtn = document.getElementById('voteToggleBtn');
    const toggleInput = document.getElementById('vote_mode');
    const toggleDot = document.getElementById('voteToggleDot');
    const toggleText = document.getElementById('voteToggleText');
    const toast = document.getElementById('voteSettingsToast');
    const saveBtn = document.getElementById('voteSettingsSaveBtn');

    // === CYCLE DES ÉTATS ===
    const etats = ['active', 'off', 'cloture'];

    toggleBtn.addEventListener('click', function() {
        const idx = etats.indexOf(toggleInput.value);
        toggleInput.value = etats[(idx + 1) % etats.length];
        updateToggleUI();
    });

    // === MISE À JOUR UI ===
    function updateToggleUI() {
        const mode = toggleInput.value;
        const colors = { active: '#198754', off: '#6c757d', cloture: '#e94560' };
        toggleBtn.style.background = colors[mode] || '#6c757d';
        toggleDot.style.background = mode === 'active' ? '#fff' : '#adb5bd';
        toggleText.textContent = mode === 'active' ? 'ACTIF' : (mode === 'cloture' ? 'CLÔTURÉ' : 'OFF');
    }

    // === SOUMISSION FORMULAIRE ===
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>';

        document.querySelector('input[name="afficher_compteur"][value="0"]').disabled = document.getElementById('afficher_compteur').checked;
        const formData = new FormData(form);
        document.querySelector('input[name="afficher_compteur"][value="0"]').disabled = false;

        try {
            const res = await fetch('{{ route("public.vote.settings") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: formData,
            });
            const data = await res.json();
            if (data.success) {
                toast.style.display = 'block';
                setTimeout(() => { toast.style.display = 'none'; }, 3000);
                updateToggleUI();
            }
        } catch (err) {
            toast.style.color = '#f87171';
            toast.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i> Erreur';
            toast.style.display = 'block';
        }
        saveBtn.disabled = false;
        saveBtn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Appliquer';
    });
})();
</script>
@endpush
@endsection