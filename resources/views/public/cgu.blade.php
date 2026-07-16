@extends('layouts.public')

@section('title', 'Conditions générales d\'utilisation — ' . config('app.name', 'FITAB'))

@section('content')
@php $derniereMiseAJour = 'juillet 2026'; @endphp
<section class="py-5" style="background: #f8f6f3; min-height: 60vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-7">
                <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-body p-4 p-md-5">
                        <h4 class="fw-bold mb-4" style="color: #9B4D07;">
                            <i class="bi bi-file-text me-2"></i>Conditions générales d'utilisation
                        </h4>
                        <p class="text-muted" style="font-size: 0.85rem;">Dernière mise à jour : {{ $derniereMiseAJour }}</p>

                        <div style="font-size: 0.9rem; line-height: 1.7;">
                            <p>Les présentes Conditions Générales d'Utilisation (CGU) régissent l'accès et l'utilisation du site officiel du <strong>Festival International des Talents Artistiques du Bénin — FITAB</strong>, accessible à l'adresse <a href="{{ route('home') }}" style="color: #9B4D07;">www.fitabe.com</a>, ainsi que l'ensemble des services numériques proposés par <strong>STRATÈGE MEDIA EVENTS</strong> dans le cadre de l'Édition 4 du festival.</p>
                            <p>L'accès et l'utilisation du site valent acceptation pleine et entière des présentes CGU.</p>

                            <h6 class="fw-bold mt-4 mb-2">1. Objet</h6>
                            <p>Les présentes CGU définissent les droits et obligations de <strong>STRATÈGE MEDIA EVENTS</strong> (ci-après « l'Organisation ») et des utilisateurs du site www.fitabe.com, dans le cadre de la consultation du site, des inscriptions aux compétitions, du système d'ovations et de toute interaction avec les services numériques du FITAB.</p>

                            <h6 class="fw-bold mt-4 mb-2">2. Accès aux services</h6>
                            <p>L'accès au site www.fitabe.com est libre et gratuit pour tout utilisateur disposant d'un accès à Internet.</p>
                            <ul>
                                <li>La consultation des pages Accueil, Médiathèque et Contact est entièrement libre et gratuite</li>
                                <li>L'accès à la page Ovations nécessite une connexion mobile money ou bancaire valide pour effectuer des transactions</li>
                                <li>L'inscription au concours en tant que candidat est gratuite</li>
                                <li>L'entrée aux présélections publiques est libre et gratuite pour tous les spectateurs</li>
                            </ul>
                            <p>L'Organisation se réserve le droit de restreindre temporairement l'accès au site pour des raisons de maintenance, de mise à jour ou de sécurité.</p>

                            <h6 class="fw-bold mt-4 mb-2">3. Responsabilité de l'utilisateur</h6>
                            <p>L'utilisateur reconnaît utiliser les services du site sous sa responsabilité exclusive. En accédant au site, l'utilisateur s'engage à :</p>
                            <ul>
                                <li>Fournir des informations exactes, complètes et sincères lors de toute inscription ou interaction</li>
                                <li>Ne pas utiliser le site à des fins illégales, frauduleuses ou contraires à l'ordre public</li>
                                <li>Ne pas porter atteinte à l'image, à la réputation ou aux droits du FITAB et de ses partenaires</li>
                                <li>Respecter les droits de propriété intellectuelle de l'Organisation</li>
                                <li>Ne pas tenter d'accéder de manière non autorisée aux systèmes informatiques du site</li>
                            </ul>

                            <h6 class="fw-bold mt-4 mb-2">4. Limitation de responsabilité de l'Organisation</h6>
                            <p>L'Organisation ne garantit pas :</p>
                            <ul>
                                <li>L'absence d'erreurs techniques ou d'interruptions du service</li>
                                <li>La disponibilité continue du site à tout moment</li>
                                <li>La compatibilité du site avec tous les équipements et navigateurs</li>
                            </ul>
                            <p>L'Organisation ne saurait être tenue responsable de tout dommage direct ou indirect résultant d'une indisponibilité du service ou d'une utilisation incorrecte du site.</p>

                            <h6 class="fw-bold mt-4 mb-2">5. Système d'ovations — Transactions financières</h6>
                            <p class="fw-bold" style="color: #8b1a1a;">Les ovations sont des actes de soutien financier volontaires et irréversibles. Toute ovation validée et payée est définitive.</p>
                            <p>Le système d'ovations permet au public de soutenir financièrement les artistes en compétition. Les règles suivantes s'appliquent :</p>
                            <ul>
                                <li>1 Ovation = 100 FCFA, montant fixe et non modifiable</li>
                                <li>Les paiements sont effectués et sécurisés exclusivement via Fedapay (MTN Mobile Money, Moov Flooz, Orange Money)</li>
                                <li>Toute ovation validée est définitive et ne peut donner lieu à aucun remboursement, sauf en cas d'erreur technique dûment constatée et validée par l'Organisation</li>
                                <li>L'utilisateur est seul responsable de la saisie correcte du nombre d'ovations souhaité avant validation</li>
                                <li>L'Organisation se réserve le droit de suspendre le système d'ovations en cas de suspicion de fraude ou d'anomalie technique</li>
                                <li>Les ovations constituent un critère de sélection officielle parmi d'autres critères définis par le jury du FITAB</li>
                            </ul>

                            <h6 class="fw-bold mt-4 mb-2">6. Propriété intellectuelle</h6>
                            <p>Tous les contenus présents sur le site www.fitabe.com (textes, images, vidéos, logos, graphismes, structure, identité visuelle) sont protégés par le droit de la propriété intellectuelle et appartiennent exclusivement à <strong>STRATÈGE MEDIA EVENTS</strong>. Toute reproduction sans autorisation préalable est interdite.</p>

                            <h6 class="fw-bold mt-4 mb-2">7. Liens hypertextes</h6>
                            <p>Le site www.fitabe.com peut contenir des liens vers des sites tiers (réseaux sociaux, partenaires, médias). L'Organisation n'est pas responsable du contenu de ces sites et ne peut être tenue pour responsable des dommages pouvant résulter de leur consultation.</p>

                            <h6 class="fw-bold mt-4 mb-2">8. Droit applicable et litiges</h6>
                            <p>Les présentes CGU sont régies par le droit en vigueur en République du Bénin. Tout litige relatif à leur interprétation ou leur exécution sera soumis, préalablement à toute action judiciaire, à une tentative de résolution amiable entre l'utilisateur et l'Organisation.</p>
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