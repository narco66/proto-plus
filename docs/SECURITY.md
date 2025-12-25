# Sécurité et Conformité - PROTO PLUS

## Date de création
24 décembre 2025

---

## 1. Vue d'ensemble

PROTO PLUS traite des données sensibles (données personnelles, statut diplomatique, documents officiels). La sécurité est intégrée dès la conception (Security by Design).

---

## 2. Authentification

### 2.1 Mécanismes
- **Laravel Breeze** : Authentification par session
- **Mots de passe** : Hashage bcrypt (Laravel par défaut)
- **Sessions** : Gestion sécurisée (expiration, régénération)

### 2.2 Politique de Mots de Passe
- Longueur minimale : 8 caractères (configurable)
- Complexité : Recommandée (majuscules, minuscules, chiffres, symboles)
- Rotation : Non implémentée en phase 1 (à prévoir)

### 2.3 Authentification Multifacteur (MFA)
- **Statut** : Non implémenté en phase 1
- **Recommandation** : Laravel Fortify ou package équivalent
- **Profils concernés** : Directeur Protocole, SG, DSI

---

## 3. Contrôle d'Accès (RBAC)

### 3.1 Spatie Laravel Permission
- **Rôles** : fonctionnaire, agent_protocole, chef_service, directeur_protocole, secretaire_general, directeur_SI
- **Permissions** : Granulaires par domaine (demandes.*, documents.*, audit.*)

### 3.2 Policies Laravel
- `DemandePolicy` : Autorisations sur les demandes
- `DocumentPolicy` : Autorisations sur les documents
- `AyantDroitPolicy` : Autorisations sur les ayants droit

### 3.3 Principe du Moindre Privilège
- Chaque utilisateur reçoit uniquement les permissions nécessaires
- Séparation stricte : rôles métiers ≠ rôles techniques

---

## 4. Protection des Données

### 4.1 Données Sensibles
- **Champs sensibles** : Passeport, données personnelles
- **Chiffrement** : En transit (HTTPS/TLS), au repos (optionnel, voir ASSUMPTIONS.md)

### 4.2 Cloisonnement Logique
- Fonctionnaires : Voient uniquement leurs demandes
- Agents : Voient toutes les demandes (selon permission)
- Hiérarchie : Voit selon périmètre de validation

### 4.3 Masquage des Données
- Affichage conditionnel selon rôle
- Documents confidentiels : Permission `documents.view_sensitive`

---

## 5. Protection Web (OWASP)

### 5.1 CSRF
- Protection activée sur toutes les actions (Laravel par défaut)
- Token CSRF dans tous les formulaires

### 5.2 XSS
- Échappement automatique Blade (`{{ }}`)
- Validation stricte des entrées utilisateur

### 5.3 SQL Injection
- Requêtes paramétrées (Eloquent)
- Pas de requêtes SQL brutes avec concaténation

### 5.4 IDOR (Insecure Direct Object Reference)
- Vérification des permissions via Policies
- Validation que l'utilisateur peut accéder à la ressource

### 5.5 Rate Limiting
- À configurer sur endpoints sensibles (login, validation)
- Configuration dans `app/Http/Kernel.php`

---

## 6. Upload de Fichiers

### 6.1 Validation
- **Types autorisés** : PDF, JPG, PNG (configurable)
- **Taille maximale** : 10MB (configurable)
- **Validation MIME** : Vérification du type réel

### 6.2 Stockage
- **Disque privé** : `storage/app/documents`
- **Accès** : Via routes contrôlées (jamais en accès direct)
- **Noms uniques** : UUID pour éviter collisions

### 6.3 Intégrité
- **Checksum** : MD5 ou SHA256 calculé à l'upload
- **Versionnement** : Champ `version` pour documents modifiés

---

## 7. Audit et Traçabilité

### 7.1 Journal d'Audit
- **Table** : `audit_logs`
- **Événements tracés** :
  - Connexions/déconnexions
  - Créations/modifications/suppressions
  - Validations/rejets
  - Téléchargements de documents sensibles

### 7.2 Historique Métier
- **Table** : `historique_demandes`
- **Actions tracées** : création, soumission, validation, rejet, etc.
- **Horodatage** : Toutes les actions sont horodatées

### 7.3 Données d'Audit
- **IP** : Adresse IP de l'utilisateur
- **User Agent** : Navigateur/système
- **Anciennes/Nouvelles valeurs** : JSON pour comparaison

---

## 8. Conformité RGPD

### 8.1 Principes
- **Minimisation** : Collecte uniquement des données nécessaires
- **Finalité** : Finalités clairement définies
- **Conservation** : Durées paramétrables

### 8.2 Droits des Personnes
- **Consultation** : Accès aux données personnelles
- **Rectification** : Modification des données
- **Suppression** : Suppression logique (soft delete)

### 8.3 Journalisation des Accès
- Tous les accès aux données personnelles sont journalisés
- Export possible pour audits externes

---

## 9. Sauvegarde et Continuité

### 9.1 Sauvegardes
- **Base de données** : Quotidienne (automatisée)
- **Documents** : Quotidienne (automatisée)
- **Rétention** : 30 jours (configurable)

### 9.2 Plan de Reprise d'Activité (PRA)
- Procédures documentées
- Tests périodiques de restauration

---

## 10. Recommandations

### 10.1 Production
- **HTTPS obligatoire** : Configuration serveur web
- **Headers de sécurité** : HSTS, CSP, X-Frame-Options
- **Monitoring** : Surveillance des tentatives d'intrusion
- **MFA** : Implémentation pour profils sensibles

### 10.2 Maintenance
- **Mises à jour** : Laravel et packages régulièrement
- **Audit de sécurité** : Périodique (trimestriel recommandé)
- **Revue des permissions** : Annuelle

---

## Document Maintenu Par
- **Auteur** : Équipe de développement PROTO PLUS
- **Dernière mise à jour** : 24 décembre 2025
- **Version** : 1.0


