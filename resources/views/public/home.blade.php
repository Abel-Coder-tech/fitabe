@extends('layouts.public')

@section('title', 'Accueil - ' . config('app.name', 'FITAB'))

@section('content')
<div class="position-relative d-flex align-items-center overflow-hidden"
     style="background: linear-gradient(135deg, rgba(62,30,5,0.92) 0%, rgba(62,30,5,0.7) 50%, rgba(62,30,5,0.4) 100%), url('{{ asset('images/hero.jpg') }}') no-repeat center center; background-size: cover; min-height: 100vh;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7 text-center text-lg-start">
                <span class="badge fw-semibold px-3 py-2 mb-3 fs-6" style="background-color: #CA7B05; color: #fff;">
                    <i class="bi bi-music-note-beamed me-1"></i> FITAB 2026
                </span>
                <h1 class="display-4 fw-bold text-white mb-3">
                    La scène la plus<br>
                    <span style="color: #E3D5AD;">attendue du Bénin</span>
                </h1>
                <p class="mb-4" style="color: rgba(227,213,173,0.65); max-width: 540px; line-height: 1.7;">
                    Le FITAB est bien plus qu'un festival. C'est une révolution culturelle. Chaque année, Porto-Novo s'arrête, tend l'oreille, et offre sa scène aux artistes qui méritent d'être vus.
                </p>
                <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start">
                    <a href="#presentation" class="btn btn-fitab btn-lg px-4 py-3 border-0 shadow-sm">
                        <i class="bi bi-calendar-event me-2"></i>Découvrir le festival
                    </a>
                    <a href="{{ route('public.vote') }}" class="btn btn-fitab-ghost btn-lg px-4 py-3">
                        <i class="bi bi-images me-2"></i>Offrir une ovation
                    </a>
                </div>

                <div class="row g-3 mt-4 justify-content-center justify-content-lg-start" style="color: rgba(227,213,173,0.7); font-size: 1.05rem;">
                    <div class="col-6 col-lg-auto"><div class="d-flex align-items-center justify-content-center justify-content-lg-start gap-2"><i class="bi bi-calendar-week fs-5" style="color: #CA7B05;"></i> 5 Jours</div></div>
                    <div class="col-6 col-lg-auto"><div class="d-flex align-items-center justify-content-center justify-content-lg-start gap-2"><i class="bi bi-people-fill fs-5" style="color: #CA7B05;"></i> 30 000 spectateurs</div></div>
                    <div class="col-6 col-lg-auto"><div class="d-flex align-items-center justify-content-center justify-content-lg-start gap-2"><i class="bi bi-grid-3x3-gap-fill fs-5" style="color: #CA7B05;"></i> 6 catégories</div></div>
                    <div class="col-6 col-lg-auto"><div class="d-flex align-items-center justify-content-center justify-content-lg-start gap-2"><i class="bi bi-trophy-fill fs-5" style="color: #CA7B05;"></i> + 3 000 000 FCFA</div></div>
                </div>
            </div>

            <div class="col-lg-5 d-none d-lg-flex justify-content-center">
                <div class="text-center p-5 rounded-4" style="border: 1px solid rgba(202,123,5,0.4); background: rgba(227,213,173,0.06); backdrop-filter: blur(8px); max-width: 320px;">
                    <i class="bi bi-calendar-event" style="color: #CA7B05; font-size: 3rem;"></i>
                    <h5 class="text-white mt-3 mb-1" style="font-size: 1.8rem; font-weight: 800;">Novembre 2026</h5>
                   
                </div>
            </div>
        </div>
    </div>

</div>

<style>
@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(8px); }
}
.animate-bounce { 
    animation: bounce 2s infinite; 
}
.cat-circle {
    transition: border-color .3s ease; 
    cursor: pointer; 
    z-index: 1; 
}
.cat-circle:hover { border-color: #CA7B05 !important; 
    z-index: 10; 
}
.cat-circle:hover .cat-inner { 
    transform: scale(1.12); 
}
.cat-circle:hover .cat-label { 
    opacity: 1; 
}
.cat-inner { 
    transition: 
    transform .3s ease; 
}

