<x-proto-layout page-title="Liste des demandes" :breadcrumbs="[['label' => 'Demandes']]">
    @section('content')
        <x-card>
            @php
                $pageActions = '<a href="' . route('demandes.create') . '" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Nouvelle demande</a>';
            @endphp
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Filtres</h5>
                <div>
                    @if(auth()->user()->can('rapports.export'))
                        <a href="{{ route('exports.demandes.excel', request()->all()) }}" class="btn btn-outline-success me-2">
                            <i class="bi bi-file-earmark-excel"></i> Excel
                        </a>
                        <a href="{{ route('exports.demandes.pdf', request()->all()) }}" class="btn btn-outline-danger me-2">
                            <i class="bi bi-file-earmark-pdf"></i> PDF
                        </a>
                    @endif
                    @if(auth()->user()->can('demandes.create'))
                        <a href="{{ route('demandes.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Nouvelle demande
                        </a>
                    @endif
                </div>
            </div>

            <!-- Filtres -->
            <form method="GET" action="{{ route('demandes.index') }}" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Statut</label>
                        <select name="statut" class="form-select">
                            <option value="">Tous</option>
                            <option value="brouillon" {{ request('statut') === 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                            <option value="soumis" {{ request('statut') === 'soumis' ? 'selected' : '' }}>Soumis</option>
                            <option value="en_cours" {{ request('statut') === 'en_cours' ? 'selected' : '' }}>En cours</option>
                            <option value="valide" {{ request('statut') === 'valide' ? 'selected' : '' }}>Validé</option>
                            <option value="rejete" {{ request('statut') === 'rejete' ? 'selected' : '' }}>Rejeté</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Type</label>
                        <select name="type_demande" class="form-select">
                            <option value="">Tous</option>
                            @foreach([
                                'visa_diplomatique' => 'Visa diplomatique',
                                'visa_courtoisie' => 'Visa courtoisie',
                                'visa_familial' => 'Visa familial',
                                'carte_diplomatique' => 'Carte diplomatique',
                                'carte_consulaire' => 'Carte consulaire',
                                'franchise_douaniere' => 'Franchise douanière',
                                'immatriculation_diplomatique' => 'Immatriculation diplomatique',
                                'autorisation_entree' => 'Autorisation d\'entrée',
                                'autorisation_sortie' => 'Autorisation de sortie',
                            ] as $value => $label)
                                <option value="{{ $value }}" {{ request('type_demande') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Priorité</label>
                        <select name="priorite" class="form-select">
                            <option value="">Toutes</option>
                            <option value="normal" {{ request('priorite') === 'normal' ? 'selected' : '' }}>Normale</option>
                            <option value="urgent" {{ request('priorite') === 'urgent' ? 'selected' : '' }}>Urgente</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-outline-primary me-2">
                            <i class="bi bi-funnel"></i> Filtrer
                        </button>
                        <a href="{{ route('demandes.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Réinitialiser
                        </a>
                    </div>
                </div>
            </form>

            <!-- Tableau -->
            @if($demandes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Référence</th>
                                <th>Type</th>
                                <th>Demandeur</th>
                                <th>Statut</th>
                                <th>Priorité</th>
                                <th>Date soumission</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($demandes as $demande)
                                <tr>
                                    <td><strong>{{ $demande->reference }}</strong></td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $demande->type_demande)) }}</td>
                                    <td>{{ $demande->demandeur->full_name }}</td>
                                    <td>
                                        <x-badge-status :status="$demande->statut" />
                                    </td>
                                    <td>
                                        @if($demande->priorite === 'urgent')
                                            <span class="badge bg-danger">Urgent</span>
                                        @else
                                            <span class="badge bg-secondary">Normal</span>
                                        @endif
                                    </td>
                                    <td>{{ $demande->date_soumission ? $demande->date_soumission->format('d/m/Y H:i') : '-' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('demandes.show', $demande) }}" class="btn btn-sm btn-outline-primary" title="Voir">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @can('update', $demande)
                                                <a href="{{ route('demandes.edit', $demande) }}" class="btn btn-sm btn-outline-secondary" title="Modifier">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endcan
                                            @can('delete', $demande)
                                                <form action="{{ route('demandes.destroy', $demande) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette demande ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $demandes->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Aucune demande trouvée.
                </div>
            @endif
        </x-card>
    @endsection
</x-proto-layout>
