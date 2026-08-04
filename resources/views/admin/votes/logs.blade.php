@extends('layouts.admin')

@section('title', 'Logs paiements')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h1 class="section-title mb-0">Logs des paiements</h1>
    <div class="d-flex gap-2 align-items-center">
        <span class="badge rounded-pill" style="background:#e8f5e9;color:#2e7d32;"><i class="bi bi-check-circle me-1"></i>Confirmés</span>
        <span class="badge rounded-pill" style="background:#fce4ec;color:#c62828;"><i class="bi bi-x-circle me-1"></i>Erreurs</span>
        <span class="badge rounded-pill" style="background:#fff3e0;color:#e65100;"><i class="bi bi-clock me-1"></i>Ignorés</span>
    </div>
</div>

<p class="text-muted small mb-3">
    Traçabilité des webhooks et retours Fedapay : permet de vérifier qu'un paiement a bien été compté,
    ou de retrouver la cause d'une ovation non comptée (vote introuvable, déjà confirmé, statut non confirmant…).
</p>

<div class="table-responsive bg-white rounded-3 shadow-sm" style="border:1px solid rgba(202,123,5,0.08);">
    <table class="table table-hover align-middle mb-0" style="font-size:0.85rem;">
        <thead style="background:#fdfaf5;color:#3E1E05;">
            <tr>
                <th class="py-3 ps-3">Date</th>
                <th class="py-3">Statut</th>
                <th class="py-3">Cause</th>
                <th class="py-3">Message</th>
                <th class="py-3">Transaction</th>
                <th class="py-3">Vote</th>
                <th class="py-3">Montant</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($logs as $log)
                <tr>
                    <td class="ps-3 text-muted small text-nowrap">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                    <td>
                        <span class="badge px-2 py-1" style="{{ $log->statut === 'ok' ? 'background:#e8f5e9;color:#2e7d32;' : ($log->statut === 'erreur' ? 'background:#fce4ec;color:#c62828;' : 'background:#fff3e0;color:#e65100;') }}">
                            {{ $log->statut === 'ok' ? 'OK' : ($log->statut === 'erreur' ? 'Erreur' : 'Ignoré') }}
                        </span>
                    </td>
                    <td>
                        @if($log->categorie)
                            <span class="badge rounded-pill" style="background:rgba(155,77,7,0.1);color:#9B4D07;font-weight:600;">{{ $log->categorie }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td style="max-width:360px;">
                        <div class="text-truncate" title="{{ $log->message }}">{{ $log->message }}</div>
                        @if($log->contexte)
                            <small class="text-muted d-block text-truncate" style="max-width:360px;" title="{{ $log->contexte }}">📎 {{ $log->contexte }}</small>
                        @endif
                    </td>
                    <td class="small">{{ $log->transaction_id ?: '—' }}</td>
                    <td class="small">{{ $log->vote_id ?: '—' }}</td>
                    <td class="fw-semibold" style="color:#3E1E05;">{{ $log->montant ? number_format($log->montant, 0, ',', ' ') . ' FCFA' : '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bi bi-terminal-fill fs-3 d-block mb-2"></i>
                        Aucun log de paiement pour le moment.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">{{ $logs->links('partials.pagination', ['label' => 'log(s)']) }}</div>

@push('styles')
<style>
    .section-title { font-size: 1.1rem; font-weight: 700; color: #3E1E05; }
</style>
@endpush
@endsection
