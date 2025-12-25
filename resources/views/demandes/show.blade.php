<x-proto-layout :page-title="'Demande ' . $demande->reference" :breadcrumbs="[['label' => 'Demandes', 'url' => route('demandes.index')], ['label' => $demande->reference]]">
    @section('content')
        <!-- Actions -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <a href="{{ route('demandes.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>
            <div class="d-flex gap-2">
                @if($demande->statut === 'brouillon' && $demande->demandeur_user_id === auth()->id())
                    <a href="{{ route('demandes.edit', $demande) }}" class="btn btn-outline-primary">
                        <i class="bi bi-pencil"></i> Modifier
                    </a>
                    <form action="{{ route('demandes.submit', $demande) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary" onclick="return confirm('Ôtes-vous sûr de vouloir soumettre cette demande ?');">
                            <i class="bi bi-send"></i> Soumettre
                        </button>
                    </form>
                @endif
                @if($demande->statut === 'valide' && auth()->user()->hasAnyRole(['admin', 'directeur_protocole', 'secretaire_general']))
                    <a href="{{ route('demandes.letter', $demande) }}" class="btn btn-outline-success">
                        <i class="bi bi-printer"></i>
                        {{ $demande->type_demande === 'autorisation_entree' ? "Autorisation d'entrée" : 'Note verbale' }}
                    </a>
                @endif
            </div>
        </div>
        <!-- Informations principales -->
        <x-card title="Informations de la demande">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Référence :</th>
                            <td><strong>{{ $demande->reference }}</strong></td>
                        </tr>
                        <tr>
                            <th>Type :</th>
                            <td>{{ ucfirst(str_replace('_', ' ', $demande->type_demande)) }}</td>
                        </tr>
                        <tr>
                            <th>Statut :</th>
                            <td><x-badge-status :status="$demande->statut" /></td>
                        </tr>
                        <tr>
                            <th>Priorité :</th>
                            <td>
                                @if($demande->priorite === 'urgent')
                                    <span class="badge bg-danger">Urgent</span>
                                @else
                                    <span class="badge bg-secondary">Normal</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Demandeur :</th>
                            <td>{{ $demande->demandeur->full_name }}</td>
                        </tr>
                        <tr>
                            <th>Date de création :</th>
                            <td>{{ $demande->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @if($demande->date_soumission)
                            <tr>
                                <th>Date de soumission :</th>
                                <td>{{ $demande->date_soumission->format('d/m/Y H:i') }}</td>
                            </tr>
                        @endif
                        @if($demande->date_validation)
                            <tr>
                                <th>Date de validation :</th>
                                <td>{{ $demande->date_validation->format('d/m/Y H:i') }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </x-card>

        <!-- Bénéficiaires -->
        <x-card title="Bénéficiaires">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Nom</th>
                            <th>Rôle</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($demande->beneficiaires as $beneficiaire)
                            <tr>
                                <td>
                                    @if($beneficiaire->beneficiaire_type === 'fonctionnaire')
                                        <span class="badge bg-primary">Fonctionnaire</span>
                                    @else
                                        <span class="badge bg-info">Ayant droit</span>
                                    @endif
                                </td>
                                <td>
                                    @if($beneficiaire->beneficiaire_type === 'fonctionnaire')
                                        {{ $demande->demandeur->full_name }}
                                    @else
                                        {{ $beneficiaire->beneficiaire->full_name ?? 'N/A' }}
                                    @endif
                                </td>
                                <td>{{ ucfirst($beneficiaire->role_dans_demande) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-card>

        <!-- Documents -->
        <x-card title="Documents">
            @if($demande->documents->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Nom du fichier</th>
                                <th>Taille</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($demande->documents as $document)
                                <tr>
                                    <td>{{ $document->type_document }}</td>
                                    <td>{{ $document->nom_fichier }}</td>
                                    <td>{{ number_format($document->taille / 1024, 2) }} KB</td>
                                    <td>{{ $document->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-download"></i> Télécharger
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">Aucun document joint pour le moment.</p>
            @endif
        </x-card>

        <!-- Historique -->
        <x-card title="Historique">
            <div class="timeline">
                @foreach($demande->historique->sortByDesc('created_at') as $historique)
                    <div class="border-start border-3 ps-3 mb-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong>{{ ucfirst($historique->action) }}</strong>
                                @if($historique->commentaire)
                                    <p class="mb-0 text-muted">{{ $historique->commentaire }}</p>
                                @endif
                            </div>
                            <div class="text-end">
                                <small class="text-muted">
                                    {{ $historique->auteur->full_name }}<br>
                                    {{ $historique->created_at->format('d/m/Y H:i') }}
                                </small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-card>

        <!-- Workflow -->
        @if($demande->workflowInstance)
            <x-card title="Workflow de validation">
                <div class="workflow-timeline">
                    @foreach($demande->workflowInstance->stepInstances->sortBy('stepDefinition.ordre') as $stepInstance)
                        @php
                            $isCompleted = in_array($stepInstance->statut, ['valide']);
                            $isRejected = $stepInstance->statut === 'rejete';
                            $isCurrent = $stepInstance->statut === 'a_faire';
                            $isSkipped = $stepInstance->statut === 'skipped';
                        @endphp
                        <div class="workflow-step mb-4 {{ $isCurrent ? 'current' : '' }} {{ $isCompleted ? 'completed' : '' }} {{ $isRejected ? 'rejected' : '' }}">
                            <div class="d-flex align-items-start">
                                <div class="step-icon me-3">
                                    @if($isCompleted)
                                        <div class="icon-circle bg-success text-white">
                                            <i class="bi bi-check-circle"></i>
                                        </div>
                                    @elseif($isRejected)
                                        <div class="icon-circle bg-danger text-white">
                                            <i class="bi bi-x-circle"></i>
                                        </div>
                                    @elseif($isCurrent)
                                        <div class="icon-circle bg-primary text-white">
                                            <i class="bi bi-hourglass-split"></i>
                                        </div>
                                    @elseif($isSkipped)
                                        <div class="icon-circle bg-secondary text-white">
                                            <i class="bi bi-dash-circle"></i>
                                        </div>
                                    @else
                                        <div class="icon-circle bg-light text-muted">
                                            <i class="bi bi-circle"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">
                                        {{ $stepInstance->stepDefinition->libelle }}
                                        @if($isCurrent)
                                            <span class="badge bg-primary ms-2">En cours</span>
                                        @endif
                                    </h6>
                                    <p class="text-muted small mb-1">
                                        Rôle requis : <strong>{{ ucfirst(str_replace('_', ' ', $stepInstance->assigned_role)) }}</strong>
                                    </p>
                                    @if($stepInstance->decided_by)
                                        <p class="text-muted small mb-1">
                                            Traité par : <strong>{{ $stepInstance->decidedBy->full_name }}</strong>
                                            @if($stepInstance->decision_at)
                                                le {{ $stepInstance->decision_at->format('d/m/Y H:i') }}
                                            @endif
                                        </p>
                                    @endif
                                    @if($stepInstance->commentaire)
                                        <div class="alert alert-info small mb-0 mt-2">
                                            <i class="bi bi-info-circle"></i> {{ $stepInstance->commentaire }}
                                        </div>
                                    @endif
                                    <div class="mt-2">
                                        <x-badge-status :status="$stepInstance->statut" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-card>
        @endif
    @endsection

    @push('styles')
    <style>
        .workflow-timeline {
            position: relative;
            padding-left: 2rem;
        }

        .workflow-step {
            position: relative;
        }

        .workflow-step::before {
            content: '';
            position: absolute;
            left: -2rem;
            top: 2.5rem;
            bottom: -1.5rem;
            width: 2px;
            background: #e9ecef;
        }

        .workflow-step:last-child::before {
            display: none;
        }

        .workflow-step.completed::before {
            background: var(--ceeac-success, #198754);
        }

        .workflow-step.current::before {
            background: linear-gradient(to bottom, var(--ceeac-success, #198754) 0%, #e9ecef 50%);
        }

        .icon-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .workflow-step.current .icon-circle {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                box-shadow: 0 0 0 0 rgba(0, 51, 102, 0.7);
            }
            50% {
                box-shadow: 0 0 0 10px rgba(0, 51, 102, 0);
            }
        }
    </style>
    @endpush
</x-proto-layout>

