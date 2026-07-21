@extends('layouts.public')

@section('title', 'Accueil - ' . config('app.name', 'FITAB'))

@push('meta')
<script type="application/ld+json">
@php echo json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Event',
    'name' => 'FITAB 2026 - Festival International des Talents Artistiques du Bénin',
    'description' => 'Théâtre, Danse, Musique, Percussion, Stylisme et Arts Visuels — Présélections et finale à Porto-Novo.',
    'startDate' => '2026',
    'location' => [
        '@type' => 'Place',
        'name' => 'Porto-Novo',
        'address' => [
            '@type' => 'PostalAddress',
            'addressLocality' => 'Porto-Novo',
            'addressCountry' => 'BJ',
        ],
    ],
    'image' => asset('images/hero.jpg'),
    'organizer' => [
        '@type' => 'Organization',
        'name' => 'FITAB',
        'url' => url('/'),
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); @endphp
</script>
@endpush

@section('content')
<div class="hero-section position-relative d-flex align-items-center"
     style="background: linear-gradient(135deg, rgba(62,30,5,0.92) 0%, rgba(62,30,5,0.7) 50%, rgba(62,30,5,0.4) 100%), url('{{ asset('images/hero.jpg') }}') no-repeat center center; background-size: cover; min-height: calc(100vh - 64px);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 text-center text-lg-start">
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

                <div class="row g-2 g-lg-3 mt-4 justify-content-center justify-content-lg-start hero-stats" style="color: rgba(227,213,173,0.7); font-size: 1.05rem;">
                    <div class="col-6 col-lg-auto"><div class="d-flex align-items-center justify-content-center justify-content-lg-start gap-2"><i class="bi bi-calendar-week fs-5" style="color: #CA7B05;"></i> <span>5 Jours</span></div></div>
                    <div class="col-6 col-lg-auto"><div class="d-flex align-items-center justify-content-center justify-content-lg-start gap-2"><i class="bi bi-people-fill fs-5" style="color: #CA7B05;"></i> <span>30 000 spect.</span></div></div>
                    <div class="col-6 col-lg-auto"><div class="d-flex align-items-center justify-content-center justify-content-lg-start gap-2"><i class="bi bi-grid-3x3-gap-fill fs-5" style="color: #CA7B05;"></i> <span>6 catégories</span></div></div>
                    <div class="col-6 col-lg-auto"><div class="d-flex align-items-center justify-content-center justify-content-lg-start gap-2"><i class="bi bi-trophy-fill fs-5" style="color: #CA7B05;"></i> <span>+ 3 000 000 FCFA</span></div></div>
                </div>
            </div>

            <div class="col-lg-4 d-none d-lg-flex justify-content-center">
                <div class="text-center p-4 p-xl-5 rounded-4" style="border: 1px solid rgba(202,123,5,0.4); background: rgba(227,213,173,0.06); backdrop-filter: blur(8px); max-width: 600px;">
                    <i class="bi bi-calendar-event" style="color: #CA7B05; font-size: 2.5rem;"></i>
                    <h5 class="text-white mt-3 mb-0" style="font-size: 1.3rem; font-weight: 700;">Novembre 2026</h5>
                   
                </div>
            </div>
        </div>
    </div>

</div>

<style>
html, body { overflow-x: hidden; width: 100%; }
.hero-stats span { white-space: nowrap; }
@@media (max-width: 767.98px) {
    .hero-section { padding: 2.5rem 0; }
    .hero-section h1 { font-size: 1.75rem !important; }
}
@@media (max-width: 575.98px) {
    .public-footer .container { padding-left: 12px; padding-right: 12px; }
    .public-footer .row.gy-4 { --bs-gutter-y: 1.5rem; }
    .public-footer .brand img { height: 70px; }
}
@@media (max-width: 420px) {
    .hero-stats { font-size: 0.85rem !important; }
    .hero-stats .fs-5 { font-size: 0.95rem !important; }
}
@@keyframes bounce {
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
    gap: 1.5rem;
    width: max-content;
}
.partenaires-track.centered {
    width: 100%;
    justify-content: center;
    flex-wrap: wrap;
}
.partenaires-track.scrolling {
    animation: scrollPartenaires 35s linear infinite;
}
.partenaires-track.scrolling:hover {
    animation-play-state: paused;
}
@@keyframes scrollPartenaires {
    0%   { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}
.partenaire-logo {
    flex-shrink: 0;
    width: 140px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.partenaire-logo img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    display: block;
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

/* ========== CAROUSEL PRÉSENTATION ========== */
.fitab-carousel {
    max-width: 480px;
    margin: 0 auto;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(0,0,0,0.12);
    background: #fff;
}
.carousel-viewport {
    position: relative;
    width: 100%;
    aspect-ratio: 1 / 1;
    overflow: hidden;
    user-select: none;
    cursor: grab;
    touch-action: pan-y;
}
.carousel-viewport:active { cursor: grabbing; }
.carousel-track {
    display: flex;
    height: 100%;
    transition: transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    will-change: transform;
}
.carousel-slide {
    flex: 0 0 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}
.carousel-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.65) 0%, rgba(0,0,0,0.15) 50%, rgba(0,0,0,0.1) 100%);
    pointer-events: none;
}
.carousel-label {
    position: relative;
    z-index: 2;
    color: #fff;
    font-size: 1.6rem;
    font-weight: 700;
    text-shadow: 0 2px 8px rgba(0,0,0,0.5);
    letter-spacing: 0.5px;
    text-align: center;
    padding: 0 1rem;
}
.carousel-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 3;
    background: rgba(255,255,255,0.2);
    border: none;
    color: #fff;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    backdrop-filter: blur(4px);
    transition: all 0.2s;
    cursor: pointer;
    opacity: 0;
}
.carousel-viewport:hover .carousel-btn { opacity: 1; }
.carousel-btn:hover { background: rgba(255,255,255,0.35); }
.carousel-btn-left  { left: 12px; }
.carousel-btn-right { right: 12px; }
.carousel-btn:disabled { opacity: 0.15; cursor: default; }

