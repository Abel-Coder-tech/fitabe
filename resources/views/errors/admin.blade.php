<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Erreur serveur — FITAB</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
        <style>
            body {
                background: linear-gradient(135deg, #fdfaf5 0%, #f5ede1 100%);
                color: #3E1E05;
                font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
                min-height: 100vh;
            }
            .panel {
                background: #fff;
                border: 1px solid rgba(202,123,5,0.15);
                border-radius: 14px;
                box-shadow: 0 8px 24px rgba(0,0,0,0.05);
            }
            .error-code {
                font-size: 4rem;
                font-weight: 800;
                color: #9B4D07;
                line-height: 1;
            }
            .badge-admin {
                background: #fef0e0;
                color: #9B4D07;
                font-weight: 600;
            }
            .detail-label {
                font-size: 0.7rem;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                color: #9B4D07;
                font-weight: 700;
            }
            pre {
                background: #2d1a05;
                color: #f5e6cc;
                border-radius: 10px;
                padding: 14px;
                font-size: 0.78rem;
                max-height: 380px;
                overflow: auto;
                white-space: pre-wrap;
                word-break: break-all;
            }
            .btn-fitab {
                background: #9B4D07;
                color: #fff;
                border: none;
                border-radius: 10px;
                padding: 10px 22px;
                font-weight: 600;
                transition: all 0.2s;
                text-decoration: none;
                display: inline-block;
            }
            .btn-fitab:hover { background: #3E1E05; color: #fff; }
        </style>
    </head>
    <body>
        <div class="container py-5" style="max-width: 860px;">
            <div class="d-flex align-items-center gap-2 mb-4">
                <img src="{{ asset('images/logo.png') }}" alt="FITAB" height="42"
                     onerror="this.style.display='none'"
                     style="background: rgba(62,30,5,0.95); border-radius: 8px; padding: 8px;">
                <span class="badge badge-admin rounded-pill px-3 py-2">
                    <i class="bi bi-shield-lock me-1"></i>Détails réservés au super admin
                </span>
            </div>

            <div class="panel p-4 mb-3">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
                    <div>
                        <div class="error-code">500</div>
                        <div class="fw-semibold mt-2" style="color: #3E1E05;">Une erreur serveur est survenue</div>
                    </div>
                    <div class="text-end">
                        <a href="{{ route('admin.dashboard') }}" class="btn-fitab"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a>
                        <a href="{{ url('/') }}" class="btn btn-outline-secondary rounded-3 ms-2"><i class="bi bi-house-door me-1"></i>Accueil</a>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="detail-label mb-1">Classe</div>
                    <code>{{ $class }}</code>
                </div>
                <div class="mb-3">
                    <div class="detail-label mb-1">Message</div>
                    <div class="p-3 rounded-3" style="background:#fdf6ec; border:1px solid rgba(202,123,5,0.15);">{{ $message }}</div>
                </div>
                <div class="mb-3">
                    <div class="detail-label mb-1">Fichier : Ligne</div>
                    <code>{{ $file }} : {{ $line }}</code>
                </div>
                <div>
                    <details>
                        <summary class="detail-label mb-1" style="cursor:pointer;">Stack trace</summary>
                        <pre>{{ $trace }}</pre>
                    </details>
                </div>
            </div>

            <p class="text-muted small text-center">
                <i class="bi bi-lightbulb me-1"></i> Cette page n'est visible que par le super admin connecté.
                Les visiteurs voient la page 500 générique. Trace complète : <code>storage/logs/laravel.log</code>.
            </p>
        </div>
    </body>
</html>
