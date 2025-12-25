# Schéma de Base de Données - PROTO PLUS

## Date de création
24 décembre 2025

## Vue d'ensemble
Ce document décrit le Modèle Conceptuel de Données (MCD) et le Modèle Logique de Données (MLD) de l'application PROTO PLUS, conforme au cahier des charges enrichi.

---

## 1. Modèle Conceptuel de Données (MCD)

### 1.1 Entités Principales

#### USER (Utilisateurs)
Représente tous les acteurs ayant un compte dans l'application (fonctionnaires, agents, hiérarchie).

**Attributs** :
- `id` (PK)
- `name` (nom)
- `firstname` (prénom)
- `email` (unique)
- `phone` (téléphone)
- `status` (actif/inactif)
- `function` (fonction)
- `department` (direction)
- `service` (service)
- `matricule` (optionnel)
- `photo` (chemin fichier)
- `password` (hashé)
- `last_login_at` (timestamp)
- `created_at`, `updated_at`

#### AYANT_DROIT (Ayants Droit)
Bénéficiaires rattachés à un fonctionnaire (conjoint, enfant, dépendant).

**Attributs** :
- `id` (PK)
- `fonctionnaire_user_id` (FK → users.id)
- `civilite` (M./Mme)
- `nom`
- `prenom`
- `date_naissance`
- `lieu_naissance`
- `lien_familial` (conjoint, enfant, autre)
- `nationalite`
- `passeport_num`
- `passeport_expire_at`
- `photo` (chemin fichier)
- `status` (actif/inactif)
- `created_at`, `updated_at`

#### DEMANDE (Demandes Protocolaires)
Dossier protocolaire initié par un fonctionnaire.

**Attributs** :
- `id` (PK)
- `reference` (unique, format: PROTO-YYYY-NNNNNN)
- `type_demande` (enum: visa_diplomatique, visa_courtoisie, visa_familial, carte_diplomatique, carte_consulaire, franchise_douaniere, immatriculation_diplomatique, autorisation_entree, autorisation_sortie)
- `demandeur_user_id` (FK → users.id)
- `statut` (enum: brouillon, soumis, en_cours, valide, rejete, expire, annule, cloture)
- `date_soumission` (nullable)
- `date_validation` (nullable)
- `date_rejet` (nullable)
- `motif_rejet` (nullable, texte)
- `date_expiration` (nullable)
- `priorite` (enum: normal, urgent)
- `canal` (varchar, défaut: interne)
- `created_at`, `updated_at`

#### DEMANDE_BENEFICIAIRE (Pivot)
Association entre une demande et ses bénéficiaires (fonctionnaire ou ayant droit).

**Attributs** :
- `id` (PK)
- `demande_id` (FK → demandes.id)
- `beneficiaire_type` (enum: fonctionnaire, ayant_droit)
- `beneficiaire_id` (int, FK selon type)
- `role_dans_demande` (enum: principal, secondaire)
- `commentaire` (nullable)
- `created_at`, `updated_at`

#### DOCUMENT (Documents & Pièces Jointes)
Documents associés aux demandes (pièces justificatives).

**Attributs** :
- `id` (PK)
- `demande_id` (FK → demandes.id)
- `beneficiaire_type` (enum: fonctionnaire, ayant_droit)
- `beneficiaire_id` (int)
- `type_document` (varchar: passeport, acte_naissance, autre)
- `nom_fichier` (nom original)
- `chemin_fichier` (chemin stockage)
- `mime_type`
- `taille` (bytes)
- `checksum` (MD5/SHA256)
- `confidentiel` (bool, défaut: false)
- `version` (int, défaut: 1)
- `created_by` (FK → users.id)
- `created_at`, `updated_at`

#### DOCUMENT_GENERE (Documents Générés)
Documents officiels générés automatiquement (notes verbales, lettres).

**Attributs** :
- `id` (PK)
- `demande_id` (FK → demandes.id)
- `type_modele` (enum: note_verbale, lettre_immigration, autre)
- `numero` (varchar, ex: N° 123 / CEEAC / DP)
- `fichier_path` (chemin PDF)
- `signe` (bool, défaut: false)
- `date_generation` (timestamp)
- `generated_by` (FK → users.id)
- `created_at`, `updated_at`

