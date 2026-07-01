<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Administration - ' . config('app.name', 'Fitabe'))</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎤</text></svg>">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div class="d-flex">
        <nav class="bg-dark text-white vh-100 p-3" style="width: 250px;">
            <h5 class="text-center mb-4">{{ config('app.name', 'Fitabe') }}</h5>
            <ul class="nav flex-column">
                <li class="nav-item"><a href="{{ route('admin.candidats.index') }}" class="nav-link text-white">Candidats</a></li>
                <li class="nav-item"><a href="{{ route('admin.votes.index') }}" class="nav-link text-white">Votes</a></li>
                <li class="nav-item"><a href="{{ route('admin.programmes.index') }}" class="nav-link text-white">Programmes</a></li>
                <li class="nav-item"><a href="{{ route('admin.partenaires.index') }}" class="nav-link text-white">Partenaires</a></li>
                <li class="nav-item"><a href="{{ route('admin.parametres.index') }}" class="nav-link text-white">Paramètres</a></li>
                <li class="nav-item"><a href="{{ route('admin.medias.index') }}" class="nav-link text-white">Médias</a></li>
                <li class="nav-item"><a href="{{ route('admin.contacts.index') }}" class="nav-link text-white">Contacts</a></li>
                <li class="nav-item"><a href="{{ route('admin.users.index') }}" class="nav-link text-white">Utilisateurs</a></li>
            </ul>
        </nav>
        <main class="flex-grow-1 p-4">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</body>
</html>
