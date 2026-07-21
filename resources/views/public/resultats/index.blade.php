@extends('layouts.public')

@section('title', 'Résultats - ' . config('app.name', 'FITAB'))
@section('description', 'Découvrez les résultats et le palmarès du FITAB — Festival International des Talents Artistiques du Bénin. Lauréats par catégorie.')

@section('content')
<section class="d-flex align-items-center justify-content-center"
         style="min-height: 220px; background: linear-gradient(135deg, rgba(62,30,5,0.88) 0%, rgba(62,30,5,0.65) 50%, rgba(62,30,5,0.4) 100%), url('{{ asset('images/hero.jpg') }}') no-repeat center center; background-size: cover;">
    <div class="container text-center">
        <h1 class="fw-bold display-6 mb-2" style="color: #E3D5AD;">Résultats</h1>
        @if($annee)
            <p class="mb-0" style="color: rgba(227,213,173,0.8);">FITAB {{ $annee }}</p>
        @endif
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container">
        @if(!$annee || $resultats->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-trophy" style="font-size: 3rem; color: #CA7B05;"></i>
                <p class="text-muted mt-3 mb-0">Aucun résultat publié pour le moment.</p>
            </div>
        @else
            @foreach ($resultats as $categorie => $items)
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="px-4 py-3" style="background: linear-gradient(135deg, #3E1E05, #9B4D07);">
                        <h5 class="fw-bold mb-0 text-white">
                            <i class="bi bi-tag-fill me-2" style="color: #E3D5AD;"></i>
                            {{ str_replace('_', ' ', ucfirst($categorie)) }}
                        </h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Candidat</th>
                                    <th>Ovations</th>
                                    <th>Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $r)
                                    <tr>
                                        <td>
                                            <span class="badge fs-6 px-2 py-1" style="background: {{ $r->prix === 1 ? '#FFD700' : ($r->prix === 2 ? '#C0C0C0' : '#CD7F32') }}; color: #3E1E05;">
                                                {{ $r->prix_label }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @if ($r->candidat_photo)
                                                    <img src="{{ $r->candidat_photo_url }}" alt="{{ $r->candidat_nom }}" width="40" height="40" class="rounded-circle" style="object-fit: cover;">
                                                @endif
                                                <span class="fw-medium">{{ $r->candidat_nom }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $r->nombre_votes }}</td>
                                        <td><strong>{{ $r->score_final ?? $r->score_public ?? '-' }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach

            <div class="alert alert-info text-center" role="alert">
                <i class="bi bi-info-circle me-2"></i>Résultats provisoires — sous réserve de validation par le jury.
            </div>
        @endif
    </div>
</section>
@endsection
