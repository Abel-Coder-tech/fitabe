<div class="card h-100 shadow-sm">
    @if ($candidat->photo)
        <img src="{{ $candidat->photo_url }}" class="card-img-top" alt="{{ $candidat->display_name }}">
    @endif
    <div class="card-body text-center">
        <h5 class="card-title">{{ $candidat->display_name }}</h5>
        <p class="badge bg-secondary">{{ $candidat->categorie }}</p>
        <p class="card-text text-muted">{{ Str::limit($candidat->biographie, 100) }}</p>
        <p class="h4 text-primary">{{ $candidat->nombre_votes }} vote(s)</p>
    </div>
</div>
