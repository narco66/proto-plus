# Documentation du Workflow de Validation - PROTO PLUS

## Vue d'ensemble

Le workflow de validation permet de gérer le processus hiérarchique de validation des demandes protocolaires. Il est basé sur un système d'étapes séquentielles où chaque étape doit être validée avant de passer à la suivante.

## Architecture

### Modèles

1. **WorkflowDefinition** : Définition d'un workflow (ex: STANDARD)
2. **WorkflowStepDefinition** : Définition d'une étape dans un workflow
3. **WorkflowInstance** : Instance d'un workflow pour une demande spécifique
4. **WorkflowStepInstance** : Instance d'une étape pour une demande spécifique

### Flux de validation

```
1. Soumission de la demande
   ↓
2. Création de l'instance de workflow
   ↓
3. Création des instances d'étapes (première étape = 'a_faire', autres = 'skipped')
   ↓
4. Notification des utilisateurs concernés par la première étape
   ↓
5. Validation de l'étape (valide/rejete/retour_correction)
   ↓
6. Si validé → Activation de l'étape suivante + Notification
   ↓
7. Si rejeté → Fin du workflow + Notification demandeur
   ↓
8. Si retour correction → Demande en brouillon + Notification demandeur
```

## Statuts des étapes

- **`a_faire`** : Étape en attente de validation
- **`en_traitement`** : Étape en cours de traitement (assignée à un utilisateur)
- **`valide`** : Étape validée
- **`rejete`** : Étape rejetée (entraîne le rejet de la demande)
- **`retour_correction`** : Retournée pour correction
- **`skipped`** : Étape non encore activée (en attente)

## Workflow Standard

Le workflow standard comprend 4 étapes :

1. **Instruction par agent du Protocole** (ordre 1, obligatoire)
   - Rôle requis : `agent_protocole`
   - Délai cible : 3 jours

2. **Validation niveau 1 - Chef de Service** (ordre 2, obligatoire)
   - Rôle requis : `chef_service`
   - Délai cible : 2 jours

3. **Validation niveau 2 - Directeur du Protocole** (ordre 3, obligatoire)
   - Rôle requis : `directeur_protocole`
   - Délai cible : 2 jours

4. **Validation niveau 3 - Secrétaire Général** (ordre 4, optionnel)
   - Rôle requis : `secretaire_general`
   - Délai cible : 3 jours

## Services

### DemandeService

- **`submit()`** : Soumet une demande et crée l'instance de workflow
  - Crée l'instance de workflow
  - Crée les instances d'étapes (première = 'a_faire', autres = 'skipped')
  - Envoie les notifications aux utilisateurs concernés

### WorkflowService

- **`validateStep()`** : Valide une étape du workflow
  - Met à jour le statut de l'étape
  - Active l'étape suivante si validation réussie
  - Met à jour le statut de la demande
  - Envoie les notifications appropriées
  - Crée l'entrée d'historique

- **`getNextStep()`** : Récupère la prochaine étape obligatoire
- **`notifyNextStep()`** : Notifie les utilisateurs de l'étape suivante
- **`notifyDemandeur()`** : Notifie le demandeur d'un changement de statut

### ValidateDemandeAction

- **`execute()`** : Action atomique pour valider une demande
  - Trouve l'étape en attente pour l'utilisateur
  - Vérifie les permissions
  - Assigne l'utilisateur à l'étape
  - Appelle WorkflowService::validateStep()

## Notifications

### DemandeEnAttenteValidation

Envoyée aux utilisateurs concernés par une étape en attente :
- Lors de la soumission d'une demande
- Lors de la validation d'une étape précédente

### DemandeStatutChanged

Envoyée au demandeur lors d'un changement de statut :
- Validation finale de la demande
- Rejet de la demande
- Retour pour correction

## Vues

### workflow/index.blade.php

Liste des demandes en attente de validation pour l'utilisateur connecté, filtrées par :
- Type de demande
- Priorité

### workflow/show.blade.php

Page de validation d'une demande avec :
- Informations de la demande
- Timeline du workflow (étapes avec statuts visuels)
- Bénéficiaires
- Documents joints
- Historique
- Formulaire de décision (Valider/Rejeter/Retour correction)

### demandes/show.blade.php

Affichage d'une demande avec timeline du workflow intégrée.

## Utilisation

### Pour un fonctionnaire

1. Créer une demande (statut : brouillon)
2. Soumettre la demande (statut : soumis)
3. Le workflow démarre automatiquement
4. Recevoir des notifications sur l'avancement

### Pour un validateur

1. Se connecter et aller dans "Workflow"
2. Voir les demandes en attente de validation
3. Cliquer sur "Valider" pour une demande
4. Choisir la décision (Valider/Rejeter/Retour correction)
5. Ajouter un commentaire si nécessaire
6. Enregistrer la décision

## Permissions

- `workflow.view` : Voir les demandes à valider
- `demandes.validate_level_1` : Valider au niveau 1
- `demandes.validate_level_2` : Valider au niveau 2
- `demandes.validate_level_3` : Valider au niveau 3

## Tests

Les tests du workflow sont disponibles dans `tests/Feature/WorkflowTest.php` :
- Création d'instance de workflow lors de la soumission
- Visibilité des demandes à valider selon les rôles
- Validation des étapes
- Passage à l'étape suivante