.cat-label { 
    opacity: 0; 
    transition: opacity .3s ease; 
    background: rgba(62,30,5,0.85); 
    pointer-events: none; 
}
.accordion-button:not(.collapsed) { 
    background-color: #fff !important; 
}
.accordion-button:focus { 
    box-shadow: none !important; 
}
.accordion-button::after { 
    margin-left: 0; 
}
.partenaires-track-wrapper { 
    overflow: hidden; 
    width: 100%; 
}
.partenaires-track { 
    display: flex; 
    gap: 2rem; 
    width: max-content; 
}
.partenaires-track.scroll { 
    animation: scrollPartenaires 20s linear infinite; 
}
.partenaires-track.scroll:hover { 
    animation-play-state: paused; 
}
.partenaires-track.centered { 
    width: 100%; 
    justify-content: center; 
}
.partenaire-item { 
    flex-shrink: 0; 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    height: 120px; 
}
.partenaire-logo { 
    width: 100px; 
    height: 100px; 
    border-radius: 50%; 
    object-fit: cover; 
    border: 2px solid #e8e8e8; 
    transition: transform .3s, border-color .3s; 
}
.partenaire-logo:hover { 
    transform: scale(1.25); 
    border-color: #CA7B05; 
}
@keyframes scrollPartenaires { 
    0% { transform: translateX(0); } 
100% { transform: translateX(-50%); } 
}
.accordion-button:not(.collapsed) { 
    background: transparent !important; 
    color: #3E1E05 !important; 
    box-shadow: none !important; 
}
.accordion-button:focus { 
    box-shadow: none !important; 
}
.accordion-button::after { 
    position: absolute; 
    right: 16px; 
}
.accordion-button:not(.collapsed)::after { 
    filter: none; 
}
.timeline { 
    position: relative; 
    padding: 1rem 0; 
}
.timeline::before { 
    content: ''; 
    position: absolute; 
    left: 50%; 
    top: 0; 
    bottom: 0; 
    width: 3px; 
    background: #E3D5AD; 
    transform: translateX(-50%); 
}
.timeline-item { 
    display: flex; 
    align-items: flex-start; 
    margin-bottom: 1.5rem; 
    position: relative; 
}
.timeline-item:last-child { 
    margin-bottom: 0; 
}
.timeline-item .timeline-badge { 
    flex-shrink: 0; 
    width: 48px; 
    height: 48px; 
    border-radius: 50%; 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    background: #9B4D07; 
    color: #fff; 
    font-weight: 700; 
    font-size: 0.7rem; 
    text-align: center; 
    line-height: 1.2; 
    z-index: 1; 
    position: absolute; 
    left: 50%; 
    transform: translateX(-50%); 
}
.timeline-item .timeline-content { 
    width: calc(50% - 36px); 
    padding: 0.75rem 1rem; 
    background: #fff; 
    border-radius: 8px; 
    border: 1px solid rgba(202,123,5,0.15); 
}
.timeline-item:nth-child(odd) .timeline-content { 
    margin-right: auto; 
    margin-left: 0; 
}
.timeline-item:nth-child(even) .timeline-content { 
    margin-left: auto; 
margin-right: 0; 
}
</style>

