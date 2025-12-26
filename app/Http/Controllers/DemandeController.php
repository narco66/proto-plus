<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDemandeRequest;
use App\Http\Requests\SubmitDemandeRequest;
use App\Http\Requests\UpdateDemandeRequest;
use App\Models\Demande;
use App\Models\User;
use App\Services\DemandeService;
use App\Services\DocumentService;
use Illuminate\Http\Request;

class DemandeController extends Controller
{
    public function __construct(
        protected DemandeService $demandeService,
        protected DocumentService $documentService
    ) {
        $this->middleware('auth');
    }

    private const POST_VALIDATION_STATUSES = ['valide', 'rejete'];
    private const VALIDATION_ROLES = [
        'admin',
        'secretaire_general',
        'directeur_protocole',
    ];

    /**
     * Liste des demandes
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Si l'utilisateur peut voir toutes les demandes
        if ($user->can('demandes.view_all')) {
            $query = Demande::with(['demandeur', 'beneficiaires'])
                ->latest();
        } else {
            // Sinon, seulement ses propres demandes
            $query = Demande::where('demandeur_user_id', $user->id)
                ->with(['demandeur', 'beneficiaires'])
                ->latest();
        }

        // Filtres
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('type_demande')) {
            $query->where('type_demande', $request->type_demande);
        }

        if ($request->filled('priorite')) {
            $query->where('priorite', $request->priorite);
        }

        $demandes = $query->paginate(15);

        return view('demandes.index', compact('demandes'));
    }

    /**
     * Formulaire de création
     */
    public function create(Request $request)
    {
        if (!$request->user()->can('demandes.create')) {
            abort(403);
        }

        $user = auth()->user();
        $ayantsDroit = $user->ayantsDroit()->actifs()->get();

        return view('demandes.create', compact('ayantsDroit'));
    }

    /**
     * Enregistrer une nouvelle demande
     */
    public function store(StoreDemandeRequest $request)
    {
        try {
            $demande = $this->demandeService->create(
                $request->validated(),
                $request->user()->id
            );

            $this->handleDocumentUploads($request, $demande);

            return redirect()
                ->route('demandes.show', $demande)
                ->with('success', 'Demande créée avec succès.');
        } catch (\App\Exceptions\DemandeException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création d\'une demande', [
                'user_id' => $request->user()->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création de la demande. Veuillez réessayer ou contacter l\'administrateur.');
        }
    }

    /**
     * Afficher une demande
     */
    public function show(Request $request, Demande $demande)
    {
        // Vérifier les permissions
        if ($demande->demandeur_user_id !== $request->user()->id && !$request->user()->can('demandes.view_all')) {
            abort(403, 'Vous n\'avez pas les permissions nécessaires pour consulter cette demande.');
        }

        $demande->load([
            'demandeur',
            'beneficiaires',
            'documents',
            'documentsGeneres',
            'workflowInstance.stepInstances.stepDefinition',
            'historique.auteur',
        ]);

        return view('demandes.show', compact('demande'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit(Request $request, Demande $demande)
    {
        $user = $request->user();
        if ($demande->demandeur_user_id !== $user->id && !$user->can('demandes.edit') && !$this->isValidationAdministrator($user)) {
            abort(403, 'Vous n\'avez pas les permissions nécessaires pour modifier cette demande.');
        }

        if ($demande->statut !== 'brouillon') {
            if (in_array($demande->statut, self::POST_VALIDATION_STATUSES, true)) {
                if (!$this->isValidationAdministrator($user)) {
                    return redirect()
                        ->route('demandes.show', $demande)
                        ->with('error', 'Seuls les administrateurs, secrétaires généraux et directeurs peuvent modifier une demande validée.');
                }
            } else {
                return redirect()
                    ->route('demandes.show', $demande)
                    ->with('error', 'Seules les demandes en brouillon peuvent être modifiées.');
            }
        }

        $ayantsDroit = $user->ayantsDroit()->actifs()->get();
        $demande->load('beneficiaires.beneficiaire');

        return view('demandes.edit', compact('demande', 'ayantsDroit'));
    }

    private function isValidationAdministrator(User $user): bool
    {
        return $user->hasAnyRole(self::VALIDATION_ROLES);
    }

    /**
     * Mettre à jour une demande
     */
    public function update(UpdateDemandeRequest $request, Demande $demande)
    {
        try {
            $this->demandeService->update(
                $demande,
                $request->validated(),
                $request->user()->id
            );

            $this->handleDocumentUploads($request, $demande);

            return redirect()
                ->route('demandes.show', $demande)
                ->with('success', 'Demande mise à jour avec succès.');
        } catch (\App\Exceptions\DemandeException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la mise à jour d\'une demande', [
                'demande_id' => $demande->id,
                'user_id' => $request->user()->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour. Veuillez réessayer.');
        }
    }

    /**
     * Supprimer une demande
     */
    public function destroy(Request $request, Demande $demande)
    {
        try {
            if ($demande->demandeur_user_id !== $request->user()->id && !$request->user()->can('demandes.delete')) {
                abort(403, 'Vous n\'avez pas les permissions nécessaires pour supprimer cette demande.');
            }

            if ($demande->statut !== 'brouillon') {
                return redirect()
                    ->route('demandes.show', $demande)
                    ->with('error', 'Seules les demandes en brouillon peuvent être supprimées.');
            }

            $demande->delete();

            return redirect()
                ->route('demandes.index')
                ->with('success', 'Demande supprimée avec succès.');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la suppression d\'une demande', [
                'demande_id' => $demande->id,
                'user_id' => $request->user()->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Une erreur est survenue lors de la suppression. Veuillez réessayer.');
        }
    }

    /**
     * Soumettre une demande
     */
    public function submit(SubmitDemandeRequest $request, Demande $demande)
    {
        try {
            $this->demandeService->submit($demande, $request->user()->id);

            return redirect()
                ->route('demandes.show', $demande)
                ->with('success', 'Demande soumise avec succès. Elle sera traitée prochainement.');
        } catch (\App\Exceptions\DemandeException $e) {
            return redirect()
                ->route('demandes.show', $demande)
                ->with('error', $e->getMessage());
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la soumission de la demande', [
                'demande_id' => $demande->id,
                'user_id' => $request->user()->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->route('demandes.show', $demande)
                ->with('error', 'Une erreur est survenue lors de la soumission de la demande. Veuillez réessayer ou contacter l\'administrateur.');
        }
    }

    protected function handleDocumentUploads(Request $request, Demande $demande): void
    {
        $payloads = [];
        $uploads = $request->file('documents', []);

        foreach ($request->input('documents', []) as $index => $document) {
            $document['file'] = $uploads[$index] ?? null;
            $payloads[] = $document;
        }

        if (empty($payloads)) {
            return;
        }

        $this->documentService->handleUploads($demande, $payloads, $request->user()->id);
    }
}
