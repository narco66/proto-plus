# Guide des Factories - PROTO PLUS

## Date de création
24 décembre 2025

---

## 1. Vue d'ensemble

Les factories Laravel permettent de générer facilement des données de test réalistes. Toutes les factories sont situées dans `database/factories/`.

---

## 2. Factories Disponibles

### 2.1 UserFactory

**Modèle** : `App\Models\User`

**Champs générés** :
- `name`, `firstname`, `email`
- `phone`, `status`, `function`, `department`, `service`, `matricule`

**États disponibles** :
- `unverified()` : Email non vérifié
- `actif()` : Statut actif
- `inactif()` : Statut inactif

**Exemple** :
```php
$user = User::factory()->create();
$userActif = User::factory()->actif()->create();
```

---

### 2.2 DemandeFactory

**Modèle** : `App\Models\Demande`

**Champs générés** :
- `reference` : Format DEM-XXXX-XXXX
- `type_demande` : visa_diplomatique, carte_diplomatique, franchise_douaniere
- `statut` : brouillon, soumis, en_cours, valide, rejete
- `priorite` : normal, urgent
- `motif`, `date_depart_prevue`, `pays_destination`

**États disponibles** :
- `brouillon()` : Demande en brouillon
- `soumis()` : Demande soumise
- `valide()` : Demande validée

**Exemple** :
```php
$demande = Demande::factory()->brouillon()->create();
$demandeSoumise = Demande::factory()->soumis()->create();
```

---

### 2.3 AyantDroitFactory

**Modèle** : `App\Models\AyantDroit`

**Champs générés** :
- `civilite` : M., Mme, Mlle
- `nom`, `prenom`
- `date_naissance`, `lieu_naissance`
- `lien_familial` : conjoint, enfant, autre
- `nationalite`, `passeport_num`, `passeport_expire_at`
- `status` : actif

**États disponibles** :
- `actif()` : Statut actif

**Exemple** :
```php
$ayantDroit = AyantDroit::factory()->create();
```

---

### 2.4 DemandeBeneficiaireFactory

**Modèle** : `App\Models\DemandeBeneficiaire`

**Champs générés** :
- `beneficiaire_type` : fonctionnaire ou ayant_droit
- `beneficiaire_id` : ID selon le type
- `role_dans_demande` : principal, accompagnant, autre

**États disponibles** :
- `fonctionnaire()` : Bénéficiaire est un fonctionnaire
- `ayantDroit()` : Bénéficiaire est un ayant droit

**Exemple** :
```php
$beneficiaire = DemandeBeneficiaire::factory()->fonctionnaire()->create();
```

---

### 2.5 DocumentFactory

**Modèle** : `App\Models\Document`

**Champs générés** :
- `type_document` : passeport, carte_identite, acte_naissance, etc.
- `nom_fichier`, `chemin_fichier`
- `mime_type`, `taille`, `checksum`
- `confidentiel`, `version`

**États disponibles** :
- `pdf()` : Document PDF
- `image()` : Document image (JPG/PNG)

**Exemple** :
```php
$document = Document::factory()->pdf()->create();
```

---

### 2.6 WorkflowDefinitionFactory

**Modèle** : `App\Models\WorkflowDefinition`

**Champs générés** :
- `code` : Format WF-XXXX
- `libelle`, `actif`, `version`

**États disponibles** :
- `standard()` : Workflow standard
- `inactif()` : Workflow inactif

**Exemple** :
```php
$workflow = WorkflowDefinition::factory()->standard()->create();
```

---

### 2.7 WorkflowStepDefinitionFactory

**Modèle** : `App\Models\WorkflowStepDefinition`

**Champs générés** :
- `ordre`, `libelle`
- `role_requis` : agent_protocole, chef_service, etc.
- `delai_cible_jours`, `obligatoire`

**États disponibles** :
- `obligatoire()` : Étape obligatoire
- `optionnel()` : Étape optionnelle

**Exemple** :
```php
$step = WorkflowStepDefinition::factory()->obligatoire()->create();
```

---

### 2.8 WorkflowInstanceFactory

**Modèle** : `App\Models\WorkflowInstance`

**Champs générés** :
- `statut` : en_cours, termine, annule
- `started_at`, `ended_at`

**États disponibles** :
- `enCours()` : Instance en cours
- `termine()` : Instance terminée
- `avecWorkflowStandard()` : Utilise le workflow standard

**Exemple** :
```php
$instance = WorkflowInstance::factory()->avecWorkflowStandard()->create();
```

---

### 2.9 WorkflowStepInstanceFactory

**Modèle** : `App\Models\WorkflowStepInstance`

**Champs générés** :
- `statut` : a_faire, en_traitement, valide, rejete, retour_correction
- `assigned_user_id`, `decided_by`, `decision_at`, `commentaire`

**États disponibles** :
- `aFaire()` : Étape à faire
- `enTraitement()` : Étape en traitement
- `valide()` : Étape validée
- `rejete()` : Étape rejetée

**Exemple** :
```php
$stepInstance = WorkflowStepInstance::factory()->aFaire()->create();
```

---

### 2.10 HistoriqueDemandeFactory

**Modèle** : `App\Models\HistoriqueDemande`

**Champs générés** :
- `action` : creation, modification, soumission, validation, rejet, etc.
- `auteur_id`, `commentaire`

**États disponibles** :
- `creation()` : Action création
- `soumission()` : Action soumission
- `validation()` : Action validation
- `rejet()` : Action rejet

**Exemple** :
```php
$historique = HistoriqueDemande::factory()->validation()->create();
```

---

## 3. Utilisation dans les Tests

### 3.1 Création Simple

```php
$user = User::factory()->create();
$demande = Demande::factory()->create();
```

### 3.2 Création avec Relations

```php
$user = User::factory()->create();
$demande = Demande::factory()->for($user, 'demandeur')->create();
$ayantDroit = AyantDroit::factory()->for($user, 'fonctionnaire')->create();
```

### 3.3 Création Multiple

```php
$users = User::factory()->count(10)->create();
$demandes = Demande::factory()->count(5)->brouillon()->create();
```

### 3.4 Création avec Attributs Personnalisés

```php
$user = User::factory()->create([
    'email' => 'test@example.com',
    'status' => 'actif',
]);
```

---

## 4. Bonnes Pratiques

1. **Utiliser les états** : Préférer `->brouillon()` plutôt que `->create(['statut' => 'brouillon'])`
2. **Relations** : Utiliser `->for()` pour créer des relations
3. **Données réalistes** : Les factories génèrent des données cohérentes
4. **Tests isolés** : Chaque test crée ses propres données

---

## Document Maintenu Par
- **Auteur** : Équipe de développement PROTO PLUS
- **Dernière mise à jour** : 24 décembre 2025
- **Version** : 1.0


