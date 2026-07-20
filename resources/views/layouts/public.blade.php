<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'Fitabe'))</title>
    <meta name="description" content="@yield('description', 'Soutenez vos artistes préférés au FITAB. Théâtre, Danse, Musique, Percussion et Art visuel.')">
    @stack('meta')
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    <style>
        :root {
            --fitab-brown: #3E1E05;
            --fitab-brown-light: #5F2B0C;
            --fitab-orange: #9B4D07;
            --fitab-orange-light: #CA7B05;
            --fitab-cream: #E3D5AD;
            --fitab-bg: #fdfaf5;
        }
        body { background-color: var(--fitab-bg); color: #2c2c2c; }
        h1, h2, h3, h4, h5, h6 { color: var(--fitab-orange); }
        .text-muted-custom { color: #5F2B0C; }
        a { color: var(--fitab-orange); transition: color 0.2s; }
        a:hover { color: var(--fitab-orange-light); }

        .btn-fitab {
            background: var(--fitab-orange);
            color: #fff;
            font-weight: 600;
            transition: all 0.25s;
        }
        .btn-fitab:hover {
            background: var(--fitab-orange-light);
            color: #fff;
            transform: translateY(-1px);
        }
        .btn-fitab-outline {
            background: transparent;
            border: 2px solid var(--fitab-orange);
            color: var(--fitab-orange);
            font-weight: 600;
        }
        .btn-fitab-outline:hover {
            background: var(--fitab-orange);
            color: #fff;
        }
        .btn-fitab-ghost {
            background: transparent;
            border: 2px solid var(--fitab-cream);
            color: var(--fitab-cream);
            font-weight: 600;
        }
        .btn-fitab-ghost:hover {
            background: var(--fitab-cream);
            color: var(--fitab-brown);
        }

        .section-light { background: #fff; }
        .section-dark { background: var(--fitab-brown); }

        .nav-hover { position: relative; }
        .nav-hover::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--fitab-orange-light);
            transition: width 0.25s;
        }
        .nav-hover:hover::after,
        .nav-hover.nav-active::after { width: 100%; }
        .nav-hover:hover { color: var(--fitab-orange-light) !important; }
        .nav-hover.nav-active { color: var(--fitab-orange-light) !important; }
        .social-icon { transition: color 0.2s; }
        .social-icon:hover { color: var(--fitab-orange-light) !important; }
        .footer-link { transition: color 0.2s; }
        .footer-link:hover { color: var(--fitab-orange-light) !important; }

        .nav-menu {
            gap: 0.5rem;
        }
        .nav-menu .nav-link {
            padding: 0.5rem 0.75rem;
        }
        @media (max-width: 991.98px) {
            .nav-menu {
                position: fixed;
                top: 76px;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(62,30,5,0.98);
                flex-direction: column !important;
                align-items: center !important;
                justify-content: center !important;
                padding: 0;
                transform: translateX(100%);
                transition: transform 0.3s ease;
                z-index: 1029;
            }
            #navToggle:checked ~ .nav-menu {
                transform: translateX(0);
            }
            #navToggle:checked ~ .nav-hamburger span:nth-child(1) {
                transform: rotate(45deg) translate(4px, 4px);
            }
            #navToggle:checked ~ .nav-hamburger span:nth-child(2) {
                opacity: 0;
            }
            #navToggle:checked ~ .nav-hamburger span:nth-child(3) {
                transform: rotate(-45deg) translate(4px, -4px);
            }
            .nav-menu .nav-link {
                display: block;
                padding: 0.8rem 2rem;
                text-align: center;
                font-size: 1.1rem;
            }
        }
        @media (min-width: 992px) {
            .nav-menu {
                flex-direction: row !important;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    {{-- ==================== NAVBAR ==================== --}}
    <nav class="navbar fixed-top" style="background-color: rgba(62,30,5,0.95); backdrop-filter: blur(10px); z-index: 1030;">
        <div class="container d-flex align-items-center justify-content-between">

            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="FITAB" height="48"
                     onerror="this.style.display='none'">
            </a>

            <input type="checkbox" id="navToggle" class="d-none" aria-label="Menu">
            <label for="navToggle" class="nav-hamburger d-flex d-lg-none flex-column gap-1" style="cursor:pointer; padding: 4px;">
                <span style="width:26px; height:3px; background:#E3D5AD; border-radius:2px; transition: 0.3s;"></span>
                <span style="width:26px; height:3px; background:#E3D5AD; border-radius:2px; transition: 0.3s;"></span>
                <span style="width:26px; height:3px; background:#E3D5AD; border-radius:2px; transition: 0.3s;"></span>
            </label>

            <ul class="nav-menu d-flex flex-column flex-lg-row align-items-lg-center gap-lg-2 list-unstyled mb-0">
                <li class="nav-item">
                    <a class="nav-link fw-medium nav-hover {{ request()->routeIs('home') ? 'nav-active' : '' }}"
                       style="color: #E3D5AD;"
                       href="{{ route('home') }}">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-medium nav-hover {{ request()->routeIs('public.medias') ? 'nav-active' : '' }}"
                       style="color: #E3D5AD;"
                       href="{{ route('public.medias') }}">Médiathèque</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-medium nav-hover {{ request()->routeIs('public.vote') ? 'nav-active' : '' }}"
                       style="color: #E3D5AD;"
                       href="{{ route('public.vote') }}">Ovation</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-medium nav-hover {{ request()->routeIs('public.contact') ? 'nav-active' : '' }}"
                       style="color: #E3D5AD;"
                       href="{{ route('public.contact') }}">Contact</a>
                </li>
            </ul>

        </div>
    </nav>

    {{-- ==================== ESPACEUR NAVBAR ==================== --}}
    <div style="height: 76px;"></div>

    <main>
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
    <footer class="pt-5 pb-3" style="background-color: #3E1E05; color: #E3D5AD;">
        <div class="container">

            {{-- Newsletter --}}
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8">
                    <div class="rounded-3 p-4 p-md-5" style="background-color: rgba(0,0,0,0.2);">
                        <div class="row align-items-center g-3">
                            <div class="col-md-5">
                                <h5 class="fw-bold mb-1" style="color: #E3D5AD;">Restez informé</h5>
                                <p class="small mb-0" style="color: rgba(227,213,173,0.7);">Recevez les actualités du festival</p>
                            </div>
                            <div class="col-md-7">
                                <form class="d-flex gap-2">
                                    <input type="email" class="form-control" style="background: rgba(0,0,0,0.3); border: 1px solid rgba(227,213,173,0.3); color: #E3D5AD;" placeholder="Votre adresse e-mail">
                                    <button type="submit" class="btn fw-semibold px-4 text-nowrap border-0" style="background: #CA7B05; color: #fff;">S'abonner</button>
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
                        <img src="{{ asset('images/logo.png') }}" alt="FITAB" height="50"
                             onerror="this.style.display='none'">
                    </div>
                    <p class="small lh-lg" style="color: rgba(227,213,173,0.65);">
                        Festival International des Talents Artistiques du Bénin. Théâtre, Danse, Musique, Percussion et Art Visuel.
                    </p>
                    <div class="d-flex gap-3 mt-3">
                        <a href="https://www.facebook.com/share/1WhHoPqx9H/" target="_blank" rel="noopener" class="text-decoration-none fs-5 social-icon" style="color: rgba(227,213,173,0.6);"><i class="bi bi-facebook"></i></a>
                        <a href="https://www.instagram.com/fitab_talents_artistiques_pn/" target="_blank" rel="noopener" class="text-decoration-none fs-5 social-icon" style="color: rgba(227,213,173,0.6);"><i class="bi bi-instagram"></i></a>
                        <a href="https://www.youtube.com/@TalentsArtistiques" target="_blank" rel="noopener" class="text-decoration-none fs-5 social-icon" style="color: rgba(227,213,173,0.6);"><i class="bi bi-youtube"></i></a>
                        <a href="https://www.tiktok.com/@fitab_talent_artistique" target="_blank" rel="noopener" class="text-decoration-none fs-5 social-icon" style="color: rgba(227,213,173,0.6);"><i class="bi bi-tiktok"></i></a>
                    </div>
                </div>

                {{-- Navigation, Support, Contact groupés --}}
                <div class="col-12 col-lg-7 offset-lg-1">
                    <div class="row gy-4">
                        <div class="col-6 col-lg-4">
                            <h6 class="fw-bold mb-3 text-uppercase small" style="color: #CA7B05; letter-spacing: 1.5px;">Navigation</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2"><a href="{{ route('home') }}" class="text-decoration-none small footer-link" style="color: rgba(227,213,173,0.65);">Accueil</a></li>
                                <li class="mb-2"><a href="{{ route('public.medias') }}" class="text-decoration-none small footer-link" style="color: rgba(227,213,173,0.65);">Médiathèque</a></li>
                                <li class="mb-2"><a href="{{ route('public.vote') }}" class="text-decoration-none small footer-link" style="color: rgba(227,213,173,0.65);">Ovation</a></li>
                                <li class="mb-2"><a href="{{ route('public.contact') }}" class="text-decoration-none small footer-link" style="color: rgba(227,213,173,0.65);">Contact</a></li>
                            </ul>
                        </div>

                        <div class="col-6 col-lg-4">
                            <h6 class="fw-bold mb-3 text-uppercase small" style="color: #CA7B05; letter-spacing: 1.5px;">Support</h6>
                            <ul class="list-unstyled">
<li class="mb-2"><a href="{{ route('public.cgu') }}" class="text-decoration-none small footer-link" style="color: rgba(227,213,173,0.65);">Conditions générales</a></li>
<li class="mb-2"><a href="{{ route('public.confidentialite') }}" class="text-decoration-none small footer-link" style="color: rgba(227,213,173,0.65);">Politique de confidentialité</a></li>
                                <li class="mb-2"><a href="{{ route('public.mentions-legales') }}" class="text-decoration-none small footer-link" style="color: rgba(227,213,173,0.65);">Mentions légales</a></li>
                                <li class="mb-2"><a href="{{ route('public.reglement') }}" class="text-decoration-none small footer-link" style="color: rgba(227,213,173,0.65);">Règlement</a></li>
                            </ul>
                        </div>

                        <div class="col-12 col-lg-4">
                            <h6 class="fw-bold mb-3 text-uppercase small" style="color: #CA7B05; letter-spacing: 1.5px;">Contact</h6>
                            <ul class="list-unstyled small" style="color: rgba(227,213,173,0.65);">
                                <li class="mb-2 d-flex gap-2">
                                    <i class="bi bi-whatsapp mt-1" style="color: #CA7B05;"></i>
                                    <a href="https://wa.me/2290166167588" target="_blank" rel="noopener" style="color: rgba(227,213,173,0.65); text-decoration: none;">+229 01 66 16 75 88</a>
                                </li>
                                <li class="mb-2 d-flex gap-2">
                                    <i class="bi bi-envelope-fill mt-1" style="color: #CA7B05;"></i>
                                    <span>strategemediaevents@gmail.com</span>
                                </li>
                                <li class="mb-2 d-flex gap-2">
                                    <i class="bi bi-geo-alt-fill mt-1" style="color: #CA7B05;"></i>
                                    <span>Agbokou Centre Social, Porto-Novo, Bénin</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>

            <hr class="mt-5 mb-4" style="border-color: rgba(227,213,173,0.2);">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <p class="small mb-0 text-center" style="color: rgba(227,213,173,0.5);">
                    &copy; {{ date('Y') }} {{ config('app.name', 'FITAB') }}. Tous droits réservés.
                </p>
                <p class="small mb-0 text-center" style="color: rgba(227,213,173,0.5);">
                    Développé par <a href="#" class="text-decoration-none" style="color: #CA7B05;">Noctam Communication</a>
                </p>
            </div>

        </div>
    </footer>

@stack('scripts')
</body>
</html>