<section>
    <header class="mb-3">
        <h5 class="fw-semibold">Préférences</h5>
        <p class="text-muted small">Personnalisez votre expérience sur la plateforme.</p>
    </header>

    @php $prefs = $user->preferences ?? []; @endphp

    <form method="post" action="{{ route('profile.preferences') }}">
        @csrf
        @method('patch')

        <div class="mb-3">
            <div class="form-check form-switch d-flex align-items-center gap-3 py-2 px-3 rounded-3" style="background: #f8f6f3;">
                <input class="form-check-input" type="checkbox" role="switch" id="email_notif" name="preferences[email_notifications]" value="1" {{ ($prefs['email_notifications'] ?? true) ? 'checked' : '' }}>
                <label class="form-check-label small fw-semibold" for="email_notif" style="color: #3E1E05;">
                    Notifications par email
                    <span class="d-block text-muted fw-normal" style="font-size: 0.75rem;">Recevez un email lors des nouvelles ovations, nouveaux messages et résultats.</span>
                </label>
            </div>
        </div>

        <div class="mb-3">
            <div class="form-check form-switch d-flex align-items-center gap-3 py-2 px-3 rounded-3" style="background: #f8f6f3;">
                <input class="form-check-input" type="checkbox" role="switch" id="compact_mode" name="preferences[compact_mode]" value="1" {{ ($prefs['compact_mode'] ?? false) ? 'checked' : '' }}>
                <label class="form-check-label small fw-semibold" for="compact_mode" style="color: #3E1E05;">
                    Mode compact
                    <span class="d-block text-muted fw-normal" style="font-size: 0.75rem;">Affiche les listes avec moins d'espacement pour plus de densité.</span>
                </label>
            </div>
        </div>

        <div class="mb-3">
            <div class="form-check form-switch d-flex align-items-center gap-3 py-2 px-3 rounded-3" style="background: #f8f6f3;">
                <input class="form-check-input" type="checkbox" role="switch" id="dark_mode" name="preferences[dark_mode]" value="1" {{ ($prefs['dark_mode'] ?? false) ? 'checked' : '' }}>
                <label class="form-check-label small fw-semibold" for="dark_mode" style="color: #3E1E05;">
                    Mode sombre
                    <span class="d-block text-muted fw-normal" style="font-size: 0.75rem;">Bascule l'interface en thème sombre (expérimental).</span>
                </label>
            </div>
        </div>

        <div class="mt-3">
            <x-primary-button>Enregistrer les préférences</x-primary-button>
            @if (session('status') === 'preferences-updated')
                <span class="small text-success">Préférences enregistrées.</span>
            @endif
        </div>
    </form>
</section>