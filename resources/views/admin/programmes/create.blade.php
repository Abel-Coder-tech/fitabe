@extends('layouts.admin')

@section('title', 'Nouveau programme')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Nouveau programme</h1>
    <a href="{{ route('admin.programmes.index') }}" class="btn btn-secondary">Annuler</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.programmes.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="titre" class="form-label">Titre <span class="text-danger">*</span></label>
                <input type="text" name="titre" id="titre" class="form-control @error('titre') is-invalid @enderror" value="{{ old('titre') }}" required>
                @error('titre')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" rows="5" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="icone" class="form-label">Icône (Bootstrap class)</label>
                    <select name="icone" id="icone" class="form-select @error('icone') is-invalid @enderror">
                        <option value="">— Aucune —</option>
                        <option value="bi-megaphone-fill" {{ old('icone') == 'bi-megaphone-fill' ? 'selected' : '' }}>Mégaphone</option>
                        <option value="bi-people-fill" {{ old('icone') == 'bi-people-fill' ? 'selected' : '' }}>Personnes</option>
                        <option value="bi-music-note-beamed" {{ old('icone') == 'bi-music-note-beamed' ? 'selected' : '' }}>Musique</option>
                        <option value="bi-trophy-fill" {{ old('icone') == 'bi-trophy-fill' ? 'selected' : '' }}>Trophée</option>
                        <option value="bi-award-fill" {{ old('icone') == 'bi-award-fill' ? 'selected' : '' }}>Award</option>
                        <option value="bi-stars" {{ old('icone') == 'bi-stars' ? 'selected' : '' }}>Étoiles</option>
                        <option value="bi-calendar-event" {{ old('icone') == 'bi-calendar-event' ? 'selected' : '' }}>Calendrier</option>
                        <option value="bi-camera-fill" {{ old('icone') == 'bi-camera-fill' ? 'selected' : '' }}>Caméra</option>
                        <option value="bi-mic-fill" {{ old('icone') == 'bi-mic-fill' ? 'selected' : '' }}>Micro</option>
                    </select>
                    @error('icone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="couleur_bordure" class="form-label">Couleur de bordure</label>
                    <div class="d-flex gap-2 flex-wrap">
                        @php $colors = ['#9B4D07', '#CA7B05', '#c9a96e', '#8b1a1a', '#3E1E05', '#c8922a', '#cd7f32']; @endphp
                        @foreach ($colors as $c)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="couleur_bordure" id="cb{{ Str::after($c, '#') }}" value="{{ $c }}" {{ old('couleur_bordure', '#9B4D07') == $c ? 'checked' : '' }}>
                            <label class="form-check-label" for="cb{{ Str::after($c, '#') }}" style="width:24px;height:24px;background:{{ $c }};border-radius:4px;cursor:pointer;"></label>
                        </div>
                        @endforeach
                    </div>
                    @error('couleur_bordure')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="date_programme" class="form-label">Date <span class="text-danger">*</span></label>
                <input type="datetime-local" name="date_programme" id="date_programme" class="form-control @error('date_programme') is-invalid @enderror" value="{{ old('date_programme') }}" required>
                <small class="text-muted">Pour les phases à plusieurs dates, renseigner la date de début ou une date indicative ; les sous-dates sont ajoutées ci-dessous.</small>
                @error('date_programme')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="lieu" class="form-label">Lieu (par défaut)</label>
                <input type="text" name="lieu" id="lieu" class="form-control @error('lieu') is-invalid @enderror" value="{{ old('lieu') }}">
                @error('lieu')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="categorie" class="form-label">Catégorie</label>
                <input type="text" name="categorie" id="categorie" class="form-control @error('categorie') is-invalid @enderror" value="{{ old('categorie') }}">
                @error('categorie')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="ordre" class="form-label">Ordre</label>
                <input type="number" name="ordre" id="ordre" class="form-control @error('ordre') is-invalid @enderror" value="{{ old('ordre', 0) }}">
                @error('ordre')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="est_actif" id="est_actif" class="form-check-input" value="1" {{ old('est_actif') ? 'checked' : '' }}>
                <label for="est_actif" class="form-check-label">Actif</label>
            </div>

            <hr>
            <div class="d-flex align-items-center gap-2 mb-2">
                <h5 class="mb-0">Sous-dates (timeline)</h5>
                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDates" aria-expanded="false">
                    <i class="bi bi-plus-circle"></i> Ajouter / gérer
                </button>
            </div>
            <div class="collapse" id="collapseDates">
                <p class="text-muted small">Ajoutez ici les dates individuelles pour les phases à timeline (ex: présélections avec plusieurs week-ends). Laisser vide pour une phase à date unique.</p>
                <div id="dates-repeater">
                    <div class="row g-2 mb-2 date-item">
                        <div class="col-md-4">
                            <input type="text" name="dates_titre[0]" class="form-control form-control-sm" placeholder="Titre (ex: Samedi 5 Septembre)">
                        </div>
                        <div class="col-md-3">
                            <input type="datetime-local" name="dates_date[0]" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="dates_lieu[0]" class="form-control form-control-sm" placeholder="Lieu (optionnel)">
                        </div>
                        <div class="col-md-2 d-flex gap-1">
                            <input type="number" name="dates_ordre[0]" class="form-control form-control-sm" placeholder="Ordre" value="0">
                            <button type="button" class="btn btn-sm btn-danger remove-date" title="Supprimer">&times;</button>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="add-date">+ Ajouter une date</button>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Créer</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    let idx = 1;
    const repeater = document.getElementById('dates-repeater');
    document.getElementById('add-date').addEventListener('click', function () {
        const div = document.createElement('div');
        div.className = 'row g-2 mb-2 date-item';
        div.innerHTML = [
            '<div class="col-md-4"><input type="text" name="dates_titre[' + idx + ']" class="form-control form-control-sm" placeholder="Titre"></div>',
            '<div class="col-md-3"><input type="datetime-local" name="dates_date[' + idx + ']" class="form-control form-control-sm"></div>',
            '<div class="col-md-3"><input type="text" name="dates_lieu[' + idx + ']" class="form-control form-control-sm" placeholder="Lieu (optionnel)"></div>',
            '<div class="col-md-2 d-flex gap-1"><input type="number" name="dates_ordre[' + idx + ']" class="form-control form-control-sm" placeholder="Ordre" value="0"><button type="button" class="btn btn-sm btn-danger remove-date">&times;</button></div>'
        ].join('');
        repeater.appendChild(div);
        idx++;
    });
    repeater.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-date')) {
            e.target.closest('.date-item').remove();
        }
    });
});
</script>
@endpush
@endsection
