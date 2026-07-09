<section>
    <header class="mb-3">
        <h5 class="fw-semibold">Sessions actives</h5>
        <p class="text-muted small">Gérez vos sessions de connexion actives.</p>
    </header>

    @if($sessions->isEmpty())
        <p class="small text-muted">Aucune session active.</p>
    @else
        <div class="list-group list-group-flush mb-3">
            @foreach($sessions as $session)
                <div class="list-group-item px-0 d-flex align-items-center justify-content-between {{ $session->is_current ? 'border-start border-3 ps-2' : '' }}" style="border-radius: 0; {{ $session->is_current ? 'border-color: #CA7B05;' : '' }}">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width: 36px; height: 36px; border-radius: 50%; background: {{ $session->is_current ? '#fef0e0' : '#f5f5f5' }}; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="bi bi-{{ $session->is_current ? 'laptop' : 'phone' }}" style="color: {{ $session->is_current ? '#9B4D07' : '#aaa' }};"></i>
                        </div>
                        <div>
                            <span class="small fw-semibold" style="color: #3E1E05;">
                                {{ $session->user_agent['browser'] }} sur {{ $session->user_agent['os'] }}
                                @if($session->is_current)
                                    <span class="badge rounded-pill ms-1" style="background: #CA7B05; color: #fff; font-size: 0.6rem;">Actuelle</span>
                                @endif
                            </span>
                            <div class="small text-muted">{{ $session->last_activity_humain }}</div>
                        </div>
                    </div>
                    @if(!$session->is_current)
                        <form action="{{ route('profile.session.destroy', $session->id) }}" method="POST" class="m-0">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger border-0">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>

        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#revoke-others-modal">
            <i class="bi bi-shield-exclamation"></i> Déconnecter les autres sessions
        </button>

        <div class="modal fade" id="revoke-others-modal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('profile.sessions.revoke-others') }}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Déconnecter les autres sessions</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p class="small text-muted">Entrez votre mot de passe pour confirmer la déconnexion de toutes les autres sessions.</p>
                            <div class="mb-3">
                                <x-input-label for="password_sessions" value="Mot de passe" />
                                <x-text-input id="password_sessions" name="password" type="password" class="form-control" placeholder="Votre mot de passe" />
                                <x-input-error :messages="$errors->sessionRevoke->get('password')" class="mt-2" />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-danger">Déconnecter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</section>