@extends('layouts.admin')

@section('title', 'Tableau de bord - Administration ' . config('app.name'))

@push('styles')
<style>
    .stat-card {
        border: none;
        border-radius: 14px;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.06);
    }
    .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
    .quick-card {
        border: none;
        border-radius: 14px;
        cursor: pointer;
        transition: all 0.25s;
    }
    .quick-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 28px rgba(0,0,0,0.07);
    }
    .quick-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
    }
    .progress-bar-cat {
        height: 8px;
        border-radius: 4px;
        background: linear-gradient(90deg, #9B4D07, #CA7B05);
    }
    .mode-toggle {
        border: 2px solid #dee2e6;
        border-radius: 10px;
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        background: #fff;
        color: #6c757d;
    }
    .mode-toggle.active {
        border-color: #9B4D07;
        background: #9B4D07;
        color: #fff;
    }
    .mode-toggle:hover:not(.active) {
        border-color: #CA7B05;
    }
    .msg-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        flex-shrink: 0;
        margin-top: 6px;
    }
</style>
@endpush

@section('page-title', 'Tableau de bord')
@section('page-subtitle', 'Édition 2026 • Votes en cours')

@section('content')

{{-- ========== 1. RACCOURCIS ACTIONS RAPIDES ========== --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <a href="{{ route('admin.candidats.create') }}" class="text-decoration-none">
            <div class="card quick-card p-3 text-center h-100 border-0">
                <div class="quick-icon mx-auto mb-2" style="background: #e8f5e9; color: #2e7d32;">
                    <i class="bi bi-person-plus-fill"></i>
                </div>
                <span class="small fw-semibold" style="color: #9B4D07;">Ajouter candidat</span>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('admin.medias.create') }}" class="text-decoration-none">
            <div class="card quick-card p-3 text-center h-100 border-0">
                <div class="quick-icon mx-auto mb-2" style="background: #e3f2fd; color: #1565c0;">
                    <i class="bi bi-plus-circle-fill"></i>
                </div>
                <span class="small fw-semibold" style="color: #9B4D07;">Ajouter média</span>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('admin.partenaires.create') }}" class="text-decoration-none">
            <div class="card quick-card p-3 text-center h-100 border-0">
                <div class="quick-icon mx-auto mb-2" style="background: #fff3e0; color: #e65100;">
                    <i class="bi bi-building-add"></i>
                </div>
                <span class="small fw-semibold" style="color: #9B4D07;">Ajouter partenaire</span>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('admin.programmes.create') }}" class="text-decoration-none">
            <div class="card quick-card p-3 text-center h-100 border-0">
                <div class="quick-icon mx-auto mb-2" style="background: #fce4ec; color: #c62828;">
                    <i class="bi bi-calendar-plus-fill"></i>
                </div>
                <span class="small fw-semibold" style="color: #9B4D07;">Ajouter programme</span>
            </div>
        </a>
    </div>
</div>

