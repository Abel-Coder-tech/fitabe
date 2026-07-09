@extends('layouts.public')

@section('title', 'Règlement — ' . config('app.name', 'FITAB'))

@section('content')
@php $derniereMiseAJour = 'juillet 2026'; @endphp
<section class="py-5" style="background: #f8f6f3; min-height: 60vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-9">
                <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-body p-4 p-md-5">
                        <h4 class="fw-bold mb-4" style="color: #9B4D07;">
                            <i class="bi bi-book me-2"></i>Règlement du FITAB — Édition 4
                        </h4>
                        <p class="text-muted" style="font-size: 0.85rem;">Dernière mise à jour : {{ $derniereMiseAJour }}</p>

                        <div style="font-size: 0.9rem; line-height: 1.7;">
                            <p>Le présent règlement définit les conditions de participation, les modalités de sélection, le fonctionnement du jury et le système d'ovations du <strong>Festival International des Talents Artistiques du Bénin — FITAB, Édition 4</strong>, organisé par <strong>STRATÈGE MEDIA EVENTS</strong> à Porto-Novo, Bénin, en novembre 2026.</p>

                            <h6 class="fw-bold mt-4 mb-2">Article 1 — Organisateur</h6>
                            <ul class="list-unstyled">
                                <li><strong>Organisateur :</strong> STRATÈGE MEDIA EVENTS</li>
                                <li><strong>Responsable :</strong> EYISSE SOBUR BABATUNDE, PDG</li>
                                <li><strong>Directeur Artistique :</strong> MISTER OKEKE</li>
                                <li><strong>Adresse :</strong> Agbokou Centre Social M/EYISSE, Porto-Novo, République du Bénin</li>
                                <li><strong>Contact :</strong> +229 01 66 16 75 88 &middot; <a href="mailto:strategemediaevents@gmail.com" style="color: #9B4D07;">strategemediaevents@gmail.com</a></li>
                                <li><strong>Site officiel :</strong> <a href="{{ route('home') }}" style="color: #9B4D07;">www.fitabe.com</a></li>
                            </ul>

                            <h6 class="fw-bold mt-4 mb-2">Article 2 — Catégories officielles</h6>
                            <p>L'Édition 4 du FITAB comporte 6 catégories artistiques en compétition officielle :</p>
                            <ul>
                                <li><strong>Théâtre :</strong> Théâtre classique, forum, rue, stand-up, comédie, drame, expérimental, marionnettes</li>
                                <li><strong>Percussions :</strong> Percussions traditionnelles béninoises et africaines</li>
                                <li><strong>Musique :</strong> Afrobeats, coupé-décalé, rap, R&amp;B, reggae, gospel, jazz, pop, fusion acoustique</li>
                                <li><strong>Danse Traditionnelle :</strong> Danses Fon, Yoruba, Goun, Adja, Bariba, Dendi et chorégraphies contemporaines</li>
                                <li><strong>Stylisme / Modélisme :</strong> Création vestimentaire, défilé, mise en scène de la mode africaine contemporaine</li>
                                <li><strong>Arts Visuels :</strong> Peinture, sculpture, dessin, art numérique, installations, street art, live painting</li>
                            </ul>

                            <h6 class="fw-bold mt-4 mb-2">Article 3 — Conditions de participation</h6>
                            <p>Peut participer au FITAB toute personne physique ou groupe répondant aux conditions suivantes :</p>
                            <ul>
                                <li>Être un artiste ou groupe artistique résidant ou étant originaire du Grand Porto-Novo (Porto-Novo, Sèmè-Podji, Akpro-Missérété, Adjarra, Avrankou, Dangbo, Adjohoun, Aguégués, Bonou, Sakété) ou du Bénin</li>
                                <li>Être disponible pour l'ensemble des phases du concours auxquelles il est sélectionné</li>
                                <li>Avoir rempli et soumis le formulaire de candidature officiel dans les délais impartis</li>
                                <li>Avoir fourni l'ensemble des éléments requis (formulaire, photos, éléments artistiques)</li>
                                <li>Avoir pris connaissance et accepté le présent règlement</li>
                            </ul>
                            <p>Il n'y a pas de limite d'âge pour participer. La participation est ouverte à tous, amateurs comme professionnels, à titre individuel ou en groupe.</p>

                            <h6 class="fw-bold mt-4 mb-2">Article 4 — Jury et critères d'évaluation</h6>
                            <p>Chaque catégorie dispose d'un jury international composé de 3 à 4 professionnels dont au minimum un membre de nationalité étrangère. Le jury évalue les candidats selon les critères pondérés suivants, adaptés à chaque catégorie :</p>
                            <div class="table-responsive">
                                <table class="table table-bordered" style="font-size: 0.9rem;">
                                    <thead style="background: #f8f6f3;">
                                        <tr><th class="fw-bold">Critère</th><th class="fw-bold">Pondération</th></tr>
                                    </thead>
                                    <tbody>
                                        <tr><td>Maîtrise technique (technique, précision, qualité d'exécution)</td><td>30 %</td></tr>
                                        <tr><td>Originalité et créativité</td><td>25 %</td></tr>
                                        <tr><td>Présence scénique, Authenticité culturelle et identité artistique</td><td>30 %</td></tr>
                                        <tr><td>Ovation (100 FCFA)</td><td>15 %</td></tr>
                                    </tbody>
                                </table>
                            </div>
                            <p>Les délibérations du jury sont confidentielles. Les résultats sont scellés jusqu'à leur annonce officielle lors de chaque phase de compétition. Les décisions du jury sont souveraines et sans appel.</p>

                            <h6 class="fw-bold mt-4 mb-2">Article 6 — Système officiel des Ovations</h6>
                            <p class="fw-bold" style="color: #8b1a1a;">Les présélections sont GRATUITES et ouvertes à tous. Aucun billet n'est requis pour assister aux présélections au Nouveau Rex. Les ovations sont un mécanisme de soutien public volontaire, distinct de l'entrée aux événements.</p>
                            <p>Le FITAB propose un système d'ovations permettant au public de soutenir financièrement les artistes en compétition. Ce système constitue un critère de sélection complémentaire au jugement du jury professionnel.</p>
                            <ul>
                                <li>1 Ovation = 100 FCFA</li>
                                <li>Les ovations sont ouvertes dès la publication des artistes sélectionnés (1er août 2026)</li>
                                <li>Les ovations se clôturent automatiquement le dimanche 22 novembre 2026 à 23h59 (GMT+1)</li>
                                <li>Le paiement s'effectue via Kkiapay (MTN Mobile Money, Moov Flooz, Carte Bancaire)</li>
                                <li>Toute ovation validée et payée est définitive et non remboursable</li>
                                <li>Le nombre d'ovations reçues est pris en compte dans l'évaluation finale, selon les modalités définies par le comité artistique du FITAB</li>
                                <li>En cas d'anomalie technique affectant les ovations, l'Organisation se réserve le droit de corriger ou d'annuler les transactions concernées</li>
                            </ul>

                            <h6 class="fw-bold mt-4 mb-2">Article 7 — Dotations et récompenses</h6>
                            <p>Les lauréats de chaque catégorie reçoivent les récompenses suivantes :</p>
                            <div class="table-responsive">
                                <table class="table table-bordered" style="font-size: 0.9rem;">
                                    <thead style="background: #f8f6f3;">
                                        <tr><th class="fw-bold">Rang</th><th class="fw-bold">Récompenses</th><th class="fw-bold">Dotation</th></tr>
                                    </thead>
                                    <tbody>
                                        <tr><td>GRAND PRIX DU FITAB</td><td>Trophée Prestige Or + Résidence de création + Couverture médiatique nationale dédiée</td><td>1 000 000 FCFA</td></tr>
                                        <tr><td>1<sup>er</sup> Prix (par catégorie)</td><td>Trophée FITAB Or + Bourse d'accompagnement professionnel 12 mois + Diplôme d'excellence</td><td>400 000 FCFA</td></tr>
                                        <tr><td>2<sup>e</sup> Prix (par catégorie)</td><td>Trophée FITAB Argent + Diplôme d'excellence</td><td>200 000 FCFA</td></tr>
                                        <tr><td>3<sup>e</sup> Prix (par catégorie)</td><td>Trophée FITAB Bronze + Diplôme d'excellence</td><td>100 000 FCFA</td></tr>
                                        <tr><td>Légendes honorées (3)</td><td>Trophée d'Or FITAB + Documentaire 3 min + Discours solennel</td><td>Hommage officiel</td></tr>
                                        <tr><td>Acteurs Culturels (5)</td><td>Trophée Flamme de la Culture</td><td>Hommage officiel</td></tr>
                                    </tbody>
                                </table>
                            </div>

                            <h6 class="fw-bold mt-4 mb-2">Article 8 — Droit à l'image et exploitation médiatique</h6>
                            <p>En participant au FITAB, tout candidat (et son représentant légal s'il est mineur) accorde à <strong>STRATÈGE MEDIA EVENTS</strong>, à titre gratuit, le droit d'utiliser son image, son nom de scène, ses prestations artistiques et les contenus qui lui sont associés dans le cadre suivant :</p>
                            <ul>
                                <li>Diffusion des prestations sur les plateformes numériques officielles du FITAB (YouTube, Facebook, TikTok, Instagram)</li>
                                <li>Production et diffusion du documentaire officiel du FITAB (26 minutes, 3 chaînes TV nationales partenaires)</li>
                                <li>Utilisation dans les supports de communication du festival (affiches, flyers, site web, catalogue officiel)</li>
                                <li>Exploitation à des fins de promotion du festival au niveau national et international</li>
                            </ul>
                            <p>Cette autorisation est accordée pour une durée de 5 ans à compter de la participation et pour une diffusion sans limitation géographique.</p>

                            <h6 class="fw-bold mt-4 mb-2">Article 9 — Engagement, discipline et conduite</h6>
                            <p>Tout candidat admis au FITAB s'engage à :</p>
                            <ul>
                                <li>Respecter les horaires et planning de chaque phase du concours</li>
                                <li>Adopter une conduite professionnelle et respectueuse envers l'organisation, le jury, les autres candidats et le public</li>
                                <li>Respecter les décisions du jury, qui sont définitives et sans appel</li>
                                <li>Assurer la qualité et l'authenticité de ses prestations</li>
                                <li>Informer l'organisation dans les meilleurs délais en cas d'empêchement</li>
                            </ul>

                            <h6 class="fw-bold mt-4 mb-2">Article 10 — Disqualification</h6>
                            <p>L'Organisation se réserve le droit de disqualifier tout candidat en cas de :</p>
                            <ul>
                                <li>Fraude, tricherie ou tentative de manipulation du système d'ovations</li>
                                <li>Comportement irrespectueux, violent ou contraire à l'éthique</li>
                                <li>Non-respect du présent règlement</li>
                                <li>Fausse déclaration dans le dossier de candidature</li>
                                <li>Absence non justifiée lors d'une phase de compétition</li>
                            </ul>
                            <p>La décision de disqualification est prise par le comité d'organisation et est sans appel.</p>

                            <h6 class="fw-bold mt-4 mb-2">Article 11 — Partenaires et sponsors</h6>
                            <p>Les partenaires et sponsors officiels du FITAB bénéficient de droits de visibilité définis dans leurs contrats respectifs. L'Organisation s'engage à respecter ses engagements de communication envers chaque partenaire.</p>
                            <p>Tout partenaire est soumis à une charte de bonne conduite garantissant le respect des valeurs culturelles et artistiques du FITAB.</p>

                            <h6 class="fw-bold mt-4 mb-2">Article 12 — Portée internationale</h6>
                            <p>Le FITAB peut collaborer avec des partenaires, jurés et artistes internationaux. En participant au festival, les candidats acceptent que leurs prestations soient diffusées au-delà du territoire béninois, dans le respect des droits définis à l'article 7 du présent règlement.</p>

                            <h6 class="fw-bold mt-4 mb-2">Article 13 — Litiges et droit applicable</h6>
                            <p>Tout litige relatif à l'interprétation ou à l'exécution du présent règlement est soumis à l'appréciation exclusive du comité d'organisation du FITAB, dont la décision est souveraine et sans appel.</p>
                            <p>En cas de désaccord persistant, les parties s'engagent à recourir à une procédure de médiation amiable avant toute action judiciaire. Le présent règlement est régi par le droit en vigueur en République du Bénin.</p>

                            <hr class="my-4">
                            <p>Fait à Porto-Novo, Juillet 2026.</p>
                            <div class="row">
                                <div class="col-6">
                                    <p class="mb-1"><strong>Pour STRATÈGE MEDIA EVENTS</strong></p>
                                    <p class="mb-0">EYISSE SOBUR BABATUNDE — PDG</p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-1"><strong>Directeur Artistique FITAB</strong></p>
                                    <p class="mb-0">MISTER OKEKE — Roi du Théâtre Béninois</p>
                                </div>
                            </div>
                            <p class="mt-3 mb-0"><strong>Contact :</strong> +229 01 66 16 75 88 &middot; <a href="mailto:strategemediaevents@gmail.com" style="color: #9B4D07;">strategemediaevents@gmail.com</a> &middot; <a href="{{ route('home') }}" style="color: #9B4D07;">www.fitabe.com</a></p>
                        </div>

                        <a href="{{ route('home') }}" class="btn btn-success mt-4" style="border-radius: 8px; background: #9B4D07; border-color: #9B4D07;">
                            <i class="bi bi-arrow-left me-1"></i> Retour à l'accueil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection