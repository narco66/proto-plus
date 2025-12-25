# Hypothèses et Assumptions - PROTO PLUS

## Date de création
24 décembre 2025

## Contexte
Ce document liste les hypothèses et décisions prises lors du développement de l'application PROTO PLUS, lorsque des informations manquaient dans le cahier des charges.

---

## 1. Hypothèses Techniques

### 1.1 Framework et Versions
- **Laravel** : Version 12 (confirmé dans composer.json)
- **PHP** : Version 8.3+ (minimum 8.2 requis, hypothèse 8.3+ pour compatibilité)
- **MySQL** : Version 8.0+
- **Frontend** : Bootstrap 5 (choisi par défaut, le cahier des charges mentionne Bootstrap ou Tailwind)

### 1.2 Packages et Dépendances
- **Authentification** : Laravel Breeze (Blade) - installé
- **Permissions** : spatie/laravel-permission - installé
- **Graphiques** : Chart.js (à installer via npm)
- **Export Excel** : maatwebsite/excel (à installer)
- **Export PDF** : barryvdh/laravel-dompdf (à installer)
- **Notifications** : Système natif Laravel (notifications DB + Mail)
- **Upload** : FilePond optionnel (à installer si besoin UX)

### 1.3 Architecture
- **Structure** : Approche modulaire simple (Option A du cahier des charges)
- **Services** : Organisation par domaine dans `app/Services/`
- **Actions** : Cas d'usage atomiques dans `app/Actions/`
- **Observers** : Pour audit automatique sur événements Eloquent

---

## 2. Hypothèses Métier

### 2.1 Gestion des Fonctionnaires
- **Hypothèse** : Tous les utilisateurs (agents, chefs, directeurs) sont aussi des fonctionnaires dans le système
- **Justification** : Le cahier des charges mentionne que les utilisateurs peuvent être des fonctionnaires ou des agents. On considère qu'un utilisateur peut avoir un rôle métier (fonctionnaire) et un rôle applicatif (agent_protocole, etc.)
- **Implémentation** : Le modèle `User` sert de base, avec un champ `fonction` pour distinguer les profils

### 2.2 Numérotation des Demandes
- **Format** : `PROTO-YYYY-NNNNNN` (ex: PROTO-2025-000123)
- **Génération** : Automatique à la création, incrémentale par année
- **Unicité** : Contrainte unique sur `reference`

### 2.3 Workflow de Validation
- **Hypothèse** : Workflow standard pour tous les types de demandes (sauf exceptions futures)
- **Étapes par défaut** :
  1. Brouillon (fonctionnaire)
  2. Soumis (fonctionnaire)
  3. En instruction (agent protocole)
  4. Validation niveau 1 (chef de service)
  5. Validation niveau 2 (directeur protocole)
  6. Validation niveau 3 (secrétaire général) - optionnel selon type
  7. Validé / Rejeté
  8. Clôturé

### 2.4 Types de Demandes
- **Liste standardisée** :
  - `visa_diplomatique`
  - `visa_courtoisie`
  - `visa_familial`
  - `carte_diplomatique`
  - `carte_consulaire`
  - `franchise_douaniere`
  - `immatriculation_diplomatique`
  - `autorisation_entree`
  - `autorisation_sortie`

### 2.5 Statuts des Demandes
- **Énumération** :
  - `brouillon`
  - `soumis`
  - `en_cours`
  - `valide`
  - `rejete`
  - `expire`
  - `annule`
  - `cloture`

### 2.6 Priorités
- **Valeurs** : `normal`, `urgent`
- **Défaut** : `normal`

---

## 3. Hypothèses Sécurité

### 3.1 Authentification Multifacteur (MFA)
- **Hypothèse** : MFA non implémenté en phase 1 (mentionné comme "obligatoire" mais complexité technique)
- **Justification** : Le cahier des charges mentionne MFA obligatoire pour certains profils, mais l'implémentation complète nécessite des packages additionnels (Laravel Fortify ou équivalent)
- **Note** : À implémenter en phase 2 si requis par la MOA

### 3.2 Stockage des Documents
- **Disque privé** : `storage/app/documents` (private)
- **Disque public** : `storage/app/public` pour documents non sensibles (avec lien symbolique)
- **Sécurité** : Accès via routes contrôlées par policies, jamais en accès direct

### 3.3 Chiffrement des Données
- **En transit** : HTTPS/TLS (géré par le serveur web)
- **Au repos** : Champs sensibles (passeport, etc.) peuvent être chiffrés avec `encrypted` cast dans les modèles si nécessaire
- **Hypothèse initiale** : Pas de chiffrement au repos en phase 1 (à valider avec DSI)

---

## 4. Hypothèses Interface Utilisateur

