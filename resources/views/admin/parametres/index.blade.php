@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Paramètres du site</h1>
    <a href="{{ route('admin.parametres.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Nouveau paramètre
    </a>
</div>

@php
    $icons = [
        'Général' => 'bi-gear',
        'Communication' => 'bi-megaphone',
        'Vote / Ovation' => 'bi-heart',
        'Médiathèque' => 'bi-camera',
        'Autre' => 'bi-three-dots',
    ];
@endphp

@foreach ($grouped as $groupe => $items)
<div class="card mb-4">
    <div class="card-header d-flex align-items-center gap-2" style="background: #fdfaf5; border-bottom: 1px solid #E3D5AD;">
        <i class="{{ $icons[$groupe] ?? 'bi-gear' }}" style="color: #9B4D07;"></i>
        <h6 class="fw-bold mb-0" style="color: #3E1E05;">{{ $groupe }}</h6>
        <span class="badge bg-secondary ms-auto" style="font-size: 0.7rem;">{{ $items->count() }}</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 35%;">Paramètre</th>
                        <th>Valeur</th>
                        <th style="width: 100px;" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $p)
                    <tr>
                        <td>
                            <code class="small" style="color: #9B4D07;">{{ $p->cle }}</code>
                        </td>
                        <td class="text-break" style="max-width: 400px;">
                            @if (str_starts_with($p->cle, 'logo_url') && $p->valeur)
                                <img src="{{ asset('storage/' . $p->valeur) }}" alt="Logo" height="30" style="object-fit: contain;">
                            @elseif (str_contains($p->cle, 'social_') && $p->valeur)
                                <a href="{{ $p->valeur }}" target="_blank" class="text-truncate d-inline-block" style="max-width: 300px;">{{ $p->valeur }}</a>
                            @elseif (str_contains($p->cle, 'date_'))
                                <span>{{ $p->valeur ?: '—' }}</span>
                            @else
                                <span>{{ $p->valeur ?: '—' }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.parametres.edit', $p) }}" class="btn btn-sm btn-outline-warning" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endforeach
@endsection
