@extends('layouts.admin')

@section('title', 'Mon compte')

@section('page-title', 'Paramètres du compte')
@section('page-subtitle', 'Gérez votre profil, votre sécurité, les utilisateurs et la facturation')

@push('styles')
<style>
    .settings-nav-link {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        border-radius: 10px;
        color: #3E1E05;
        text-decoration: none;
        transition: all 0.15s;
        font-size: 0.9rem;
        font-weight: 500;
    }
    .settings-nav-link:hover {
        background: #fef0e0;
        color: #9B4D07;
    }
    .settings-nav-link.active {
        background: #fef0e0;
        color: #9B4D07;
        font-weight: 600;
    }
    .settings-nav-link i {
        width: 20px;
        font-size: 1.1rem;
    }
    .settings-section {
        display: none;
    }
    .settings-section.active {
        display: block;
        animation: fadeInUp 0.25s ease;
    }
    @media (max-width: 767.98px) {
        .settings-sidebar {
            display: flex;
            overflow-x: auto;
            gap: 4px;
            padding-bottom: 4px;
        }
        .settings-sidebar .settings-nav-link {
            white-space: nowrap;
            font-size: 0.8rem;
            padding: 8px 12px;
        }
    }
</style>
@endpush

@section('content')
<div class="row g-4">

    {{-- ============ SIDEBAR ONGLETS ============ --}}
    <div class="col-lg-3">
        <div class="card border-0 rounded-4 shadow-sm">
            <div class="card-body p-2 settings-sidebar flex-lg-column">
                <a href="#profil" class="settings-nav-link active" data-section="profil">
                    <i class="bi bi-person-circle"></i> Profil
                </a>
                <a href="#securite" class="settings-nav-link" data-section="securite">
                    <i class="bi bi-shield-lock"></i> Sécurité
                </a>
                <a href="#preferences" class="settings-nav-link" data-section="preferences">
                    <i class="bi bi-sliders"></i> Préférences
                </a>
                @if(auth()->user()->role === 'super_admin')
                <a href="#utilisateurs" class="settings-nav-link" data-section="utilisateurs">
                    <i class="bi bi-people"></i> Utilisateurs
                </a>
                @endif
                @if(auth()->user()->role === 'super_admin')
                <a href="#facturation" class="settings-nav-link" data-section="facturation">
                    <i class="bi bi-credit-card"></i> Facturation
                </a>
                @endif
                <a href="#logs" class="settings-nav-link" data-section="logs">
                    <i class="bi bi-activity"></i> Activité
                </a>
                <a href="#danger" class="settings-nav-link" data-section="danger" style="color: #dc3545;">
                    <i class="bi bi-exclamation-triangle"></i> Zone danger
                </a>
            </div>
        </div>
    </div>

    {{-- ============ CONTENU ============ --}}
    <div class="col-lg-9">

        {{-- Section Profil --}}
        <div class="settings-section active" id="section-profil">
            <div class="card border-0 rounded-4 shadow-sm">
                <div class="card-body p-4">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>

        {{-- Section Sécurité --}}
        <div class="settings-section" id="section-securite">
            <div class="card border-0 rounded-4 shadow-sm mb-4">
                <div class="card-body p-4">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
            <div class="card border-0 rounded-4 shadow-sm">
                <div class="card-body p-4">
                    @include('profile.partials.sessions')
                </div>
            </div>
        </div>

        {{-- Section Préférences --}}
        <div class="settings-section" id="section-preferences">
            <div class="card border-0 rounded-4 shadow-sm">
                <div class="card-body p-4">
                    @include('profile.partials.preferences')
                </div>
            </div>
        </div>

        {{-- Section Utilisateurs --}}
        @if(auth()->user()->role === 'super_admin')
        <div class="settings-section" id="section-utilisateurs">
            <div class="card border-0 rounded-4 shadow-sm">
                <div class="card-header bg-transparent border-bottom d-flex align-items-center justify-content-between px-4 py-3">
                    <span class="fw-semibold" style="color: #9B4D07;">Gestion des utilisateurs</span>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-fitab btn-sm">
                        <i class="bi bi-plus-lg"></i> Nouvel utilisateur
                    </a>
                </div>
                <div class="card-body p-0">
                    @php $users = \App\Models\User::orderBy('created_at', 'desc')->get(); @endphp
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <thead class="text-muted small" style="background: #f9f9fb;">
                                <tr>
                                    <th class="px-4 py-3 fw-semibold">Nom</th>
                                    <th class="px-4 py-3 fw-semibold">Email</th>
                                    <th class="px-4 py-3 fw-semibold">Rôle</th>
                                    <th class="px-4 py-3 fw-semibold">Date</th>
                                    <th class="px-4 py-3 fw-semibold">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $u)
                                    <tr class="border-bottom" style="border-color: #f5f5f5 !important;">
                                        <td class="px-4 py-3">
                                            <span class="fw-semibold small" style="color: #3E1E05;">{{ $u->name }}</span>
                                            @if($u->id === auth()->id())
                                                <span class="badge rounded-pill ms-1" style="background: #fef0e0; color: #9B4D07; font-size: 0.6rem;">Vous</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 small text-muted">{{ $u->email }}</td>
                                        <td class="px-4 py-3">
                                            <span class="badge rounded-pill" style="background: {{ $u->role === 'super_admin' ? '#3E1E05' : '#CA7B05' }}; color: #fff; font-size: 0.7rem;">
                                                {{ $u->role === 'super_admin' ? 'Super Admin' : 'Éditeur' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 small text-muted">{{ $u->created_at->format('d/m/Y') }}</td>
                                        <td class="px-4 py-3">
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm btn-warning">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                @if($u->id !== auth()->id())
                                                    <form action="{{ route('admin.users.destroy', $u) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                                        @csrf @method('DELETE')
                                                        <button class="btn btn-sm btn-danger">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(auth()->user()->role === 'super_admin')
        {{-- Section Facturation --}}
        <div class="settings-section" id="section-facturation">
            <div class="card border-0 rounded-4 shadow-sm">
                <div class="card-body p-4 text-center" style="min-height: 200px;">
                    <h6 class="fw-semibold" style="color: #3E1E05;">Gestion de la facturation</h6>
                    <p class="small text-muted mb-0">Aucun abonnement actif pour le moment.</p>
                </div>
            </div>
        </div>
        @endif

        {{-- Section Activité --}}
        <div class="settings-section" id="section-logs">
            <div class="card border-0 rounded-4 shadow-sm">
                <div class="card-body p-4">
                    @include('profile.partials.activity-logs')
                </div>
            </div>
        </div>

        {{-- Section Zone danger --}}
        <div class="settings-section" id="section-danger">
            <div class="card border-0 rounded-4 shadow-sm border-danger">
                <div class="card-body p-4">

                    {{-- Exporter les données --}}
                    <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom">
                        <div>
                            <h6 class="fw-semibold mb-1" style="color: #dc3545;">Exporter mes données</h6>
                            <p class="small text-muted mb-0">Téléchargez l'ensemble de vos données avant de quitter la plateforme.</p>
                        </div>
                        <a href="{{ route('profile.export') }}" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-download"></i> Exporter
                        </a>
                    </div>

                    {{-- Supprimer le compte --}}
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="fw-semibold mb-1" style="color: #dc3545;">Supprimer le compte</h6>
                            <p class="small text-muted mb-0">Action irréversible. Toutes les données associées seront définitivement perdues.</p>
                        </div>
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirm-user-deletion">
                            <i class="bi bi-trash"></i> Supprimer
                        </button>
                    </div>

                </div>
            </div>
        </div>

        {{-- Modal suppression --}}
        <div class="modal fade" id="confirm-user-deletion" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" action="{{ route('profile.destroy') }}">
                        @csrf
                        @method('delete')
                        <div class="modal-header">
                            <h5 class="modal-title">Supprimer le compte</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p class="small text-muted">
                                Cette action est irréversible. Toutes vos données seront définitivement effacées.
                                Veuillez entrer votre mot de passe pour confirmer.
                            </p>
                            <div class="mb-3">
                                <x-input-label for="password" value="Mot de passe" />
                                <x-text-input id="password" name="password" type="password" class="form-control" placeholder="Votre mot de passe" />
                                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
(function() {
    var links = document.querySelectorAll('.settings-nav-link');
    var sections = document.querySelectorAll('.settings-section');

    function showSection(id) {
        links.forEach(function(l) { l.classList.remove('active'); });
        sections.forEach(function(s) { s.classList.remove('active'); });
        var link = document.querySelector('.settings-nav-link[data-section="' + id + '"]');
        var section = document.getElementById('section-' + id);
        if (link) link.classList.add('active');
        if (section) section.classList.add('active');
    }

    links.forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            var id = this.getAttribute('data-section');
            showSection(id);
            history.replaceState(null, '', '#profil');
        });
    });
})();
</script>
@endsection