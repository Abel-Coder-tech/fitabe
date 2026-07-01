@extends('layouts.public')

@section('title', 'Médias - ' . config('app.name', 'Fitabe'))

@section('content')
<div class="container">
    <h1 class="mb-4">Galerie Médias</h1>

    @forelse ($medias as $media)
        <div class="row mb-4">
            <div class="col-md-4">
                @if (in_array($media->type, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                    <img src="{{ asset('storage/' . $media->url) }}" class="img-fluid rounded" alt="{{ $media->titre }}">
                @else
                    <div class="bg-light text-center py-5 rounded">
                        <p class="mb-0">{{ $media->type }}</p>
                    </div>
                @endif
            </div>
            <div class="col-md-8">
                <h5>{{ $media->titre ?? 'Sans titre' }}</h5>
                @if ($media->candidat)
                    <p class="text-muted">Candidat : {{ $media->candidat->display_name }}</p>
                @endif
            </div>
        </div>
    @empty
        <div class="alert alert-info">Aucun média pour le moment.</div>
    @endforelse
</div>
@endsection
