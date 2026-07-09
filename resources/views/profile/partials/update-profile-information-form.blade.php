<section>
    <header class="mb-3">
        <h5 class="fw-semibold">Informations du profil</h5>
        <p class="text-muted small">Mettez à jour vos informations et votre photo.</p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="d-flex align-items-center gap-4 mb-4">
            <div class="position-relative">
                <div style="width: 80px; height: 80px; border-radius: 50%; overflow: hidden; background: #fef0e0; display: flex; align-items: center; justify-content: center; border: 3px solid #CA7B05;">
                    @if($user->avatar)
                        <img src="{{ Storage::url($user->avatar) }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <i class="bi bi-person-fill" style="font-size: 2rem; color: #9B4D07;"></i>
                    @endif
                </div>
                <label for="avatar" class="position-absolute bottom-0 end-0" style="cursor: pointer; background: #9B4D07; color: #fff; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid #fff;">
                    <i class="bi bi-camera-fill" style="font-size: 0.7rem;"></i>
                </label>
                <input type="file" id="avatar" name="avatar" class="d-none" accept="image/jpeg,image/png,image/webp" onchange="this.form.submit()">
            </div>
            <div>
                <span class="fw-semibold" style="color: #3E1E05;">{{ $user->name }}</span>
                <p class="small text-muted mb-0">{{ $user->email }}</p>
            </div>
        </div>

        <div class="mb-3">
            <x-input-label for="name" value="Nom" />
            <x-text-input id="name" name="name" type="text" class="form-control" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="mb-3">
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" name="email" type="email" class="form-control" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div class="d-flex align-items-center gap-3">
            <x-primary-button>Enregistrer</x-primary-button>
            @if (session('status') === 'profile-updated')
                <span class="small text-success">Enregistré.</span>
            @endif
        </div>
    </form>
</section>