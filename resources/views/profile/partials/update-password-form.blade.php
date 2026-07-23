<section>
    <header class="mb-3">
        <h5 class="fw-semibold">Modifier le mot de passe</h5>
        <p class="text-muted small">Assurez-vous que votre compte utilise un mot de passe long et aléatoire pour rester sécurisé.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" novalidate>
        @csrf
        @method('put')

        <div class="mb-3">
            <x-input-label for="update_password_current_password" value="Mot de passe actuel" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="form-control" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div class="mb-3">
            <x-input-label for="update_password_password" value="Nouveau mot de passe" />
            <x-text-input id="update_password_password" name="password" type="password" class="form-control" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            <small class="text-muted">8 caractères minimum, 1 majuscule, 1 minuscule, 1 chiffre.</small>
        </div>

        <div class="mb-3">
            <x-input-label for="update_password_password_confirmation" value="Confirmer le mot de passe" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="d-flex align-items-center gap-3">
            <x-primary-button>Enregistrer</x-primary-button>
            @if (session('status') === 'password-updated')
                <span class="small text-success">Enregistré.</span>
            @endif
        </div>
    </form>
</section>
