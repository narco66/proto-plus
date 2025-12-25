<x-proto-layout page-title="Tableau de bord" :breadcrumbs="[['label' => 'Tableau de bord']]">
    @section('content')
        @php
            $isAdmin = auth()->user()->can('demandes.view_all');
        @endphp

        @if($isAdmin)
            <!-- Filtres période (admin) -->
            <x-card>
                <form method="GET" action="{{ route('dashboard') }}" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Date début</label>
                        <input type="date" name="date_start" class="form-control" value="{{ $filtreDateDebut ?? now()->startOfMonth()->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Date fin</label>
                        <input type="date" name="date_end" class="form-control" value="{{ $filtreDateFin ?? now()->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel"></i> Filtrer
                        </button>
                    </div>
                </form>
            </x-card>
        @endif

        <!-- KPIs -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-0 h-100" style="border-radius: 12px; background: linear-gradient(135deg, #003366 0%, #0066CC 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1 fw-normal">Total demandes</h6>
                                <h2 class="mb-0 fw-bold">{{ $stats['total'] ?? 0 }}</h2>
                            </div>
                            <div class="opacity-75">
                                <i class="bi bi-file-earmark-text" style="font-size: 3rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-0 h-100" style="border-radius: 12px; background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1 fw-normal">En cours</h6>
                                <h2 class="mb-0 fw-bold">{{ $stats['en_cours'] ?? 0 }}</h2>
                            </div>
                            <div class="opacity-75">
                                <i class="bi bi-hourglass-split" style="font-size: 3rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-0 h-100" style="border-radius: 12px; background: linear-gradient(135deg, #198754 0%, #20c997 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1 fw-normal">Validées</h6>
                                <h2 class="mb-0 fw-bold">{{ $stats['valide'] ?? 0 }}</h2>
                            </div>
                            <div class="opacity-75">
                                <i class="bi bi-check-circle" style="font-size: 3rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-0 h-100" style="border-radius: 12px; background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1 fw-normal">Rejetées</h6>
                                <h2 class="mb-0 fw-bold">{{ $stats['rejete'] ?? 0 }}</h2>
                            </div>
                            <div class="opacity-75">
                                <i class="bi bi-x-circle" style="font-size: 3rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphiques -->
        @if(isset($chartData) && !empty($chartData['labels']))
            <div class="row mb-4">
                <div class="col-md-6">
                    <x-card title="Répartition par type">
                        <canvas id="chartType" height="200"></canvas>
                    </x-card>
                </div>
                @if($isAdmin && isset($chartEvolution) && !empty($chartEvolution['labels']))
                    <div class="col-md-6">
                        <x-card title="Évolution mensuelle">
                            <canvas id="chartEvolution" height="200"></canvas>
                        </x-card>
                    </div>
                @endif
            </div>
        @endif

        <!-- Dernières demandes / Activités -->
        <div class="row">
            <div class="col-md-{{ $isAdmin ? '6' : '12' }}">
                <x-card title="{{ $isAdmin ? 'Dernières activités' : 'Mes dernières demandes' }}">
                    @if($isAdmin && isset($dernieresActivites) && $dernieresActivites->count() > 0)
                        <div class="list-group">
                            @foreach($dernieresActivites as $activite)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <strong>{{ ucfirst($activite->action) }}</strong>
                                            <p class="mb-0 small text-muted">
                                                Demande {{ $activite->demande->reference }}
                                            </p>
                                        </div>
                                        <small class="text-muted">
                                            {{ $activite->created_at->format('d/m/Y H:i') }}
                                        </small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @elseif(!$isAdmin && isset($dernieresDemandes) && $dernieresDemandes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Référence</th>
                                        <th>Type</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dernieresDemandes as $demande)
                                        <tr>
                                            <td>{{ $demande->reference }}</td>
                                            <td>{{ ucfirst(str_replace('_', ' ', $demande->type_demande)) }}</td>
                                            <td><x-badge-status :status="$demande->statut" /></td>
                                            <td>
                                                <a href="{{ route('demandes.show', $demande) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Aucune activité récente.</p>
                    @endif
                </x-card>
            </div>

            @if($isAdmin && isset($stats['par_type']))
                <div class="col-md-6">
                    <x-card title="Répartition par type de demande">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th class="text-end">Nombre</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stats['par_type'] as $type => $total)
                                        <tr>
                                            <td>{{ ucfirst(str_replace('_', ' ', $type)) }}</td>
                                            <td class="text-end"><strong>{{ $total }}</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </x-card>
                </div>
            @endif
        </div>

        @push('scripts')
        <script>
            @if(isset($chartData) && !empty($chartData['labels']) && count($chartData['labels']) > 0)
            // Graphique par type
            const ctxType = document.getElementById('chartType');
            if (ctxType) {
                new Chart(ctxType, {
                    type: 'doughnut',
                    data: {
                        labels: @json($chartData['labels']),
                        datasets: [{
                            data: @json($chartData['data']),
                            backgroundColor: [
                                '#003366',
                                '#0066CC',
                                '#198754',
                                '#ffc107',
                                '#dc3545',
                                '#6f42c1',
                                '#20c997',
                                '#FF6600',
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                    }
                });
            }
            @else
            // Afficher un message si pas de données
            const ctxType = document.getElementById('chartType');
            if (ctxType) {
                ctxType.parentElement.innerHTML = '<p class="text-muted text-center p-4">Aucune donnée disponible pour le graphique</p>';
            }
            @endif

            @if($isAdmin && isset($chartEvolution) && !empty($chartEvolution['labels']) && count($chartEvolution['labels']) > 0)
            // Graphique évolution (si admin)
            const ctxEvolution = document.getElementById('chartEvolution');
            if (ctxEvolution) {
                new Chart(ctxEvolution, {
                    type: 'line',
                    data: {
                        labels: @json($chartEvolution['labels']),
                        datasets: [{
                            label: 'Nombre de demandes',
                            data: @json($chartEvolution['data']),
                            borderColor: '#003366',
                            backgroundColor: 'rgba(0, 51, 102, 0.1)',
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
            @elseif($isAdmin)
            // Afficher un message si pas de données
            const ctxEvolution = document.getElementById('chartEvolution');
            if (ctxEvolution) {
                ctxEvolution.parentElement.innerHTML = '<p class="text-muted text-center p-4">Aucune donnée disponible pour le graphique</p>';
            }
            @endif
        </script>
        @endpush
    @endsection
</x-proto-layout>
