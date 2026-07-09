@extends('layouts.admin')

@section('title', 'Ovations')

@push('styles')
<style>
    .traffic-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 1px 3px rgba(62,30,5,0.06), 0 8px 24px rgba(62,30,5,0.06);
        overflow: hidden;
    }
    .traffic-header {
        background: #3E1E05;
        padding: 1rem 1.5rem;
    }
    .hero-stat {
        text-align: center;
        padding: 0.4rem 1.2rem;
        border-radius: 12px;
        background: rgba(255,255,255,0.08);
        min-width: 90px;
    }
    .hero-stat .num {
        font-size: 1.6rem;
        font-weight: 800;
        color: #fff;
        line-height: 1.1;
    }
    .hero-stat .lbl {
        font-size: 0.6rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: rgba(255,255,255,0.5);
    }
    .traffic-body { padding: 1.5rem; }
    .state-group {
        display: flex; gap: 2px;
        background: #f0ebe5; border-radius: 12px;
        padding: 3px;
    }
    .state-btn {
        border: none; background: transparent;
        border-radius: 10px; padding: 0.55rem 1.1rem;
        font-size: 0.78rem; font-weight: 700;
        cursor: pointer; transition: all 0.25s;
        color: rgba(62,30,5,0.4);
        letter-spacing: 0.3px;
        position: relative;
    }
    .state-btn:hover { color: rgba(62,30,5,0.7); }
    .state-btn.active {
        color: #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.12);
    }
    .state-btn.active.s-off    { background: #6c757d; }
    .state-btn.active.s-active { background: #198754; }
    .state-btn.active.s-cloture { background: #dc3545; }
    .state-dot {
        display: inline-block; width: 8px; height: 8px;
        border-radius: 50%; margin-right: 6px;
        vertical-align: middle;
        background: rgba(62,30,5,0.15);
    }
    .state-btn.active .state-dot { background: rgba(255,255,255,0.8); }
    .state-sub {
        font-size: 0.55rem; font-weight: 600;
        letter-spacing: 0.3px;
        color: rgba(62,30,5,0.3);
        margin-top: 1px;
    }
    .state-btn.active .state-sub { color: rgba(255,255,255,0.65); }
    .ctrl-group {
        background: #fdfaf5; border-radius: 14px;
        padding: 0.8rem 1rem; border: 1px solid rgba(202,123,5,0.1);
    }
    .ctrl-group .form-control-sm {
        border-radius: 8px; border: 1px solid rgba(202,123,5,0.12);
        font-size: 0.82rem; padding: 0.35rem 0.65rem; color: #3E1E05;
    }
    .ctrl-group .form-control-sm:focus {
        border-color: #CA7B05; box-shadow: 0 0 0 3px rgba(202,123,5,0.08);
    }
    .ctrl-group .input-group-text {
        background: #fff; border-color: rgba(202,123,5,0.12);
        color: #9B4D07; font-weight: 600; font-size: 0.75rem;
    }
    .save-btn {
        background: linear-gradient(135deg, #9B4D07, #CA7B05);
        color: #fff; border: none; border-radius: 50px;
        padding: 0.5rem 1.8rem; font-weight: 700; font-size: 0.85rem;
        box-shadow: 0 4px 14px rgba(155,77,7,0.3);
        transition: all 0.25s;
    }
    .save-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(155,77,7,0.35); }
    .save-btn:active { transform: scale(0.97); }
    .progress-ovation {
        height: 5px; border-radius: 50px;
        background: rgba(202,123,5,0.12); overflow: hidden;
    }
    .progress-ovation .bar {
        height: 100%; border-radius: 50px;
        background: linear-gradient(90deg, #9B4D07, #CA7B05);
    }
    .section-title { font-size: 1.1rem; font-weight: 700; color: #3E1E05; }
    .btn-outline-bdx {
        color: #8b1a1a; border: 1px solid rgba(139,26,26,0.2);
        background: rgba(139,26,26,0.04);
    }
    .btn-outline-bdx:hover { color: #fff; background: #8b1a1a; border-color: #8b1a1a; }
    .form-switch .form-check-input {
        width: 40px; height: 22px; cursor: pointer;
        border-radius: 50px; background-color: #e9ecef; border-color: #ced4da;
    }
    .form-switch .form-check-input:checked { background-color: #198754; border-color: #198754; }
    .toast-vote { border-radius: 50px; padding: 0.5rem 1.5rem; font-weight: 600; font-size: 0.85rem; }
</style>
@endpush

@section('content')

<div class="traffic-card mb-4">
    <div class="traffic-header d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center justify-content-center rounded-circle bg-white" style="width:36px;height:36px;flex-shrink:0;">
                <i class="bi bi-heart-pulse-fill" style="color:#dc3545;font-size:1.1rem;"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-0 text-white" style="font-size:0.95rem;letter-spacing:0.3px;">Panneau des ovations</h6>
                <small class="text-white-50" style="font-size:0.7rem;">Contrôle en temps réel</small>
            </div>
        </div>
        <div class="d-flex gap-2">
            <div class="hero-stat">
                <div class="num">{{ \App\Models\Candidats::count() }}</div>
                <div class="lbl">Candidats</div>
            </div>
            <div class="hero-stat">
                <div class="num">{{ \App\Models\Candidats::sum('nombre_votes') }}</div>
                <div class="lbl">Ovations</div>
            </div>
        </div>
    </div>
    <div class="traffic-body">
        <form id="voteSettingsForm">
            @csrf
            @php
                $now = now();
                $debut = $dateDebut ? \Carbon\Carbon::parse($dateDebut) : null;
                $fin = $dateFin ? \Carbon\Carbon::parse($dateFin) : null;
                $total = $debut && $fin ? $debut->diffInSeconds($fin) : 0;
                $elapsed = $debut && $fin ? $debut->diffInSeconds($now) : 0;
                $pct = $total > 0 ? max(0, min(100, round($elapsed / $total * 100))) : 0;
            @endphp

            <div class="row g-3 mb-3">
                <div class="col">
                    <div class="ctrl-group h-100">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span style="font-size:0.6rem;font-weight:700;text-transform:uppercase;letter-spacing:0.4px;color:#9B4D07;">
                                <i class="bi bi-calendar-range me-1"></i> Planning des ovations
                            </span>
                            @if($debut && $fin)
                                <span class="small text-muted">
                                    @if($now < $debut)
                                        <i class="bi bi-clock me-1"></i>Ouv. {{ $debut->format('d/m/Y H:i') }}
                                    @elseif($now > $fin)
                                        <i class="bi bi-lock me-1"></i>Clos le {{ $fin->format('d/m/Y H:i') }}
                                    @else
                                        <i class="bi bi-unlock me-1"></i>Jusqu'au {{ $fin->format('d/m/Y H:i') }}
                                    @endif
                                </span>
                            @endif
                        </div>
                        <div class="row g-2 align-items-end">
                            <div class="col">
                                <label style="font-size:0.6rem;font-weight:600;color:rgba(62,30,5,0.45);">Début</label>
                                <input type="datetime-local" name="date_debut_vote" id="date_debut_vote"
                                       class="form-control form-control-sm"
                                       value="{{ $dateDebut ? date('Y-m-d\TH:i', strtotime($dateDebut)) : '' }}">
                            </div>
                            <div class="col">
                                <label style="font-size:0.6rem;font-weight:600;color:rgba(62,30,5,0.45);">Fin</label>
                                <input type="datetime-local" name="date_fin_vote" id="date_fin_vote"
                                       class="form-control form-control-sm"
                                       value="{{ $dateFin ? date('Y-m-d\TH:i', strtotime($dateFin)) : '' }}">
                            </div>
                            <div class="col" style="max-width:100px;">
                                <span class="badge w-100 py-2" style="background: #3E1E05; color: #E3D5AD; font-size: 0.75rem;">
                                    <i class="bi bi-ticket-perforated me-1"></i> 100 FCFA
                                </span>
                            </div>
                        </div>
                        @if($debut && $fin && $now >= $debut && $now <= $fin)
                        <div class="mt-2">
                            <div class="d-flex justify-content-between small mb-1">
                                <span style="color:#9B4D07;font-size:0.7rem;">{{ $debut->format('d/m') }}</span>
                                <span style="color:#3E1E05;font-weight:700;font-size:0.7rem;">{{ $pct }}%</span>
                                <span style="color:#9B4D07;font-size:0.7rem;">{{ $fin->format('d/m') }}</span>
                            </div>
                            <div class="progress-ovation">
                                <div class="bar" style="width:{{ $pct }}%;"></div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <span class="badge fs-6 px-3 py-2" style="background: {{ $voteMode === 'active' ? '#198754' : ($voteMode === 'cloture' ? '#dc3545' : '#6c757d') }}; border-radius: 50px;">
                    <i class="bi bi-{{ $voteMode === 'active' ? 'unlock' : ($voteMode === 'cloture' ? 'lock' : 'clock') }} me-1"></i>
                    {{ $voteMode === 'active' ? 'Ouvert' : ($voteMode === 'cloture' ? 'Fermé' : 'À venir') }}
                </span>
                <small class="text-muted ms-2">(Mode automatique basé sur les dates)</small>
            </div>

            <div class="ctrl-group">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <div class="d-flex align-items-center gap-3">
                            <div class="form-check form-switch ps-0 m-0 d-flex align-items-center gap-2">
                                <input type="hidden" name="afficher_compteur" value="0">
                                <input type="checkbox" class="form-check-input" id="afficher_compteur"
                                       name="afficher_compteur" value="1" role="switch"
                                       {{ $afficherCompteur ? 'checked' : '' }}>
                                <label for="afficher_compteur" style="font-size:0.82rem;color:#3E1E05;cursor:pointer;margin:0;font-weight:500;">
                                    <i class="bi bi-eye me-1"></i> Afficher le compteur public
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="save-btn" id="voteSettingsSaveBtn">
                            <i class="bi bi-check-lg me-1"></i> Appliquer
                        </button>
                    </div>
                </div>
            </div>

            <div id="voteSettingsToast" class="toast-vote text-center mx-auto mt-3" style="display:none;color:#fff;background:#198754;">
                <i class="bi bi-check-circle-fill me-1"></i> Paramètres mis à jour
            </div>
        </form>
    </div>
</div>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h1 class="section-title mb-0">Ovations reçues</h1>
    @if($votes->total() > 0)
    <form action="{{ route('admin.votes.clearAll') }}" method="POST" class="d-inline" onsubmit="return confirm('⚠️ Supprimer TOUTES les ovations ? Cette action est irréversible.')">
        @csrf
        <button type="submit" class="btn btn-sm btn-outline-bdx fw-semibold rounded-pill px-3">
            <i class="bi bi-trash3 me-1"></i> Tout supprimer ({{ $votes->total() }})
        </button>
    </form>
    @endif
</div>

<div class="table-responsive bg-white rounded-3 shadow-sm" style="border:1px solid rgba(202,123,5,0.08);">
    <table class="table table-hover align-middle mb-0" style="font-size:0.85rem;">
        <thead style="background:#fdfaf5;color:#3E1E05;">
            <tr>
                <th class="py-3 ps-3">ID</th>
                <th class="py-3">Candidat</th>
                <th class="py-3">Ovationneur</th>
                <th class="py-3">Email</th>
                <th class="py-3 text-center">Qté</th>
                <th class="py-3">Montant</th>
                <th class="py-3">Paiement</th>
                <th class="py-3">Statut</th>
                <th class="py-3">Date</th>
                <th class="py-3 pe-3 text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($votes as $vote)
                <tr>
                    <td class="ps-3 fw-semibold text-muted">{{ $vote->id }}</td>
                    <td>{{ $vote->candidat?->nom ?? 'N/A' }}</td>
                    <td>{{ $vote->nom_votant ?? $vote->name ?? Str::limit($vote->votant_nom, 20) ?? 'N/A' }}</td>
                    <td class="text-muted small">{{ $vote->votant_email ?? $vote->email }}</td>
                    <td class="text-center fw-semibold">{{ $vote->quantite ?? 1 }}</td>
                    <td class="fw-semibold" style="color:#3E1E05;">{{ $vote->montant ? number_format($vote->montant, 0, ',', ' ') . ' FCFA' : '-' }}</td>
                    <td>
                        @if($vote->payment_method)
                            <span class="badge px-2 py-1" style="background:rgba(202,123,5,0.12);color:#9B4D07;font-weight:600;">{{ ucfirst($vote->payment_method) }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $badgeClass = match($vote->statut) {
                                'confirme' => 'success',
                                'rejete' => 'danger',
                                default => 'warning',
                            };
                        @endphp
                        <span class="badge bg-{{ $badgeClass }} px-2 py-1">
                            {{ match($vote->statut) {
                                'confirme' => 'Confirmé',
                                'rejete' => 'Rejeté',
                                default => 'En attente',
                            } }}
                        </span>
                    </td>
                    <td class="text-muted small">{{ $vote->created_at->format('d/m/Y H:i') }}</td>
                    <td class="pe-3 text-end">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('admin.votes.show', $vote) }}" class="btn btn-sm" style="background:rgba(155,77,7,0.08);color:#9B4D07;border-radius:8px;" title="Voir">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                            <form action="{{ route('admin.votes.destroy', $vote) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cette ovation ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="background:rgba(139,26,26,0.08);color:#8b1a1a;border-radius:8px;" title="Supprimer">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center py-4 text-muted">Aucune ovation trouvée.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">{{ $votes->links() }}</div>

@push('scripts')
<script>
(function() {
    const form = document.getElementById('voteSettingsForm');
    const toast = document.getElementById('voteSettingsToast');
    const saveBtn = document.getElementById('voteSettingsSaveBtn');

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
                toast.style.display = 'block'; toast.style.background = '#198754';
                toast.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Paramètres mis à jour';
                setTimeout(function() { toast.style.display = 'none'; }, 3000);
            }
        } catch (err) {
            toast.style.display = 'block'; toast.style.background = '#dc3545';
            toast.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i> Erreur';
            setTimeout(function() { toast.style.display = 'none'; }, 3000);
        }
        saveBtn.disabled = false;
        saveBtn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Appliquer';
    });
})();
</script>
@endpush
@endsection
