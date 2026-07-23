@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Paramètres du site</h1>
</div>

@php
    $labels = [
        'contact_telephone' => 'Contact',
        'contact_email' => 'Email',
        'social_facebook' => 'Facebook',
        'social_instagram' => 'Instagram',
        'social_youtube' => 'YouTube',
        'social_tiktok' => 'TikTok',
        'hero_titre' => 'Titre héros',
        'hero_sous_titre' => 'Sous-titre héros',
        'texte_info_vote' => 'Info ovation',
        'texte_mediatheque' => 'Description médiathèque',
    ];
@endphp

<div class="card">
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
                    @foreach ($parametres as $p)
                    <tr>
                        <td class="fw-semibold" style="color: #3E1E05;">
                            {{ $labels[$p->cle] ?? $p->cle }}
                        </td>
                        <td class="text-break" style="max-width: 500px;">
                            @if (str_contains($p->cle, 'social_') && $p->valeur)
                                <a href="{{ $p->valeur }}" target="_blank" class="text-truncate d-inline-block" style="max-width: 400px;">{{ $p->valeur }}</a>
                            @elseif ($p->valeur)
                                <span>{{ $p->valeur }}</span>
                            @else
                                <span class="text-muted">—</span>
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
@endsection
