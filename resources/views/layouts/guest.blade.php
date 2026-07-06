<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>FITA — Administration</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎤</text></svg>">
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>
<body style="background: #3E1E05;">
    <div class="d-flex align-items-center justify-content-center min-vh-100 px-3">
        <div class="card border-0 rounded-4 shadow-lg" style="max-width: 420px; width: 100%;">
            <div class="card-body px-5 py-5">
                <div class="text-center mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="FITA" height="50"
                         style="background: rgba(62,30,5,0.95); border-radius: 8px; padding: 12px;"
                         onerror="this.style.display='none'">
                </div>
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>
