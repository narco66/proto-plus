# PROTO PLUS - Application de Gestion des Services du Protocole

## Commission de la CEEAC

Application web Laravel 12 pour la gestion complète, traçable et conforme de tous les actes protocolaires de la Commission de la Communauté Économique des États de l'Afrique Centrale (CEEAC).

---

## 📋 Table des matières

- [Prérequis](#prérequis)
- [Installation](#installation)
- [Configuration](#configuration)
- [Utilisation](#utilisation)
- [Structure du projet](#structure-du-projet)
- [Documentation](#documentation)
- [Tests](#tests)
- [Déploiement](#déploiement)

---

## 🔧 Prérequis

- **PHP** : 8.3+ (minimum 8.2)
- **Composer** : 2.x
- **MySQL** : 8.0+
- **Node.js** : 18+ et npm
- **Extensions PHP** : pdo_mysql, mbstring, xml, openssl, fileinfo, gd

---

## 🚀 Installation

### Windows (Laragon)

1. **Cloner le projet** (ou extraire l'archive)
   ```bash
   cd c:\laragon\www\proto-plus
   ```

2. **Installer les dépendances PHP**
   ```bash
   composer install
   ```

3. **Installer les dépendances JavaScript**
   ```bash
   npm install
   ```

4. **Configurer l'environnement**
   ```bash
   copy .env.example .env
   php artisan key:generate
   ```

5. **Configurer la base de données** dans `.env` :
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=proto_plus
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Créer la base de données**
   ```sql
   CREATE DATABASE proto_plus CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

7. **Exécuter les migrations et seeders**
   ```bash
   php artisan migrate --seed
   ```
   
   **Note :** Les données de test sont automatiquement générées en environnement local. Pour regénérer uniquement les données de test :
   ```bash
   php artisan db:seed --class=TestDataSeeder
   ```

8. **Créer le lien symbolique pour le stockage**
   ```bash
   php artisan storage:link
   ```

9. **Compiler les assets**
   ```bash
   npm run build
   ```

10. **Démarrer le serveur**
    ```bash
    php artisan serve
    ```

L'application sera accessible sur `http://localhost:8000`

### Linux / macOS

```bash
# Installer les dépendances
composer install
npm install

# Configuration
cp .env.example .env
php artisan key:generate

# Configurer .env (DB, MAIL, etc.)

# Base de données
php artisan migrate --seed

# Assets
npm run build

# Serveur
php artisan serve
```

---

## ⚙️ Configuration

### Fichier `.env`

Variables importantes à configurer :

```env
APP_NAME="PROTO PLUS"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=proto_plus
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@ceeac.org"
MAIL_FROM_NAME="${APP_NAME}"

QUEUE_CONNECTION=database
```

> Attention : ce dépôt ne versionne plus `.env`. Copiez `.env.example`, ajustez vos secrets locaux (APP_KEY, DB, mail, etc.) et régénérez la clé (`php artisan key:generate`). N'ajoutez jamais ce fichier au dépôt.

### Comptes par défaut

Après `php artisan migrate --seed`, un compte administrateur est créé :

- **Email** : `admin@ceeac.org`
- **Mot de passe** : `password`
- **Rôle** : Directeur SI

⚠️ **Important** : Changez le mot de passe en production !

---

## 📖 Utilisation

### Accès à l'application

1. Ouvrir `http://localhost:8000`
2. Se connecter avec les identifiants admin
3. Accéder au tableau de bord

### Rôles et permissions

- **Fonctionnaire** : Créer et suivre ses demandes
- **Agent du Protocole** : Instruire les demandes
- **Chef de Service** : Validation niveau 1
- **Directeur du Protocole** : Validation niveau 2
- **Secrétaire Général** : Validation niveau 3
- **Directeur SI** : Administration technique

### Commandes utiles

```bash
# Réinitialiser la base de données
php artisan migrate:fresh --seed

# Créer un utilisateur
php artisan tinker
>>> $user = \App\Models\User::create([...]);
>>> $user->assignRole('fonctionnaire');

# Vider le cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Queue worker (pour notifications, exports)
php artisan queue:work

# Tests
php artisan test
```

---

## 📁 Structure du projet

```
proto-plus/
├── app/
│   ├── Http/
│   │   ├── Controllers/     # Contrôleurs
│   │   ├── Requests/         # FormRequests (validation)
│   │   └── Middleware/       # Middleware personnalisés
│   ├── Models/               # Modèles Eloquent
│   ├── Policies/             # Policies (autorisations)
│   ├── Services/             # Services métier
│   ├── Actions/              # Actions atomiques
│   ├── Jobs/                 # Jobs asynchrones
│   ├── Notifications/        # Notifications
│   └── Observers/            # Observers Eloquent
├── database/
│   ├── migrations/           # Migrations
│   ├── seeders/             # Seeders
│   └── factories/           # Factories
├── resources/
│   ├── views/               # Vues Blade
│   │   ├── layouts/         # Layouts
│   │   └── components/      # Composants Blade
│   ├── css/                 # Styles
│   └── js/                  # JavaScript
├── routes/
│   ├── web.php              # Routes web
│   └── api.php              # Routes API (futur)
├── storage/
│   ├── app/
│   │   ├── documents/        # Documents privés
│   │   └── public/          # Documents publics
│   └── logs/                # Logs
├── tests/                   # Tests
└── docs/                    # Documentation
    ├── ASSUMPTIONS.md
    ├── BACKLOG.md
    ├── DB_SCHEMA.md
    ├── ARCHITECTURE.md
    └── SECURITY.md
```

---

## 📚 Documentation

Documentation complète disponible dans `/docs` :

- **[ASSUMPTIONS.md](docs/ASSUMPTIONS.md)** : Hypothèses et décisions techniques
- **[BACKLOG.md](docs/BACKLOG.md)** : Backlog Agile détaillé
- **[DB_SCHEMA.md](docs/DB_SCHEMA.md)** : Schéma de base de données (MCD/MLD)
- **[ARCHITECTURE.md](docs/ARCHITECTURE.md)** : Architecture technique
- **[SECURITY.md](docs/SECURITY.md)** : Sécurité et conformité

---

## 🧪 Tests

```bash
# Tous les tests
php artisan test

# Tests spécifiques
php artisan test --filter DemandeTest

# Avec couverture (si configuré)
php artisan test --coverage
```

**Couverture minimale cible** : 70% sur modules critiques (auth, permissions, workflow, documents)

**Tests disponibles** :
- ✅ Tests Feature : Demandes, Workflow, Ayants Droit, Sécurité
- ✅ Factories : User, Demande, AyantDroit, WorkflowInstance, WorkflowStepInstance
- ✅ Tests de sécurité : CSRF, XSS, autorisations, cloisonnement des données

Voir [docs/TESTING.md](docs/TESTING.md) pour le guide complet.

---

## 🚢 Déploiement

### Préparation

1. **Optimiser l'application**
   ```bash
   composer install --optimize-autoloader --no-dev
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   npm run build
   ```

2. **Variables d'environnement**
   - Configurer `.env` pour la production
   - `APP_DEBUG=false`
   - `APP_ENV=production`

3. **Permissions**
   ```bash
   chmod -R 755 storage bootstrap/cache
   ```

4. **Base de données**
   ```bash
   php artisan migrate --force
   ```

### Serveur web

- **Apache** : Configuration `.htaccess` incluse
- **Nginx** : Configuration recommandée dans `/docs/DEPLOYMENT.md`

### Queue Worker

En production, utiliser un process manager (Supervisor, systemd) :

```ini
[program:proto-plus-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/proto-plus/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/proto-plus/storage/logs/worker.log
```

---

## 🔒 Sécurité

- ✅ Authentification sécurisée (Laravel Breeze)
- ✅ RBAC (Spatie Permission)
- ✅ Protection CSRF
- ✅ Validation stricte (FormRequests)
- ✅ Upload sécurisé (types, taille, stockage privé)
- ✅ Audit logs (actions critiques)
- ✅ Policies (autorisations granulaires)

Voir [docs/SECURITY.md](docs/SECURITY.md) pour plus de détails.

---

## 📞 Support

Pour toute question ou problème :

1. Consulter la documentation dans `/docs`
2. Vérifier les logs dans `storage/logs/laravel.log`
3. Contacter l'équipe DSI

---

## 📄 Licence

Propriété de la Commission de la CEEAC - Usage interne uniquement

---

## 🎯 Roadmap

### Phase 1 (Actuelle)
- ✅ Authentification et RBAC
- ✅ Gestion des fonctionnaires et ayants droit
- ✅ Gestion des demandes protocolaires
- ✅ Workflow de validation hiérarchique
- ✅ Documents et pièces jointes
- ✅ Notifications
- ✅ Tableaux de bord et reporting
- ✅ Audit et traçabilité

### Phase 2 (Futur)
- MFA (Authentification multifacteur)
- API REST complète
- Broadcast notifications
- Signature électronique
- Intégrations externes

---

**Version** : 1.0.0  
**Dernière mise à jour** : 24 décembre 2025
