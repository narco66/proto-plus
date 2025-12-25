# Gestion des Erreurs - PROTO PLUS

## Vue d'ensemble

L'application utilise une gestion d'erreurs centralisée et conforme aux meilleures pratiques Laravel, avec des exceptions personnalisées, des messages clairs et un affichage cohérent.

## Architecture

### Exceptions personnalisées

#### 1. `WorkflowException`
Exception pour les erreurs liées au workflow de validation.

**Utilisation :**
```php
throw new WorkflowException('Message d\'erreur', 422);
```

**Caractéristiques :**
- Code HTTP par défaut : 422 (Unprocessable Entity)
- Log automatique des erreurs
- Support JSON et HTML
- Redirection avec message d'erreur

#### 2. `DemandeException`
Exception pour les erreurs liées aux demandes.

**Utilisation :**
```php
throw new DemandeException('Message d\'erreur', 422);
```

**Caractéristiques :**
- Code HTTP par défaut : 422
- Log automatique
- Support JSON et HTML

#### 3. `PermissionException`
Exception pour les erreurs de permissions.

**Utilisation :**
```php
throw new PermissionException('Message d\'erreur');
```

**Caractéristiques :**
- Code HTTP : 403 (Forbidden)
- Log avec user_id
- Support JSON et HTML

### Configuration globale

**`bootstrap/app.php`** :
- Gestion centralisée des exceptions
- Réponses JSON pour les API
- Réponses HTML pour les vues web
- Log automatique des exceptions non gérées

**Types d'exceptions gérées :**
- `ValidationException` : Erreurs de validation (422)
- `AuthenticationException` : Non authentifié (401)
- `AuthorizationException` : Non autorisé (403)
- `NotFoundHttpException` : Ressource introuvable (404)

### Contrôleur de base

**`app/Http/Controllers/Controller.php`** :
Méthodes utilitaires pour gérer les réponses :

- `successResponse()` : Réponse de succès
- `errorResponse()` : Réponse d'erreur
- `handleException()` : Gestion centralisée des exceptions

## Utilisation dans les Services

### Exemple : WorkflowService

```php
use App\Exceptions\WorkflowException;

if (!in_array($decision, ['valide', 'rejete', 'retour_correction'])) {
    throw new WorkflowException('La décision doit être : valide, rejete ou retour_correction.', 422);
}
```

### Exemple : DemandeService

```php
use App\Exceptions\DemandeException;

if ($demande->statut !== 'brouillon') {
    throw new DemandeException('Seules les demandes en brouillon peuvent être soumises.', 422);
}
```

## Utilisation dans les Contrôleurs

### Pattern recommandé

```php
public function store(Request $request)
{
    try {
        // Logique métier
        return $this->successResponse('Opération réussie', $data);
    } catch (\App\Exceptions\DemandeException $e) {
        return $this->errorResponse($e->getMessage(), $e->getCode());
    } catch (\Exception $e) {
        \Log::error('Erreur non gérée', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        return $this->errorResponse('Une erreur est survenue. Veuillez réessayer.', 500);
    }
}
```

## Affichage dans les Vues

### Composant Alert

**`resources/views/components/alert.blade.php`** :
- Affiche les messages de session (success, error, warning, info)
- Affiche les erreurs de validation
- Style cohérent avec Bootstrap 5
- Auto-dismissible

**Utilisation :**
```blade
<x-alert />
```

### Composant Form Error

**`resources/views/components/form-error.blade.php`** :
- Affiche les erreurs de validation pour un champ spécifique

**Utilisation :**
```blade
<input type="text" class="form-control @error('name') is-invalid @enderror" name="name">
<x-form-error field="name" />
```

## Messages d'erreur

### Messages utilisateur

Les messages doivent être :
- **Clairs** : Compréhensibles par l'utilisateur final
- **Actionnables** : Indiquer ce qui peut être fait
- **Courts** : Maximum 2-3 phrases
- **Professionnels** : Ton formel mais accessible

### Exemples de bons messages

✅ **Bon :**
- "Seules les demandes en brouillon peuvent être soumises."
- "Vous n'avez pas le rôle requis pour valider cette étape. Rôle requis : Chef de Service."
- "Une erreur est survenue lors de la création. Veuillez réessayer ou contacter l'administrateur."

❌ **Mauvais :**
- "Error 500"
- "Exception occurred"
- "Invalid state"

## Logging

### Niveaux de log

- **ERROR** : Erreurs critiques nécessitant une intervention
- **WARNING** : Erreurs métier (exceptions personnalisées)
- **INFO** : Informations importantes (workflow, validations)

### Format des logs

```php
\Log::error('Contexte de l\'erreur', [
    'user_id' => $userId,
    'demande_id' => $demandeId,
    'error' => $e->getMessage(),
    'trace' => $e->getTraceAsString(),
]);
```

## Bonnes pratiques

### 1. Utiliser les exceptions personnalisées

✅ **Bon :**
```php
throw new DemandeException('Message clair', 422);
```

❌ **Mauvais :**
```php
throw new \Exception('Erreur');
```

### 2. Gérer les exceptions dans les contrôleurs

✅ **Bon :**
```php
try {
    // Logique
} catch (DemandeException $e) {
    return $this->errorResponse($e->getMessage());
} catch (\Exception $e) {
    \Log::error(...);
    return $this->errorResponse('Message générique');
}
```

### 3. Logger les erreurs non gérées

Toujours logger les exceptions non gérées avec le contexte nécessaire.

### 4. Ne pas exposer les détails techniques

Les messages d'erreur pour l'utilisateur ne doivent pas contenir :
- Stack traces
- Noms de fichiers
- Numéros de ligne
- Détails techniques

### 5. Messages d'erreur contextuels

Les messages doivent être adaptés au contexte :
- Erreur de validation : Indiquer les champs concernés
- Erreur de permission : Indiquer le rôle/permission requis
- Erreur métier : Indiquer la règle métier violée

## Tests

Les exceptions personnalisées doivent être testées :

```php
#[Test]
public function test_workflow_exception_renders_correctly(): void
{
    $exception = new WorkflowException('Test error', 422);
    $response = $exception->render(request());
    
    $this->assertInstanceOf(RedirectResponse::class, $response);
}
```

## Références

- [Laravel Exception Handling](https://laravel.com/docs/errors)
- [Best Practices for Error Handling](https://laravel.com/docs/errors#exception-handling)


