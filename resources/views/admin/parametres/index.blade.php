@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Paramètres</h1>
    <a href="{{ route('admin.parametres.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Nouveau paramètre
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Clé</th>
                        <th>Valeur</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($parametres as $parametre)
                        <tr>
                            <td>{{ $parametre->id }}</td>
                            <td><code>{{ $parametre->cle }}</code></td>
                            <td class="text-break" style="max-width: 400px;">{{ $parametre->valeur }}</td>
                            <td class="d-flex gap-2">
                                <a href="{{ route('admin.parametres.edit', $parametre) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.parametres.destroy', $parametre) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Aucun paramètre trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $parametres->links() }}
        </div>
    </div>
</div>
@endsection
