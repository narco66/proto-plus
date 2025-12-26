<x-proto-layout page-title="Nouvelle demande" :breadcrumbs="[['label' => 'Demandes', 'url' => route('demandes.index')], ['label' => 'Nouvelle demande']]">
    @section('content')
        <x-card title="Créer une nouvelle demande protocolaire">
            <form action="{{ route('demandes.store') }}" method="POST" id="demande-form" enctype="multipart/form-data">
                @csrf

                <!-- Type de demande -->
                <div class="mb-3">
                    <label for="type_demande" class="form-label">Type de demande <span class="text-danger">*</span></label>
                    <select name="type_demande" id="type_demande" class="form-select @error('type_demande') is-invalid @enderror" required>
                        <option value="">Sélectionner un type</option>
                        <option value="visa_diplomatique" {{ old('type_demande') === 'visa_diplomatique' ? 'selected' : '' }}>Visa diplomatique</option>
                        <option value="visa_courtoisie" {{ old('type_demande') === 'visa_courtoisie' ? 'selected' : '' }}>Visa courtoisie</option>
                        <option value="visa_familial" {{ old('type_demande') === 'visa_familial' ? 'selected' : '' }}>Visa familial</option>
                        <option value="carte_diplomatique" {{ old('type_demande') === 'carte_diplomatique' ? 'selected' : '' }}>Carte diplomatique</option>
                        <option value="carte_consulaire" {{ old('type_demande') === 'carte_consulaire' ? 'selected' : '' }}>Carte consulaire</option>
                        <option value="franchise_douaniere" {{ old('type_demande') === 'franchise_douaniere' ? 'selected' : '' }}>Franchise douanière</option>
                        <option value="immatriculation_diplomatique" {{ old('type_demande') === 'immatriculation_diplomatique' ? 'selected' : '' }}>Immatriculation diplomatique</option>
                        <option value="autorisation_entree" {{ old('type_demande') === 'autorisation_entree' ? 'selected' : '' }}>Autorisation d'entrée</option>
                        <option value="autorisation_sortie" {{ old('type_demande') === 'autorisation_sortie' ? 'selected' : '' }}>Autorisation de sortie</option>
                    </select>
                    @error('type_demande')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Priorité -->
                <div class="mb-3">
                    <label for="priorite" class="form-label">Priorité</label>
                    <select name="priorite" id="priorite" class="form-select">
                        <option value="normal" {{ old('priorite', 'normal') === 'normal' ? 'selected' : '' }}>Normale</option>
                        <option value="urgent" {{ old('priorite') === 'urgent' ? 'selected' : '' }}>Urgente</option>
                    </select>
                </div>

                <!-- Détails de la demande -->
                <div class="mb-3">
                    <label for="motif" class="form-label">Motif <span class="text-danger">*</span></label>
                    <textarea name="motif" id="motif" rows="3" class="form-control @error('motif') is-invalid @enderror" required>{{ old('motif') }}</textarea>
                    @error('motif')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="date_depart_prevue" class="form-label">Date de départ prévue <span class="text-danger">*</span></label>
                        <input type="date" name="date_depart_prevue" id="date_depart_prevue" class="form-control @error('date_depart_prevue') is-invalid @enderror" value="{{ old('date_depart_prevue') }}" required>
                        @error('date_depart_prevue')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="pays_destination" class="form-label">Pays de destination <span class="text-danger">*</span></label>
                        <input type="text" name="pays_destination" id="pays_destination" class="form-control @error('pays_destination') is-invalid @enderror" value="{{ old('pays_destination') }}" required>
                        @error('pays_destination')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Bénéficiaires -->
                <div class="mb-3">
                    <label class="form-label">Bénéficiaires <span class="text-danger">*</span></label>
                    <div id="beneficiaires-container">
                        <!-- Le demandeur (fonctionnaire) est toujours inclus -->
                        <div class="beneficiaire-item mb-2 p-2 border rounded">
                            <input type="hidden" name="beneficiaires[0][beneficiaire_type]" value="fonctionnaire">
                            <input type="hidden" name="beneficiaires[0][beneficiaire_id]" value="{{ auth()->id() }}">
                            <input type="hidden" name="beneficiaires[0][role_dans_demande]" value="principal">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ auth()->user()->full_name }}</strong>
                                    <span class="badge bg-primary ms-2">Fonctionnaire (Principal)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ajouter ayant droit -->
                    @if($ayantsDroit->count() > 0)
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addAyantDroit()">
                            <i class="bi bi-plus-circle"></i> Ajouter un ayant droit
                        </button>
                        <select id="ayants-droit-select" class="form-select mt-2 d-none">
                            <option value="">Sélectionner un ayant droit</option>
                            @foreach($ayantsDroit as $ayantDroit)
                                <option value="{{ $ayantDroit->id }}" data-nom="{{ $ayantDroit->full_name }}">
                                    {{ $ayantDroit->full_name }} ({{ $ayantDroit->lien_familial }})
                                </option>
                            @endforeach
                        </select>
                    @else
                        <div class="alert alert-info mt-2">
                            <i class="bi bi-info-circle"></i> Aucun ayant droit déclaré. 
                            <a href="{{ route('ayants-droit.create') }}">Déclarer un ayant droit</a>
                        </div>
                    @endif
                    @error('beneficiaires')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">Pièces jointes</label>
                    <div id="documents-container"></div>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addDocument()">
                        <i class="bi bi-paperclip me-1"></i> Ajouter une pièce
                    </button>
                    <p class="small text-muted mt-2 mb-0">
                        Formats supportés : PDF, JPG, PNG (max 10 Mo).
                    </p>
                    @error('documents')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('demandes.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Annuler
                    </a>
                    <div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Enregistrer en brouillon
                        </button>
                    </div>
                </div>
            </form>
        </x-card>

        @push('scripts')
        <script>
            let beneficiaireIndex = 1;

            function addAyantDroit() {
                const select = document.getElementById('ayants-droit-select');
                const selectedOption = select.options[select.selectedIndex];
                
                if (!selectedOption.value) {
                    select.classList.remove('d-none');
                    return;
                }

                const container = document.getElementById('beneficiaires-container');
                const div = document.createElement('div');
                div.className = 'beneficiaire-item mb-2 p-2 border rounded';
                div.id = 'beneficiaire-' + beneficiaireIndex;
                div.innerHTML = `
                    <input type="hidden" name="beneficiaires[${beneficiaireIndex}][beneficiaire_type]" value="ayant_droit">
                    <input type="hidden" name="beneficiaires[${beneficiaireIndex}][beneficiaire_id]" value="${selectedOption.value}">
                    <input type="hidden" name="beneficiaires[${beneficiaireIndex}][role_dans_demande]" value="secondaire">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${selectedOption.dataset.nom}</strong>
                            <span class="badge bg-info ms-2">Ayant droit</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeBeneficiaire(${beneficiaireIndex})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                `;
                container.appendChild(div);
                beneficiaireIndex++;
                select.value = '';
                select.classList.add('d-none');
            }

            function removeBeneficiaire(index) {
                document.getElementById('beneficiaire-' + index)?.remove();
            }

            document.getElementById('ayants-droit-select')?.addEventListener('change', function() {
                if (this.value) {
                    addAyantDroit();
                }
            });

            const DOCUMENT_TYPES = [
                { value: 'passeport', label: 'Passeport' },
                { value: 'carte_identite', label: 'Carte d\'identité' },
                { value: 'acte_naissance', label: 'Acte de naissance' },
                { value: 'justificatif_domicile', label: 'Justificatif de domicile' },
                { value: 'photo_identite', label: 'Photo d\'identité' },
                { value: 'autre', label: 'Autre' },
            ];

            let documentIndex = 0;

            function addDocument() {
                const container = document.getElementById('documents-container');
                if (!container) {
                    return;
                }

                const selectOptions = DOCUMENT_TYPES.map(type => `<option value="${type.value}">${type.label}</option>`).join('');
                const div = document.createElement('div');
                div.className = 'document-item border rounded p-3 mb-3';
                div.id = 'document-' + documentIndex;
                div.innerHTML = `
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Type de document</label>
                            <select name="documents[${documentIndex}][type_document]" class="form-select" required>
                                ${selectOptions}
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Titre</label>
                            <input type="text" name="documents[${documentIndex}][titre]" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Fichier</label>
                            <input type="file" name="documents[${documentIndex}][file]" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="documents[${documentIndex}][description]" class="form-control" rows="2" required></textarea>
                        </div>
                    </div>
                    <div class="text-end mt-2">
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeDocument(${documentIndex})">
                            <i class="bi bi-trash"></i> Retirer
                        </button>
                    </div>
                `;

                container.appendChild(div);
                documentIndex++;
            }

            function removeDocument(index) {
                document.getElementById('document-' + index)?.remove();
            }
        </script>
        @endpush
    @endsection
</x-proto-layout>