### 4.1 Design System
- **Framework CSS** : Bootstrap 5
- **Couleurs institutionnelles** : Palette sobre (bleu foncé, blanc, gris) - à adapter selon charte CEEAC
- **Icônes** : Bootstrap Icons (inclus avec Bootstrap 5)

### 4.2 Composants Réutilisables
- `<x-alert>` : Messages flash
- `<x-card>` : Cartes de contenu
- `<x-badge-status>` : Badges de statut colorés
- `<x-breadcrumbs>` : Fil d'Ariane
- `<x-datatable-actions>` : Actions sur lignes de tableau

### 4.3 Responsive
- **Priorité** : Desktop first
- **Breakpoints** : Bootstrap 5 standard (sm, md, lg, xl, xxl)

---

## 5. Hypothèses Données et Performance

### 5.1 Pagination
- **Taille par défaut** : 15 éléments par page
- **Configurable** : Via paramètre de requête `per_page` (max 100)

### 5.2 Cache
- **Données de référence** : Rôles, permissions, types de demandes (cache 24h)
- **KPIs Dashboard** : Cache 1 heure (recalcul périodique)

### 5.3 Indexation Base de Données
- **Index recommandés** :
  - `demandes.reference` (unique)
  - `demandes.statut`
  - `demandes.type_demande`
  - `demandes.demandeur_user_id`
  - `demande_beneficiaires.demande_id`
  - `workflow_step_instances.assigned_user_id`
  - `audit_logs.user_id`
  - `audit_logs.created_at`

---

## 6. Hypothèses Notifications

### 6.1 Canaux
- **In-app** : Table `notifications` Laravel (obligatoire)
- **Email** : SMTP institutionnel (configuration .env)
- **Broadcast** : Non implémenté en phase 1 (optionnel selon cahier des charges)

### 6.2 Types de Notifications
- `demande_soumise`
- `demande_validee`
- `demande_rejetee`
- `demande_retour_correction`
- `document_genere`
- `relance_delai`
- `expiration_imminente`

---

## 7. Hypothèses Exports et Rapports

### 7.1 Formats
- **Excel** : `.xlsx` (maatwebsite/excel)
- **PDF** : `.pdf` (barryvdh/laravel-dompdf)

### 7.2 Rapports Disponibles
- Liste des demandes (filtrable)
- Rapport mensuel d'activité
- Rapport trimestriel/annuel (stratégique)
- Journal d'audit (exportable)
- Rapport RGPD (accès données personnelles)

### 7.3 Génération
- **Mode** : Asynchrone (Jobs) pour gros volumes
- **Stockage** : Temporaire (7 jours) dans `storage/app/exports`

---

## 8. Hypothèses Tests

### 8.1 Couverture Minimale
- **Objectif** : 70% sur modules critiques (auth, permissions, workflow, documents)
- **Types** : Tests Feature (workflows complets) prioritaires

### 8.2 Scénarios Critiques à Tester
- Authentification et autorisations
- Création et soumission de demande
- Workflow de validation (tous les niveaux)
- Upload et téléchargement de documents
- Génération de documents officiels
- Exports PDF/Excel
- Audit logs

---

## 9. Hypothèses Déploiement

### 9.1 Environnements
- **DEV** : Local (Windows/Linux)
- **REC** : Serveur de recette (à configurer)
- **PROD** : Serveur de production (à configurer)

### 9.2 Configuration
- **Fichiers .env** : Séparés par environnement (jamais versionnés)
- **Secrets** : Stockage sécurisé (Vault ou mécanisme DSI)

### 9.3 Sauvegardes
- **Base de données** : Quotidienne (automatisée)
- **Documents** : Quotidienne (automatisée)
- **Rétention** : 30 jours (configurable)

---

## 10. Points à Valider avec la MOA

1. **MFA** : Implémentation en phase 1 ou phase 2 ?
2. **Chiffrement au repos** : Nécessaire pour quels champs ?
3. **Couleurs institutionnelles** : Palette exacte CEEAC ?
4. **Format numérotation** : `PROTO-YYYY-NNNNNN` validé ?
5. **Durée de conservation** : Politique exacte pour documents et données ?
6. **SMTP** : Serveur et configuration email institutionnelle ?
7. **Logo CEEAC** : Fichier logo officiel disponible ?

---

## 11. Évolutions Futures (Hors Phase 1)

- Intégration avec systèmes externes (administrations nationales)
- API REST complète pour mobile/SPA
- Broadcast notifications en temps réel
- Signature électronique des documents
- Interopérabilité avec SIRH futur
- Module de gestion des quotas par type de demande

---

## Document Maintenu Par
- **Auteur** : Équipe de développement PROTO PLUS
- **Dernière mise à jour** : 24 décembre 2025
- **Version** : 1.0


