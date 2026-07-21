@extends('layouts.admin')

@section('title', $media->titre ?? 'Média')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>{{ $media->titre ?? 'Média' }}</h1>
    <div>
        <a href="{{ route('admin.medias.edit', $media) }}" class="btn btn-warning">Modifier</a>
        <a href="{{ route('admin.medias.index') }}" class="btn btn-secondary">Retour</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <p><strong>Type :</strong> {{ $media->type === 'photo' ? 'Photo' : 'Vidéo' }}</p>
        <p><strong>Titre :</strong> {{ $media->titre ?? '—' }}</p>
        <p><strong>Description :</strong> {{ $media->description ?? '—' }}</p>
        <p><strong>Année :</strong> {{ $media->annee_edition ?? '—' }}</p>
        <p><strong>Aperçu :</strong></p>
        @if ($media->type === 'photo' && $media->url)
            <img src="{{ asset('storage/' . $media->url) }}" class="img-fluid rounded" style="max-height:400px;">
        @elseif ($media->type === 'video' && $media->lien_youtube)
            <div class="ratio ratio-16x9">
                <iframe src="https://www.youtube.com/embed/{{ $media->youtube_id }}" allowfullscreen></iframe>
            </div>
        @endif
    </div>
</div>
@endsection