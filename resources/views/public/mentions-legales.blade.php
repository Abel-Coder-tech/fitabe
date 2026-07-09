@extends('layouts.public')

@section('title', 'Mentions légales — ' . config('app.name', 'FITAB'))

@section('content')
@php $derniereMiseAJour = 'juillet 2026'; @endphp
<section class="py-5" style="background: #f8f6f3; min-height: 60vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-7">
                <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-body p-4 p-md-5">
                        <h4 class="fw-bold mb-4" style="color: #9B4D07;">
                            <i class="bi bi-info-circle me-2"></i>Mentions légales
                        </h4>
                        <p class="text-muted" style="font-size: 0.85rem;">Dernière mise à jour : {{ $derniereMiseAJour }}</p>

                        <div style="font-size: 0.9rem; line-height: 1.7;">
                            <h6 class="fw-bold mt-4 mb-2">Éditeur de la plateforme</h6>
                            <ul class="list-unstyled">
                                <li><strong>Dénomination :</strong> STRATÈGE MEDIA EVENTS</li>
                                <li><strong>Forme juridique :</strong> Entreprise privée — Promotion événementielle &amp; culturelle</li>
                                <li><strong>RCCM :</strong> RB/COT/19/A 52603</li>
                                <li><strong>IFU :</strong> 02017100049059</li>
                                <li><strong>Siège social :</strong> Agbokou Centre Social M/EYISSE, Porto-Novo, Bénin</li>
                                <li><strong>Responsable :</strong> EYISSE SOBUR BABATUNDE, PDG</li>
                                <li><strong>Téléphones :</strong> +229 01 66 16 75 88 / 01 94 74 05 85 / 01 44 99 21 47</li>
                                <li><strong>Email :</strong> <a href="mailto:strategemediaevents@gmail.com" style="color: #9B4D07;">strategemediaevents@gmail.com</a></li>
                                <li><strong>Site :</strong> <a href="{{ route('home') }}" style="color: #9B4D07;">www.fitabe.com</a></li>
                                <li><strong>Enregistrement culturel :</strong> Troupe TOWAKONOU MISTER OKEKE — N°2014/139/SG/SAG/SA du 27 novembre 2014</li>
                            </ul>

                            <h6 class="fw-bold mt-4 mb-2">Hébergement</h6>
                            <ul class="list-unstyled">
                                <li><strong>Hébergeur :</strong> o2switch</li>
                                <li><strong>Développement :</strong> Noctam Communication — Amos AHOUANVOEKE Ferdinand Kayodé</li>
                                <li><strong>Contact :</strong> <a href="mailto:noctamcom@gmail.com" style="color: #9B4D07;">noctamcom@gmail.com</a> / +229 01 62 83 66 29</li>
                                <li><strong>Paiement :</strong> Kkiapay (MTN Mobile Money, Moov Flooz, Carte Bancaire)</li>
                            </ul>

                            <h6 class="fw-bold mt-4 mb-2">Propriété intellectuelle</h6>
                            <p>L'ensemble des éléments du site fitabe.com et des supports officiels du FITAB (textes, images, vidéos, logos, identité visuelle, concept « Ovation », structures de compétition, contenus artistiques) est la propriété exclusive de <strong>STRATÈGE MEDIA EVENTS</strong>, protégé par les dispositions nationales et internationales. Toute reproduction ou exploitation sans autorisation écrite préalable est interdite.</p>

                            <h6 class="fw-bold mt-4 mb-2">Responsabilité</h6>
                            <p><strong>STRATÈGE MEDIA EVENTS</strong> s'engage à fournir des informations fiables, mais ne saurait être tenu responsable des erreurs involontaires, interruptions techniques, dommages liés à l'utilisation du site, ni des contenus de sites tiers.</p>

                            <h6 class="fw-bold mt-4 mb-2">Contact</h6>
                            <p>Pour toute question relative aux présentes mentions légales :</p>
                            <ul>
                                <li>Email : <a href="mailto:strategemediaevents@gmail.com" style="color: #9B4D07;">strategemediaevents@gmail.com</a></li>
                                <li>Téléphones : +229 01 66 16 75 88 / 01 94 74 05 85 / 01 44 99 21 47</li>
                            </ul>
                        </div>

                        <a href="{{ route('home') }}" class="btn btn-success mt-3" style="border-radius: 8px; background: #9B4D07; border-color: #9B4D07;">
                            <i class="bi bi-arrow-left me-1"></i> Retour à l'accueil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection