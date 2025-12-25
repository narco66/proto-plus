# Backlog Agile - PROTO PLUS

## Date de création
24 décembre 2025

## Structure
Ce backlog est organisé en **Épics → User Stories → Critères d'acceptation**, conforme à la méthodologie Agile Scrum définie dans le cahier des charges.

---

## EPIC 1 – Gestion des utilisateurs, authentification et rôles (RBAC)
**Priorité** : MUST HAVE  
**Statut** : En cours

### US-1.1 : Création et gestion des comptes utilisateurs
**En tant que** DSI  
**Je veux** créer, modifier et désactiver des comptes utilisateurs  
**Afin de** maîtriser les accès au système

**Critères d'acceptation** :
- [ ] Interface d'administration des utilisateurs accessible uniquement au rôle `directeur_SI`
- [ ] Création d'un utilisateur avec email unique
- [ ] Modification des informations utilisateur (nom, prénom, fonction, service)
- [ ] Désactivation (soft delete) d'un compte utilisateur
- [ ] Attribution de rôles lors de la création/modification
- [ ] Historique des modifications (audit log)
- [ ] Validation : email valide, mot de passe fort (min 8 caractères, complexité)

**Estimation** : 5 points

---

### US-1.2 : Gestion des rôles et permissions
**En tant que** DSI  
**Je veux** attribuer des rôles et permissions  
**Afin de** garantir la séparation des responsabilités

**Critères d'acceptation** :
- [ ] Interface de gestion des rôles (CRUD)
- [ ] Interface de gestion des permissions (CRUD)
- [ ] Attribution de permissions à un rôle
- [ ] Attribution de rôles à un utilisateur
- [ ] Liste des rôles : `fonctionnaire`, `agent_protocole`, `chef_service_protocole`, `directeur_protocole`, `secretaire_general`, `directeur_SI`
- [ ] Permissions structurées par domaine (demandes.*, documents.*, audit.*, etc.)
- [ ] Vérification des permissions via middleware et policies

**Estimation** : 8 points

---

### US-1.3 : Authentification sécurisée
**En tant qu'** utilisateur  
**Je veux** m'authentifier de manière sécurisée  
**Afin d'** accéder à l'application

**Critères d'acceptation** :
- [ ] Page de connexion (email + mot de passe)
- [ ] Validation des identifiants
- [ ] Protection CSRF
- [ ] Gestion des sessions (expiration configurable)
- [ ] Redirection selon le rôle après connexion
- [ ] Message d'erreur en cas d'échec
- [ ] Journalisation des tentatives de connexion (succès/échec)

**Estimation** : 3 points

---

### US-1.4 : Authentification multifacteur (MFA)
**En tant que** DSI  
**Je veux** imposer le MFA aux profils sensibles  
**Afin de** renforcer la sécurité

**Critères d'acceptation** :
- [ ] Identification des profils sensibles (directeur_protocole, secretaire_general, directeur_SI)
- [ ] Activation MFA obligatoire pour ces profils
- [ ] Génération et envoi de code OTP
- [ ] Validation du code OTP
- [ ] **Note** : À valider avec MOA (implémentation phase 1 ou 2)

**Estimation** : 13 points (complexe)

---

## EPIC 2 – Gestion des fonctionnaires
**Priorité** : MUST HAVE  
**Statut** : À faire

### US-2.1 : Consultation des fiches fonctionnaires
**En tant qu'** agent du Protocole  
**Je veux** consulter la fiche d'un fonctionnaire  
**Afin d'** instruire ses dossiers

**Critères d'acceptation** :
- [ ] Liste des fonctionnaires (pagination, filtres)
- [ ] Fiche détaillée d'un fonctionnaire
- [ ] Affichage des informations : identité, fonction, service, coordonnées
- [ ] Liste des ayants droit associés
- [ ] Historique des demandes du fonctionnaire
- [ ] Accès restreint selon rôle (agent_protocole, chef_service, directeur, etc.)

**Estimation** : 5 points

---

### US-2.2 : Gestion du référentiel des fonctionnaires
**En tant que** DSI  
**Je veux** maintenir un référentiel fiable des fonctionnaires  
**Afin d'** assurer la cohérence des données

**Critères d'acceptation** :
- [ ] CRUD complet des fonctionnaires
- [ ] Validation des données (email unique, champs obligatoires)
- [ ] Historisation des modifications (audit log)
- [ ] Désactivation d'un fonctionnaire (soft delete)
- [ ] Export de la liste (Excel)

