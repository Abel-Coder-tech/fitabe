@extends('layouts.admin')

@section('title', "Détail de l'ovation")

@push('styles')
<style>
    .detail-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(62,30,5,0.06), 0 4px 16px rgba(62,30,5,0.04);
        overflow: hidden;
    }
    .detail-card-header {
        background: linear-gradient(135deg, #3E1E05 0%, #9B4D07 100%);
        padding: 0.75rem 1.5rem;
    }
    .detail-card-header h5 {
        font-size: 0.95rem;
        letter-spacing: 0.3px;
    }
    .detail-card-body {
        padding: 1.5rem;
    }
    .detail-label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #9B4D07;
    }
    .detail-value {
        font-size: 0.95rem;
        color: #3E1E05;
        font-weight: 500;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 style="font-size:1.3rem;color:#3E1E05;font-weight:700;">Détail de l'ovation</h1>
    <a href="{{ route('admin.votes.index') }}" class="btn btn-sm rounded-pill px-3" style="background:rgba(155,77,7,0.08);color:#9B4D07;border:1px solid rgba(155,77,7,0.15);">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
</div>

<div class="detail-card mb-4">
    <div class="detail-card-header">
        <h5 class="mb-0 text-white">
            <i class="bi bi-info-circle-fill me-2" style="color:#c9a96e;"></i> Informations générales
        </h5>
    </div>
    <div class="detail-card-body">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="detail-label">Candidat</div>
                <div class="detail-value">{{ $vote->candidat?->nom ?? 'N/A' }}</div>
            </div>
            <div class="col-md-6">
                <div class="detail-label">Ovationneur</div>
                <div class="detail-value">{{ $vote->nom_votant ?? $vote->name ?? 'N/A' }}</div>
            </div>
            <div class="col-md-6">
                <div class="detail-label">Email</div>
                <div class="detail-value">{{ $vote->email }}</div>
            </div>
            <div class="col-md-6">
                <div class="detail-label">Téléphone</div>
                <div class="detail-value">{{ $vote->telephone ?? 'N/A' }}</div>
            </div>
            <div class="col-md-4">
                <div class="detail-label">Quantité</div>
                <div class="detail-value">{{ $vote->quantite ?? 1 }}</div>
            </div>
            <div class="col-md-4">
                <div class="detail-label">Montant</div>
                <div class="detail-value">{{ $vote->montant ? number_format($vote->montant, 0, ',', ' ') . ' FCFA' : ($prixDuVote * ($vote->quantite ?? 1) . ' FCFA (est.)') }}</div>
            </div>
            <div class="col-md-4">
                <div class="detail-label">Paiement</div>
                <div class="detail-value">
                    @if($vote->payment_method)
                        <span class="badge px-2 py-1" style="background:rgba(202,123,5,0.12);color:#9B4D07;font-weight:600;">{{ ucfirst($vote->payment_method) }}</span>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <div class="detail-label">Statut</div>
                <div class="detail-value">
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
                </div>
            </div>
            <div class="col-md-4">
                <div class="detail-label">IP</div>
                <div class="detail-value">{{ $vote->ip ?? 'N/A' }}</div>
            </div>
            <div class="col-md-4">
                <div class="detail-label">Date</div>
                <div class="detail-value">{{ $vote->created_at->format('d/m/Y H:i') }}</div>
            </div>
        </div>
    </div>
</div>

@endsection