{{-- ========== 2. MÉTRIQUES CLÉS ========== --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card p-3 border-0">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="small text-muted">Votes confirmés</span>
                <div class="stat-icon" style="background: #e8f5e9; color: #2e7d32;">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
            </div>
            <div class="h2 fw-bold mb-0" style="color: #3E1E05;">{{ number_format($votesConfirmes, 0, ',', ' ') }}</div>
            <small class="text-muted">Total des votes validés</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card p-3 border-0">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="small text-muted">Recettes</span>
                <div class="stat-icon" style="background: #fff3e0; color: #e65100;">
                    <i class="bi bi-coin"></i>
                </div>
            </div>
            <div class="h2 fw-bold mb-0" style="color: #3E1E05;">{{ number_format($totalRecettes, 0, ',', ' ') }} FCFA</div>
            <small class="text-muted">Recettes totales</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card p-3 border-0">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="small text-muted">Messages non lus</span>
                <div class="stat-icon" style="background: #fce4ec; color: #c62828;">
                    <i class="bi bi-envelope-fill"></i>
                </div>
            </div>
            <div class="h2 fw-bold mb-0" style="color: #3E1E05;">{{ $messagesNonLus }}</div>
            <small class="text-muted">En attente de lecture</small>
        </div>
    </div>
</div>

{{-- ========== 3. SECTION PRINCIPALE (DEUX COLONNES) ========== --}}
<div class="row g-3 mb-4">

    {{-- Colonne gauche : Votes par catégorie --}}
    <div class="col-lg-7">
        <div class="card border-0 rounded-4 h-100">
            <div class="card-header bg-transparent border-bottom d-flex align-items-center justify-content-between px-4 py-3">
                <span class="fw-semibold" style="color: #9B4D07;">Votes par catégorie</span>
                <i class="bi bi-bar-chart-fill" style="color: #9B4D07;"></i>
            </div>
            <div class="card-body px-4 py-3">
                @forelse($votesParCategorie as $cat)
                    @php
                        $pct = $totalVotes > 0 ? round(($cat->total / $totalVotes) * 100) : 0;
                    @endphp
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="small fw-semibold" style="color: #3E1E05;">{{ $cat->categorie }}</span>
                            <span class="small fw-bold" style="color: #9B4D07;">{{ number_format($cat->total, 0, ',', ' ') }}</span>
                        </div>
                        <div class="progress" style="height: 8px; background: #eee;">
                            <div class="progress-bar" style="width: {{ $pct }}%; background: linear-gradient(90deg, #9B4D07, #CA7B05); border-radius: 4px;"></div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                        <small>Aucun vote pour le moment.</small>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Colonne droite --}}
    <div class="col-lg-5 d-flex flex-column gap-3">

        {{-- Carte 1 : Mode du site --}}
        <div class="card border-0 rounded-4">
            <div class="card-header bg-transparent border-bottom d-flex align-items-center justify-content-between px-4 py-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="fw-semibold" style="color: #9B4D07;">Mode du site</span>
                    @if($voteMode === 'active')
                        <span class="badge rounded-pill" style="background: #2e7d32; font-size: 0.65rem;">Votes actifs</span>
                    @elseif($voteMode === 'off')
                        <span class="badge rounded-pill bg-secondary" style="font-size: 0.65rem;">Hors-événement</span>
                    @else
                        <span class="badge rounded-pill bg-warning text-dark" style="font-size: 0.65rem;">Clôturé</span>
                    @endif
                </div>
                <i class="bi bi-sliders" style="color: #9B4D07;"></i>
            </div>
            <div class="card-body px-4 py-3">
                <div class="d-flex gap-2 mb-3">
                    <span class="mode-toggle {{ $voteMode === 'off' ? 'active' : '' }}">Hors-événement</span>
                    <span class="mode-toggle {{ $voteMode === 'active' ? 'active' : '' }}">Vote actif</span>
                    <span class="mode-toggle {{ $voteMode === 'closed' ? 'active' : '' }}">Clôturé</span>
                </div>
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom" style="border-color: #f0f0f0 !important;">
                    <small class="text-muted">Prix du vote</small>
                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-semibold" style="color: #3E1E05;">{{ number_format((int)$prixDuVote, 0, ',', ' ') }} FCFA</span>
                        <i class="bi bi-pencil" style="color: #9B4D07; font-size: 0.8rem; cursor: pointer;"></i>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom" style="border-color: #f0f0f0 !important;">
                    <small class="text-muted">Date de clôture</small>
                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-semibold" style="color: #3E1E05; font-size: 0.85rem;">{{ $voteDeadline ? \Carbon\Carbon::parse($voteDeadline)->format('d/m/Y H:i') : '—' }}</span>
                        <i class="bi bi-pencil" style="color: #9B4D07; font-size: 0.8rem; cursor: pointer;"></i>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center py-2">
                    <small class="text-muted">Édition</small>
                    <span class="fw-semibold" style="color: #3E1E05;">2026</span>
                </div>
            </div>
        </div>

        {{-- Carte 2 : Messages récents --}}
        <div class="card border-0 rounded-4">
            <div class="card-header bg-transparent border-bottom d-flex align-items-center justify-content-between px-4 py-3">
                <span class="fw-semibold" style="color: #9B4D07;">Messages récents</span>
                <a href="{{ route('admin.contacts.index') }}" class="small text-decoration-none" style="color: #9B4D07;">Voir tout →</a>
            </div>
            <div class="card-body px-4 py-2">
                @forelse($messagesRecents as $msg)
                    <div class="d-flex align-items-start gap-3 py-2 border-bottom" style="border-color: #f5f5f5 !important;">
                        <span class="msg-dot {{ $msg->lu ? 'bg-secondary' : 'bg-danger' }}"></span>
                        <div class="flex-grow-1 min-w-0">
                            <div class="fw-semibold small" style="color: #3E1E05;">{{ $msg->nom }}</div>
                            <div class="small text-muted text-truncate">{{ $msg->sujet ?: 'Sans sujet' }}</div>
                        </div>
                        <small class="text-muted text-nowrap">{{ $msg->created_at->diffForHumans() }}</small>
                    </div>
                @empty
                    <div class="text-center text-muted py-3">
                        <small>Aucun message.</small>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