{{-- ==================== PRÉSENTATION ==================== --}}
<section class="py-5 section-light" id="presentation">
    <div class="container">
        <div class="row align-items-center ">
            <div class="col-lg-6">
                <span class="text-uppercase fw-semibold small" style="color: #CA7B05; letter-spacing: 2px;">Présentation</span>
                <h2 class="display-6 fw-bold mt-2 mb-2" style="color: #9B4D07;">
                    Porto-Novo a un festival.<br>
                    <span style="color: #CA7B05;">Il s'appelle FITAB.</span>
                </h2>

                <div class="d-flex flex-wrap justify-content-between gap-3 mb-2 py-3 px-3 rounded-3" style="background: #fdfaf5;">
                    <div class="d-flex align-items-start gap-2 flex-fill" style="min-width: 180px;">
                        <div>
                            <strong style="color: #3E1E05; font-size: 1.25rem;">Naissance & mission</strong>
                            <p style="color: #5F2B0C; line-height: 1.8; margin-bottom: 0;">Né en 2023 à Porto-Novo, le Festival International des Talents Artistiques du Bénin - FITAB est la réponse à un paradoxe douloureux : la capitale du Bénin, ville aux trois noms, terre de traditions millénaires, n'avait pas de grand festival. Les talents naissaient, brillaient dans l'ombre, et s'éteignaient sans jamais avoir eu leur scène.</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-2 flex-fill" style="min-width: 180px;">
                        <div>
                            <strong style="color: #3E1E05; font-size: 1.25rem;">Origine</strong>
                            <p style="color: #5F2B0C; line-height: 1.8; margin-bottom: 0;">Fondé par MISTER OKEKE, Roi du Théâtre Béninois, 1 903 spectacles, 50 ans de scène et EYISSE SOBUR BABATUNDE, le FITAB est né d'une blessure intime : celle d'une légende trop longtemps ignorée. Aujourd'hui, ce festival est l'acte fondateur qu'il aurait voulu trouver à ses débuts.</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-2 flex-fill" style="min-width: 180px;">
                        <div>
                            <strong style="color: #3E1E05; font-size: 1.25rem;">Bilan & ambition</strong>
                            <p style="color: #5F2B0C; line-height: 1.8; margin-bottom: 0;">En 3 éditions, le FITAB a réuni plus de 15 000 spectateurs, décerné 36 trophées en une seule soirée, et imposé Porto-Novo sur la carte culturelle de l'Afrique de l'Ouest. L'Édition 4 vise 30 000 spectateurs, 6 catégories artistiques, un jury international, et une Grande Finale sur l'esplanade de l'Assemblée Nationale.</p>
                        </div>
                    </div>
                </div>

                <div class="p-3 rounded-3 border-start" style="border-color: #CA7B05 !important; border-width: 4px !important; background-color: #fdfaf5;">
                    <p class="mb-0 fst-italic" style="color: #5F2B0C; line-height: 1.7;">
                        « Il n'y a pas de grande nation sans grande culture. Porto-Novo mérite un festival à la hauteur de son âme. »
                    </p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="position-relative mx-auto" style="width: 360px; height: 500px;">
                    @php
                        $items = [
                            ['img' => 'cat1', 'angle' => 90,  'label' => 'Théatre',       'pos' => 'top'],
                            ['img' => 'cat2', 'angle' => 30,  'label' => 'Percussion',      'pos' => 'right'],
                            ['img' => 'cat3', 'angle' => 330, 'label' => 'Musique',         'pos' => 'right'],
                            ['img' => 'cat4', 'angle' => 270, 'label' => 'Danse',    'pos' => 'bottom'],
                            ['img' => 'cat5', 'angle' => 210, 'label' => 'Art visuel',    'pos' => 'left'],
                            ['img' => 'cat6', 'angle' => 150, 'label' => 'Stylisme/Modélisme', 'pos' => 'left'],
                        ];
                        $labelStyles = [
                            'top'    => 'bottom: 100%; left: 50%; transform: translateX(-50%); margin-bottom: 8px; text-align: center;',
                            'right'  => 'left: 100%; top: 50%; transform: translateY(-50%); margin-left: 8px;',
                            'bottom' => 'top: 100%; left: 50%; transform: translateX(-50%); margin-top: 8px; text-align: center;',
                            'left'   => 'right: 100%; top: 50%; transform: translateY(-50%); margin-right: 8px; text-align: right;',
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

{{-- ==================== LES 3 PILIERS ==================== --}}
<section class="py-5" style="background-color: #fdfaf5;">
    <div class="container">
        <div class="text-center mb-5">
            <span class="text-uppercase fw-semibold small" style="color: #CA7B05; letter-spacing: 2px;">Les 3 piliers</span>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-center p-4 rounded-4 h-100" style="border: 2px solid rgba(202,123,5,0.2); background: #fff;">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px; background: #3E1E05;">
                        <i class="bi bi-eye-fill text-white fs-4"></i>
                    </div>
                    <h5 class="fw-bold" style="color: #9B4D07;">RÉVÉLER</h5>
                    <p style="color: #5F2B0C; line-height: 1.8;">Donner une scène internationale aux talents émergents du Grand Porto-Novo qui n'ont jamais été vus.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-4 rounded-4 h-100" style="border: 2px solid rgba(202,123,5,0.2); background: #fff;">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px; background: #3E1E05;">
                        <i class="bi bi-award-fill text-white fs-4"></i>
                    </div>
                    <h5 class="fw-bold" style="color: #9B4D07;">VALORISER</h5>
                    <p style="color: #5F2B0C; line-height: 1.8;">Honorer publiquement les artistes confirmés et les Légendes de Porto-Novo qui ont tout donné à la culture.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-4 rounded-4 h-100" style="border: 2px solid rgba(202,123,5,0.2); background: #fff;">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px; background: #3E1E05;">
                        <i class="bi bi-globe2 text-white fs-4"></i>
                    </div>
                    <h5 class="fw-bold" style="color: #9B4D07;">RAYONNER</h5>
                    <p style="color: #5F2B0C; line-height: 1.8;">Projeter Porto-Novo et le Grand Porto-Novo sur la scène internationale à travers 7 mois de communication.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ==================== MOTS DES PROMOTEURS ==================== --}}
