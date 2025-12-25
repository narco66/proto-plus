# Guide de Tests - PROTO PLUS

## Date de création
24 décembre 2025

---

## 1. Vue d'ensemble

Les tests de PROTO PLUS sont organisés en **Feature Tests** (tests d'intégration) et **Unit Tests** (tests unitaires). L'objectif est d'assurer la qualité et la fiabilité de l'application.

### 1.1 Couverture Cible

- **Modules critiques** : ≥ 70% (auth, permissions, workflow, documents)
- **Services métier** : ≥ 80%
- **Global** : ≥ 60%

---

## 2. Structure des Tests

### 2.1 Tests Feature

Situés dans `tests/Feature/`, testent les workflows complets :

- **DemandeTest** : CRUD demandes, permissions, validations
- **WorkflowTest** : Validation hiérarchique, transitions d'états
- **AyantDroitTest** : Gestion des ayants droit
- **SecurityTest** : Sécurité, autorisations, CSRF, XSS

### 2.2 Factories

Dans `database/factories/` :

- `UserFactory` : Utilisateurs avec champs personnalisés
- `DemandeFactory` : Demandes avec états (brouillon, soumis, valide)
- `AyantDroitFactory` : Ayants droit
- `WorkflowInstanceFactory` : Instances de workflow
- `WorkflowStepInstanceFactory` : Étapes de workflow

---

## 3. Exécution des Tests

### 3.1 Tous les Tests

```bash
php artisan test
```

### 3.2 Tests Spécifiques

```bash
# Par classe
php artisan test --filter DemandeTest

# Par méthode
php artisan test --filter test_un_fonctionnaire_peut_creer_une_demande

# Par suite
php artisan test tests/Feature
```

### 3.3 Avec Couverture (si configuré)

```bash
php artisan test --coverage
php artisan test --coverage --min=70
```

---

## 4. Configuration

### 4.1 phpunit.xml

- **Base de données** : SQLite en mémoire (`:memory:`)
- **Environnement** : `testing`
- **Cache** : Array (pas de cache réel)

### 4.2 Base de Données de Test

Les tests utilisent SQLite en mémoire pour la rapidité. Les migrations sont exécutées automatiquement via `RefreshDatabase`.

---

## 5. Bonnes Pratiques

### 5.1 Arrange-Act-Assert

```php
/** @test */
public function un_fonctionnaire_peut_creer_une_demande()
{
    // Arrange
    $user = User::factory()->create();
    $user->assignRole('fonctionnaire');
    
    // Act
    $response = $this->actingAs($user)
        ->post(route('demandes.store'), $data);
    
    // Assert
    $response->assertRedirect();
    $this->assertDatabaseHas('demandes', [...]);
}
```

### 5.2 Isolation

- Chaque test est isolé (RefreshDatabase)
- Pas de dépendances entre tests
- Données créées via factories

### 5.3 Nommage

- **Méthodes** : `test_` + description en français
- **Descriptif** : Décrit le comportement attendu

---

## 6. Tests Critiques

### 6.1 Sécurité

- ✅ Protection CSRF
- ✅ Autorisations (403 pour accès non autorisé)
- ✅ Échappement XSS
- ✅ Cloisonnement des données (fonctionnaires voient uniquement leurs données)

### 6.2 Workflow

- ✅ Création d'instance à la soumission
- ✅ Transitions d'états (brouillon → soumis → en_cours → valide)
- ✅ Validation hiérarchique
- ✅ Rejet avec commentaire obligatoire

### 6.3 Permissions

- ✅ Fonctionnaires : leurs demandes uniquement
- ✅ Agents : toutes les demandes
- ✅ Validation : selon rôle requis

---

## 7. Exemples de Tests

### 7.1 Test de Création

```php
/** @test */
public function un_fonctionnaire_peut_creer_une_demande()
{
    $user = User::factory()->create();
    $user->assignRole('fonctionnaire');

    $response = $this->actingAs($user)
        ->get(route('demandes.create'));

    $response->assertStatus(200);
}
```

### 7.2 Test de Permission

```php
/** @test */
public function un_fonctionnaire_ne_peut_pas_voir_les_demandes_des_autres()
{
    $autreUser = User::factory()->create();
    $demande = Demande::factory()->create([
        'demandeur_user_id' => $autreUser->id,
    ]);

    $response = $this->actingAs($this->fonctionnaire)
        ->get(route('demandes.show', $demande));

    $response->assertStatus(403);
}
```

### 7.3 Test de Workflow

```php
/** @test */
public function la_soumission_d_une_demande_cree_une_instance_de_workflow()
{
    $demande = Demande::factory()->create(['statut' => 'brouillon']);

    $this->demandeService->submit($demande, $this->fonctionnaire->id);

    $this->assertDatabaseHas('workflow_instances', [
        'demande_id' => $demande->id,
    ]);
}
```

---

## 8. Débogage

### 8.1 Logs

Les erreurs sont loggées dans `storage/logs/laravel.log` en mode test.

### 8.2 Dump

```php
$response->dump(); // Affiche la réponse
$this->dump($variable); // Affiche une variable
```

### 8.3 Base de Données

Pour inspecter la base après un test :

```php
$this->artisan('migrate:fresh --seed');
// Exécuter le test
// Inspecter la base (ne pas utiliser RefreshDatabase)
```

---

## 9. CI/CD

### 9.1 GitHub Actions (exemple)

```yaml
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.3'
      - name: Install Dependencies
        run: composer install
      - name: Run Tests
        run: php artisan test
```

---

## 10. Améliorations Futures

- [ ] Tests unitaires pour Services
- [ ] Tests de performance (chargement de listes)
- [ ] Tests E2E (Dusk ou Cypress)
- [ ] Couverture de code automatisée
- [ ] Tests de régression automatisés

---

## Document Maintenu Par
- **Auteur** : Équipe de développement PROTO PLUS
- **Dernière mise à jour** : 24 décembre 2025
- **Version** : 1.0


