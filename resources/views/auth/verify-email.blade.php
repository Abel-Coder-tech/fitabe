<x-guest-layout>
    <div class="text-muted mb-4 small">
        Merci de vous être inscrit ! Avant de commencer, veuillez vérifier votre adresse e-mail en cliquant sur le lien que nous venons de vous envoyer. Si vous n'avez pas reçu l'e-mail, nous vous en enverrons un autre.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success small mb-4">
            Un nouveau lien de vérification a été envoyé à l'adresse e-mail que vous avez fournie.
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mt-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button>
                Renvoyer l'e-mail de vérification
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Voulez-vous vraiment vous déconnecter ?')">
            @csrf
            <button type="submit" class="btn btn-link text-decoration-none p-0">
                Déconnexion
            </button>
        </form>
    </div>
</x-guest-layout>