<section class="py-5" style="background-color: #fdfaf5;">
    <div class="container">
        <div class="text-center mb-5">
            <span class="text-uppercase fw-semibold small" style="color: #CA7B05; letter-spacing: 2px;">Mots des promoteurs</span>
            <p class="mt-2" style="color: #5F2B0C;">Derrière le FITAB, deux hommes. Deux générations. Une seule vision.</p>
        </div>

        {{-- Promoteur 1 : image gauche, texte droite --}}
        <div class="row align-items-center g-5 mb-5">
            <div class="col-lg-6">
                <div class="position-relative d-inline-block">
                    <img src="{{ asset('images/promoteurs/promoteur1.png') }}" alt="MISTER OKEKE"
                         style="width: 100%; max-width: 400px; border-radius: 12px; border: 2px solid #c9a96e; display: block;">
                    <div class="position-absolute" style="bottom: 12px; right: 12px; background: #8b1a1a; border-radius: 8px; padding: 8px 16px; text-align: center;">
                        <div style="color: #fff; font-size: 0.75rem; line-height: 1.2;">Cofondateur</div>
                        <div style="color: #fff; font-size: 1rem; font-weight: 700; line-height: 1.3;">Depuis 2023</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <h3 style="color: #1a1a1a; font-size: 1.8rem; font-weight: 700; margin-bottom: 0.25rem;">MISTER OKEKE</h3>
                <p style="color: #c8922a; font-size: 0.875rem; margin-bottom: 0.75rem;">Roi du Théâtre Béninois · Cofondateur</p>
                <hr style="width: 50px; height: 3px; background: #c0392b; border: none; margin: 0 0 1rem 0;">
                <p style="color: #1a1a1a; line-height: 1.8;">
                    En près de 50 ans, j'ai joué sur presque toutes les scènes du Bénin. Mais j'ai aussi vécu de l'intérieur la douleur d'un talent ignoré. J'ai vu des artistes prodigieux s'éteindre dans l'ombre, faute d'une scène pour les révéler.
                </p>
                <p style="color: #1a1a1a; line-height: 1.8;">
                    Le FITAB, c'est ce que j'aurais voulu trouver à mes débuts. C'est ma façon de dire : plus jamais. Plus jamais un talent du Grand Porto-Novo et du Bénin ne disparaîtra sans avoir eu sa chance.
                </p>
                <div style="border-left: 4px solid #c9a96e; padding-left: 16px;">
                    <div style="color: #c9a96e; font-size: 2.5rem; line-height: 1; font-family: Georgia, serif;">"</div>
                    <p style="color: #1a1a1a; font-style: italic; line-height: 1.7; margin-bottom: 0.25rem;">
                        Le théâtre n'est pas un métier, c'est une mission. Transmettre aux jeunes générations ce feu sacré est la seule chose qui compte.
                    </p>
                    <p style="color: #c8922a; font-style: normal; margin-bottom: 0;">— MISTER OKEKE</p>
                </div>
            </div>
        </div>

        {{-- Promoteur 2 : inversé (image droite, texte gauche) --}}
        <div class="row align-items-center g-5 flex-row-reverse">
            <div class="col-lg-6">
                <div class="position-relative d-inline-block">
                    <img src="{{ asset('images/promoteurs/promoteur2.png') }}" alt="EYISSE SOBUR BABATUNDE"
                         style="width: 100%; max-width: 400px; border-radius: 12px; border: 2px solid #c9a96e; display: block;">
                    <div class="position-absolute" style="bottom: 12px; right: 12px; background: #8b1a1a; border-radius: 8px; padding: 8px 16px; text-align: center;">
                        <div style="color: #fff; font-size: 0.75rem; line-height: 1.2;">Cofondateur</div>
                        <div style="color: #fff; font-size: 1rem; font-weight: 700; line-height: 1.3;">Depuis 2023</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <h3 style="color: #1a1a1a; font-size: 1.8rem; font-weight: 700; margin-bottom: 0.25rem;">EYISSE SOBUR BABATUNDE</h3>
                <p style="color: #c8922a; font-size: 0.875rem; margin-bottom: 0.75rem;">PDG Stratège Media Events · Cofondateur</p>
                <hr style="width: 50px; height: 3px; background: #c0392b; border: none; margin: 0 0 1rem 0;">
                <p style="color: #1a1a1a; line-height: 1.8;">
                    Je suis né dans l'art, j'ai grandi dans la culture. Je connais les jeunes du Grand Porto-Novo de l'intérieur, leur énergie, leur talent brut, leur besoin urgent d'être vus et reconnus.
                </p>
                <p style="color: #1a1a1a; line-height: 1.8;">
                    Porto-Novo n'est pas une ville ordinaire. C'est une capitale culturelle qui mérite un festival à la hauteur de son histoire. Le FITAB est cet engagement et nous le tenons, édition après édition.
                </p>
                <div style="border-left: 4px solid #c9a96e; padding-left: 16px;">
                    <div style="color: #c9a96e; font-size: 2.5rem; line-height: 1; font-family: Georgia, serif;">"</div>
                    <p style="color: #1a1a1a; font-style: italic; line-height: 1.7; margin-bottom: 0.25rem;">
                        La culture est le moteur du développement. Donner une voix aux artistes, c'est investir dans l'avenir de notre nation.
                    </p>
                    <p style="color: #c8922a; font-style: normal; margin-bottom: 0;">— EYISSE SOBUR BABATUNDE</p>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ==================== PROGRAMME ==================== --}}
