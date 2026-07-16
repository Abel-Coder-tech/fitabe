<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title') — FITAB</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <style>
            html, body {
                background: linear-gradient(135deg, #fdfaf5 0%, #f5ede1 100%);
                color: #3E1E05;
                font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
                height: 100vh;
                margin: 0;
            }
            .full-height { height: 100vh; }
            .flex-center { align-items: center; display: flex; justify-content: center; }
            .error-code {
                font-size: 6rem;
                font-weight: 800;
                color: #9B4D07;
                line-height: 1;
                text-shadow: 0 2px 10px rgba(155,77,7,0.15);
            }
            .error-message {
                font-size: 1.1rem;
                color: #5F2B0C;
                opacity: 0.8;
            }
            .error-icon {
                font-size: 3rem;
                color: #c9a96e;
            }
            .btn-fitab {
                background: #9B4D07;
                color: #fff;
                border: none;
                border-radius: 10px;
                padding: 10px 28px;
                font-weight: 600;
                transition: all 0.2s;
                text-decoration: none;
                display: inline-block;
            }
            .btn-fitab:hover {
                background: #3E1E05;
                color: #fff;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content text-center px-3">
                <div class="mb-3">
                    <img src="{{ asset('images/logo.png') }}" alt="FITAB" height="50"
                         onerror="this.style.display='none'"
                         style="background: rgba(62,30,5,0.95); border-radius: 8px; padding: 10px;">
                </div>
                <div class="error-code">@yield('code')</div>
                <div class="error-icon mb-2">
                    @yield('icon')
                </div>
                <div class="error-message mb-4">@yield('message')</div>
                <a href="{{ url('/') }}" class="btn-fitab">
                    <i class="bi bi-house-door me-1"></i> Retour à l'accueil
                </a>
            </div>
        </div>
    </body>
</html>
