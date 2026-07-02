<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Administration - ' . config('app.name', 'Fitabe'))</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎤</text></svg>">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    <style>
        .nav-link.nav-active { background: rgba(255,255,255,0.08); }
        .nav-link:hover:not(.nav-active) { background: rgba(255,255,255,0.04); color: #fff !important; }
    </style>
    @stack('styles')
</head>
<body>

<div class="d-flex" style="min-height: 100vh;">

    {{-- ============ SIDEBAR ============ --}}
    <nav class="d-flex flex-column text-white shadow-sm" style="width: 250px; min-height: 100vh; background-color: #3E1E05; flex-shrink: 0;">
        {{-- Logo --}}
        <div class="d-flex align-items-center gap-2 px-4 pt-4 pb-3">
            <img src="{{ asset('images/logo.png') }}" alt="FITAB" height="38"
                 onerror="this.style.display='none'">
            <div>
                <div class="fw-bold" style="font-size: 1.05rem;">{{ config('app.name', 'FITAB') }}</div>
                <div class="small opacity-50" style="font-size: 0.7rem; letter-spacing: 0.5px;">Administration</div>
            </div>
        </div>

        @php
            $route = request()->route()?->getName() ?? '';
            $isActive = fn($prefixes) => collect((array)$prefixes)->contains(fn($p) => str_starts_with($route, $p));
            $navClass = fn($prefixes) => $isActive($prefixes) ? 'nav-active text-white fw-semibold' : '';
        @endphp

        {{-- Navigation --}}
        <div class="px-3 mt-2 flex-grow-1 overflow-auto">
            <div class="small text-uppercase px-2 mb-2" style="color: rgba(255,255,255,0.3); font-size: 0.65rem; letter-spacing: 1.5px;">Principal</div>
            <ul class="nav flex-column gap-1">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link d-flex align-items-center gap-2 px-3 py-2 rounded-3 {{ $navClass('dashboard') }}" style="color: rgba(255,255,255,0.7); transition: all 0.15s;">
                        <i class="bi bi-grid-1x2-fill" style="width: 18px;"></i> Tableau de bord
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.candidats.index') }}" class="nav-link d-flex align-items-center gap-2 px-3 py-2 rounded-3 {{ $navClass(['admin.candidats', 'admin.candidat']) }}" style="color: rgba(255,255,255,0.7); transition: all 0.15s;">
                        <i class="bi bi-people-fill" style="width: 18px;"></i> Candidats
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.votes.index') }}" class="nav-link d-flex align-items-center gap-2 px-3 py-2 rounded-3 {{ $navClass('admin.votes') }}" style="color: rgba(255,255,255,0.7); transition: all 0.15s;">
                        <i class="bi bi-check-circle-fill" style="width: 18px;"></i> Votes
                    </a>
                </li>
            </ul>

            <div class="small text-uppercase px-2 mt-4 mb-2" style="color: rgba(255,255,255,0.3); font-size: 0.65rem; letter-spacing: 1.5px;">Contenu</div>
            <ul class="nav flex-column gap-1">
                <li class="nav-item">
                    <a href="{{ route('admin.medias.index') }}" class="nav-link d-flex align-items-center gap-2 px-3 py-2 rounded-3 {{ $navClass('admin.medias') }}" style="color: rgba(255,255,255,0.7); transition: all 0.15s;">
                        <i class="bi bi-images" style="width: 18px;"></i> Médiathèque
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.programmes.index') }}" class="nav-link d-flex align-items-center gap-2 px-3 py-2 rounded-3 {{ $navClass('admin.programmes') }}" style="color: rgba(255,255,255,0.7); transition: all 0.15s;">
                        <i class="bi bi-calendar-event-fill" style="width: 18px;"></i> Programme
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.partenaires.index') }}" class="nav-link d-flex align-items-center gap-2 px-3 py-2 rounded-3 {{ $navClass('admin.partenaires') }}" style="color: rgba(255,255,255,0.7); transition: all 0.15s;">
                        <i class="bi bi-handshake" style="width: 18px;"></i> Partenaires
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.parametres.index') }}" class="nav-link d-flex align-items-center gap-2 px-3 py-2 rounded-3 {{ $navClass('admin.parametres') }}" style="color: rgba(255,255,255,0.7); transition: all 0.15s;">
                        <i class="bi bi-gear-fill" style="width: 18px;"></i> Paramètres
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.contacts.index') }}" class="nav-link d-flex align-items-center gap-2 px-3 py-2 rounded-3 {{ $navClass('admin.contacts') }}" style="color: rgba(255,255,255,0.7); transition: all 0.15s;">
                        <i class="bi bi-envelope-fill" style="width: 18px;"></i> Messages
                        @php $nonLuCount = \App\Models\Contact::nonLu()->count(); @endphp
                        @if($nonLuCount > 0)
                            <span class="badge rounded-pill ms-auto" style="background: #dc3545; font-size: 0.65rem;">{{ $nonLuCount }}</span>
                        @endif
                    </a>
                </li>
            </ul>
        </div>

        {{-- Bas de sidebar --}}
        <div class="px-3 pb-3 mt-auto border-top pt-3" style="border-color: rgba(255,255,255,0.06) !important;">
            <ul class="nav flex-column gap-1">
                <li class="nav-item">
                    <a href="{{ route('profile.edit') }}" class="nav-link d-flex align-items-center gap-2 px-3 py-2 rounded-3" style="color: rgba(255,255,255,0.7); transition: all 0.15s;">
                        <i class="bi bi-person-circle" style="width: 18px;"></i> Mon compte
                    </a>
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link d-flex align-items-center gap-2 px-3 py-2 rounded-3 w-100 text-start border-0 bg-transparent" style="color: rgba(255,255,255,0.7); transition: all 0.15s;">
                            <i class="bi bi-box-arrow-right" style="width: 18px;"></i> Déconnexion
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    {{-- ============ CONTENU PRINCIPAL ============ --}}
    <div class="d-flex flex-column flex-grow-1" style="min-height: 100vh; background: #fdfaf5;">

        {{-- Topbar --}}
        <div class="bg-white border-bottom px-4 py-3 d-flex align-items-center justify-content-between" style="min-height: 64px;">
            <div>
                <h5 class="fw-bold mb-0" style="color: #9B4D07;">@yield('page-title', 'Tableau de bord')</h5>
                <small class="text-muted">@yield('page-subtitle', 'Édition 2026 • Votes en cours')</small>
            </div>
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light btn-sm rounded-circle p-2 border-0 position-relative" style="width: 36px; height: 36px;">
                    <i class="bi bi-bell"></i>
                </button>
                <button class="btn btn-light btn-sm rounded-circle p-2 border-0" style="width: 36px; height: 36px;">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
                <div class="d-flex align-items-center gap-2 ps-2 border-start" style="border-color: #e9ecef !important;">
                    <div class="text-end">
                        <div class="small fw-semibold" style="color: #9B4D07; line-height: 1.2;">{{ Auth::user()->name }}</div>
                        <div class="small text-muted" style="font-size: 0.7rem; line-height: 1.2;">{{ ucfirst(Auth::user()->role ?? 'admin') }}</div>
                    </div>
                    <div class="d-flex align-items-center justify-content-center rounded-circle fw-bold text-white" style="width: 36px; height: 36px; background: linear-gradient(135deg, #9B4D07, #CA7B05); font-size: 0.8rem;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Flash messages --}}
        <div class="px-4 pt-3">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show py-2">{{ session('success') }}
                    <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show py-2">{{ session('error') }}
                    <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>

        {{-- Content --}}
        <div class="flex-grow-1 p-4 overflow-auto">
            @yield('content')
        </div>
    </div>

</div>

@stack('scripts')
</body>
</html>
