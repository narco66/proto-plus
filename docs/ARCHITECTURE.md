# Architecture Technique - PROTO PLUS

## Date de création
24 décembre 2025

---

## 1. Vue d'ensemble

PROTO PLUS est une application web Laravel 12 construite selon une architecture modulaire, respectant les principes SOLID, DRY et KISS.

### 1.1 Stack Technique

- **Backend** : Laravel 12 (PHP 8.3+)
- **Base de données** : MySQL 8.0+
- **Frontend** : Blade + Bootstrap 5
- **JavaScript** : Alpine.js (léger), Chart.js (graphiques)
- **Authentification** : Laravel Breeze (Blade)
- **Permissions** : Spatie Laravel Permission (RBAC)
- **Exports** : Maatwebsite Excel, DomPDF
- **Notifications** : Laravel Notifications (DB + Mail)

---

## 2. Architecture Applicative

### 2.1 Structure des Dossiers

```
app/
├── Actions/              # Actions atomiques (cas d'usage)
│   └── ValidateDemandeAction.php
├── Http/
│   ├── Controllers/      # Contrôleurs web
│   ├── Requests/         # FormRequests (validation)
│   └── Middleware/       # Middleware personnalisés
├── Models/               # Modèles Eloquent
├── Policies/             # Policies (autorisations)
├── Services/             # Services métier
│   ├── DemandeService.php
│   └── WorkflowService.php
├── Exports/              # Classes d'export Excel
├── Jobs/                 # Jobs asynchrones (futur)
├── Notifications/        # Notifications Laravel (futur)
└── Observers/            # Observers Eloquent (futur)
```

### 2.2 Principes d'Architecture

#### Séparation des Responsabilités
- **Controllers** : Orchestration, redirections, préparation des données pour les vues
- **Services** : Logique métier complexe (création demande, validation workflow)
- **Actions** : Cas d'usage atomiques (valider une demande)
- **FormRequests** : Validation stricte des entrées
- **Policies** : Autorisations granulaires

#### Injection de Dépendances
Tous les services sont injectés via le constructeur :

```php
public function __construct(
    protected DemandeService $demandeService
) {}
```

#### Transactions
Les opérations critiques utilisent des transactions DB :

```php
DB::transaction(function () {
    // Opérations atomiques
});
```

---

## 3. Base de Données

### 3.1 Modèle de Données

Voir [DB_SCHEMA.md](DB_SCHEMA.md) pour le schéma complet.

### 3.2 Relations Eloquent

- **User** → hasMany Demande, hasMany AyantDroit
- **Demande** → belongsTo User, hasMany Document, hasOne WorkflowInstance
- **WorkflowInstance** → hasMany WorkflowStepInstance
- **Document** → belongsTo Demande, belongsTo User (created_by)

### 3.3 Indexation

Index créés sur :
- `demandes.reference` (UNIQUE)
- `demandes.statut`, `demandes.type_demande`
- `workflow_step_instances.assigned_user_id`
- `audit_logs.user_id`, `audit_logs.created_at`

---

## 4. Sécurité

### 4.1 Authentification
- Laravel Breeze (sessions)
- Protection CSRF sur toutes les actions
- Mots de passe hashés (bcrypt)

### 4.2 Autorisations (RBAC)
- **Spatie Permission** : Rôles et permissions
- **Policies Laravel** : Autorisations sur modèles
- **Middleware** : Vérification des permissions dans les routes

### 4.3 Validation
- **FormRequests** : Validation stricte côté serveur
- **Sanitization** : Échappement automatique Blade (XSS)

### 4.4 Upload de Fichiers
- Stockage privé (`storage/app/documents`)
- Validation des types MIME
- Taille maximale configurable
- Noms de fichiers uniques (UUID)
- Checksum pour intégrité

---

## 5. Workflow de Validation

### 5.1 Architecture

Le workflow est modélisé en 4 tables :
1. `workflow_definitions` : Définition du circuit
2. `workflow_step_definitions` : Étapes du circuit
3. `workflow_instances` : Instance sur une demande
4. `workflow_step_instances` : Exécution des étapes

### 5.2 Flux de Validation

1. **Soumission** : Création de l'instance + étapes initialisées
2. **Validation étape** : Mise à jour statut étape → passage à l'étape suivante
3. **Finalisation** : Toutes les étapes validées → demande validée

### 5.3 Service WorkflowService

- `validateStep()` : Valide une étape
- `getNextStep()` : Trouve l'étape suivante
- `getPendingDemandesForUser()` : Liste des demandes à valider

---

## 6. Notifications

### 6.1 Canaux
- **In-app** : Table `notifications` Laravel
- **Email** : SMTP (configuration .env)

### 6.2 Types de Notifications
- `DemandeSoumiseNotification`
- `DemandeValideeNotification`
- `DemandeRejeteeNotification`
- `RetourCorrectionNotification`

---

## 7. Exports

### 7.1 Excel (Maatwebsite)
- Classe `DemandesExport` implémentant `FromCollection`, `WithHeadings`, `WithMapping`
- Export avec filtres appliqués

### 7.2 PDF (DomPDF)
- Template Blade dédié (`resources/views/exports/demandes-pdf.blade.php`)
- Mise en page institutionnelle

---

## 8. Performance

### 8.1 Optimisations
- **Eager Loading** : `with()` pour éviter N+1
- **Indexation** : Index sur colonnes fréquemment filtrées
- **Pagination** : 15 éléments par page (configurable)
- **Cache** : Données de référence (rôles, permissions)

### 8.2 Requêtes Optimisées
```php
// Eager loading
Demande::with(['demandeur', 'beneficiaires', 'documents'])->get();

// Scopes réutilisables
Demande::enCours()->urgentes()->get();
```

---

## 9. Tests

### 9.1 Structure
- **Feature Tests** : Workflows complets
- **Unit Tests** : Services, actions
- **Tests de Sécurité** : Permissions, autorisations

### 9.2 Couverture Cible
- Modules critiques : ≥ 70%
- Services métier : ≥ 80%

---

## 10. Évolutivité

### 10.1 Extensions Futures
- API REST complète (Sanctum)
- Broadcast notifications (Pusher/Redis)
- Signature électronique
- Intégrations externes (administrations nationales)

### 10.2 Modularité
L'architecture permet d'ajouter facilement :
- Nouveaux types de demandes
- Nouvelles étapes de workflow
- Nouveaux canaux de notification
- Nouveaux exports

---

## Document Maintenu Par
- **Auteur** : Équipe de développement PROTO PLUS
- **Dernière mise à jour** : 24 décembre 2025
- **Version** : 1.0


