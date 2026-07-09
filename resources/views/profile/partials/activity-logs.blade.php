<section>
    <header class="mb-3">
        <h5 class="fw-semibold">Activité récente</h5>
        <p class="text-muted small">Historique des actions importantes sur votre compte.</p>
    </header>

    @php
        $logs = [
            ['action' => 'Inscription', 'detail' => 'Création du compte', 'date' => $user->created_at],
            ['action' => 'Dernière modification', 'detail' => 'Mise à jour du profil', 'date' => $user->updated_at],
        ];

        if ($user->email_verified_at) {
            $logs[] = ['action' => 'Email vérifié', 'detail' => 'Adresse email confirmée', 'date' => $user->email_verified_at];
        }
    @endphp

    @if(empty($logs))
        <p class="small text-muted">Aucune activité enregistrée.</p>
    @else
        <div class="list-group list-group-flush">
            @foreach($logs as $log)
                <div class="list-group-item px-0 d-flex align-items-center gap-3" style="border-radius: 0; border-color: #f5f5f5;">
                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #fef0e0; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="bi bi-{{ $loop->first ? 'person-check' : 'arrow-repeat' }}" style="color: #9B4D07; font-size: 0.8rem;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <span class="small fw-semibold" style="color: #3E1E05;">{{ $log['action'] }}</span>
                        <span class="small text-muted d-block">{{ $log['detail'] }}</span>
                    </div>
                    <span class="small text-muted" style="white-space: nowrap;">{{ $log['date']->diffForHumans() }}</span>
                </div>
            @endforeach
        </div>
    @endif

    @php
        $lastSession = $sessions->first();
    @endphp
    @if($lastSession)
        <hr>
        <div class="d-flex align-items-center gap-3">
            <div style="width: 32px; height: 32px; border-radius: 50%; background: #f0f7ff; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <i class="bi bi-globe2" style="color: #3b82f6; font-size: 0.8rem;"></i>
            </div>
            <div>
                <span class="small fw-semibold" style="color: #3E1E05;">Dernière connexion</span>
                <span class="small text-muted d-block">
                    {{ $lastSession->user_agent['browser'] }} sur {{ $lastSession->user_agent['os'] }} — {{ $lastSession->last_activity_humain }}
                </span>
            </div>
        </div>
    @endif
</section>