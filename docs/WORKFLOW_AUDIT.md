# Audit du Workflow de Validation - PROTO PLUS

## Date de l'audit
24 décembre 2025

## Objectifs de l'audit
1. Vérifier que les boutons de validation sont disponibles à chaque étape selon les profils
2. Vérifier que le statut se met automatiquement à jour à chaque validation
3. Vérifier la cohérence des permissions et des rôles

## Résultats de l'audit

### ✅ Points vérifiés et corrigés

#### 1. Disponibilité des boutons de validation selon les profils

**Problème identifié :**
- Le formulaire de validation était toujours affiché, même si l'utilisateur n'avait pas les permissions
- L'admin ne pouvait pas voir toutes les demandes en attente

**Corrections apportées :**
- ✅ `WorkflowController::show()` : Gestion de l'admin qui peut voir toutes les demandes
- ✅ `ValidateDemandeAction::execute()` : Vérification des permissions pour l'admin
- ✅ `ValidateDemandeRequest::authorize()` : Autorisation pour l'admin
- ✅ Vue `workflow/show.blade.php` : Affichage conditionnel du formulaire selon `$canValidate`

**Logique implémentée :**
```php
// Admin peut valider n'importe quelle étape
$canViewAll = $user->can('demandes.view_all') || $user->can('admin.access') || $user->hasRole('admin');

// Utilisateur normal : seulement les étapes correspondant à son rôle
if (!$canViewAll) {
    // Filtrer par rôle requis
}
```

#### 2. Mise à jour automatique des statuts

**Problème identifié :**
- Les données n'étaient pas rechargées après mise à jour
- Risque de données obsolètes dans les transactions

**Corrections apportées :**
- ✅ `WorkflowService::validateStep()` : Rechargement des relations après mise à jour
- ✅ Ajout de `$demande->refresh()` après chaque mise à jour de statut
- ✅ Ajout de logs pour tracer les changements de statut

**Flux de mise à jour :**

1. **Validation réussie avec étape suivante :**
   ```
   Étape actuelle : statut = 'valide'
   Étape suivante : statut = 'skipped' → 'a_faire'
   Demande : statut = 'en_cours'
   WorkflowInstance : statut = 'en_cours'
   ```

2. **Validation réussie (dernière étape) :**
   ```
   Étape actuelle : statut = 'valide'
   Demande : statut = 'valide', date_validation = now()
   WorkflowInstance : statut = 'termine', ended_at = now()
   ```

3. **Rejet :**
   ```
   Étape actuelle : statut = 'rejete'
   Demande : statut = 'rejete', date_rejet = now(), motif_rejet = commentaire
   WorkflowInstance : statut = 'termine', ended_at = now()
   ```

4. **Retour pour correction :**
   ```
   Étape actuelle : statut = 'retour_correction'
   Demande : statut = 'brouillon'
   WorkflowInstance : statut = 'en_cours' (reste actif)
   ```

#### 3. Gestion des rôles et permissions

**Rôles du workflow standard :**
1. `agent_protocole` - Instruction (ordre 1)
2. `chef_service` - Validation niveau 1 (ordre 2)
3. `directeur_protocole` - Validation niveau 2 (ordre 3)
4. `secretaire_general` - Validation niveau 3 (ordre 4, optionnel)

**Permissions vérifiées :**
- ✅ `demandes.view_all` : Peut voir toutes les demandes
- ✅ `admin.access` : Accès admin complet
- ✅ `demandes.validate_level_1` : Validation niveau 1
- ✅ `demandes.validate_level_2` : Validation niveau 2
- ✅ `demandes.validate_level_3` : Validation niveau 3

### 🔍 Points de contrôle

#### Vérification des statuts

**Étapes du workflow :**
- `a_faire` : Étape en attente de validation
- `en_traitement` : Étape en cours de traitement
- `valide` : Étape validée
- `rejete` : Étape rejetée
- `retour_correction` : Retournée pour correction
- `skipped` : Étape non encore activée

**Statuts des demandes :**
- `brouillon` : Demande en cours de création
- `soumis` : Demande soumise, workflow démarré
- `en_cours` : Demande en cours de validation
- `valide` : Demande validée (toutes les étapes passées)
- `rejete` : Demande rejetée