**Estimation** : 8 points

---

### US-2.3 : Historique des actes d'un fonctionnaire
**En tant que** responsable  
**Je veux** consulter l'historique des actes d'un fonctionnaire  
**Afin d'** avoir une vision complète

**Critères d'acceptation** :
- [ ] Affichage chronologique des demandes
- [ ] Filtres par type de demande, statut, période
- [ ] Statistiques : nombre total, par statut, par type
- [ ] Export de l'historique (PDF/Excel)

**Estimation** : 5 points

---

## EPIC 3 – Gestion des ayants droit
**Priorité** : MUST HAVE  
**Statut** : À faire

### US-3.1 : Déclaration des ayants droit
**En tant que** fonctionnaire  
**Je veux** déclarer mes ayants droit  
**Afin de** déposer des demandes pour eux

**Critères d'acceptation** :
- [ ] Formulaire de création d'un ayant droit
- [ ] Champs : civilité, nom, prénom, date de naissance, lieu de naissance, lien familial, nationalité, passeport
- [ ] Validation des données (date de naissance valide, passeport si requis)
- [ ] Upload de pièces justificatives (acte de naissance, passeport, etc.)
- [ ] Liste de mes ayants droit
- [ ] Modification et suppression (si pas de demande active)

**Estimation** : 8 points

---

### US-3.2 : Consultation des ayants droit
**En tant qu'** agent du Protocole  
**Je veux** consulter les ayants droit  
**Afin de** vérifier l'éligibilité

**Critères d'acceptation** :
- [ ] Consultation depuis la fiche fonctionnaire
- [ ] Affichage des informations complètes
- [ ] Liste des documents associés
- [ ] Historique des demandes incluant cet ayant droit

**Estimation** : 3 points

---

### US-3.3 : Validation des informations des ayants droit
**En tant que** Chef de Service  
**Je veux** valider les informations des ayants droit  
**Afin de** garantir leur conformité

**Critères d'acceptation** :
- [ ] Interface de validation
- [ ] Commentaires de validation
- [ ] Historique des validations
- [ ] Notification au fonctionnaire en cas de rejet

**Estimation** : 5 points

---

## EPIC 4 – Gestion des demandes protocolaires
**Priorité** : MUST HAVE  
**Statut** : À faire

### US-4.1 : Création d'une demande protocolaire
**En tant que** fonctionnaire  
**Je veux** créer une demande protocolaire  
**Afin d'** initier une procédure officielle

**Critères d'acceptation** :
- [ ] Formulaire de création (wizard multi-étapes)
- [ ] Sélection du type de demande
- [ ] Sélection des bénéficiaires (fonctionnaire + ayants droit)
- [ ] Upload des pièces justificatives
- [ ] Génération automatique de la référence (PROTO-YYYY-NNNNNN)
- [ ] Statut initial : `brouillon`
- [ ] Sauvegarde en brouillon possible
- [ ] Validation de la complétude avant soumission

**Estimation** : 13 points

---

### US-4.2 : Instruction d'une demande
**En tant qu'** agent  
**Je veux** instruire une demande  
**Afin d'** en vérifier la complétude

**Critères d'acceptation** :
- [ ] Liste des demandes à instruire (filtres : statut, type, priorité)
- [ ] Vue détaillée de la demande
- [ ] Vérification des pièces justificatives
- [ ] Ajout d'observations
- [ ] Retour pour correction si incomplet
- [ ] Transmission pour validation si complet
- [ ] Changement de statut : `en_cours`

**Estimation** : 8 points

---

### US-4.3 : Suivi du statut d'une demande
**En tant que** demandeur  
**Je veux** suivre le statut de ma demande  
**Afin d'** être informé

**Critères d'acceptation** :
- [ ] Tableau de bord personnel avec mes demandes
- [ ] Statut visible et clair (badges colorés)
- [ ] Timeline du traitement
- [ ] Historique des actions
- [ ] Notifications automatiques aux changements de statut
- [ ] Téléchargement des documents générés

**Estimation** : 5 points

---

## EPIC 5 – Workflow de validation hiérarchique
**Priorité** : MUST HAVE  
**Statut** : À faire

### US-5.1 : Validation niveau 1 (Chef de Service)
**En tant que** Chef de Service  
**Je veux** valider ou rejeter une demande  
**Afin de** garantir sa conformité

