<h1 align="center"> OURATABLE</h1>

<p align="center">
  <strong>L'art de bien manger</strong> — Plateforme communautaire de partage de recettes de cuisine.<br>
  Construite avec Laravel 9.
</p>

---

##  Présentation

**OURATABLE** est un réseau social culinaire où chacun peut :

-  **Publier ses recettes** (titre, description, instructions, ingrédients, photo)
-  **Donner des coups de cœur** aux recettes et aux publications de la communauté
- **Échanger** via une communauté (publications, commentaires, messagerie privée et groupes de discussion)
-  **Participer aux défis culinaires** générés par IA (DeepSeek, avec mode de secours hors-ligne)
-  **Voter pour la recette de la semaine** — même sans compte (visiteurs anonymes)
-  **Espace administrateur** complet pour gérer toutes les données et communiquer avec les membres

---

##  Fonctionnalités

### Côté utilisateur
| Fonctionnalité | Description |
|---|---|
| Inscription / Connexion | Avec **vérification de l'email** (lien signé envoyé par mail) |
| Mon profil | Infos, badges, niveau/XP, statistiques |
| Coups de cœur | Recettes likées **et** publications likées (+ dernier coup de cœur) — dans la page profil |
| Mes recettes | CRUD complet des recettes avec ingrédients et image |
| Communauté | Publications, commentaires, likes, messagerie privée, groupes, défis IA |
| Recette de la semaine | Classement public des recettes les plus aimées des 7 derniers jours |

### Côté administrateur
| Fonctionnalité | Description |
|---|---|
| Tableau de bord | Statistiques + gestion utilisateurs / recettes / publications / commentaires |
| Permissions totales | L'admin peut supprimer n'importe quelle donnée et promouvoir/rétrograder des membres |
| Email direct | Envoi d'un email à un membre ou à tous (SMTP) |
| Message privé | Apparaît dans la messagerie communauté du membre |
| Annonce communauté | Publication visible par toute la communauté |
| Export CSV | Export de la liste des utilisateurs |

### Anti-redondance (intégrité des données)
- Un utilisateur ne peut liker **qu'une seule fois** une même recette / publication (contrainte unique en base)
- Un membre ne peut rejoindre un groupe qu'une fois
- Un visiteur ne peut voter qu'une fois par recette (empreinte de session/IP)
- Pas de recette en double pour un même utilisateur (même titre)
- Pas de publication en double du même contenu

---

## Stack technique

- **Laravel 9** (PHP 8.2)
- **Base de données : SQLite** par défaut (zéro configuration) — MySQL possible
- **Blade + Tailwind CSS** (CDN) + Alpine.js + Lucide icons
- **Laravel Sanctum**, **libphonenumber** (validation des numéros)
- **DeepSeek API** (optionnelle) pour les défis culinaires

---

##  Installation et lancement local (Windows)

### Prérequis
- **PHP 8.2+** (extensions : `pdo_sqlite`, `mbstring`, `openssl`, `curl`, `fileinfo`, `gd`, `intl`, `zip`)
- **Composer**

> Sur cette machine, PHP est installé dans `C:\php82` et Composer dans `C:\php82\composer.phar`.

### Étapes

```bash
# 1. Installer les dépendances PHP
composer install

# 2. Configuration (le fichier .env est déjà présent et configuré en SQLite)
#    Si besoin : cp .env.example .env  puis  php artisan key:generate

# 3. Créer la base + les tables + les données de démonstration
php artisan migrate:fresh --seed

# 4. Lien symbolique du storage (images)
php artisan storage:link

# 5. Lancer le serveur
php artisan serve
```

L'application est disponible sur **http://127.0.0.1:8000**

> Avec l'installation locale : `C:\php82\php.exe artisan serve`

---

##  Comptes de démonstration

### Administratrice
| Email | Mot de passe |
|---|---|
| `luciarasoanirina8@gmail.com` | `admin1707` |

→ redirigée automatiquement vers `/admin`.

### Utilisateurs (emails déjà vérifiés)
| Nom | Email | Mot de passe |
|---|---|---|
| Anniah | `anniah@ouratable.mg` | `123456` |
| Mbolatiana | `mbolatiana@ouratable.mg` | `123456` |
| Joba | `joba@ouratable.mg` | `123456` |
| Lorraine | `lorraine@ouratable.mg` | `123456` |
| Genitah | `genitah@ouratable.mg` | `123456` |

---

##  Configuration de l'email

L'envoi d'emails (vérification de compte, messages admin) utilise un compte Gmail (SMTP) configuré dans `.env` :

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=luciarasoanirina8@gmail.com
MAIL_ENCRYPTION=tls
```

> Pour les défis IA en ligne, renseignez `DEEPSEEK_API_KEY` dans `.env` (sinon un mode de secours fournit des défis variés).

---

## Passer sur MySQL (optionnel)

Dans `.env`, remplacez la connexion SQLite par :

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=recette_db
DB_USERNAME=root
DB_PASSWORD=
```

Créez la base `recette_db` puis relancez `php artisan migrate:fresh --seed`.

---

##  Structure du projet

```
app/
  Http/Controllers/   AuthController, RecetteController, UserRecetteController,
                      CommunityController, ProfileController, AdminController,
                      StatsController, VerificationController
  Models/             User, Recette, Ingredient, Post, Comment, Like, RecipeVote,
                      Conversation, Message, ChatGroup, GroupMessage, Challenge, AiChallenge
  Mail/               OuratableVerificationMail, AdminMessageMail
  Http/Middleware/    IsAdmin
database/
  migrations/         16 migrations (schéma complet)
  seeders/            DatabaseSeeder (admin + 5 membres + données de démo)
resources/views/
  layouts/            header, UserHeader, AdminHeader, footer, UserFooter,
                      AdminFooter, partials/weekly-recipe
  page/               home, login, UserHome, UserCommunity, community, profile,
                      recettes, admin, recette-semaine
```

---

## Licence

Projet sous licence MIT.
