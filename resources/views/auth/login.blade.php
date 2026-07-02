<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="mb-3">
        <label for="email" class="form-label fw-semibold" style="color: #3E1E05;">Email</label>
        <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="admin@fitabe.bj">
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <div class="mb-4">
        <label for="password" class="form-label fw-semibold" style="color: #3E1E05;">Mot de passe</label>
        <input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
        <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    @if (session('status'))
        <div class="alert alert-success py-2 small mb-3">{{ session('status') }}</div>
    @endif

    <button type="submit" class="btn w-100 text-white fw-semibold py-2 border-0" style="background: #9B4D07;">
        Connexion <i class="bi bi-arrow-right ms-1"></i>
    </button>
</form>
