<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'FITAB'))</title>
    <meta name="description" content="@yield('description', 'Soutenez vos artistes préférés au FITAB. Théâtre, Danse, Musique, Percussion et Art visuel.')">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:title" content="@yield('og_title', @yield('title', config('app.name', 'FITAB')))">
    <meta property="og:description" content="@yield('og_description', @yield('description', 'Soutenez vos artistes préférés au FITAB.'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="@yield('og_image', asset('images/hero.jpg'))">
    <meta property="og:site_name" content="{{ config('app.name', 'FITAB') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', @yield('title', config('app.name', 'FITAB')))">
    <meta name="twitter:description" content="@yield('og_description', @yield('description', 'Soutenez vos artistes préférés au FITAB.'))">
    <meta name="twitter:image" content="@yield('og_image', asset('images/hero.jpg'))">
    @stack('meta')
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "FITAB",
        "alternateName": "Festival International des Talents Artistiques du Bénin",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('images/logo.png') }}",
        "sameAs": [
            "https://www.facebook.com/fitab.benin",
            "https://www.instagram.com/fitab.benin",
            "https://www.youtube.com/@fitab"
        ]
    }
    </script>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "FITAB",
        "url": "{{ url('/') }}"
    }
    </script>
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

        .social-icon { transition: color 0.2s; }
        .social-icon:hover { color: var(--fitab-orange-light) !important; }
        .footer-link { transition: color 0.2s; }
        .footer-link:hover { color: var(--fitab-orange-light) !important; }

        /* ========== STICKY HEADER ========== */
        .public-header {
            background: rgba(62,30,5,0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(227,213,173,0.1);
            padding: 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1050;
        }

        .header-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
        }

        .public-header .brand-logo {
            flex-shrink: 0;
            display: flex;
            align-items: center;
        }
        .public-header .brand-logo img {
            height: 40px;
            display: block;
        }

        .public-nav {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 0.15rem;
            align-items: center;
        }

        .public-nav a {
            color: rgba(227,213,173,0.85);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            padding: 0.45rem 0.85rem;
            border-radius: 8px;
            transition: all 0.15s;
            white-space: nowrap;
        }

        .public-nav a:hover {
            background: rgba(202,123,5,0.15);
            color: var(--fitab-orange-light);
        }

        .public-nav a.active {
            background: transparent;
            color: var(--fitab-orange-light);
            font-weight: 700;
            border-top: 3px solid var(--fitab-orange-light);
            border-radius: 4px 4px 0 0;
            padding-top: calc(0.45rem - 3px);
        }

        .public-nav a.active:hover {
            background: transparent;
            color: var(--fitab-orange-light);
        }

        /* Footer spacing mobile */
        @media (max-width: 575.98px) {
            footer .col-6,
            footer .col-12 { padding-left: 0.75rem !important; }
        }

        /* Mobile toggle */
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #E3D5AD;
            padding: 0.25rem 0.5rem;
            cursor: pointer;
        }

        @media (max-width: 991.98px) {
            .mobile-toggle { display: block; }

            .public-nav {
                display: none;
                position: absolute;
                top: 64px;
                left: 0;
                right: 0;
                background: rgba(62,30,5,0.98);
                border-bottom: 1px solid rgba(227,213,173,0.1);
                flex-direction: column;
                padding: 0.75rem 1rem;
                gap: 0.25rem;
            }

            .public-nav.show { display: flex; }

            .public-nav a {
                display: block;
                padding: 0.65rem 0.75rem;
                font-size: 0.92rem;
                border-radius: 8px;
                text-align: center;
            }

            .public-nav a.active {
                border-top: none;
                border-left: 3px solid var(--fitab-orange-light);
                border-radius: 4px;
                padding-top: 0.65rem;
                background: rgba(202,123,5,0.08);
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    {{-- ==================== STICKY HEADER ==================== --}}
    <header class="public-header">
        <div class="container">
            <div class="header-inner">

                <a class="brand-logo" href="{{ route('home') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="FITAB"
                         onerror="this.style.display='none'">
                </a>

                <button class="mobile-toggle" id="mobileToggle" aria-label="Menu">
                    <i class="bi bi-list"></i>
                </button>

                <ul class="public-nav" id="publicNav">
                    <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Accueil</a></li>
                    <li><a href="{{ route('public.medias') }}" class="{{ request()->routeIs('public.medias') ? 'active' : '' }}">Médiathèque</a></li>
                    <li><a href="{{ route('public.vote') }}" class="{{ request()->routeIs('public.vote') ? 'active' : '' }}">Ovation</a></li>
                    <li><a href="{{ route('public.contact') }}" class="{{ request()->routeIs('public.contact') ? 'active' : '' }}">Contact</a></li>
                </ul>

            </div>
        </div>
    </header>

    {{-- ==================== ESPACEUR HEADER ==================== --}}
    <div style="height: 64px;"></div>

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
    <footer class="public-footer pt-5 pb-3" style="background-color: #3E1E05; color: #E3D5AD;">
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
                                <form method="POST" action="{{ route('public.newsletter.store') }}" class="d-flex gap-2">
                                    @csrf
                                    <input type="email" name="email" class="form-control" style="background: rgba(0,0,0,0.3); border: 1px solid rgba(227,213,173,0.3); color: #E3D5AD;" placeholder="Votre adresse e-mail" required>
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
                    <div class="d-flex align-items-center gap-2 mb-3 brand">
                        <img src="{{ asset('images/logo.png') }}" alt="FITAB" height="50"
                             onerror="this.style.display='none'">
                    </div>
                    <p class="small lh-lg" style="color: rgba(227,213,173,0.65);">
                        Festival International des Talents Artistiques du Bénin. Théâtre, Danse, Musique, Percussion et Art Visuel.
                    </p>
                    <div class="d-flex gap-3 mt-3 footer-social">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    var toggle = document.getElementById('mobileToggle');
    var nav = document.getElementById('publicNav');
    if (toggle && nav) {
        toggle.addEventListener('click', function() {
            nav.classList.toggle('show');
        });
    }
});
</script>
@stack('scripts')
</body>
</html>