<section class="py-5" style="background-color: #fdfaf5;">
    <div class="container">
        <div class="text-center mb-5">
            <span class="text-uppercase fw-semibold small" style="color: #CA7B05; letter-spacing: 2px;">Programme</span>
            <h2 class="display-6 fw-bold mt-2" style="color: #9B4D07;">Le calendrier de l'Édition 4</h2>
            <p class="text-muted-custom mt-2" style="max-width: 540px; margin: 0 auto;">Du premier souffle au Grand Prix. 7 mois pour changer des destins.</p>
        </div>

        <div class="accordion" id="programmeAccordion">
            @forelse ($programmes as $p)
            @php
                $hasTimeline = $p->dates->count() > 0;
                $borderColor = $p->couleur_bordure ?? '#9B4D07';
                $isGrande = Str::contains(Str::lower($p->titre), 'finale');
                $targetId = 'phase' . $p->id;
            @endphp
            <div class="accordion-item border-0 mb-3 rounded-3 overflow-hidden" style="border-left: 4px solid {{ $borderColor }} !important; border-radius: 8px !important;">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-bold py-3" type="button"
                            data-bs-toggle="collapse" data-bs-target="#{{ $targetId }}"
                            style="background: transparent; color: {{ $isGrande ? '#CA7B05' : '#3E1E05' }}; box-shadow: none; padding-left: 48px; position: relative;">
                        @if ($p->icone)
                        <i class="bi {{ $p->icone }}" style="color: {{ $isGrande ? '#FFD700' : $borderColor }}; position: absolute; left: 16px; top: 50%; transform: translateY(-50%);"></i>
                        @endif
                        {{ $p->titre }}
                    </button>
                </h2>
                <div id="{{ $targetId }}" class="accordion-collapse collapse" data-bs-parent="#programmeAccordion">
                    <div class="accordion-body p-3">
                        @if ($p->description)
                        <div class="px-3 py-2 mb-3 small rounded-2" style="background: rgba(202,123,5,0.08); color: #9B4D07;">
                            <i class="bi bi-info-circle me-1"></i> {{ $p->description }}
                        </div>
                        @endif

                        @if ($hasTimeline)
                            <div class="timeline">
                                @foreach ($p->dates as $sd)
                                <div class="timeline-item">
                                    <div class="timeline-badge"><i class="bi bi-calendar-event fs-6"></i></div>
                                    <div class="timeline-content">
                                        <strong style="color: #9B4D07;">{{ $sd->date->locale('fr')->isoFormat('D MMM YYYY') }}</strong><br>
                                        <span style="color: #5F2B0C;">{{ $sd->titre ?? $sd->date->locale('fr')->isoFormat('dddd D MMMM YYYY') }}</span>
                                        <small class="d-block text-muted-custom mt-1">
                                            @if ($sd->lieu)
                                            <i class="bi bi-geo-alt me-1"></i>{{ $sd->lieu }}
                                            @elseif ($p->lieu)
                                            <i class="bi bi-geo-alt me-1"></i>{{ $p->lieu }}
                                            @endif
                                        </small>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background: #fff; border: 1px solid rgba(202,123,5,0.15);">
                                <div class="d-flex align-items-center justify-content-center rounded-2 text-white flex-shrink-0 fw-bold small" style="width: 70px; height: 50px; background: {{ $borderColor }};">
                                    {{ $p->date_programme->locale('fr')->isoFormat('D MMM') }}
                                </div>
                                <div>
                                    <strong style="color: #3E1E05;">{{ $p->date_programme->locale('fr')->isoFormat('dddd D MMMM YYYY') }}</strong>
                                    @if ($p->lieu)
                                    <small class="d-block text-muted-custom mt-1"><i class="bi bi-geo-alt me-1"></i>{{ $p->lieu }}</small>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <p class="text-center text-muted py-4">Aucun programme pour le moment.</p>
            @endforelse
        </div>
    </div>
