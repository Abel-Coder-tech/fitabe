<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Administration - ' . config('app.name', 'FITAB'))</title>
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    <style>
        .nav-link.nav-active { background: rgba(255,255,255,0.15); }

        /* ============ FITAB Buttons ============ */
        .btn-fitab, .btn.btn-primary {
            background: #9B4D07;
            border-color: #9B4D07;
            color: #fff;
            transition: all 0.2s ease;
        }
        .btn-fitab:hover, .btn.btn-primary:hover {
            background: #7B3D05;
            border-color: #7B3D05;
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(155,77,7,0.3);
        }
        .btn-fitab:active, .btn.btn-primary:active {
            background: #5C2D04 !important;
            border-color: #5C2D04 !important;
            transform: translateY(0);
        }
        .btn-fitab-info, .btn.btn-info {
            background: #CA7B05;
            border-color: #CA7B05;
            color: #fff;
            transition: all 0.2s ease;
        }
        .btn-fitab-info:hover, .btn.btn-info:hover {
            background: #A86504;
            border-color: #A86504;
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(202,123,5,0.3);
        }

        /* ============ Table row hover ============ */
        .table-hover tbody tr { transition: background 0.15s ease; }

        /* ============ Card hover lift ============ */
        .card-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .card-hover:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,0.06); }

        /* ============ Page content fade-in ============ */
        .page-content {
            animation: fadeInUp 0.3s ease;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ============ RESPONSIVE SIDEBAR ============ */
        .sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 1035;
        }
        .sidebar-overlay.show { display: block; }

        .admin-sidebar {
            width: 250px;
            min-height: 100vh;
            background: #3E1E05;
            flex-shrink: 0;
        }
        .hamburger-btn {
            display: none;
            background: none; border: none;
            font-size: 1.4rem; color: #9B4D07;
            padding: 0; line-height: 1;
        }

        /* ============ Pagination ============ */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 2px;
            padding: 18px 12px;
            margin: 0;
            overflow-x: auto;
            flex-wrap: nowrap;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }
        .pagination::-webkit-scrollbar {
            display: none;
        }
        .page-item {
            list-style: none;
            flex-shrink: 0;
        }
        .page-item:first-child {
            margin-right: auto;
        }
        .page-item:last-child {
            margin-left: auto;
        }
        .page-item .page-link {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 40px;
            height: 40px;
            border: none;
            background: transparent;
            color: #5F2B0C;
            font-size: 0.85rem;
            font-weight: 500;
            border-radius: 10px;
            padding: 0;
            text-decoration: none;
            transition: all 0.15s;
        }
        .page-item .page-link:hover {
            background: rgba(155, 77, 7, 0.08);
            color: #9B4D07;
        }
        .page-item.active .page-link {
            background: #9B4D07;
            color: #fff;
            font-weight: 600;
        }
        .page-item.disabled .page-link {
            color: #ccc;
            pointer-events: none;
        }
        .page-item:first-child .page-link,
        .page-item:last-child .page-link {
            width: 45px;
            height: 45px;
            border: none;
            background: transparent;
        }
        .page-item:first-child .page-link i,
        .page-item:last-child .page-link i {
            font-size: 30px;
            color: #1f2937;
        }
        .page-item:first-child.disabled .page-link i,
        .page-item:last-child.disabled .page-link i {
            color: #ccc;
        }

        @media (max-width: 767.98px) {
            .admin-sidebar {
                position: fixed; top: 0; left: 0; bottom: 0;
                z-index: 1040;
                transform: translateX(-100%);
                transition: transform 0.25s ease;
            }
            .admin-sidebar.open { transform: translateX(0); }

            .hamburger-btn { display: inline-flex; }

            .admin-main { padding: 1rem !important; }

            .topbar-title h5 { font-size: 0.95rem; }
            .topbar-title small { font-size: 0.7rem; }
            .topbar-user .user-text { display: none; }

            /* Mobile: tables → cards */
            .admin-main .table-responsive { overflow-x: visible; }
            .admin-main table thead { display: none; }
            .admin-main table tbody tr {
                display: block;
                border: 1px solid #e9ecef;
                border-radius: 12px;
                padding: 14px;
                margin-bottom: 12px;
                background: #fff;
                box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            }
            .admin-main table tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 8px 0;
                border: none;
                border-bottom: 1px solid #f0f0f0;
                font-size: 0.85rem;
            }
            .admin-main table tbody td:last-child { border-bottom: none; padding-bottom: 0; }
            .admin-main table tbody td[data-label]::before {
                content: attr(data-label);
                font-weight: 700;
                color: #3E1E05;
                font-size: 0.7rem;
                text-transform: uppercase;
                letter-spacing: 0.3px;
                white-space: nowrap;
                margin-right: 8px;
            }
            .admin-main table tbody td[colspan] {
                justify-content: center;
            }
            .admin-main table tbody td[colspan]::before { display: none; }
            .admin-main table tbody td img { max-width: 40px; height: auto; }

            /* Mobile: quick cards compact */
            .quick-card { padding: 0.75rem !important; }
            .quick-card .quick-icon { width: 38px; height: 38px; font-size: 1rem !important; }
            .quick-card span { font-size: 0.7rem !important; }

            /* Mobile: stat cards compact */
            .stat-card { padding: 0.75rem !important; }
            .stat-card .h2 { font-size: 1.2rem !important; }

            /* Mobile: mode-toggle compact */
            .mode-toggle { font-size: 0.65rem !important; padding: 0.3rem 0.6rem !important; }
        }
    </style>
    @stack('styles')
