<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-semibold mb-0">Tableau de bord</h2>
    </x-slot>

    <div class="card">
        <div class="card-body">
            <p class="mb-0">Vous êtes connecté !</p>
            <a href="{{ route('admin.candidats.index') }}" class="btn btn-primary mt-3">Accéder à l'administration</a>
        </div>
    </div>
</x-app-layout>
