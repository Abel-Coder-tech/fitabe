@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Paramètres du site</h1>
</div>

@php
    $map = [
        'contact_telephone' => ['icon' => 'bi-telephone-fill', 'label' => 'Contact', 'type' => 'tel'],
        'contact_email'     => ['icon' => 'bi-envelope-fill', 'label' => 'Email', 'type' => 'email'],
        'hero_titre'        => ['icon' => 'bi-type', 'label' => 'Titre héros', 'type' => 'text'],
        'hero_sous_titre'   => ['icon' => 'bi-text-paragraph', 'label' => 'Sous-titre héros', 'type' => 'text'],
        'texte_info_vote'   => ['icon' => 'bi-megaphone-fill', 'label' => 'Info ovation', 'type' => 'textarea'],
        'texte_mediatheque' => ['icon' => 'bi-camera-fill', 'label' => 'Description médiathèque', 'type' => 'textarea'],
        'social_facebook'   => ['icon' => 'bi-facebook', 'label' => 'Facebook', 'type' => 'url'],
        'social_instagram'  => ['icon' => 'bi-instagram', 'label' => 'Instagram', 'type' => 'url'],
        'social_youtube'    => ['icon' => 'bi-youtube', 'label' => 'YouTube', 'type' => 'url'],
        'social_tiktok'     => ['icon' => 'bi-tiktok', 'label' => 'TikTok', 'type' => 'url'],
    ];
    $byKey = $parametres->keyBy('cle');
@endphp

<form action="{{ route('admin.parametres.updateAll') }}" method="POST" novalidate>
    @csrf
    @method('PUT')

    {{-- Contact --}}
    <div class="card mb-4">
        <div class="card-header fw-bold" style="background: #fdfaf5; color: #3E1E05; border-bottom: 1px solid #E3D5AD;">
            <i class="bi bi-telephone-fill me-2" style="color: #9B4D07;"></i>Contact
        </div>
        <div class="card-body">
            <div class="row g-3">
                @foreach (['contact_telephone', 'contact_email'] as $cle)
                @php $m = $map[$cle]; $val = $byKey[$cle]->valeur ?? ''; @endphp
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">{{ $m['label'] }}</label>
                    <div class="input-group">
                        <span class="input-group-text" style="background: #fdfaf5;"><i class="{{ $m['icon'] }}" style="color: #9B4D07;"></i></span>
                        <input type="{{ $m['type'] }}" name="parametres[{{ $cle }}]" class="form-control" value="{{ $val }}">
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Textes --}}
    <div class="card mb-4">
        <div class="card-header fw-bold" style="background: #fdfaf5; color: #3E1E05; border-bottom: 1px solid #E3D5AD;">
            <i class="bi bi-type me-2" style="color: #9B4D07;"></i>Textes du site
        </div>
        <div class="card-body">
            <div class="row g-3">
                @foreach (['hero_titre', 'hero_sous_titre'] as $cle)
                @php $m = $map[$cle]; $val = $byKey[$cle]->valeur ?? ''; @endphp
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">{{ $m['label'] }}</label>
                    <div class="input-group">
                        <span class="input-group-text" style="background: #fdfaf5;"><i class="{{ $m['icon'] }}" style="color: #9B4D07;"></i></span>
                        <input type="text" name="parametres[{{ $cle }}]" class="form-control" value="{{ $val }}">
                    </div>
                </div>
                @endforeach
                @foreach (['texte_info_vote', 'texte_mediatheque'] as $cle)
                @php $m = $map[$cle]; $val = $byKey[$cle]->valeur ?? ''; @endphp
                <div class="col-12">
                    <label class="form-label fw-semibold small">{{ $m['label'] }}</label>
                    <div class="input-group">
                        <span class="input-group-text align-items-start pt-2" style="background: #fdfaf5;"><i class="{{ $m['icon'] }}" style="color: #9B4D07;"></i></span>
                        <textarea name="parametres[{{ $cle }}]" class="form-control" rows="2">{{ $val }}</textarea>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Réseaux sociaux --}}
    <div class="card mb-4">
        <div class="card-header fw-bold" style="background: #fdfaf5; color: #3E1E05; border-bottom: 1px solid #E3D5AD;">
            <i class="bi bi-share-fill me-2" style="color: #9B4D07;"></i>Réseaux sociaux
        </div>
        <div class="card-body">
            <div class="row g-3">
                @foreach (['social_facebook', 'social_instagram', 'social_youtube', 'social_tiktok'] as $cle)
                @php $m = $map[$cle]; $val = $byKey[$cle]->valeur ?? ''; @endphp
                <div class="col-md-6">
                    <label class="form-label fw-semibold small d-flex align-items-center gap-2">
                        <i class="{{ $m['icon'] }}" style="color: #9B4D07; font-size: 1.1rem;"></i> {{ $m['label'] }}
                    </label>
                    <input type="url" name="parametres[{{ $cle }}]" class="form-control" value="{{ $val }}" placeholder="https://...">
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="text-end mb-4">
        <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-check-lg me-1"></i> Enregistrer
        </button>
    </div>
</form>
@endsection
