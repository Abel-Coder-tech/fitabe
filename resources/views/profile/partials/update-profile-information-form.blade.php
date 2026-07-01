<section>
    <header class="mb-3">
        <h5 class="fw-semibold">Informations du profil</h5>
        <p class="text-muted small">Mettez à jour les informations de votre profil et votre adresse e-mail.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div class="mb-3">
            <x-input-label for="name" value="Nom" />
            <x-text-input id="name" name="name" type="text" class="form-control" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="mb-3">
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" name="email" type="email" class="form-control" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="small text-muted mb-1">
                        Votre adresse e-mail n'est pas vérifiée.
                        <button form="send-verification" class="btn btn-link btn-sm p-0">Cliquez ici pour renvoyer l'e-mail de vérification.</button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="small text-success">Un nouveau lien de vérification a été envoyé à votre adresse e-mail.</p>
                    @endif
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center gap-3">
            <x-primary-button>Enregistrer</x-primary-button>
            @if (session('status') === 'profile-updated')
                <span class="small text-success">Enregistré.</span>
            @endif
        </div>
    </form>
</section>
