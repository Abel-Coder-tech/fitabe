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
    @stack('styles')
</head>
<body>

    {{-- ==================== NAVBAR ==================== --}}
    <nav class="navbar navbar-expand-lg fixed-top" style="background-color: rgba(24,61,106,0.92); backdrop-filter: blur(10px);">
        <div class="container">

            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="FITAB" height="48"
                     onerror="this.style.display='none'">
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon navbar-toggler-white"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto gap-lg-2">
                    <li class="nav-item">
                        <a class="nav-link text-white fw-medium nav-hover {{ request()->routeIs('home') ? 'nav-active' : '' }}"
                           href="{{ route('home') }}">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white fw-medium nav-hover {{ request()->routeIs('public.medias') ? 'nav-active' : '' }}"
                           href="{{ route('public.medias') }}">Médiathèque</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white fw-medium nav-hover {{ request()->routeIs('public.vote') ? 'nav-active' : '' }}"
                           href="{{ route('public.vote') }}">Voter</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white fw-medium nav-hover {{ request()->routeIs('public.contact') ? 'nav-active' : '' }}"
                           href="{{ route('public.contact') }}">Contact</a>
                    </li>
                </ul>
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
    <footer class="text-white pt-5 pb-3" style="background-color: #12263a;">
        <div class="container">

            {{-- Newsletter --}}
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8">
                    <div class="bg-dark rounded-3 p-4 p-md-5 border border-secondary border-opacity-25">
                        <div class="row align-items-center g-3">
                            <div class="col-md-5">
                                <h5 class="fw-bold mb-1">Restez informé</h5>
                                <p class="text-white-50 small mb-0">Recevez les actualités du festival</p>
                            </div>
                            <div class="col-md-7">
                                <form class="d-flex gap-2">
                                    <input type="email" class="form-control bg-black text-white border-secondary" placeholder="Votre adresse e-mail">
                                    <button type="submit" class="btn fw-semibold px-4 text-nowrap text-white border-0" style="background-color: #98732B;">S'abonner</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Colonnes --}}
            <div class="row gy-4">

                {{-- À propos --}}
                <div class="col-12 col-lg-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <img src="{{ asset('images/logo.png') }}" alt="FITAB" height="40"
                             onerror="this.style.display='none'">
                        <span class="fw-bold fs-5">{{ config('app.name', 'FITAB') }}</span>
                    </div>
                    <p class="text-white-50 small lh-lg">
                        Festival International des Talents Artistiques du Bénin.<br>
                        Théâtre, Danse, Musique, Percussion et Art Visuel.
                    </p>
                    <div class="d-flex gap-3 mt-3">
                        <a href="#" class="text-white-50 text-decoration-none fs-5 footer-social"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-white-50 text-decoration-none fs-5 footer-social"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-white-50 text-decoration-none fs-5 footer-social"><i class="bi bi-youtube"></i></a>
                        <a href="#" class="text-white-50 text-decoration-none fs-5 footer-social"><i class="bi bi-tiktok"></i></a>
                    </div>
                </div>

                {{-- Navigation, Support, Contact groupés --}}
                <div class="col-12 col-lg-7 offset-lg-1">
                    <div class="row gy-4">
                        <div class="col-6 col-lg-4">
                            <h6 class="fw-bold mb-3 text-uppercase small" style="color: #98732B; letter-spacing: 1.5px;">Navigation</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none small footer-link">Accueil</a></li>
                                <li class="mb-2"><a href="{{ route('public.medias') }}" class="text-white-50 text-decoration-none small footer-link">Médiathèque</a></li>
                                <li class="mb-2"><a href="{{ route('public.vote') }}" class="text-white-50 text-decoration-none small footer-link">Vote</a></li>
                                <li class="mb-2"><a href="{{ route('public.contact') }}" class="text-white-50 text-decoration-none small footer-link">Contact</a></li>
                            </ul>
                        </div>

                        <div class="col-6 col-lg-4">
                            <h6 class="fw-bold mb-3 text-uppercase small" style="color: #98732B; letter-spacing: 1.5px;">Support</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none small footer-link">Conditions générales</a></li>
                                <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none small footer-link">Politique de confidentialité</a></li>
                                <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none small footer-link">Mentions légales</a></li>
                                <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none small footer-link">Aide au vote</a></li>
                            </ul>
                        </div>

                        <div class="col-12 col-lg-4">
                            <h6 class="fw-bold mb-3 text-uppercase small" style="color: #98732B; letter-spacing: 1.5px;">Contact</h6>
                            <ul class="list-unstyled text-white-50 small">
                                <li class="mb-2 d-flex gap-2">
                                    <i class="bi bi-telephone-fill mt-1" style="color: #98732B;"></i>
                                    <span>+229 01 66 16 75 88</span>
                                </li>
                                <li class="mb-2 d-flex gap-2">
                                    <i class="bi bi-envelope-fill mt-1" style="color: #98732B;"></i>
                                    <span>eyissobur@gmail.com</span>
                                </li>
                                <li class="mb-2 d-flex gap-2">
                                    <i class="bi bi-geo-alt-fill mt-1" style="color: #98732B;"></i>
                                    <span>Porto-Novo, Bénin</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>



            </div>

            <hr class="border-secondary mt-5 mb-4">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <p class="text-white-50 small mb-0">
                    &copy; {{ date('Y') }} {{ config('app.name', 'FITAB') }} — STRATEGE MEDIAS EVENTS. Tous droits réservés.
                </p>
                <p class="text-white-50 small mb-0">
                    Développé par <a href="#" class="text-warning text-decoration-none footer-link">Noctam Communication</a>
                </p>
            </div>

        </div>
    </footer>

@stack('scripts')
</body>
</html>