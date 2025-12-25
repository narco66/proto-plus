<x-proto-layout page-title="Demandes à valider" :breadcrumbs="[['label' => 'Workflow']]">
    @section('content')
        <x-card>
            <h5 class="mb-3">Mes validations en attente</h5>

            <!-- Filtres -->
            <form method="GET" action="{{ route('workflow.index') }}" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Type</label>
                        <select name="type_demande" class="form-select">
                            <option value="">Tous</option>
                            @foreach($typeOptions as $type)
                                <option value="{{ $type }}" {{ request('type_demande') === $type ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $type)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Priorité</label>
                        <select name="priorite" class="form-select">
                            <option value="">Toutes</option>
                            @foreach($priorityOptions as $priority)
                                <option value="{{ $priority }}" {{ request('priorite') === $priority ? 'selected' : '' }}>
                                    {{ ucfirst($priority) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-outline-primary me-2">
                            <i class="bi bi-funnel"></i> Filtrer
                        </button>
                        <a href="{{ route('workflow.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Réinitialiser
                        </a>
                    </div>
                </div>
            </form>

            @if($demandes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Référence</th>
                                <th>Type</th>
                                <th>Demandeur</th>
                                <th>Priorité</th>
                                <th>Date soumission</th>
                                <th>Étape en attente</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($demandes as $demande)
                                @php
                                    $workflowInstance = $demande->workflowInstance;
                                    $pendingStep = null;
                                    if ($workflowInstance) {
                                        $pendingStep = $workflowInstance->stepInstances
                                            ->where('statut', 'a_faire')
                                            ->sortBy(fn($step) => $step->stepDefinition->ordre ?? PHP_INT_MAX)
                                            ->first();
                                    }
                                    $userRoles = auth()->user()->getRoleNames()->toArray();
                                    $canValidateAll = auth()->user()->can('demandes.view_all') || auth()->user()->can('admin.access') || auth()->user()->hasRole('admin');
                                    $canValidateThisRequest = $pendingStep && ($canValidateAll || in_array($pendingStep->assigned_role, $userRoles, true));
                                @endphp
                                <tr>
                                    <td><strong>{{ $demande->reference }}</strong></td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $demande->type_demande)) }}</td>
                                    <td>{{ $demande->demandeur->full_name }}</td>
                                    <td>
                                        @if($demande->priorite === 'urgent')
                                            <span class="badge bg-danger">Urgent</span>
                                        @else
                                            <span class="badge bg-secondary">Normal</span>
                                        @endif
                                    </td>
                                    <td>{{ $demande->date_soumission ? $demande->date_soumission->format('d/m/Y H:i') : '-' }}</td>
                                    <td>
                                        @if($pendingStep && $pendingStep->stepDefinition)
                                            <div>
                                                <span class="badge bg-warning">{{ $pendingStep->stepDefinition->libelle }}</span>
                                            </div>
                                            <small class="text-muted">
                                                {{ ucfirst(str_replace('_', ' ', $pendingStep->assigned_role)) }}
                                            </small>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('workflow.show', $demande) }}" class="btn btn-sm btn-primary {{ $canValidateThisRequest ? '' : 'disabled' }}" title="Valider" {{ $canValidateThisRequest ? '' : 'aria-disabled="true" tabindex="-1"' }}>
                                                <i class="bi bi-check-circle"></i> Valider
                                            </a>
                                            <a href="{{ route('demandes.show', $demande) }}" class="btn btn-sm btn-outline-info" title="Voir la demande">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $demandes->appends(request()->query())->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Aucune demande en attente de validation.
                    @if(config('app.debug'))
                        <div class="mt-2 small">
                            <strong>Debug:</strong><br>
                            Rôles utilisateur: {{ implode(', ', auth()->user()->getRoleNames()->toArray()) }}<br>
                            Filtres appliqués: 
                            @if(request('type_demande'))
                                Type: {{ request('type_demande') }}
                            @endif
                            @if(request('priorite'))
                                Priorité: {{ request('priorite') }}
                            @endif
                        </div>
                    @endif
                </div>
            @endif
        </x-card>
    @endsection
</x-proto-layout>
