@extends('layouts.public')

@section('title', 'Accueil - ' . config('app.name', 'FITAB'))

@section('content')
<div class="position-relative d-flex align-items-center overflow-hidden"
     style="background: linear-gradient(135deg, rgba(62,30,5,0.92) 0%, rgba(62,30,5,0.7) 50%, rgba(62,30,5,0.4) 100%), url('{{ asset('images/hero.jpg') }}') no-repeat center center; background-size: cover; min-height: 100vh;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7 text-center text-lg-start">
                <span class="badge fw-semibold px-3 py-2 mb-3 fs-6" style="background-color: #CA7B05; color: #fff;">
                    <i class="bi bi-music-note-beamed me-1"></i> Édition 2026
                </span>
                <h1 class="display-4 fw-bold text-white mb-3">
                    Festival International<br>
                    <span style="color: #E3D5AD;">des Talents Artistiques</span>
                </h1>
                <p class="lead mb-4 fs-5" style="color: rgba(227,213,173,0.8); max-width: 540px;">
                    Théâtre, Danse, Musique, Percussion &amp; Art visuel <br>
                     Découvrez et soutenez les artistes béninois en votant pour vos favoris.
                </p>
                <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start">
                    <a href="{{ route('public.vote') }}" class="btn btn-fitab btn-lg px-4 py-3 border-0 shadow-sm">
                        <i class="bi bi-calendar-event me-2"></i>Voter maintenant
                    </a>
                    <a href="{{ route('public.medias') }}" class="btn btn-fitab-ghost btn-lg px-4 py-3">
                        <i class="bi bi-images me-2"></i>Médiathèque
                    </a>
                </div>

                <div class="mt-3 small" style="color: rgba(227,213,173,0.7);">
                    <i class="bi bi-calendar-event me-1" style="color: #CA7B05;"></i> Novembre 2026
                </div>

                <div class="d-flex flex-wrap gap-4 mt-4" style="color: rgba(227,213,173,0.7);">
                    <div><i class="bi bi-people-fill me-1" style="color: #CA7B05;"></i> 15+ Artistes</div>
                    <div><i class="bi bi-trophy-fill me-1" style="color: #CA7B05;"></i> 5 Catégories</div>
                </div>
            </div>

            <div class="col-lg-5 d-none d-lg-flex justify-content-center">
                <div class="text-center p-5 rounded-4" style="border: 1px solid rgba(202,123,5,0.4); background: rgba(227,213,173,0.06); backdrop-filter: blur(8px); max-width: 320px;">
                    <i class="bi bi-calendar-event" style="color: #CA7B05; font-size: 3rem;"></i>
                    <h5 class="text-white mt-3 mb-1" style="font-size: 1.8rem; font-weight: 800;">Novembre 2026</h5>
                    <p class="small mb-0" style="color: rgba(227,213,173,0.7);">
                        Préparez-vous à vivre <strong>5 jours</strong> de festival.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="position-absolute bottom-0 start-50 translate-middle-x mb-4 small animate-bounce" style="color: rgba(227,213,173,0.5);">
        <i class="bi bi-chevron-down fs-4"></i>
    </div>
</div>

