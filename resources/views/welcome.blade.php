<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div class="container py-5">
        <header class="mb-4">
            @if (Route::has('login'))
                <nav class="d-flex justify-content-end gap-2">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-outline-secondary btn-sm">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm">
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-dark btn-sm">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        <div class="row align-items-center min-vh-75">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h1 class="fw-medium mb-2">{{ config('app.name', 'Fitabe') }}</h1>
                <p class="text-muted mb-3">Bienvenue sur la plateforme de vote Fitabe.</p>
                <ul class="list-unstyled mb-4">
                    <li class="d-flex align-items-center gap-3 py-2">
                        <span class="badge bg-primary rounded-pill">&check;</span>
                        <span>Consultez les candidats et leurs programmes</span>
                    </li>
                    <li class="d-flex align-items-center gap-3 py-2">
                        <span class="badge bg-primary rounded-pill">&check;</span>
                        <span>Votez pour vos candidats favoris</span>
                    </li>
                    <li class="d-flex align-items-center gap-3 py-2">
                        <span class="badge bg-primary rounded-pill">&check;</span>
                        <span>Suivez les résultats en temps réel</span>
                    </li>
                </ul>
            </div>
            <div class="col-lg-6 text-center">
                <div class="p-4 bg-light rounded-4">
                    <p class="text-muted mb-0">Contenu à venir</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