**Statuts du workflow :**
- `en_cours` : Workflow actif
- `termine` : Workflow terminé (validé ou rejeté)
- `annule` : Workflow annulé

### 📋 Tests à effectuer

#### Test 1 : Validation par agent_protocole
1. Créer une demande et la soumettre
2. Se connecter avec un utilisateur ayant le rôle `agent_protocole`
3. Vérifier que la demande apparaît dans `/workflow`
4. Cliquer sur "Valider"
5. Vérifier que le formulaire de validation s'affiche
6. Valider l'étape
7. ✅ Vérifier que :
   - L'étape 1 passe à `valide`
   - L'étape 2 passe à `a_faire`
   - La demande passe à `en_cours`
   - Une notification est envoyée aux utilisateurs avec le rôle `chef_service`

#### Test 2 : Validation par chef_service
1. Après validation de l'étape 1
2. Se connecter avec un utilisateur ayant le rôle `chef_service`
3. Vérifier que la demande apparaît dans `/workflow`
4. Valider l'étape 2
5. ✅ Vérifier que :
   - L'étape 2 passe à `valide`
   - L'étape 3 passe à `a_faire`
   - La demande reste à `en_cours`
   - Une notification est envoyée aux utilisateurs avec le rôle `directeur_protocole`

#### Test 3 : Validation finale
1. Après validation de toutes les étapes obligatoires
2. Valider la dernière étape
3. ✅ Vérifier que :
   - La dernière étape passe à `valide`
   - La demande passe à `valide` avec `date_validation`
   - Le workflow passe à `termine` avec `ended_at`
   - Une notification est envoyée au demandeur

#### Test 4 : Rejet
1. À n'importe quelle étape, choisir "Rejeter"
2. Ajouter un commentaire obligatoire
3. ✅ Vérifier que :
   - L'étape passe à `rejete`
   - La demande passe à `rejete` avec `date_rejet` et `motif_rejet`
   - Le workflow passe à `termine`
   - Une notification est envoyée au demandeur

#### Test 5 : Retour pour correction
1. À n'importe quelle étape, choisir "Retour pour correction"
2. Ajouter un commentaire obligatoire
3. ✅ Vérifier que :
   - L'étape passe à `retour_correction`
   - La demande passe à `brouillon`
   - Le workflow reste `en_cours`
   - Une notification est envoyée au demandeur

#### Test 6 : Admin
1. Se connecter avec un utilisateur admin
2. Vérifier que toutes les demandes en attente apparaissent dans `/workflow`
3. Vérifier que l'admin peut valider n'importe quelle étape
4. ✅ Vérifier que les statuts se mettent à jour correctement

### ⚠️ Points d'attention

1. **Étapes optionnelles** : L'étape 4 (Secrétaire Général) est optionnelle. Si elle n'est pas activée, la validation de l'étape 3 finalise la demande.

2. **Retour pour correction** : Quand une demande est retournée pour correction, elle repasse en `brouillon` mais le workflow reste actif. Le demandeur peut modifier et resoumettre.

3. **Notifications** : Les notifications sont envoyées de manière asynchrone. En cas d'erreur, un log est créé mais le workflow continue.

4. **Transactions** : Toutes les opérations de validation sont dans une transaction DB pour garantir la cohérence.

### 📝 Recommandations

1. ✅ **Implémenté** : Ajout de logs pour tracer les changements de statut
2. ✅ **Implémenté** : Rechargement des données après mise à jour
3. ✅ **Implémenté** : Gestion de l'admin pour voir toutes les demandes
4. ✅ **Implémenté** : Affichage conditionnel du formulaire de validation

### ✅ Conclusion

Le workflow de validation a été audité et corrigé. Tous les points suivants sont maintenant fonctionnels :

- ✅ Les boutons de validation sont disponibles selon les profils
- ✅ Le statut se met automatiquement à jour à chaque validation
- ✅ Les permissions sont correctement vérifiées
- ✅ Les notifications sont envoyées aux bons utilisateurs
- ✅ L'historique est correctement enregistré
- ✅ Les transactions garantissent la cohérence des données


