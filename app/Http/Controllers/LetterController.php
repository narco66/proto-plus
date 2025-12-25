<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class LetterController extends Controller
{
    private const VALIDATOR_ROLES = [
        'admin',
        'directeur_protocole',
        'secretaire_general',
    ];

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function download(Request $request, Demande $demande)
    {
        $validator = $request->user();

        if (!$demande->statut || $demande->statut !== 'valide') {
            abort(403, 'La demande doit être validée pour générer la lettre.');
        }

        if (!$validator->hasAnyRole(self::VALIDATOR_ROLES)) {
            abort(403, 'Vous n\'avez pas les droits pour générer cette lettre.');
        }

        $view = $this->resolveTemplate($demande->type_demande);
        $filename = 'entree_' . Str::slug($demande->reference) . '_' . now()->format('YmdHis') . '.pdf';

        try {
            $pdf = Pdf::loadView($view, [
                'demande' => $demande->fresh(['demandeur']),
                'validator' => $validator,
                'dateGenerated' => now()->format('d/m/Y'),
                'logo' => public_path('images/logo-ceeac.png'),
                'typeLabel' => $this->getTypeLabel($demande->type_demande),
            ])->setPaper('a4');

            return $pdf->download($filename);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la génération de la lettre', [
                'demande_id' => $demande->id,
                'error' => $e->getMessage(),
            ]);
            abort(500, 'Impossible de générer le PDF pour le moment.');
        }
    }

    private function resolveTemplate(string $type): string
    {
        return $type === 'autorisation_entree'
            ? 'letters.autorisation'
            : 'letters.note-verbale';
    }

    private function getTypeLabel(string $type): string
    {
        return match ($type) {
            'visa_familial' => 'Visa familial',
            'franchise_douaniere' => 'Franchise douanière',
            'carte_diplomatique' => 'Carte diplomatique',
            'carte_consulaire' => 'Carte consulaire',
            'immatriculation_diplomatique' => 'Immatriculation diplomatique',
            'autorisation_entree' => 'Autorisation d\'entrée',
            'autorisation_sortie' => 'Autorisation de sortie',
            'visa_diplomatique' => 'Visa diplomatique',
            'visa_courtoisie' => 'Visa courtoisie',
            default => ucfirst(str_replace('_', ' ', $type)),
        };
    }
}