<style>
@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(8px); }
}
.animate-bounce { animation: bounce 2s infinite; }
.cat-circle { transition: border-color .3s ease; cursor: pointer; z-index: 1; }
.cat-circle:hover { border-color: #CA7B05 !important; z-index: 10; }
.cat-circle:hover .cat-inner { transform: scale(1.12); }
.cat-circle:hover .cat-label { opacity: 1; }
.cat-inner { transition: transform .3s ease; }
.cat-label { opacity: 0; transition: opacity .3s ease; background: rgba(62,30,5,0.85); pointer-events: none; }
.accordion-button:not(.collapsed) { background-color: #fff !important; }
.accordion-button:focus { box-shadow: none !important; }
.accordion-button::after { margin-left: 0; }
.partenaires-track-wrapper { overflow: hidden; width: 100%; }
.partenaires-track { display: flex; gap: 2rem; width: max-content; animation: scrollPartenaires 20s linear infinite; }
.partenaires-track:hover { animation-play-state: paused; }
.partenaire-item { flex-shrink: 0; display: flex; align-items: center; justify-content: center; height: 120px; }
.partenaire-logo { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; filter: grayscale(0.6); opacity: 0.8; border: 3px solid #e8e8e8; transition: filter .3s, opacity .3s, transform .3s, border-color .3s; }
.partenaire-logo:hover { filter: grayscale(0); opacity: 1; transform: scale(1.08); border-color: #CA7B05; }
@keyframes scrollPartenaires { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
</style>

{{-- ==================== PRÉSENTATION ==================== --}}
<section class="py-5 section-light">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="text-uppercase fw-semibold small ls-1" style="color: #CA7B05; letter-spacing: 2px;">Présentation</span>
                <h2 class="display-6 fw-bold mt-2 mb-4" style="color: #9B4D07;">
                    Le Festival International<br>
                    <span style="color: #CA7B05;">des Talents Artistiques du Bénin</span>
                </h2>
                <p class="mb-3" style="color: #5F2B0C; line-height: 1.8;">
                    Le <strong>FITAB</strong> est bien plus qu'un festival — c'est un mouvement culturel qui célèbre la richesse et la diversité des talents artistiques béninois. Théâtre, danse, musique, percussion et art visuel s'entremêlent pour offrir une scène unique aux artistes émergents et confirmés.
                </p>
                <p class="mb-4" style="color: #5F2B0C; line-height: 1.8;">
                    Chaque édition est une invitation à découvrir l'âme créative du Bénin, à travers des performances vibrantes, des expositions captivantes et des rencontres inoubliables.
                </p>

                <div class="p-4 rounded-3 border-start" style="border-color: #CA7B05 !important; border-width: 4px !important; background-color: #fdfaf5;">
                    <p class="mb-0 fst-italic" style="color: #5F2B0C; line-height: 1.8;">
                        <span class="fw-bold" style="color: #9B4D07;">Mot de l'organisation</span><br>
                        « Nous croyons que l'art est le pont qui unit les peuples. Le FITAB est né de cette conviction profonde : offrir aux talents d'ici une vitrine à la hauteur de leur génie. Merci à tous ceux qui nous accompagnent dans cette aventure. »
                    </p>
                </div>

                <div class="row g-3 mt-4">
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 d-flex align-items-center gap-3 h-100" style="background-color: rgba(202,123,5,0.08); border: 1px solid rgba(202,123,5,0.15);">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background-color: #9B4D07;">
                                <i class="bi bi-trophy-fill text-white"></i>
                            </div>
                            <div>
                                <strong style="color: #9B4D07;">Plus de 5 000 000 FCFA</strong>
                                <small class="d-block text-muted-custom">de prix à gagner</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 d-flex align-items-center gap-3 h-100" style="background-color: rgba(202,123,5,0.08); border: 1px solid rgba(202,123,5,0.15);">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background-color: #9B4D07;">
                                <i class="bi bi-award-fill text-white"></i>
                            </div>
                            <div>
                                <strong style="color: #9B4D07;">Grand Prix FITAB</strong>
                                <small class="d-block text-muted-custom">Bourses d'accompagnement</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="position-relative mx-auto" style="width: 360px; height: 500px;">
                    @php
                        $items = [
                            ['img' => 'cat1', 'angle' => 90,  'label' => 'Théatre',       'pos' => 'top'],
                            ['img' => 'cat2', 'angle' => 18,  'label' => 'Percussion',      'pos' => 'right'],
                            ['img' => 'cat3', 'angle' => 306, 'label' => 'Musique',         'pos' => 'right'],
                            ['img' => 'cat4', 'angle' => 234, 'label' => 'Danse',    'pos' => 'left'],
                            ['img' => 'cat5', 'angle' => 162, 'label' => 'Art visuel',    'pos' => 'left'],
                        ];
                        $labelStyles = [
                            'top'   => 'bottom: 100%; left: 50%; transform: translateX(-50%); margin-bottom: 8px; text-align: center;',
                            'right' => 'left: 100%; top: 50%; transform: translateY(-50%); margin-left: 8px;',
                            'left'  => 'right: 100%; top: 50%; transform: translateY(-50%); margin-right: 8px; text-align: right;',
                        ];
                    @endphp
                    @foreach ($items as $item)
                    <div class="position-absolute rounded-circle cat-circle" style="width: 130px; height: 130px; top: 50%; left: 50%; margin-top: -65px; margin-left: -65px; border: 3px solid #e8e8e8; transform: rotate({{ $item['angle'] }}deg) translateX(90px) rotate(-{{ $item['angle'] }}deg);">
                        <div class="w-100 h-100 overflow-hidden rounded-circle cat-inner">
                            <img src="{{ asset('images/categories/' . $item['img'] . '.jpg') }}" alt="" class="w-100 h-100" style="object-fit: cover; display: block;">
                        </div>
                        <span class="cat-label position-absolute fw-semibold small text-nowrap px-3 py-1 rounded-pill text-white" style="{{ $labelStyles[$item['pos']] }}">{{ $item['label'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ==================== APPEL À CANDIDATURES ==================== --}}
<section class="py-5 section-dark">
    <div class="container py-4">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <span class="text-uppercase fw-semibold small" style="color: #CA7B05; letter-spacing: 2px;">Appel à candidatures</span>
                <h2 class="display-6 fw-bold mt-2 mb-3" style="color: #E3D5AD;">
                    Êtes-vous styliste <span style="color: #CA7B05;">ou modéliste</span> ?
                </h2>
                <p class="mb-4" style="color: rgba(227,213,173,0.75); line-height: 1.8; max-width: 540px;">
                    Nous recrutons des talents de la mode pour participer au FITAB 2026. 
                    Si vous avez une passion pour le stylisme ou le modélisme, 
                    soumettez votre candidature et tentez de faire partie de l'aventure.
                </p>
                <a href="#" target="_blank" rel="noopener" class="btn btn-fitab btn-lg px-5 py-3 fw-semibold">
                    <i class="bi bi-send me-2"></i>Postuler maintenant
                </a>
            </div>
            <div class="col-lg-5 text-center">
                <div class="p-4 rounded-4 d-inline-block" style="border: 2px dashed rgba(202,123,5,0.4);">
                    <i class="bi bi-scissors" style="color: #CA7B05; font-size: 4rem;"></i>
                    <div class="small mt-2" style="color: rgba(227,213,173,0.6);">Concours de mode</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ==================== PROGRAMME ==================== --}}
<section class="py-5" style="background-color: #fdfaf5;">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="text-uppercase fw-semibold small" style="color: #CA7B05; letter-spacing: 2px;">Programme</span>
            <h2 class="display-6 fw-bold mt-2" style="color: #9B4D07;">Calendrier des activités</h2>
            <p class="text-muted-custom mt-2" style="max-width: 540px; margin: 0 auto;">Spectacles, panels, ateliers et rencontres — plongez au cœur du festival.</p>
        </div>

        @if ($jours->count())
        <div class="accordion" id="programmeAccordion">
            @foreach ($jours as $dateKey => $events)
                @php
                    $first = $events->first();
                    $dt = $first->date_programme;
                    $jourLabel = 'Jour ' . $loop->iteration . ' — ' . ucfirst($dt->translatedFormat('l j F'));
                    $isFirst = $loop->first;
                @endphp
                <div class="accordion-item border-0 mb-3 rounded-3 shadow-sm overflow-hidden">
                    <h2 class="accordion-header">
                        <button class="accordion-button fw-bold {{ $isFirst ? '' : 'collapsed' }}" type="button"
                                data-bs-toggle="collapse" data-bs-target="#jour{{ $loop->iteration }}"
                                style="background-color: #fff; color: #9B4D07; font-size: 1.05rem; box-shadow: none; border-left: 4px solid #9B4D07; border-radius: 0 !important;">
                            {{ $jourLabel }}
                            <i class="bi bi-calendar-event ms-auto me-2" style="color: #CA7B05;"></i>
                        </button>
                    </h2>
                    <div id="jour{{ $loop->iteration }}" class="accordion-collapse collapse {{ $isFirst ? 'show' : '' }}"
                         data-bs-parent="#programmeAccordion">
                        <div class="accordion-body p-0">
                            <div class="list-group list-group-flush">
                                @foreach ($events as $event)
                                <div class="list-group-item border-bottom d-flex align-items-start gap-3 p-3 p-md-4">
                                    <div class="d-flex align-items-center justify-content-center rounded-2 text-white flex-shrink-0"
                                         style="width: 60px; height: 60px; background-color: #9B4D07;">
                                        <span class="small lh-1 text-center fw-bold">
                                            {{ $event->date_programme->format('H:i') }}
                                        </span>
                                    </div>
                                    <div class="flex-grow-1 min-w-0">
                                        <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                            @if ($event->categorie)
                                            <span class="badge fw-normal small px-2 py-1" style="background-color: #E3D5AD; color: #9B4D07;">{{ $event->categorie }}</span>
                                            @endif
                                            @if ($event->lieu)
                                            <small class="text-muted-custom"><i class="bi bi-geo-alt me-1"></i>{{ $event->lieu }}</small>
                                            @endif
                                        </div>
                                        <h6 class="fw-bold mb-1" style="color: #9B4D07;">{{ $event->titre }}</h6>
                                        @if ($event->description)
                                        <p class="small text-muted-custom mb-0">{{ $event->description }}</p>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-calendar-week fs-1" style="color: #CA7B05;"></i>
            <p class="text-muted-custom mt-3 mb-0">Aucun programme publié pour le moment. Revenez bientôt !</p>
        </div>
        @endif
    </div>
</section>

{{-- ==================== PARTENAIRES ==================== --}}
<section class="py-5 section-dark text-center">
    <div class="container">
        <div class="text-center mb-4">
            <span class="text-uppercase fw-semibold small" style="color: #CA7B05; letter-spacing: 2px;">Partenaires</span>
            <h2 class="fw-bold mt-2" style="color: #E3D5AD;">Nos partenaires</h2>
        </div>
        @if ($partenaires->count())
        <div class="partenaires-track-wrapper">
            <div class="partenaires-track">
                @foreach ($partenaires as $p)
                <div class="partenaire-item">
                    <img src="{{ $p->logo_url }}" alt="{{ $p->nom }}" class="partenaire-logo">
                </div>
                @endforeach
                @foreach ($partenaires as $p)
                <div class="partenaire-item">
                    <img src="{{ $p->logo_url }}" alt="{{ $p->nom }}" class="partenaire-logo">
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>
@endsection
