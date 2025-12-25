<?php

namespace App\Http\Controllers;

use App\Exports\DemandesExport;
use App\Models\Demande;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    private const STATUS_OPTIONS = [
        'brouillon' => 'Brouillon',
        'soumis' => 'Soumis',
        'en_cours' => 'En cours',
        'valide' => 'Valide',
        'rejete' => 'Rejete',
    ];

    private const TYPE_OPTIONS = [
        'visa_diplomatique' => 'Visa diplomatique',
        'visa_courtoisie' => 'Visa courtoisie',
        'visa_familial' => 'Visa familial',
        'carte_diplomatique' => 'Carte diplomatique',
        'carte_consulaire' => 'Carte consulaire',
        'franchise_douaniere' => 'Franchise douaniere',
        'immatriculation_diplomatique' => 'Immatriculation diplomatique',
        'autorisation_entree' => 'Autorisation d\'entree',
        'autorisation_sortie' => 'Autorisation de sortie',
    ];

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Export Excel des demandes
     */
    public function exportExcel(Request $request)
    {
        if (!$request->user()->can('rapports.export')) {
            abort(403, 'Vous n\'avez pas les permissions necessaires pour exporter des rapports.');
        }

        $query = Demande::with('demandeur');

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('type_demande')) {
            $query->where('type_demande', $request->type_demande);
        }
        if ($request->filled('date_start') && $request->filled('date_end')) {
            $query->whereBetween('created_at', [$request->date_start, $request->date_end]);
        }

        $demandes = $query->get();
        $filename = 'demandes_' . date('Y-m-d_His') . '.xlsx';

        return Excel::download(new DemandesExport($demandes), $filename);
    }

    /**
     * Export PDF des demandes
     */
    public function exportPDF(Request $request)
    {
        if (!$request->user()->can('rapports.export')) {
            abort(403, 'Vous n\'avez pas les permissions necessaires pour exporter des rapports.');
        }

        $query = Demande::with('demandeur', 'beneficiaires');

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('type_demande')) {
            $query->where('type_demande', $request->type_demande);
        }
        if ($request->filled('date_start') && $request->filled('date_end')) {
            $query->whereBetween('created_at', [$request->date_start, $request->date_end]);
        }

        $demandes = $query->get();

        $pdf = Pdf::loadView('exports.demandes-pdf', [
            'demandes' => $demandes,
            'date_export' => now()->format('d/m/Y H:i'),
        ]);

        return $pdf->download('demandes_' . date('Y-m-d_His') . '.pdf');
    }

    public function index(Request $request)
    {
        if (!$request->user()->can('rapports.export')) {
            abort(403, 'Vous n\'avez pas les permissions necessaires pour exporter des rapports.');
        }

        $filters = [
            'statut' => $request->statut,
            'type_demande' => $request->type_demande,
            'date_start' => $request->date_start,
            'date_end' => $request->date_end,
        ];

        $previewQuery = Demande::with('demandeur')->latest();
        if ($request->filled('statut')) {
            $previewQuery->where('statut', $request->statut);
        }
        if ($request->filled('type_demande')) {
            $previewQuery->where('type_demande', $request->type_demande);
        }
        if ($request->filled('date_start') && $request->filled('date_end')) {
            $previewQuery->whereBetween('created_at', [$request->date_start, $request->date_end]);
        }

        $previewDemandes = $previewQuery->paginate(10)->withQueryString();

        return view('exports.index', [
            'statusOptions' => self::STATUS_OPTIONS,
            'typeOptions' => self::TYPE_OPTIONS,
            'filters' => $filters,
            'previewDemandes' => $previewDemandes,
        ]);
    }
}
