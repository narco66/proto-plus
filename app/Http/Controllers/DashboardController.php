<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = $request->user();

        // KPIs selon le rôle
        if ($user->can('demandes.view_all')) {
            return $this->dashboardAdmin($request);
        } else {
            return $this->dashboardFonctionnaire($request);
        }
    }

    protected function dashboardFonctionnaire(Request $request)
    {
        $user = $request->user();

        $stats = [
            'total' => $user->demandes()->count(),
            'brouillon' => $user->demandes()->where('statut', 'brouillon')->count(),
            'en_cours' => $user->demandes()->where('statut', 'en_cours')->count(),
            'valide' => $user->demandes()->where('statut', 'valide')->count(),
            'rejete' => $user->demandes()->where('statut', 'rejete')->count(),
        ];

        // Graphique par type
        $demandesCollection = $user->demandes()->get();
        $chartData = $this->getChartDataByTypeCollection($demandesCollection);

        // Dernières demandes
        $dernieresDemandes = $user->demandes()
            ->with('workflowInstance')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'chartData', 'dernieresDemandes'));
    }

    protected function dashboardAdmin(Request $request)
    {
        $filtreDateDebut = $request->get('date_start', now()->startOfMonth()->format('Y-m-d'));
        $filtreDateFin = $request->get('date_end', now()->format('Y-m-d'));

        // Convertir les dates en datetime pour inclure toute la journée
        $dateDebut = \Carbon\Carbon::parse($filtreDateDebut)->startOfDay();
        $dateFin = \Carbon\Carbon::parse($filtreDateFin)->endOfDay();

        // Créer une fonction pour générer une nouvelle query à chaque fois (évite les problèmes de clonage)
        $createQuery = function() use ($dateDebut, $dateFin) {
            return Demande::whereBetween('created_at', [$dateDebut, $dateFin]);
        };

        $stats = [
            'total' => $createQuery()->count(),
            'soumis' => $createQuery()->where('statut', 'soumis')->count(),
            'en_cours' => $createQuery()->where('statut', 'en_cours')->count(),
            'valide' => $createQuery()->where('statut', 'valide')->count(),
            'rejete' => $createQuery()->where('statut', 'rejete')->count(),
            'par_type' => $createQuery()
                ->select('type_demande', DB::raw('count(*) as total'))
                ->groupBy('type_demande')
                ->pluck('total', 'type_demande')
                ->toArray(),
        ];

        // Graphique évolution mensuelle - passer les objets Carbon
        $chartEvolution = $this->getChartDataEvolution($dateDebut, $dateFin);
        
        // Graphique par type
        $query = $createQuery();
        $chartData = $this->getChartDataByType($query);

        // Dernières activités
        $dernieresActivites = \App\Models\HistoriqueDemande::with(['demande', 'auteur'])
            ->whereBetween('created_at', [$dateDebut, $dateFin])
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard', compact('stats', 'chartData', 'chartEvolution', 'dernieresActivites', 'filtreDateDebut', 'filtreDateFin'));
    }

    protected function getChartDataByType($query)
    {
        $data = $query
            ->select('type_demande', DB::raw('count(*) as total'))
            ->groupBy('type_demande')
            ->pluck('total', 'type_demande')
            ->toArray();

        if (empty($data)) {
            return [
                'labels' => [],
                'data' => [],
            ];
        }

        $labels = array_map(function($type) {
            return ucfirst(str_replace('_', ' ', $type));
        }, array_keys($data));

        return [
            'labels' => $labels,
            'data' => array_values($data),
        ];
    }

    protected function getChartDataByTypeCollection($collection)
    {
        if ($collection->isEmpty()) {
            return [
                'labels' => [],
                'data' => [],
            ];
        }

        $data = $collection->groupBy('type_demande')->map->count()->toArray();

        $labels = array_map(function($type) {
            return ucfirst(str_replace('_', ' ', $type));
        }, array_keys($data));

        return [
            'labels' => $labels,
            'data' => array_values($data),
        ];
    }

    protected function getChartDataEvolution($dateDebut, $dateFin)
    {
        // Les dates sont déjà des objets Carbon, mais on vérifie quand même
        if (is_string($dateDebut)) {
            $dateDebut = \Carbon\Carbon::parse($dateDebut)->startOfDay();
        }
        if (is_string($dateFin)) {
            $dateFin = \Carbon\Carbon::parse($dateFin)->endOfDay();
        }

        $data = Demande::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as mois'),
                DB::raw('count(*) as total')
            )
            ->whereBetween('created_at', [$dateDebut, $dateFin])
            ->groupBy('mois')
            ->orderBy('mois')
            ->get();

        if ($data->isEmpty()) {
            return [
                'labels' => [],
                'data' => [],
            ];
        }

        return [
            'labels' => $data->pluck('mois')->toArray(),
            'data' => $data->pluck('total')->toArray(),
        ];
    }
}