</section>

{{-- ==================== PARTENAIRES ==================== --}}
<section class="py-5 bg-white text-center">
    <div class="container">
        <div class="text-center mb-5">
            <span class="text-uppercase fw-semibold small" style="color: #CA7B05; letter-spacing: 2px;">Partenaires</span>
            <h2 class="fw-bold mt-2" style="color: #3E1E05;">Nos partenaires</h2>
        </div>
        @if ($partenaires->count())
        <div class="partenaires-track-wrapper">
            <div class="partenaires-track {{ $partenaires->count() > 1 ? 'scroll' : 'centered' }}">
                @foreach ($partenaires as $p)
                <div class="partenaire-item">
                    <img src="{{ $p->logo_url }}" alt="{{ $p->nom }}" class="partenaire-logo">
                </div>
                @endforeach
                @if ($partenaires->count() > 1)
                @foreach ($partenaires as $p)
                <div class="partenaire-item">
                    <img src="{{ $p->logo_url }}" alt="{{ $p->nom }}" class="partenaire-logo">
                </div>
                @endforeach
                @endif
            </div>
        </div>
        @endif
    </div>
</section>

{{-- Section appel à l'action --}}
<section class="py-5 bg-light text-center">
    <div class="container">
        <h2 class="fw-bold mb-3" style="color: #3E1E05;">Prêt à vivre l'expérience FITAB ?</h2>
        <p class="mb-4" style="color: #5F2B0C;">Rejoignez-nous pour célébrer la culture, la créativité et le talent du Grand Porto-Novo.</p>
        <a href="{{ route('public.contact') }}" class="btn btn-fitab btn-lg px-4 py-3 border-0 shadow-sm">
            <i class="bi bi-calendar-event me-2"></i>Dévenir partenaire ou sponsor
        </a>
    </div>
</section>
@endsection