.carousel-dots {
    position: absolute;
    bottom: 14px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 3;
    display: flex;
    gap: 8px;
}
.carousel-dots span {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: rgba(255,255,255,0.45);
    cursor: pointer;
    transition: all 0.25s;
}
.carousel-dots span.active {
    background: #CA7B05;
    width: 22px;
    border-radius: 4px;
}

@@media (max-width: 575.98px) {
    .fitab-carousel { max-width: 100%; border-radius: 12px; }
    .carousel-label { font-size: 1.3rem; }
    .carousel-btn { width: 32px; height: 32px; font-size: 0.9rem; opacity: 1; background: rgba(0,0,0,0.3); }
}
</style>

{{-- ==================== PRÉSENTATION ==================== --}}
<section class="py-5 section-light" id="presentation">
    <div class="container">
        <div class="row align-items-center ">
            <div class="col-lg-8 mb-4 mb-lg-0">
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

                <div class="p-3 rounded-3 border-start" style="border-color: #CA7B05 !important; border-width: 4px !important; background-color: #f5ebe0;">
                    <p class="mb-0 fst-italic" style="color: #5F2B0C; line-height: 1.7;">
                        « Il n'y a pas de grande nation sans grande culture. Porto-Novo mérite un festival à la hauteur de son âme. »
                    </p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="fitab-carousel">
                    @php
                        $slides = [
                            ['img' => 'cat1', 'label' => 'Théâtre'],
                            ['img' => 'cat2', 'label' => 'Percussion'],
                            ['img' => 'cat3', 'label' => 'Musique'],
                            ['img' => 'cat4', 'label' => 'Danse'],
                            ['img' => 'cat5', 'label' => 'Art visuel'],
                            ['img' => 'cat6', 'label' => 'Stylisme & Modélisme'],
                        ];
                    @endphp
                    <div class="carousel-viewport" id="presentationCarousel">
                        <div class="carousel-track">
                            @foreach ($slides as $i => $slide)
                            <div class="carousel-slide{{ $i === 0 ? ' active' : '' }}" style="background-image: url('{{ asset('images/categories/'.$slide['img'].'.jpg') }}');">
                                <div class="carousel-overlay"></div>
                                <div class="carousel-label">{{ $slide['label'] }}</div>
                            </div>
                            @endforeach
                        </div>
                        <button class="carousel-btn carousel-btn-left" aria-label="Précédent"><i class="bi bi-chevron-left"></i></button>
                        <button class="carousel-btn carousel-btn-right" aria-label="Suivant"><i class="bi bi-chevron-right"></i></button>
                        <div class="carousel-dots"></div>
                    </div>
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
        <div class="row align-items-center g-3 mb-5">
            <div class="col-lg-6  ">
                <div class="position-relative d-inline-block">
                    <img src="{{ asset('images/promoteurs/promoteur1.png') }}" alt="MISTER OKEKE" loading="lazy" width="400" height="400"
                         style="width: 100%; max-width: 400px; border-radius: 12px; border: 2px solid #c9a96e; display: block;">
                    <div class="position-absolute" style="bottom: 12px; right: 12px; background: #8b1a1a; border-radius: 8px; padding: 8px 16px; text-align: center;">
                        <div style="color: #fff; font-size: 0.75rem; line-height: 1.2;">Cofondateur</div>
                        <div style="color: #fff; font-size: 1rem; font-weight: 700; line-height: 1.3;">Depuis 2023</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 ">
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
                    <img src="{{ asset('images/promoteurs/promoteur2.png') }}" alt="EYISSE SOBUR BABATUNDE" loading="lazy" width="400" height="400"
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
        <div class="partenaires-track-wrapper mb-4">
            <div class="partenaires-track" id="partnerTrack">
                @foreach ($partenaires as $p)
            <div class="partenaire-logo"{{ $p->site_web ? ' onclick="window.open(\''.$p->site_web.'\',\'_blank\')"' : '' }}>
                <img src="{{ $p->logo_url }}" alt="{{ $p->nom }}" loading="lazy">
            </div>
                @endforeach
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var viewport = document.getElementById('presentationCarousel');
    if (!viewport) return;
    var track = viewport.querySelector('.carousel-track');
    var slides = track.querySelectorAll('.carousel-slide');
    var dotsContainer = viewport.querySelector('.carousel-dots');
    var prevBtn = viewport.querySelector('.carousel-btn-left');
    var nextBtn = viewport.querySelector('.carousel-btn-right');
    var total = slides.length;
    var current = 0;
    var autoTimer;
    var isDragging = false;
    var startX = 0;
    var currentTranslate = 0;
    var prevTranslate = 0;
    var animationID = 0;

    function buildDots() {
        dotsContainer.innerHTML = '';
        for (var i = 0; i < total; i++) {
            var dot = document.createElement('span');
            if (i === 0) dot.classList.add('active');
            dot.addEventListener('click', function() { goTo(parseInt(this.dataset.index)); });
            dot.dataset.index = i;
            dotsContainer.appendChild(dot);
        }
    }

    function goTo(index) {
        if (index < 0) index = 0;
        if (index >= total) index = total - 1;
        if (index === current) return;
        current = index;
        var offset = -current * 100;
        track.style.transform = 'translateX(' + offset + '%)';
        slides.forEach(function(s, i) { s.classList.toggle('active', i === current); });
        dotsContainer.querySelectorAll('span').forEach(function(d, i) { d.classList.toggle('active', i === current); });
        resetAuto();
    }

    function next() { goTo(current + 1); }
    function prev() { goTo(current - 1); }

    function resetAuto() {
        clearTimeout(autoTimer);
        autoTimer = setTimeout(next, 4000);
    }

    // Touch / drag
    function dragStart(e) {
        isDragging = true;
        startX = e.type === 'touchstart' ? e.touches[0].clientX : e.clientX;
        track.style.transition = 'none';
        prevTranslate = currentTranslate || -current * (viewport.offsetWidth || 480);
        viewport.classList.add('dragging');
        cancelAnimationFrame(animationID);
    }
    function dragMove(e) {
        if (!isDragging) return;
        var x = e.type === 'touchmove' ? e.touches[0].clientX : e.clientX;
        var diff = (x - startX);
        currentTranslate = prevTranslate + diff;
        track.style.transform = 'translateX(' + currentTranslate + 'px)';
    }
    function dragEnd() {
        if (!isDragging) return;
        isDragging = false;
        viewport.classList.remove('dragging');
        track.style.transition = 'transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
        var w = viewport.offsetWidth || 480;
        var movedBy = currentTranslate - (-current * w);
        if (Math.abs(movedBy) > w * 0.15) {
            if (movedBy < 0) next(); else prev();
        } else {
            goTo(current);
        }
        prevTranslate = currentTranslate;
    }

    prevBtn.addEventListener('click', prev);
    nextBtn.addEventListener('click', next);
    viewport.addEventListener('mousedown', dragStart);
    viewport.addEventListener('mousemove', dragMove);
    viewport.addEventListener('mouseup', dragEnd);
    viewport.addEventListener('mouseleave', dragEnd);
    viewport.addEventListener('touchstart', dragStart, { passive: true });
    viewport.addEventListener('touchmove', dragMove, { passive: true });
    viewport.addEventListener('touchend', dragEnd);

    buildDots();
    resetAuto();
});

// Partenaires : centré si tient, scroll droite→gauche si déborde
(function() {
    var track = document.getElementById('partnerTrack');
    if (!track) return;
    var wrapper = track.parentElement;
    var items = track.querySelectorAll('.partenaire-card');
    if (items.length < 2) { track.classList.add('centered'); return; }
    var totalWidth = 0;
    items.forEach(function(el) { totalWidth += el.offsetWidth + 24; });
    if (totalWidth <= wrapper.offsetWidth) {
        track.classList.add('centered');
    } else {
        items.forEach(function(el) { track.appendChild(el.cloneNode(true)); });
        track.classList.add('scrolling');
    }
})();
</script>
@endpush
@endsection
