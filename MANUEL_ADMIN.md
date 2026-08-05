# Manuel d'administration FITAB

## 1. Connexion

1. Rendez-vous sur `https://fitabe.com/admin`
2. Saisissez votre **email** et **mot de passe**
3. Cliquez sur **Connexion**

Si vous avez oublié votre mot de passe, cliquez sur « Mot de passe oublié ? » pour le réinitialiser.

---

## 2. Tableau de bord

Le tableau de bord affiche un résumé de l'activité du festival :

- **Ovations confirmées** — Nombre total d'ovations validées
- **Revenus** — Montant total collecté (en FCFA)
- **Messages non lus** — Contacts en attente de réponse
- **Top candidats** — Classement des candidats par nombre d'ovations
- **Dernières transactions** — Les 10 dernières ovations confirmées
- **Calendrier des votes** — Dates d'ouverture/fermeture et statut actuel
- **Répartition par catégorie** — Graphique des votes par catégorie

---

## 3. Candidats

`Menu : Candidats`

### Ajouter un candidat
1. Cliquez sur **Nouveau candidat**
2. Remplissez les champs :
   - **Nom et prénom(s)** (obligatoire)
   - **Nom de scène** — S'il utilise un nom d'artiste
   - **Catégorie** — Théâtre, Danse, Musique, Percussion, Arts visuels, Stylisme/Modélisme
   - **Numéro de scène** — Ordre de passage (optionnel)
   - **Photo** — 2 Mo max, formats jpeg/png/jpg/gif
   - **Biographie** — 500 caractères max
3. Cliquez sur **Créer**

### Modifier un candidat
Cliquez sur le bouton **Modifier** dans la liste.

### Voir un candidat
Cliquez sur le **nom** du candidat pour voir sa fiche détaillée (photo, biographie, nombre de votes, note du jury).

### Note du jury
Dans la fiche détaillée, vous pouvez attribuer une **note sur 20** au candidat (note technique/artistique). Cette note est utilisée dans le calcul des résultats finaux.

### Supprimer un candidat
Cliquez sur **Supprimer** dans la liste. Cette action est irréversible.

---

## 4. Programme

`Menu : Programme`

Gérez les différentes phases du festival (présélections, finale, etc.).

### Ajouter un programme
1. Cliquez sur **Nouveau programme**
2. Remplissez :
   - **Titre** (obligatoire)
   - **Description** (optionnelle)
   - **Icône** — choisissez une icône Bootstrap pour illustrer
   - **Couleur de bordure** — sélectionnez une couleur
   - **Date** — date et heure de l'événement
   - **Lieu**
   - **Catégorie** (optionnelle, pour filtrer)
   - **Ordre d'affichage**
   - **Actif** — cochez pour afficher sur le site

### Sous-dates (timeline)
Vous pouvez ajouter plusieurs sous-événements à un programme :
- Cliquez sur **Ajouter une date** dans le formulaire
- Saisissez titre, date, lieu pour chaque sous-événement

---

## 5. Ovations (Votes)

`Menu : Ovations` — **Réservé au Super Admin**

### Contrôle des votes
Le panneau de contrôle vous permet de :

- **Voir les dates** d'ouverture et de fermeture des votes (configurées dans Paramètres)
- **Voir la progression** — barre de progression entre la date de début et la date de fin
- **Ouvrir les votes** — cliquez sur « Démarrer les votes » pour lancer la période de vote
- **Fermer les votes** — cliquez sur « Clôturer les votes » pour arrêter. Les résultats sont automatiquement générés pour l'édition en cours.

### Liste des ovations
- Tableau complet avec candidat, email, téléphone, quantité, montant, statut, date
- Filtrez par statut (en attente / confirmé)
- Consultez le détail d'une ovation en cliquant dessus

### Actions
- **Supprimer** une ovation individuelle
- **Tout effacer** — supprime TOUTES les ovations et remet les compteurs à zéro (attention, irréversible)

---

## 6. Résultats

`Menu : Résultats`

### Consultation
Les résultats sont organisés par année d'édition. Cliquez sur une année pour voir le podium par catégorie :
- **1er Prix** — Or
- **2e Prix** — Argent
- **3e Prix** — Bronze

### Notation Jury
1. Cliquez sur le bouton **Noter** à côté d'un résultat
2. Saisissez les notes (total jury : 85 points) :
   - **Technique** (/30)
   - **Originalité** (/25)
   - **Présence** (/20)
   - **Authenticité culturelle** (/10)
3. Le score public (sur 15) est calculé automatiquement à partir des ovations
4. Le score final (sur 100 = jury 85 + public 15) est recalculé automatiquement

### Publication
- **Publier / Dépublier** — basculez la visibilité des résultats sur le site public
- **Régénérer** — supprime et recrée les résultats à partir des votes actuels
- **Supprimer l'édition** — efface tous les résultats d'une année

---

## 7. Partenaires

`Menu : Partenaires`

