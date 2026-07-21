@extends('layouts.admin')

@section('title', 'Newsletter')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Abonnés newsletter</h1>
    <span class="badge rounded-pill px-3 py-2" style="background: #9B4D07; font-size: 0.8rem;">
        {{ $subscribers->total() }} abonné(s)
    </span>
</div>

<div class="card border-0 rounded-4 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="small text-muted" style="background: #f9f9fb;">
                    <tr>
                        <th class="px-4 py-3 fw-semibold">Email</th>
                        <th class="px-4 py-3 fw-semibold">Date d'abonnement</th>
                        <th class="px-4 py-3 fw-semibold text-center" style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($subscribers as $subscriber)
                        <tr class="border-bottom" style="border-color: #f5f5f5 !important;">
                            <td class="px-4 py-3">
                                <span class="small" style="color: #3E1E05;">{{ $subscriber->email }}</span>
                            </td>
                            <td class="px-4 py-3 small text-muted">
                                {{ $subscriber->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <form action="{{ route('admin.subscribers.destroy', $subscriber) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cet abonné ?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm" style="background: #ffe6e6; color: #dc3545;" title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-5 text-muted small">Aucun abonné pour le moment.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $subscribers->links() }}
</div>
@endsection
