<?php

namespace App\Http\Controllers;

use App\Actions\ValidateDemandeAction;
use App\Http\Requests\ValidateDemandeRequest;
use App\Models\Demande;
use App\Models\User;
use App\Notifications\DemandeEnAttenteValidation;
use App\Services\WorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WorkflowController extends Controller
{
    private const TYPES = [
        'visa_diplomatique',
        'visa_courtoisie',
        'visa_familial',
        'carte_diplomatique',
        'carte_consulaire',
        'franchise_douaniere',
        'immatriculation_diplomatique',
        'autorisation_entree',
        'autorisation_sortie',
    ];

    private const PRIORITIES = ['normal', 'urgent'];

    public function __construct(
        protected WorkflowService $workflowService,
        protected ValidateDemandeAction $validateAction
    ) {
        $this->middleware('auth');
    }

    /**
     * Liste des demandes à valider
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $userRoles = $user->getRoleNames();
        $canViewAll = $user->can('demandes.view_all') || $user->can('admin.access') || $user->hasRole('admin');

        // Récupérer les demandes en attente de validation
        $query = Demande::whereHas('workflowInstance', function ($q) use ($userRoles, $canViewAll) {
            $q->whereHas('stepInstances', function ($sq) use ($userRoles, $canViewAll) {
                $sq->where('statut', 'a_faire');
                
                // Si l'utilisateur ne peut pas voir toutes les demandes, filtrer par rôle
                if (!$canViewAll) {
                    $sq->whereHas('stepDefinition', function ($ssq) use ($userRoles) {
                        $ssq->whereIn('role_requis', $userRoles);
                    });
                }
            });
        })
        ->whereIn('statut', ['soumis', 'en_cours'])
        ->with(['demandeur', 'workflowInstance.stepInstances.stepDefinition']);

        // Filtres
        $query->when($request->type_demande, fn ($q) => $q->where('type_demande', $request->type_demande));
        $query->when($request->priorite, fn ($q) => $q->where('priorite', $request->priorite));

        // Debug: logger la requête SQL et les résultats
        if (config('app.debug')) {
            Log::debug('Workflow query', [
                'user_id' => $user->id,
                'user_roles' => $userRoles->toArray(),
                'filters' => [
                    'type_demande' => $request->type_demande,
                    'priorite' => $request->priorite,
                ],
                'sql' => $query->toSql(),
                'bindings' => $query->getBindings(),
            ]);
        }

        $demandes = $query->latest()->paginate(15)->withQueryString();

        // Debug: logger le nombre de résultats
        if (config('app.debug')) {
            Log::debug('Workflow results', [
                'count' => $demandes->count(),
                'total' => $demandes->total(),
            ]);
        }

        $this->cleanupStaleWorkflowNotifications($user, $demandes->pluck('id')->toArray());

        return view('workflow.index', [
            'demandes' => $demandes,
            'typeOptions' => self::TYPES,
            'priorityOptions' => self::PRIORITIES,
        ]);
    }

    /**
     * Afficher une demande à valider
     */
    public function show(Demande $demande)
    {
        $user = auth()->user();
        $userRoles = $user->getRoleNames();
        $canViewAll = $user->can('demandes.view_all') || $user->can('admin.access') || $user->hasRole('admin');

        // Vérifier que l'utilisateur peut valider cette demande
        $workflowInstance = $demande->workflowInstance;
        if (!$workflowInstance) {
            abort(404, 'Aucun workflow actif pour cette demande.');
        }

        // Si l'utilisateur peut voir toutes les demandes, trouver la première étape en attente
        // Sinon, trouver l'étape correspondant à son rôle
        if ($canViewAll) {
            // Récupérer toutes les étapes en attente et les trier par ordre
            $currentStep = $workflowInstance->stepInstances()
                ->where('statut', 'a_faire')
                ->with('stepDefinition')
                ->get()
                ->sortBy('stepDefinition.ordre')
                ->first();
        } else {
            $currentStep = $workflowInstance->stepInstances()
                ->where('statut', 'a_faire')
                ->whereHas('stepDefinition', function ($query) use ($userRoles) {
                    $query->whereIn('role_requis', $userRoles);
                })
                ->with('stepDefinition')
                ->get()
                ->sortBy('stepDefinition.ordre')
                ->first();
        }

        // Vérifier si l'utilisateur peut valider cette étape
        $canValidate = false;
        if ($currentStep) {
            if ($canViewAll) {
                // Admin peut valider n'importe quelle étape
                $canValidate = true;
            } else {
                // Vérifier que l'utilisateur a le rôle requis
                $canValidate = $userRoles->contains($currentStep->assigned_role);
            }
        }

        $this->markWorkflowNotificationsRead($user, $demande);

        $demande->load([
            'demandeur',
            'beneficiaires',
            'documents',
            'historique.auteur',
            'workflowInstance.stepInstances.stepDefinition',
        ]);

        return view('workflow.show', compact('demande', 'currentStep', 'canValidate', 'canViewAll'));
    }

    /**
     * Valider une demande (renommé pour ne pas masquer Controller::validate)
     */
    public function validateDemande(ValidateDemandeRequest $request, Demande $demande)
    {
        try {
            $this->validateAction->execute(
                $demande,
                $request->user()->id,
                $request->decision,
                $request->commentaire,
                $request->level
            );

        $this->markWorkflowNotificationsRead($request->user(), $demande);

            $message = match($request->decision) {
                'valide' => 'Demande validée avec succès.',
                'rejete' => 'Demande rejetée.',
                'retour_correction' => 'Demande retournée pour correction.',
            };

            return redirect()
                ->route('workflow.index')
                ->with('success', $message);
        } catch (\App\Exceptions\WorkflowException | \App\Exceptions\PermissionException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Erreur lors de la validation de la demande', [
                'demande_id' => $demande->id,
                'user_id' => $request->user()->id,
                'decision' => $request->decision,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la validation. Veuillez réessayer ou contacter l\'administrateur.');
        }
    }

    private function markWorkflowNotificationsRead(User $user, Demande $demande): void
    {
        $user->unreadNotifications()
            ->where('data->demande_id', $demande->id)
            ->get()
            ->each
            ->markAsRead();
    }

    private function cleanupStaleWorkflowNotifications(User $user, array $activeDemandeIds): void
    {
        $user->unreadNotifications()
            ->where('type', DemandeEnAttenteValidation::class)
            ->get()
            ->filter(function ($notification) use ($activeDemandeIds) {
                if (!isset($notification->data['demande_id'])) {
                    return false;
                }

                return !in_array($notification->data['demande_id'], $activeDemandeIds, true);
            })
            ->each
            ->markAsRead();
    }
}
