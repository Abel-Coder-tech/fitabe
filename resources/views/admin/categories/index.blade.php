@extends('layouts.admin')

@section('title', 'Catégories')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Catégories</h1>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Nouvelle catégorie</a>
</div>

<div class="card border-0 rounded-4 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="min-width: 500px;">
                <thead class="small text-muted" style="background: #f9f9fb;">
                    <tr>
                        <th class="px-4 py-3 fw-semibold" style="width: 60px;">ID</th>
                        <th class="px-4 py-3 fw-semibold">Nom</th>
                        <th class="px-4 py-3 fw-semibold">Slug</th>
                        <th class="px-4 py-3 fw-semibold">Ordre</th>
                        <th class="px-4 py-3 fw-semibold text-center" style="width: 110px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $cat)
                        <tr class="border-bottom" style="border-color: #f5f5f5 !important;">
                            <td class="px-4 py-3 small text-muted">{{ $cat->id }}</td>
                            <td class="px-4 py-3"><span class="fw-semibold small" style="color: #3E1E05;">{{ $cat->nom }}</span></td>
                            <td class="px-4 py-3 small text-muted"><code>{{ $cat->slug }}</code></td>
                            <td class="px-4 py-3 small text-muted">{{ $cat->ordre }}</td>
                            <td class="px-4 py-3 text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-sm" style="background: #fef0e0; color: #9B4D07;" title="Modifier"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?')">@csrf @method('DELETE')<button type="submit" class="btn btn-sm" style="background: #ffe6e6; color: #dc3545;" title="Supprimer"><i class="bi bi-trash"></i></button></form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-5 text-muted small">Aucune catégorie.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="d-flex justify-content-center mt-4">{{ $categories->links() }}</div>
@endsection
