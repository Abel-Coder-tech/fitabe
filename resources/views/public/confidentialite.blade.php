@extends('layouts.public')

@section('title', 'Politique de confidentialité — ' . config('app.name', 'FITAB'))

@section('content')
@php $derniereMiseAJour = 'juillet 2026'; @endphp
<section class="py-5" style="background: #f8f6f3; min-height: 60vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-7">
                <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-body p-4 p-md-5">
                        <h4 class="fw-bold mb-4" style="color: #9B4D07;">
                            <i class="bi bi-shield-lock me-2"></i>Politique de confidentialité
                        </h4>
                        <p class="text-muted" style="font-size: 0.85rem;">Dernière mise à jour : {{ $derniereMiseAJour }}</p>

                        <div style="font-size: 0.9rem; line-height: 1.7;">
                            <p><strong>STRATÈGE MEDIA EVENTS</strong> attache une importance fondamentale à la protection des données personnelles de ses utilisateurs, candidats, partenaires et spectateurs. La présente politique décrit les données collectées, les finalités de leur traitement et les droits dont dispose chaque personne concernée.</p>

                            <h6 class="fw-bold mt-4 mb-2">1. Données collectées</h6>
                            <p>Dans le cadre des activités du FITAB (site web, inscriptions, ovations, communication), les données suivantes peuvent être collectées :</p>
                            <ul>
                                <li><strong>Identité :</strong> nom, prénom, nom de scène</li>
                                <li><strong>Coordonnées :</strong> numéro de téléphone, adresse email</li>
                                <li><strong>Informations artistiques :</strong> catégorie, biographie, photos, vidéos de prestation</li>
                                <li><strong>Données de paiement :</strong> les transactions d'ovations sont traitées exclusivement par Fedapay (prestataire certifié) — aucune donnée bancaire n'est stockée sur les serveurs du site</li>
                                <li><strong>Données de navigation :</strong> adresse IP, pages visitées, durée de session (via outils d'analyse anonymisés)</li>
                            </ul>

                            <h6 class="fw-bold mt-4 mb-2">2. Finalités du traitement</h6>
                            <p>Les données personnelles collectées sont utilisées exclusivement pour :</p>
                            <ul>
                                <li>La gestion et le traitement des candidatures au FITAB</li>
                                <li>L'organisation des présélections, compétitions et de la Grande Finale</li>
                                <li>La gestion du système d'ovations et des transactions financières associées</li>
                                <li>La communication officielle du festival (informations, résultats, programme)</li>
                                <li>La promotion des artistes et du festival sur les supports médiatiques</li>
                                <li>La production du documentaire officiel et des contenus audiovisuels</li>
                                <li>L'amélioration de l'expérience utilisateur sur le site</li>
                            </ul>

                            <h6 class="fw-bold mt-4 mb-2">3. Sécurité et protection des données</h6>
                            <p><strong>STRATÈGE MEDIA EVENTS</strong> met en œuvre toutes les mesures techniques et organisationnelles appropriées pour garantir la sécurité des données personnelles :</p>
                            <ul>
                                <li>Protocole HTTPS avec certificat SSL Let's Encrypt — toutes les communications sont chiffrées</li>
                                <li>Architecture Laravel sécurisée : protection contre les injections SQL, protection CSRF</li>
                                <li>Données de paiement traitées exclusivement par Fedapay — aucun numéro de carte bancaire, code PIN ou identifiant de paiement n'est stocké sur le serveur du site</li>
                                <li>Accès aux données restreint aux seules personnes habilitées de l'organisation</li>
                                <li>Sauvegardes régulières de la base de données</li>
                            </ul>

                            <h6 class="fw-bold mt-4 mb-2">4. Partage des données</h6>
                            <p>Les données personnelles ne sont ni vendues, ni cédées à des tiers à des fins commerciales. Elles peuvent être partagées uniquement avec :</p>
                            <ul>
                                <li><strong>Fedapay :</strong> prestataire de paiement sécurisé pour le traitement des ovations</li>
                                <li><strong>o2switch :</strong> hébergeur du site (accès technique limité aux données)</li>
                                <li><strong>Noctam Communication :</strong> prestataire de développement web (accès limité à la maintenance)</li>
                                <li><strong>Partenaires institutionnels du FITAB :</strong> uniquement si nécessaire à l'organisation du festival et avec le consentement préalable des personnes concernées</li>
                            </ul>

                            <h6 class="fw-bold mt-4 mb-2">5. Droits des utilisateurs</h6>
                            <p>Conformément aux principes internationaux de protection des données personnelles, chaque utilisateur dispose des droits suivants concernant ses données :</p>
                            <ul>
                                <li><strong>Droit d'accès :</strong> obtenir une copie des données le concernant</li>
                                <li><strong>Droit de rectification :</strong> corriger des données inexactes ou incomplètes</li>
                                <li><strong>Droit à l'effacement :</strong> demander la suppression de ses données</li>
                                <li><strong>Droit d'opposition :</strong> s'opposer au traitement de ses données à des fins de communication</li>
                            </ul>
                            <p>Pour exercer ces droits, adresser une demande écrite à :</p>
                            <ul>
                                <li><strong>Email :</strong> <a href="mailto:strategemediaevents@gmail.com" style="color: #9B4D07;">strategemediaevents@gmail.com</a></li>
                                <li><strong>Objet :</strong> [RGPD] — Demande de [accès / rectification / suppression / opposition]</li>
                                <li><strong>Délai de réponse :</strong> sous 30 jours ouvrables</li>
                            </ul>

                            <h6 class="fw-bold mt-4 mb-2">6. Cookies et traceurs</h6>
                            <p>Le site fitabe.com peut utiliser des cookies techniques nécessaires à son fonctionnement (session, sécurité) et des outils d'analyse de trafic anonymisés. L'utilisateur est informé de leur présence lors de sa première visite et peut paramétrer son navigateur pour les refuser.</p>

                            <h6 class="fw-bold mt-4 mb-2">7. Durée de conservation</h6>
                            <p>Les données personnelles sont conservées pour la durée strictement nécessaire aux finalités pour lesquelles elles ont été collectées, et au maximum pour une durée de 3 ans après le dernier contact, conformément aux pratiques recommandées.</p>
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