<x-proto-layout :page-title="'Validation - ' . $demande->reference" :breadcrumbs="[['label' => 'Workflow', 'url' => route('workflow.index')], ['label' => $demande->reference]]">
    @section('content')
        <!-- Informations de la demande -->
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
                            <th>Demandeur :</th>
                            <td>{{ $demande->demandeur->full_name }}</td>
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
                            <th width="40%">Statut :</th>
                            <td><x-badge-status :status="$demande->statut" /></td>
                        </tr>
                        <tr>
                            <th>Date soumission :</th>
                            <td>{{ $demande->date_soumission ? $demande->date_soumission->format('d/m/Y H:i') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Étape en cours :</th>
                            <td>
                                @if($currentStep && $currentStep->stepDefinition)
                                    <strong>{{ $currentStep->stepDefinition->libelle }}</strong>
                                @else
                                    <span class="text-muted">Aucune étape disponible</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </x-card>

        <!-- Workflow de validation -->
        @if($demande->workflowInstance)
            <x-card title="Workflow de validation">
                <div class="workflow-timeline">
                    @foreach($demande->workflowInstance->stepInstances->sortBy('stepDefinition.ordre') as $stepInstance)
                        @php
                            $isCurrent = $currentStep && $stepInstance->id === $currentStep->id;
                            $isCompleted = in_array($stepInstance->statut, ['valide']);
                            $isRejected = $stepInstance->statut === 'rejete';
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
                                            Validé par : <strong>{{ $stepInstance->decidedBy->full_name }}</strong>
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
        @if($demande->documents->count() > 0)
            <x-card title="Documents joints">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Nom du fichier</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($demande->documents as $document)
                                <tr>
                                    <td>{{ $document->type_document }}</td>
                                    <td>{{ $document->nom_fichier }}</td>
                                    <td>{{ $document->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-card>
        @endif

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

        <!-- Formulaire de validation -->
        @if($currentStep && $canValidate)
            <x-card title="Décision">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> 
                    <strong>Étape en cours :</strong> {{ $currentStep->stepDefinition->libelle }}
                    <br>
                    <small>Rôle requis : <strong>{{ ucfirst(str_replace('_', ' ', $currentStep->assigned_role)) }}</strong></small>
                </div>

                <form action="{{ route('workflow.validate', $demande) }}" method="POST" id="validation-form">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Décision <span class="text-danger">*</span></label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="decision" id="decision_valide" value="valide" checked>
                            <label class="btn btn-outline-success" for="decision_valide">
                                <i class="bi bi-check-circle"></i> Valider
                            </label>

                            <input type="radio" class="btn-check" name="decision" id="decision_retour" value="retour_correction">
                            <label class="btn btn-outline-warning" for="decision_retour">
                                <i class="bi bi-arrow-counterclockwise"></i> Retour pour correction
                            </label>

                            <input type="radio" class="btn-check" name="decision" id="decision_rejete" value="rejete">
                            <label class="btn btn-outline-danger" for="decision_rejete">
                                <i class="bi bi-x-circle"></i> Rejeter
                            </label>
                        </div>
                    </div>

                    <div class="mb-3" id="commentaire-group">
                        <label for="commentaire" class="form-label">Commentaire</label>
                        <textarea name="commentaire" id="commentaire" class="form-control @error('commentaire') is-invalid @enderror" rows="4" placeholder="Commentaire (obligatoire en cas de rejet ou retour pour correction)"></textarea>
                        @error('commentaire')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('workflow.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Retour
                        </a>
                        <button type="submit" class="btn btn-primary" onclick="return confirm('Confirmer votre décision ?');">
                            <i class="bi bi-send"></i> Enregistrer la décision
                        </button>
                    </div>
                </form>
            </x-card>
        @elseif($currentStep && !$canValidate)
            <x-card title="Validation">
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i> 
                    Vous n'avez pas les permissions nécessaires pour valider cette étape.
                    <br>
                    <small>Rôle requis : <strong>{{ ucfirst(str_replace('_', ' ', $currentStep->assigned_role)) }}</strong></small>
                </div>
                <a href="{{ route('workflow.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </x-card>
        @else
            <x-card title="Validation">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> 
                    Aucune étape en attente de validation pour cette demande.
                </div>
                <a href="{{ route('workflow.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </x-card>
        @endif

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

        @push('scripts')
        <script>
            // Rendre le commentaire obligatoire pour rejet et retour correction
            document.querySelectorAll('input[name="decision"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    const commentaire = document.getElementById('commentaire');
                    if (this.value === 'rejete' || this.value === 'retour_correction') {
                        commentaire.setAttribute('required', 'required');
                        commentaire.classList.add('border-warning');
                    } else {
                        commentaire.removeAttribute('required');
                        commentaire.classList.remove('border-warning');
                    }
                });
            });
        </script>
        @endpush
    @endsection
</x-proto-layout>