**Critères d'acceptation** :
- [ ] Liste des demandes en attente de validation niveau 1
- [ ] Vue synthétique de la demande
- [ ] Boutons "Valider" / "Rejeter" / "Retour correction"
- [ ] Champ commentaire obligatoire en cas de rejet
- [ ] Horodatage de la décision
- [ ] Notification automatique au demandeur et à l'agent
- [ ] Historique de la décision (non modifiable)
- [ ] Respect de l'ordre hiérarchique (pas de saut d'étape)

**Estimation** : 8 points

---

### US-5.2 : Validation niveau 2 (Directeur du Protocole)
**En tant que** Directeur du Protocole  
**Je veux** arbitrer les dossiers sensibles  
**Afin d'** engager la responsabilité de la Direction

**Critères d'acceptation** :
- [ ] Liste des demandes validées niveau 1
- [ ] Vue complète avec historique
- [ ] Validation / rejet avec motivation
- [ ] Accès aux statistiques du service
- [ ] Notification automatique

**Estimation** : 8 points

---

### US-5.3 : Validation niveau 3 (Secrétaire Général)
**En tant que** Secrétaire Général  
**Je veux** valider les dossiers stratégiques  
**Afin d'** assurer la conformité institutionnelle

**Critères d'acceptation** :
- [ ] Liste des demandes nécessitant validation finale
- [ ] Vue macro avec indicateurs
- [ ] Validation / rejet final
- [ ] Notification automatique
- [ ] Génération automatique des documents officiels après validation

**Estimation** : 8 points

---

## EPIC 6 – Gestion des documents & génération documentaire
**Priorité** : MUST HAVE  
**Statut** : À faire

### US-6.1 : Téléversement de pièces justificatives
**En tant qu'** agent  
**Je veux** téléverser des pièces justificatives  
**Afin de** compléter un dossier

**Critères d'acceptation** :
- [ ] Upload sécurisé (types autorisés : PDF, JPG, PNG, max 10MB)
- [ ] Stockage dans `storage/app/documents` (private)
- [ ] Génération de nom unique (UUID)
- [ ] Calcul du checksum (MD5/SHA256)
- [ ] Association à une demande et un bénéficiaire
- [ ] Prévisualisation des documents
- [ ] Téléchargement sécurisé (via route contrôlée)
- [ ] Journalisation des accès (audit)

**Estimation** : 8 points

---

### US-6.2 : Génération automatique de documents officiels
**En tant que** système  
**Je veux** générer automatiquement une note verbale  
**Afin de** standardiser les documents officiels

**Critères d'acceptation** :
- [ ] Template de note verbale (Blade)
- [ ] Remplissage automatique des placeholders
- [ ] Génération PDF (DomPDF)
- [ ] Numérotation automatique (N° XXX / CEEAC / DP)
- [ ] Stockage dans `storage/app/documents_generes`
- [ ] Versionnement des documents générés
- [ ] Notification au demandeur et aux validateurs

**Estimation** : 13 points

---

### US-6.3 : Consultation des documents générés
**En tant que** responsable  
**Je veux** consulter les documents générés  
**Afin de** les valider

**Critères d'acceptation** :
- [ ] Liste des documents générés par demande
- [ ] Prévisualisation PDF (embed)
- [ ] Téléchargement sécurisé
- [ ] Historique des versions
- [ ] Statut : brouillon, validé, signé

**Estimation** : 5 points

---

## EPIC 7 – Notifications et alertes
**Priorité** : SHOULD HAVE  
**Statut** : À faire

### US-7.1 : Notifications de suivi
**En tant que** demandeur  
**Je veux** recevoir des notifications  
**Afin de** suivre l'évolution de mes dossiers

**Critères d'acceptation** :
- [ ] Notifications in-app (badge compteur)
- [ ] Notifications email (optionnelles, paramétrables)
- [ ] Types : soumission, validation, rejet, retour correction, document généré
- [ ] Marquer comme lu
- [ ] Historique des notifications

**Estimation** : 8 points

---

### US-7.2 : Alertes sur dossiers urgents
**En tant qu'** agent  
**Je veux** recevoir des alertes  
**Afin de** prioriser les dossiers urgents

**Critères d'acceptation** :
- [ ] Alertes pour dossiers prioritaires (`urgent`)
- [ ] Alertes pour dossiers en attente > X jours
- [ ] Alertes pour expiration imminente
- [ ] Dashboard avec indicateurs visuels