{{-- ========== 4. TABLEAU DERNIÈRES TRANSACTIONS ========== --}}
<div class="card border-0 rounded-4">
    <div class="card-header bg-transparent border-bottom d-flex align-items-center justify-content-between px-4 py-3">
        <span class="fw-semibold" style="color: #9B4D07;">Dernières transactions</span>
        <a href="{{ route('admin.votes.index') }}" class="small text-decoration-none" style="color: #9B4D07;">Voir tout →</a>
    </div>
    <div class="card-body px-0 py-0">
        <div class="table-responsive">
            <table class="table table-borderless align-middle mb-0">
                <thead class="text-muted small" style="background: #f9f9fb;">
                    <tr>
                        <th class="px-4 py-3 fw-semibold">Candidat</th>
                        <th class="px-4 py-3 fw-semibold">Votes</th>
                        <th class="px-4 py-3 fw-semibold">Montant</th>
                        <th class="px-4 py-3 fw-semibold">Passerelle</th>
                        <th class="px-4 py-3 fw-semibold">Moyen</th>
                        <th class="px-4 py-3 fw-semibold">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dernieresTransactions as $vote)
                        <tr class="border-bottom" style="border-color: #f5f5f5 !important;">
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center gap-2">
                                    @if($vote->candidat && $vote->candidat->photo)
                                        <img src="{{ $vote->candidat->photo_url }}" alt="" class="rounded-circle" width="32" height="32" style="object-fit:cover;">
                                    @endif
                                    <span class="fw-semibold small" style="color: #3E1E05;">{{ $vote->candidat?->display_name ?? '—' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3"><span class="fw-bold" style="color: #3E1E05;">{{ $vote->quantite }}</span></td>
                            <td class="px-4 py-3 fw-semibold" style="color: #3E1E05;">{{ number_format($vote->montant, 0, ',', ' ') }} FCFA</td>
                            <td class="px-4 py-3">
                                @if($vote->payment_method === 'kkiapay')
                                    <span class="badge rounded-pill" style="background: #e3f2fd; color: #1565c0; font-size: 0.7rem;">Kkiapay</span>
                                @elseif($vote->payment_method === 'fedapay')
                                    <span class="badge rounded-pill" style="background: #fff3e0; color: #e65100; font-size: 0.7rem;">Fedapay</span>
                                @else
                                    <span class="badge rounded-pill bg-light text-muted" style="font-size: 0.7rem;">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="small text-muted">—</span>
                            </td>
                            <td class="px-4 py-3">
                                @if($vote->statut === 'confirme')
                                    <span class="badge rounded-pill" style="background: #e8f5e9; color: #2e7d32; font-size: 0.7rem;">Confirmé</span>
                                @elseif($vote->statut === 'en_attente')
                                    <span class="badge rounded-pill" style="background: #fff3e0; color: #e65100; font-size: 0.7rem;">En attente</span>
                                @else
                                    <span class="badge rounded-pill" style="background: #fce4ec; color: #c62828; font-size: 0.7rem;">Échoué</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-credit-card fs-3 d-block mb-2"></i>
                                <small>Aucune transaction.</small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