### Ajouter un partenaire
1. Cliquez sur **Nouveau partenaire**
2. Remplissez :
   - **Nom** (obligatoire)
   - **Logo** — image du logo (optionnel)
   - **Site web** — URL complète (avec https://)
   - **Description** (optionnelle)
   - **Ordre d'affichage** — pour contrôler la position

Les logos des partenaires s'affichent sur la page d'accueil avec un défilement infini. Au survol, le logo passe du gris à la couleur et s'agrandit légèrement.

---

## 8. Soutiens

`Menu : Soutiens`

Ajoutez les personnalités qui soutiennent le festival (parrains, marraines, etc.).

### Ajouter un soutien
1. Cliquez sur **Nouveau soutien**
2. Remplissez :
   - **Nom** (obligatoire)
   - **Photo** (obligatoire)
   - **Titre/qualité** — ex : Ministre de la Culture
   - **Rôle** — Parrain, Marraine, Mécène, etc.

Les soutiens apparaissent dans un carrousel sur la page d'accueil.

---

## 9. Médiathèque

`Menu : Médiathèque`

Gérez les photos et vidéos du festival.

### Ajouter un média
1. Cliquez sur **Nouveau média**
2. Choisissez le **type** :
   - **Photo** — téléchargez un fichier (10 Mo max, jpeg/png/jpg/gif/webp)
   - **Vidéo** — collez un lien YouTube
3. Ajoutez un **titre**, une **description**, et l'**année d'édition**

---

## 10. Messages (Contacts)

`Menu : Messages`

Gérez les messages envoyés depuis le formulaire de contact public.

- **Message non lu** — marqué par un badge rouge
- **Consulter** — cliquez sur un message pour le lire
- **Répondre** — cliquez sur « Répondre », saisissez votre réponse, elle sera envoyée par email à l'expéditeur
- **Supprimer** — supprimez définitivement un message

---

## 11. Paramètres

`Menu : Paramètres` — **Réservé au Super Admin**

Trois catégories de paramètres :

### Contact
- **Téléphone** — numéro affiché sur le site
- **Email** — adresse de contact

### Réseaux sociaux
- Facebook, Instagram, YouTube, TikTok — liens vers les profils

### Contenu du site
- **Texte du héros** — titre principal de la page d'accueil
- **Sous-titre du héros** — phrase d'accroche sous le titre
- **Texte d'information vote** — message affiché sur la page de vote
- **Texte de la médiathèque** — description de la galerie média

> ⚠️ Cliquez sur **Enregistrer** pour sauvegarder tous les paramètres. Le cache est automatiquement vidé.

### Paramètres système (non visibles dans le formulaire)
Ces paramètres sont gérés via les pages dédiées (Ovations, Résultats) :
- **Prix de l'ovation** — 100 FCFA (fixe)
- **Date début/fin des votes**
- **Statut des votes** (off/actif/clôturé)

---

## 12. Utilisateurs

`Menu : Mon Compte > Gérer les utilisateurs` — **Réservé au Super Admin**

### Créer un compte éditeur
1. Cliquez sur **Nouvel utilisateur**
2. Saisissez nom, email et mot de passe
3. Le rôle est automatiquement « Éditeur » (accès limité à la gestion de contenu)

### Types de comptes
| Rôle | Accès |
|------|-------|
| **Super Admin** | Accès complet : contenu, votes, résultats, paramètres, utilisateurs |
| **Éditeur** | Gestion du contenu uniquement : candidats, programme, partenaires, soutiens, médiathèque, messages |

> ⚠️ Vous ne pouvez pas modifier le rôle d'un Super Admin ni le supprimer.

---

## 13. Mon compte

`Menu : Mon Compte`

- Modifiez votre **nom** et **email**
- Changez votre **mot de passe**
- Mettez à jour votre **avatar**
- Gérez vos **sessions actives**

---

## 14. Rôles et permissions

| Fonctionnalité | Éditeur | Super Admin |
|---|---|---|
| Tableau de bord | ✅ | ✅ |
| Candidats (CRUD) | ✅ | ✅ |
| Programme (CRUD) | ✅ | ✅ |
| Partenaires (CRUD) | ✅ | ✅ |
| Soutiens (CRUD) | ✅ | ✅ |
| Médiathèque (CRUD) | ✅ | ✅ |
| Messages (lire/répondre) | ✅ | ✅ |
| Résultats (consulter) | ✅ | ✅ |
| Résultats (noter jury) | ❌ | ✅ |
| Résultats (publier/régénérer) | ❌ | ✅ |
| Ovations (voir/supprimer) | ❌ | ✅ |
| Ovations (ouvrir/fermer) | ❌ | ✅ |
| Paramètres site | ❌ | ✅ |
| Utilisateurs | ❌ | ✅ |
| Informations personnelles | ✅ (profil) | ✅ (profil) |

---

## 15. Dépannage

**Le site public n'affiche pas les changements ?**
→ Vider le cache : `php artisan optimize:clear` sur le serveur

**Un candidat n'apparaît pas sur la page de vote ?**
→ Vérifier que les votes sont ouverts (Paramètres ou menu Ovations)

**Les résultats ne s'affichent pas sur le site ?**
→ Vérifier qu'ils sont publiés (menu Résultats > Publier/Dépublier)

**Le carrousel des partenaires ne défile pas ?**
→ Vérifier que vous avez au moins un partenaire avec un logo

**L'image d'un candidat ne s'affiche pas ?**
→ Vérifier que le fichier a bien été uploadé (2 Mo max)