**Estimation** : 5 points

---

## EPIC 8 – Tableaux de bord & reporting
**Priorité** : SHOULD HAVE  
**Statut** : À faire

### US-8.1 : Dashboard opérationnel
**En tant que** Directeur du Protocole  
**Je veux** consulter des indicateurs  
**Afin de** piloter l'activité

**Critères d'acceptation** :
- [ ] KPIs : nombre total de demandes, par statut, par type
- [ ] Graphiques : évolution mensuelle, répartition par type
- [ ] Filtres : période (date_start, date_end)
- [ ] Liste des dernières activités
- [ ] Délais moyens de traitement
- [ ] Taux de validation / rejet
- [ ] Export PDF/Excel du dashboard

**Estimation** : 13 points

---

### US-8.2 : Dashboard stratégique
**En tant que** SG  
**Je veux** disposer d'une vision macro  
**Afin de** prendre des décisions stratégiques

**Critères d'acceptation** :
- [ ] Vue consolidée de l'activité
- [ ] Indicateurs stratégiques (volume annuel, tendances)
- [ ] Comparaison N/N-1
- [ ] Points d'alerte
- [ ] Export rapport trimestriel/annuel

**Estimation** : 8 points

---

## EPIC 9 – Audit, traçabilité et conformité
**Priorité** : MUST HAVE  
**Statut** : À faire

### US-9.1 : Consultation des journaux d'audit
**En tant que** DSI  
**Je veux** consulter les journaux d'audit  
**Afin d'** assurer la conformité et la sécurité

**Critères d'acceptation** :
- [ ] Interface de consultation (filtres : user, date, type d'événement)
- [ ] Liste des actions critiques : connexions, modifications, validations, téléchargements
- [ ] Détails : user, IP, user_agent, timestamp, anciennes/nouvelles valeurs
- [ ] Export pour audit externe (CSV/Excel)
- [ ] Accès restreint (rôle `directeur_SI` uniquement)

**Estimation** : 8 points

---

### US-9.2 : Export des logs d'audit
**En tant qu'** auditeur  
**Je veux** exporter les logs  
**Afin de** conduire un audit formel

**Critères d'acceptation** :
- [ ] Export CSV/Excel avec toutes les colonnes
- [ ] Filtres appliqués conservés dans l'export
- [ ] Horodatage de l'export
- [ ] Traçabilité de l'export (qui, quand, quoi)

**Estimation** : 3 points

---

## Définition of Done (DoD) Globale

Une user story est considérée comme terminée si :
- [ ] Les critères d'acceptation sont validés
- [ ] Les tests Feature sont passés (couverture minimale 70% sur modules critiques)
- [ ] Les règles de sécurité sont respectées (validation, autorisations, CSRF)
- [ ] La traçabilité est complète (audit logs sur actions critiques)
- [ ] La documentation est à jour (code commenté, README si nécessaire)
- [ ] La validation MOA est obtenue (recette fonctionnelle)

---

## Priorisation MoSCoW

### Must Have (Phase 1)
- EPIC 1 : Gestion utilisateurs & RBAC
- EPIC 2 : Gestion fonctionnaires
- EPIC 3 : Gestion ayants droit
- EPIC 4 : Gestion demandes
- EPIC 5 : Workflow validation
- EPIC 6 : Documents & génération
- EPIC 9 : Audit & traçabilité

### Should Have (Phase 1 - si temps disponible)
- EPIC 7 : Notifications
- EPIC 8 : Dashboards & reporting

### Could Have (Phase 2)
- MFA complet
- API REST complète
- Broadcast notifications
- Signature électronique

### Won't Have (Phase 1)
- Intégrations externes (administrations nationales)
- Module mobile natif

---

## Estimation Globale (Points Story)

- **EPIC 1** : 29 points
- **EPIC 2** : 18 points
- **EPIC 3** : 16 points
- **EPIC 4** : 26 points
- **EPIC 5** : 24 points
- **EPIC 6** : 26 points
- **EPIC 7** : 13 points
- **EPIC 8** : 21 points
- **EPIC 9** : 11 points

**Total Must Have** : ~154 points  
**Total Should Have** : ~34 points  
**Total Global** : ~188 points

---

## Document Maintenu Par
- **Auteur** : Équipe de développement PROTO PLUS
- **Dernière mise à jour** : 24 décembre 2025
- **Version** : 1.0