</head>
<body>

<div class="d-flex" style="min-height: 100vh;">

    {{-- ============ SIDEBAR OVERLAY ============ --}}
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    {{-- ============ SIDEBAR ============ --}}
    <nav class="d-flex flex-column text-white shadow-sm admin-sidebar" id="adminSidebar">
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
                    <a href="{{ route('admin.dashboard') }}" class="nav-link d-flex align-items-center gap-2 px-3 py-2 rounded-3 {{ $navClass('admin.dashboard') }}" style="color: rgba(255,255,255,0.7); transition: all 0.15s;">
                        <i class="bi bi-grid-1x2-fill" style="width: 18px;"></i> Tableau de bord
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.candidats.index') }}" class="nav-link d-flex align-items-center gap-2 px-3 py-2 rounded-3 {{ $navClass(['admin.candidats', 'admin.candidat']) }}" style="color: rgba(255,255,255,0.7); transition: all 0.15s;">
                        <i class="bi bi-people-fill" style="width: 18px;"></i> Candidats
                    </a>
                </li>
                @if(auth()->user()?->role === 'super_admin')
                <li class="nav-item">
                    <a href="{{ route('admin.votes.index') }}" class="nav-link d-flex align-items-center gap-2 px-3 py-2 rounded-3 {{ $navClass('admin.votes') }}" style="color: rgba(255,255,255,0.7); transition: all 0.15s;">
                        <i class="bi bi-check-circle-fill" style="width: 18px;"></i> Ovations
                    </a>
                </li>
                @endif
            </ul>

            <div class="small text-uppercase px-2 mt-4 mb-2" style="color: rgba(255,255,255,0.3); font-size: 0.65rem; letter-spacing: 1.5px;">Contenu</div>
            <ul class="nav flex-column gap-1">
                <li class="nav-item">
                    <a href="{{ route('admin.medias.index') }}" class="nav-link d-flex align-items-center gap-2 px-3 py-2 rounded-3 {{ $navClass('admin.medias') }}" style="color: rgba(255,255,255,0.7); transition: all 0.15s;">
                        <i class="bi bi-images" style="width: 18px;"></i> Médiathèque
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.resultats.index') }}" class="nav-link d-flex align-items-center gap-2 px-3 py-2 rounded-3 {{ $navClass('admin.resultats') }}" style="color: rgba(255,255,255,0.7); transition: all 0.15s;">
                        <i class="bi bi-trophy-fill" style="width: 18px;"></i> Résultats
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.programmes.index') }}" class="nav-link d-flex align-items-center gap-2 px-3 py-2 rounded-3 {{ $navClass('admin.programmes') }}" style="color: rgba(255,255,255,0.7); transition: all 0.15s;">
                        <i class="bi bi-calendar-event-fill" style="width: 18px;"></i> Programme
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.partenaires.index') }}" class="nav-link d-flex align-items-center gap-2 px-3 py-2 rounded-3 {{ $navClass('admin.partenaires') }}" style="color: rgba(255,255,255,0.7); transition: all 0.15s;">
                        <i class="bi bi-building" style="width: 18px;"></i> Partenaires
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.soutiens.index') }}" class="nav-link d-flex align-items-center gap-2 px-3 py-2 rounded-3 {{ $navClass('admin.soutiens') }}" style="color: rgba(255,255,255,0.7); transition: all 0.15s;">
                        <i class="bi bi-heart-fill" style="width: 18px;"></i> Soutiens
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
        <div class="px-3 pb-3 border-top pt-3" style="border-color: rgba(255,255,255,0.06) !important;">
            <ul class="nav flex-column gap-1">
                <li class="nav-item">
                    <a href="{{ route('home') }}" target="_blank" class="nav-link d-flex align-items-center gap-2 px-3 py-2 rounded-3" style="color: rgba(255,255,255,0.7); transition: all 0.15s;">
                        <i class="bi bi-box-arrow-up-right" style="width: 18px;"></i> Voir le site
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('profile.edit') }}" class="nav-link d-flex align-items-center gap-2 px-3 py-2 rounded-3" style="color: rgba(255,255,255,0.7); transition: all 0.15s;">
                        <i class="bi bi-person-circle" style="width: 18px;"></i> Mon compte
                    </a>
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Voulez-vous vraiment vous déconnecter ?')">
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
            <div class="d-flex align-items-center gap-3">
                <button class="hamburger-btn" id="sidebarToggle" aria-label="Menu">
                    <i class="bi bi-list"></i>
                </button>
                <div class="topbar-title">
                    <h5 class="fw-bold mb-0" style="color: #9B4D07;">@yield('page-title', 'Tableau de bord')</h5>
                    <small class="text-muted">@yield('page-subtitle','FITAB 2026' )</small>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.contacts.index') }}" class="btn btn-light btn-sm rounded-circle p-2 border-0 position-relative" style="width: 36px; height: 36px;">
                    <i class="bi bi-bell"></i>
                    @php $nonLuCount = \App\Models\Contact::nonLu()->count(); @endphp
                    @if($nonLuCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background: #dc3545; font-size: 0.6rem; min-width: 18px; height: 18px; line-height: 18px; padding: 0 4px;">{{ $nonLuCount }}</span>
                    @endif
                </a>
                <button class="btn btn-light btn-sm rounded-circle p-2 border-0" style="width: 36px; height: 36px;">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
                <div class="d-flex align-items-center gap-2 ps-2 border-start topbar-user" style="border-color: #e9ecef !important;">
                    <div class="text-end user-text">
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
        <div class="flex-grow-1 p-4 overflow-auto page-content admin-main">
            @yield('content')
        </div>
    </div>

</div>

<script>
(function() {
    var sidebar = document.getElementById('adminSidebar');
    var overlay = document.getElementById('sidebarOverlay');
    var toggle = document.getElementById('sidebarToggle');
    if (!sidebar || !overlay || !toggle) return;
    function open() { sidebar.classList.add('open'); overlay.classList.add('show'); }
    function close() { sidebar.classList.remove('open'); overlay.classList.remove('show'); }
    toggle.addEventListener('click', open);
    overlay.addEventListener('click', close);
    document.querySelectorAll('.admin-sidebar .nav-link').forEach(function(link) {
        link.addEventListener('click', function() { if (window.innerWidth < 768) close(); });
    });
})();
</script>

<style>
.password-toggle {
    position: relative;
}
.password-toggle .form-control {
    padding-right: 40px;
}
.password-toggle .bi {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #aaa;
    cursor: pointer;
    font-size: 15px;
    z-index: 5;
}
.password-toggle .bi:hover {
    color: #9B4D07;
}
</style>
<script>
document.querySelectorAll('input[type="password"]').forEach(function(input) {
    if (input.closest('.password-toggle')) return;
    var wrapper = document.createElement('div');
    wrapper.className = 'password-toggle';
    input.parentNode.insertBefore(wrapper, input);
    wrapper.appendChild(input);
    var icon = document.createElement('i');
    icon.className = 'bi bi-eye-slash';
    icon.style.cssText = 'position:absolute;right:12px;top:50%;transform:translateY(-50%);color:#aaa;cursor:pointer;font-size:15px;z-index:5;';
    icon.onmouseenter = function() { this.style.color = '#9B4D07'; };
    icon.onmouseleave = function() { this.style.color = '#aaa'; };
    icon.onclick = function() {
        var isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';
        this.classList.toggle('bi-eye-slash', isPassword);
        this.classList.toggle('bi-eye', !isPassword);
    };
    wrapper.appendChild(icon);
});
</script>

@stack('scripts')
</body>
</html>
