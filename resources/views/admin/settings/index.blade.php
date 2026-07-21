@extends('layouts.admin')

@section('title', 'Paramètres du site')

@section('content')
<h1 class="mb-4">Paramètres du site</h1>

<form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf

    <div class="card border-0 rounded-4 shadow-sm mb-4">
        <div class="card-header bg-transparent border-bottom px-4 py-3">
            <h5 class="fw-semibold mb-0" style="color: #3E1E05;">Informations générales</h5>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Titre du site</label>
                    <input type="text" name="site_titre" class="form-control" value="{{ $settings['site_titre'] ?? config('app.name') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Description</label>
                    <input type="text" name="site_description" class="form-control" value="{{ $settings['site_description'] ?? '' }}" maxlength="500">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email de notification</label>
                    <input type="email" name="email_notifications" class="form-control" value="{{ $settings['email_notifications'] ?? '' }}">
                    <div class="form-text">Recevoir les alertes (nouveaux messages, votes).</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 rounded-4 shadow-sm mb-4">
        <div class="card-header bg-transparent border-bottom px-4 py-3">
            <h5 class="fw-semibold mb-0" style="color: #3E1E05;">Vote / Ovation</h5>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Date début des votes</label>
                    <input type="datetime-local" name="date_debut_vote" class="form-control" value="{{ $settings['date_debut_vote'] ?? '' }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Date fin des votes</label>
                    <input type="datetime-local" name="date_fin_vote" class="form-control" value="{{ $settings['date_fin_vote'] ?? '' }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Prix d'une ovation (FCFA)</label>
                    <input type="number" name="prix_du_vote" class="form-control" value="{{ $settings['prix_du_vote'] ?? '500' }}" min="0">
                </div>
                <div class="col-md-6">
                    <div class="form-check form-switch mt-4">
                        <input class="form-check-input" type="checkbox" role="switch" id="afficher_compteur" name="afficher_compteur" value="1" {{ ($settings['afficher_compteur'] ?? '1') === '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="afficher_compteur">Afficher le compteur de votes public</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 rounded-4 shadow-sm mb-4">
        <div class="card-header bg-transparent border-bottom px-4 py-3">
            <h5 class="fw-semibold mb-0" style="color: #3E1E05;">Réseaux sociaux</h5>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Facebook</label>
                    <input type="url" name="facebook" class="form-control" value="{{ $settings['facebook'] ?? '' }}" placeholder="https://facebook.com/...">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Instagram</label>
                    <input type="url" name="instagram" class="form-control" value="{{ $settings['instagram'] ?? '' }}" placeholder="https://instagram.com/...">
                </div>
                <div class="col-md-6">
                    <label class="form-label">YouTube</label>
                    <input type="url" name="youtube" class="form-control" value="{{ $settings['youtube'] ?? '' }}" placeholder="https://youtube.com/...">
                </div>
                <div class="col-md-6">
                    <label class="form-label">TikTok</label>
                    <input type="url" name="tiktok" class="form-control" value="{{ $settings['tiktok'] ?? '' }}" placeholder="https://tiktok.com/...">
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Enregistrer les paramètres</button>
</form>
@endsection
