<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'Fitabe'))</title>
    <meta name="description" content="@yield('description', 'Soutenez vos artistes préférés au FITAB. Théâtre, Danse, Musique, Percussion et Art visuel.')">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎤</text></svg>">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>
<body>

    {{-- ==================== NAVBAR ==================== --}}
    <nav class="navbar navbar-expand-lg fixed-top" style="background-color: rgba(0,0,0,0.85); backdrop-filter: blur(10px);">
        <div class="container">

            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="FITAB" height="45"
                     onerror="this.style.display='none'">
                <div>
                    <span class="fw-bold text-white" style="font-size: 1rem; line-height: 1.1;">
                        {{ config('app.name', 'Fitabe') }}
                    </span><br>
                    <span class="text-warning" style="font-size: 0.65rem; letter-spacing: 1px;">FESTIVAL INTERNATIONAL</span>
                </div>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto gap-lg-2">
                    <li class="nav-item">
                        <a class="nav-link text-white fw-medium {{ request()->routeIs('home') ? 'text-warning' : '' }}"
                           href="{{ route('home') }}">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white fw-medium {{ request()->routeIs('public.medias') ? 'text-warning' : '' }}"
                           href="{{ route('public.medias') }}">Médiathèque</a>
                    </li>

                    @php $voteMode = \App\Models\Parametres::where('cle', 'vote_mode')->value('valeur') ?? 'off'; @endphp

                    @if($voteMode === 'off')
                        <li class="nav-item">
                            <span class="nav-link text-white-50" style="cursor: default;">
                                Votes <small class="text-warning">(bientôt)</small>
                            </span>
                        </li>
                    @elseif($voteMode === 'active')
                        <li class="nav-item">
                            <a class="nav-link text-white fw-medium {{ request()->routeIs('public.vote') ? 'text-warning' : '' }}"
                               href="{{ route('public.vote') }}">Voter</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link text-white fw-medium {{ request()->routeIs('public.vote') ? 'text-warning' : '' }}"
                               href="{{ route('public.vote') }}">Résultats</a>
                        </li>
                    @endif

                    <li class="nav-item">
                        <a class="nav-link text-white fw-medium {{ request()->routeIs('public.contact') ? 'text-warning' : '' }}"
                           href="{{ route('public.contact') }}">Contact</a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2 mt-3 mt-lg-0">
                    @if($voteMode === 'active')
                        <a href="{{ route('public.vote') }}" class="btn btn-warning btn-sm fw-bold px-3">
                            Voter maintenant
                        </a>
                    @else
                        <a href="{{ route('public.contact') }}" class="btn btn-outline-light btn-sm px-3">
                            Nous contacter
                        </a>
                    @endif
                </div>
            </div>

        </div>
    </nav>

    {{-- ==================== CONTENU ==================== --}}
    <main style="margin-top: 76px;">

        @if (session('success'))
            <div class="container mt-3">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="container mt-3">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @yield('content')

    </main>

    {{-- ==================== FOOTER ==================== --}}
    <footer class="text-white pt-5 pb-3" style="background-color: #0d0d0d;">
        <div class="container">
            <div class="row gy-4">

                {{-- Colonne 1 : À propos --}}
                <div class="col-lg-4 col-md-6">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <img src="{{ asset('images/logo.png') }}" alt="FITAB" height="40"
                             onerror="this.style.display='none'">
                        <span class="fw-bold fs-5">{{ config('app.name', 'Fitabe') }}</span>
                    </div>
                    <p class="text-white-50 small">
                        Festival International des Talents Artistiques du Bénin.
                        Théâtre, Danse, Musique, Percussion et Art Visuel.
                    </p>
                    <div class="d-flex gap-3 mt-3">
                        <a href="#" class="text-white-50 text-decoration-none fs-5"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-white-50 text-decoration-none fs-5"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-white-50 text-decoration-none fs-5"><i class="bi bi-youtube"></i></a>
                        <a href="#" class="text-white-50 text-decoration-none fs-5"><i class="bi bi-tiktok"></i></a>
                    </div>
                </div>

                {{-- Colonne 2 : Navigation --}}
                <div class="col-lg-2 col-md-6">
                    <h6 class="text-warning fw-bold mb-3 text-uppercase" style="letter-spacing: 1px;">Navigation</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none small">Accueil</a></li>
                        <li class="mb-2"><a href="{{ route('public.medias') }}" class="text-white-50 text-decoration-none small">Médiathèque</a></li>
                        <li class="mb-2"><a href="{{ route('public.vote') }}" class="text-white-50 text-decoration-none small">Vote</a></li>
                        <li class="mb-2"><a href="{{ route('public.contact') }}" class="text-white-50 text-decoration-none small">Contact</a></li>
                    </ul>
                </div>

                {{-- Colonne 3 : Contact --}}
                <div class="col-lg-3 col-md-6">
                    <h6 class="text-warning fw-bold mb-3 text-uppercase" style="letter-spacing: 1px;">Contact</h6>
                    <ul class="list-unstyled text-white-50 small">
                        <li class="mb-2 d-flex gap-2">
                            <i class="bi bi-telephone-fill text-warning"></i>
                            <span>+229 01 66 16 75 88</span>
                        </li>
                        <li class="mb-2 d-flex gap-2">
                            <i class="bi bi-envelope-fill text-warning"></i>
                            <span>eyissobur@gmail.com</span>
                        </li>
                        <li class="mb-2 d-flex gap-2">
                            <i class="bi bi-geo-alt-fill text-warning"></i>
                            <span>Porto-Novo, Bénin</span>
                        </li>
                    </ul>
                </div>

                {{-- Colonne 4 : Partenaires --}}
                <div class="col-lg-3 col-md-6">
                    <h6 class="text-warning fw-bold mb-3 text-uppercase" style="letter-spacing: 1px;">Nos partenaires</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach(\App\Models\Partenaires::ordered()->take(6)->get() as $partenaire)
                            <a href="{{ $partenaire->site_web ?? '#' }}" target="_blank" title="{{ $partenaire->nom }}">
                                <img src="{{ $partenaire->logo_url }}"
                                     alt="{{ $partenaire->nom }}"
                                     height="35"
                                     class="rounded"
                                     style="filter: grayscale(100%) brightness(1.5); opacity: 0.7; transition: all .3s;"
                                     onmouseover="this.style.filter='none'; this.style.opacity='1';"
                                     onmouseout="this.style.filter='grayscale(100%) brightness(1.5)'; this.style.opacity='0.7';">
                            </a>
                        @endforeach
                    </div>
                </div>

            </div>

            <hr class="border-secondary mt-4">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <p class="text-white-50 small mb-0">
                    &copy; {{ date('Y') }} {{ config('app.name', 'Fitabe') }} — STRATEGE MEDIAS EVENTS. Tous droits réservés.
                </p>
                <p class="text-white-50 small mb-0">
                    Développé par <a href="#" class="text-warning text-decoration-none">Noctam Communication</a>
                </p>
            </div>

        </div>
    </footer>

</body>
</html>