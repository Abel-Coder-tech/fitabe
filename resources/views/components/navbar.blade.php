<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">{{ config('app.name', 'FITAB') }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('public.vote') }}">Ovation</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('public.medias') }}">Médias</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('public.contact') }}">Contact</a></li>
            </ul>
        </div>
    </div>
</nav>
