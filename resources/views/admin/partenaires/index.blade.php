@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Partenaires</h1>
    <a href="{{ route('admin.partenaires.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Nouveau partenaire
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Logo</th>
                        <th>Site web</th>
                        <th>Ordre</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($partenaires as $partenaire)
                        <tr>
                            <td>{{ $partenaire->id }}</td>
                            <td>{{ $partenaire->nom }}</td>
                            <td>
                                @if ($partenaire->logo)
                                    <img src="{{ asset('storage/' . $partenaire->logo) }}" alt="{{ $partenaire->nom }}" width="60" class="img-thumbnail">
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if ($partenaire->site_web)
                                    <a href="{{ $partenaire->site_web }}" target="_blank" rel="noopener noreferrer">{{ $partenaire->site_web }}</a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>{{ $partenaire->ordre }}</td>
                            <td class="d-flex gap-2">
                                <a href="{{ route('admin.partenaires.edit', $partenaire) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.partenaires.destroy', $partenaire) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?')">
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
                            <td colspan="6" class="text-center text-muted">Aucun partenaire trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $partenaires->links() }}
        </div>
    </div>
</div>
@endsection