#### WORKFLOW_DEFINITION (Définition de Workflow)
Définition d'un circuit de validation (ex: workflow standard, workflow urgent).

**Attributs** :
- `id` (PK)
- `code` (unique, ex: VISA, CARTE_DIPLO)
- `libelle` (nom du workflow)
- `actif` (bool, défaut: true)
- `version` (int, défaut: 1)
- `created_at`, `updated_at`

#### WORKFLOW_STEP_DEFINITION (Étapes du Workflow)
Étapes d'un workflow (instruction, validation niveau 1, etc.).

**Attributs** :
- `id` (PK)
- `workflow_definition_id` (FK → workflow_definitions.id)
- `ordre` (int, ordre d'exécution)
- `libelle` (nom de l'étape)
- `role_requis` (varchar, ex: agent_protocole, chef_service_protocole)
- `delai_cible_jours` (int, nullable)
- `obligatoire` (bool, défaut: true)
- `created_at`, `updated_at`
- **Contrainte unique** : (workflow_definition_id, ordre)

#### WORKFLOW_INSTANCE (Instance de Workflow)
Exécution d'un workflow sur une demande spécifique.

**Attributs** :
- `id` (PK)
- `demande_id` (FK → demandes.id, unique)
- `workflow_definition_id` (FK → workflow_definitions.id)
- `statut` (enum: en_cours, termine, annule)
- `started_at` (timestamp)
- `ended_at` (nullable, timestamp)
- `created_at`, `updated_at`

#### WORKFLOW_STEP_INSTANCE (Instance d'Étape)
Exécution d'une étape du workflow.

**Attributs** :
- `id` (PK)
- `workflow_instance_id` (FK → workflow_instances.id)
- `step_definition_id` (FK → workflow_step_definitions.id)
- `statut` (enum: a_faire, en_traitement, valide, rejete, retour_correction, skipped)
- `assigned_role` (varchar)
- `assigned_user_id` (FK → users.id, nullable)
- `decided_by` (FK → users.id, nullable)
- `decision_at` (nullable, timestamp)
- `commentaire` (nullable, texte)
- `created_at`, `updated_at`

#### HISTORIQUE_DEMANDE (Historique Métier)
Journal fonctionnel lisible retraçant les étapes du traitement.

**Attributs** :
- `id` (PK)
- `demande_id` (FK → demandes.id)
- `action` (varchar: creation, soumission, modif, validation, rejet, generation_doc, etc.)
- `auteur_id` (FK → users.id)
- `commentaire` (nullable, texte)
- `created_at`

#### AUDIT_LOG (Journal d'Audit)
Journal technique exhaustif pour conformité et sécurité.

**Attributs** :
- `id` (PK)
- `event_type` (varchar: login, logout, create, update, delete, validate, reject, download, etc.)
- `user_id` (FK → users.id, nullable)
- `ip` (varchar)
- `user_agent` (varchar)
- `cible_type` (varchar, ex: App\Models\Demande)
- `cible_id` (int)
- `old_values` (json, nullable)
- `new_values` (json, nullable)
- `created_at`

#### NOTIFICATION (Notifications)
Notifications in-app et email (table Laravel native + extension si besoin).

**Attributs** (Laravel standard) :
- `id` (PK)
- `notifiable_type` (varchar)
- `notifiable_id` (int)
- `type` (varchar, classe notification)
- `data` (json)
- `read_at` (nullable, timestamp)
- `created_at`

---

### 1.2 Relations et Cardinalités

```
User (fonctionnaire) 1 ──── N AyantDroit
User (demandeur) 1 ──── N Demande
Demande 1 ──── N DemandeBeneficiaire
Demande 1 ──── N Document
Demande 1 ──── 1 WorkflowInstance
Demande 1 ──── N DocumentGenere
Demande 1 ──── N HistoriqueDemande
WorkflowDefinition 1 ──── N WorkflowStepDefinition
WorkflowInstance 1 ──── N WorkflowStepInstance
User 1 ──── N Notification
User 1 ──── N AuditLog
```

---

## 2. Modèle Logique de Données (MLD) - MySQL

### 2.1 Tables Cœur Métier

#### users
```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    firstname VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(50) NULL,
    status ENUM('actif', 'inactif') DEFAULT 'actif',
    function VARCHAR(255) NULL,
    department VARCHAR(255) NULL,
    service VARCHAR(255) NULL,
    matricule VARCHAR(50) NULL,
    photo VARCHAR(255) NULL,
    password VARCHAR(255) NOT NULL,
    last_login_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_email (email),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### ayant_droits
```sql
CREATE TABLE ayant_droits (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fonctionnaire_user_id BIGINT UNSIGNED NOT NULL,
    civilite ENUM('M.', 'Mme', 'Mlle') DEFAULT 'M.',
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    date_naissance DATE NULL,
    lieu_naissance VARCHAR(255) NULL,
    lien_familial ENUM('conjoint', 'enfant', 'autre') NOT NULL,
    nationalite VARCHAR(100) NULL,
    passeport_num VARCHAR(50) NULL,
    passeport_expire_at DATE NULL,
    photo VARCHAR(255) NULL,
    status ENUM('actif', 'inactif') DEFAULT 'actif',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (fonctionnaire_user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_fonctionnaire (fonctionnaire_user_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### demandes
```sql
CREATE TABLE demandes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    reference VARCHAR(50) UNIQUE NOT NULL,
    type_demande ENUM(
        'visa_diplomatique',
        'visa_courtoisie',
        'visa_familial',
        'carte_diplomatique',
        'carte_consulaire',
        'franchise_douaniere',
        'immatriculation_diplomatique',
        'autorisation_entree',
        'autorisation_sortie'
    ) NOT NULL,
    demandeur_user_id BIGINT UNSIGNED NOT NULL,
    statut ENUM(
        'brouillon',
        'soumis',
        'en_cours',
        'valide',
        'rejete',
        'expire',
        'annule',
        'cloture'
    ) DEFAULT 'brouillon',
    date_soumission TIMESTAMP NULL,
    date_validation TIMESTAMP NULL,
    date_rejet TIMESTAMP NULL,
    motif_rejet TEXT NULL,
    date_expiration DATE NULL,
    priorite ENUM('normal', 'urgent') DEFAULT 'normal',
    canal VARCHAR(50) DEFAULT 'interne',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (demandeur_user_id) REFERENCES users(id) ON DELETE RESTRICT,
    INDEX idx_reference (reference),
    INDEX idx_statut (statut),
    INDEX idx_type_demande (type_demande),
    INDEX idx_demandeur (demandeur_user_id),
    INDEX idx_date_soumission (date_soumission)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### demande_beneficiaires
```sql
CREATE TABLE demande_beneficiaires (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    demande_id BIGINT UNSIGNED NOT NULL,
    beneficiaire_type ENUM('fonctionnaire', 'ayant_droit') NOT NULL,
    beneficiaire_id BIGINT UNSIGNED NOT NULL,
    role_dans_demande ENUM('principal', 'secondaire') DEFAULT 'principal',
    commentaire TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (demande_id) REFERENCES demandes(id) ON DELETE CASCADE,
    INDEX idx_demande (demande_id),
    INDEX idx_beneficiaire (beneficiaire_type, beneficiaire_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### documents
```sql
CREATE TABLE documents (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    demande_id BIGINT UNSIGNED NOT NULL,
    beneficiaire_type ENUM('fonctionnaire', 'ayant_droit') NULL,
    beneficiaire_id BIGINT UNSIGNED NULL,
    type_document VARCHAR(100) NOT NULL,
    nom_fichier VARCHAR(255) NOT NULL,
    chemin_fichier VARCHAR(500) NOT NULL,
    mime_type VARCHAR(100) NULL,
    taille BIGINT UNSIGNED NULL,
    checksum VARCHAR(64) NULL,
    confidentiel BOOLEAN DEFAULT FALSE,
    version INT UNSIGNED DEFAULT 1,
    created_by BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (demande_id) REFERENCES demandes(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE RESTRICT,
    INDEX idx_demande (demande_id),
    INDEX idx_created_by (created_by),
    INDEX idx_confidentiel (confidentiel)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### documents_generes
```sql
CREATE TABLE documents_generes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    demande_id BIGINT UNSIGNED NOT NULL,
    type_modele ENUM('note_verbale', 'lettre_immigration', 'autre') NOT NULL,
    numero VARCHAR(100) NOT NULL,
    fichier_path VARCHAR(500) NOT NULL,
    signe BOOLEAN DEFAULT FALSE,
    date_generation TIMESTAMP NOT NULL,
    generated_by BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (demande_id) REFERENCES demandes(id) ON DELETE CASCADE,
    FOREIGN KEY (generated_by) REFERENCES users(id) ON DELETE RESTRICT,
    INDEX idx_demande (demande_id),
    INDEX idx_type_modele (type_modele)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 2.2 Tables Workflow

#### workflow_definitions
```sql
CREATE TABLE workflow_definitions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) UNIQUE NOT NULL,
    libelle VARCHAR(255) NOT NULL,
    actif BOOLEAN DEFAULT TRUE,
    version INT UNSIGNED DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_code (code),
    INDEX idx_actif (actif)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### workflow_step_definitions
```sql
CREATE TABLE workflow_step_definitions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    workflow_definition_id BIGINT UNSIGNED NOT NULL,
    ordre INT UNSIGNED NOT NULL,
    libelle VARCHAR(255) NOT NULL,
    role_requis VARCHAR(100) NOT NULL,
    delai_cible_jours INT UNSIGNED NULL,
    obligatoire BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (workflow_definition_id) REFERENCES workflow_definitions(id) ON DELETE CASCADE,
    UNIQUE KEY unique_workflow_ordre (workflow_definition_id, ordre),
    INDEX idx_workflow (workflow_definition_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### workflow_instances
```sql
CREATE TABLE workflow_instances (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    demande_id BIGINT UNSIGNED UNIQUE NOT NULL,
    workflow_definition_id BIGINT UNSIGNED NOT NULL,
    statut ENUM('en_cours', 'termine', 'annule') DEFAULT 'en_cours',
    started_at TIMESTAMP NOT NULL,
    ended_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (demande_id) REFERENCES demandes(id) ON DELETE CASCADE,
    FOREIGN KEY (workflow_definition_id) REFERENCES workflow_definitions(id) ON DELETE RESTRICT,
    INDEX idx_demande (demande_id),
    INDEX idx_statut (statut)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### workflow_step_instances
```sql
CREATE TABLE workflow_step_instances (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    workflow_instance_id BIGINT UNSIGNED NOT NULL,
    step_definition_id BIGINT UNSIGNED NOT NULL,
    statut ENUM(
        'a_faire',
        'en_traitement',
        'valide',
        'rejete',
        'retour_correction',
        'skipped'
    ) DEFAULT 'a_faire',
    assigned_role VARCHAR(100) NULL,
    assigned_user_id BIGINT UNSIGNED NULL,
    decided_by BIGINT UNSIGNED NULL,
    decision_at TIMESTAMP NULL,
    commentaire TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (workflow_instance_id) REFERENCES workflow_instances(id) ON DELETE CASCADE,
    FOREIGN KEY (step_definition_id) REFERENCES workflow_step_definitions(id) ON DELETE RESTRICT,
    FOREIGN KEY (assigned_user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (decided_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_workflow_instance (workflow_instance_id),
    INDEX idx_statut (statut),
    INDEX idx_assigned_user (assigned_user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 2.3 Tables Traçabilité & Audit

#### historique_demandes
```sql
CREATE TABLE historique_demandes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    demande_id BIGINT UNSIGNED NOT NULL,
    action VARCHAR(100) NOT NULL,
    auteur_id BIGINT UNSIGNED NOT NULL,
    commentaire TEXT NULL,
    created_at TIMESTAMP NULL,
    FOREIGN KEY (demande_id) REFERENCES demandes(id) ON DELETE CASCADE,
    FOREIGN KEY (auteur_id) REFERENCES users(id) ON DELETE RESTRICT,
    INDEX idx_demande (demande_id),
    INDEX idx_auteur (auteur_id),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### audit_logs
```sql
CREATE TABLE audit_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    event_type VARCHAR(100) NOT NULL,
    user_id BIGINT UNSIGNED NULL,
    ip VARCHAR(45) NULL,
    user_agent VARCHAR(500) NULL,
    cible_type VARCHAR(255) NULL,
    cible_id BIGINT UNSIGNED NULL,
    old_values JSON NULL,
    new_values JSON NULL,
    created_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user (user_id),
    INDEX idx_event_type (event_type),
    INDEX idx_created_at (created_at),
    INDEX idx_cible (cible_type, cible_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### notifications
```sql
-- Table Laravel native (créée automatiquement par migration Laravel)
CREATE TABLE notifications (
    id CHAR(36) PRIMARY KEY,
    type VARCHAR(255) NOT NULL,
    notifiable_type VARCHAR(255) NOT NULL,
    notifiable_id BIGINT UNSIGNED NOT NULL,
    data TEXT NOT NULL,
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    INDEX idx_notifiable (notifiable_type, notifiable_id),
    INDEX idx_read_at (read_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 2.4 Tables RBAC (Spatie Laravel Permission)

Les tables suivantes sont créées automatiquement par le package `spatie/laravel-permission` :
- `roles`
- `permissions`
- `model_has_roles`
- `model_has_permissions`
- `role_has_permissions`

Voir documentation Spatie pour le schéma exact.

---

## 3. Index et Optimisations

### 3.1 Index Recommandés (déjà inclus dans les CREATE TABLE ci-dessus)

**Tables critiques** :
- `demandes.reference` : UNIQUE (recherche rapide)
- `demandes.statut` : INDEX (filtres fréquents)
- `demandes.type_demande` : INDEX (filtres)
- `demandes.demandeur_user_id` : INDEX (jointures)
- `demande_beneficiaires.demande_id` : INDEX (jointures)
- `workflow_step_instances.assigned_user_id` : INDEX (mes tâches)
- `audit_logs.user_id` : INDEX (audit par utilisateur)
- `audit_logs.created_at` : INDEX (filtres temporels)

### 3.2 Contraintes d'Intégrité

- **Référence unique** : `demandes.reference` (UNIQUE)
- **Workflow ordre** : `workflow_step_definitions` (UNIQUE workflow_definition_id + ordre)
- **Workflow instance unique** : `workflow_instances.demande_id` (UNIQUE)
- **Foreign Keys** : Toutes les relations sont contraintes avec ON DELETE CASCADE ou RESTRICT selon la logique métier

---

## 4. Règles de Gestion Techniques

### 4.1 Génération de Référence
- Format : `PROTO-YYYY-NNNNNN`
- Génération : Automatique à la création, incrémentale par année
- Unicité : Contrainte UNIQUE en base

### 4.2 Workflow
- Une demande validée = toutes les étapes obligatoires "valide"
- Respect strict de l'ordre hiérarchique (pas de saut d'étape)
- Toute décision est horodatée et non modifiable

### 4.3 Documents
- Stockage : `storage/app/documents` (private)
- Accès : Via routes contrôlées par policies
- Checksum : Calculé à l'upload (MD5 ou SHA256)
- Versionnement : Champ `version` incrémenté à chaque modification

### 4.4 Audit
- Toute action critique écrit dans `audit_logs`
- IP et user_agent enregistrés pour traçabilité
- Anciennes/nouvelles valeurs en JSON pour comparaison

---

## 5. Évolutions Futures

### 5.1 Extensions Possibles
- Table `profil_fonctionnaires` (séparation compte/données RH)
- Table `document_requirements` (pièces obligatoires par type de demande)
- Table `quota_demandes` (limites par fonctionnaire/type)
- Table `signatures_electroniques` (si signature électronique implémentée)

### 5.2 Optimisations
- Partitionnement de `audit_logs` par mois (si volume important)
- Archive des demandes clôturées > X années
- Index composites selon requêtes fréquentes

---

## Document Maintenu Par
- **Auteur** : Équipe de développement PROTO PLUS
- **Dernière mise à jour** : 24 décembre 2025
- **Version** : 1.0


