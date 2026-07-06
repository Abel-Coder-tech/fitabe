<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>FITAB — Administration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    <style>
        body {
            min-height: 100vh;
            background-color:white;
            background-image:
                radial-gradient(ellipse at top left, rgba(202,123,5,0.15) 0%, transparent 60%),
                radial-gradient(ellipse at bottom right, rgba(155,77,7,0.2) 0%, transparent 60%);
        }

        .login-card {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            max-width: 440px;
            width: 100%;
            box-shadow: 0 25px 60px rgba(0,0,0,0.4);
        }

        .login-header {
            background: #3E1E05;
            padding: 32px 40px 24px;
            text-align: center;
            border-bottom: 3px solid #CA7B05;
        }

        .login-header img {
            height: 70px;
            margin-bottom: 14px;
        }

        .login-header .site-title {
            font-size: 20px;
            font-weight: 800;
            color: #F3EACE;
            letter-spacing: 1px;
            margin-bottom: 2px;
        }

        .login-header .site-sub {
            font-size: 10px;
            color: #CA7B05;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 600;
        }

        .login-body {
            padding: 32px 40px 36px;
        }

        .login-title {
            font-size: 15px;
            font-weight: 700;
            color: #3E1E05;
            margin-bottom: 4px;
        }

        .login-sub {
            font-size: 12px;
            color: #888;
            margin-bottom: 24px;
        }

        .form-label {
            font-size: 12px;
            font-weight: 600;
            color: #3E1E05;
            margin-bottom: 6px;
        }

        .form-control {
            border: 1.5px solid #e8e0d0;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            color: #3E1E05;
            background: #fdfaf5;
            transition: border-color .2s, box-shadow .2s;
        }

        .form-control:focus {
            border-color: #9B4D07;
            box-shadow: 0 0 0 3px rgba(155,77,7,0.12);
            background: #fff;
            outline: none;
        }

        .form-control::placeholder {
            color: #c0b8a8;
            font-size: 12px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper .bi {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #c0b8a8;
            font-size: 15px;
            cursor: pointer;
        }

        .input-wrapper .bi:hover {
            color: #9B4D07;
        }

        .input-wrapper .form-control {
            padding-right: 40px;
        }

        .btn-connexion {
            background: #9B4D07;
            color: #F3EACE;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-size: 13px;
            font-weight: 700;
            width: 100%;
            letter-spacing: .5px;
            transition: background .2s, transform .1s;
        }

        .btn-connexion:hover {
            background: #CA7B05;
            color: #fff;
        }

        .btn-connexion:active {
            transform: scale(0.98);
        }

        .divider {
            border: none;
            border-top: 1px solid #f0ebe0;
            margin: 24px 0 16px;
        }

        .login-footer {
            text-align: center;
            font-size: 11px;
            color: #aaa;
        }

        .login-footer span {
            color: #9B4D07;
            font-weight: 600;
        }

        .alert-danger {
            background: #fff5f5;
            border: 1px solid #fdd;
            border-radius: 8px;
            font-size: 12px;
            color: #c0392b;
            padding: 10px 14px;
        }

        .alert-success {
            background: #f5fff8;
            border: 1px solid #c3e6cb;
            border-radius: 8px;
            font-size: 12px;
            color: #1e6b3a;
            padding: 10px 14px;
        }
    </style>
</head>
<body>
    <div class="d-flex align-items-center justify-content-center min-vh-100 px-3">
        <div class="login-card">

            {{-- En-tête --}}
            <div class="login-header">
                <img src="{{ asset('images/logo1.png') }}"
                     alt="FITAB"
                     onerror="this.style.display='none'">
                <div class="site-title">FITAB</div>
                <div class="site-sub">Festival International des Talents Artistiques du Bénin</div>
            </div>

            {{-- Corps --}}
            <div class="login-body">
                <div class="login-title">Espace Administration</div>
                <div class="login-sub">Connectez-vous pour accéder au panneau de gestion.</div>

                {{-- Alertes --}}
                @if (session('status'))
                    <div class="alert-success mb-3">{{ session('status') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert-danger mb-3">
                        <i class="bi bi-exclamation-circle me-1"></i>
                        Email ou mot de passe incorrect.
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse email</label>
                        <div class="input-wrapper">
                            <input
                                id="email"
                                type="email"
                                name="email"
                                class="form-control"
                                value="{{ old('email') }}"
                                placeholder="admin@fitab.com"
                                required
                                autofocus
                                autocomplete="username">
                            <i class="bi bi-envelope"></i>
                        </div>
                    </div>

                    {{-- Mot de passe --}}
                    <div class="mb-4">
                        <label for="password" class="form-label">Mot de passe</label>
                        <div class="input-wrapper">
                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="form-control"
                                placeholder="••••••••"
                                required
                                autocomplete="current-password">
                            <i class="bi bi-eye" id="togglePassword"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn-connexion">
                        Se connecter <i class="bi bi-arrow-right ms-1"></i>
                    </button>

                </form>

                <hr class="divider">

                <div class="login-footer">
                    Accès réservé aux administrateurs <span>FITAB</span><br>
                    <span style="color:#aaa;font-weight:400">© {{ date('Y') }} Noctam Communication</span>
                </div>

            </div>
        </div>
    </div>

    <script>
        // Toggle affichage mot de passe
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', () => {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            togglePassword.classList.toggle('bi-eye', !isPassword);
            togglePassword.classList.toggle('bi-eye-slash', isPassword);
        });
    </script>
</body>
</html>