<x-proto-layout page-title="Modifier la demande {{ $demande->reference }}" :breadcrumbs="[['label' => 'Demandes', 'url' => route('demandes.index')], ['label' => $demande->reference, 'url' => route('demandes.show', $demande)], ['label' => 'Modifier']]">
    @section('content')
        @php
            $defaultBeneficiaires = $demande->beneficiaires->map(function ($beneficiaire) {
                $label = $beneficiaire->beneficiaire_type === 'fonctionnaire'
                    ? optional($beneficiaire->beneficiaire)->full_name ?? 'Fonctionnaire'
                    : optional($beneficiaire->beneficiaire)->full_name ?? 'Ayant droit';

                return [
                    'beneficiaire_type' => $beneficiaire->beneficiaire_type,
                    'beneficiaire_id' => $beneficiaire->beneficiaire_id,
                    'role_dans_demande' => $beneficiaire->role_dans_demande,
                    'label' => $label,
                    'lien_familial' => $beneficiaire->beneficiaire_type === 'ayant_droit'
                        ? optional($beneficiaire->beneficiaire)->lien_familial
                        : null,
                ];
            })->toArray();

            if ($oldBeneficiaires = old('beneficiaires')) {
                $beneficiaires = collect($oldBeneficiaires)->values()->map(function ($item) {
                    $type = $item['beneficiaire_type'] ?? '';
                    return [
                        'beneficiaire_type' => $type,
                        'beneficiaire_id' => $item['beneficiaire_id'] ?? '',
                        'role_dans_demande' => $item['role_dans_demande'] ?? 'secondaire',
                        'label' => $type === 'fonctionnaire'
                            ? (auth()->user()->full_name ?? 'Fonctionnaire')
                            : ('Ayant droit #' . ($item['beneficiaire_id'] ?? '')),
                        'lien_familial' => null,
                    ];
                })->toArray();
            } else {
                $beneficiaires = $defaultBeneficiaires;
            }

            $initialBeneficiaireCount = count($beneficiaires);
        @endphp

        <x-card title="Modifier la demande">
            <form action="{{ route('demandes.update', $demande) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Type de demande -->
                <div class="mb-3">
                    <label for="type_demande" class="form-label">Type de demande <span class="text-danger">*</span></label>
                    <select name="type_demande" id="type_demande" class="form-select @error('type_demande') is-invalid @enderror" required>
                        <option value="">Sélectionner un type</option>
                        <option value="visa_diplomatique" {{ old('type_demande', $demande->type_demande) === 'visa_diplomatique' ? 'selected' : '' }}>Visa diplomatique</option>
                        <option value="visa_courtoisie" {{ old('type_demande', $demande->type_demande) === 'visa_courtoisie' ? 'selected' : '' }}>Visa courtoisie</option>
                        <option value="visa_familial" {{ old('type_demande', $demande->type_demande) === 'visa_familial' ? 'selected' : '' }}>Visa familial</option>
                        <option value="carte_diplomatique" {{ old('type_demande', $demande->type_demande) === 'carte_diplomatique' ? 'selected' : '' }}>Carte diplomatique</option>
                        <option value="carte_consulaire" {{ old('type_demande', $demande->type_demande) === 'carte_consulaire' ? 'selected' : '' }}>Carte consulaire</option>
                        <option value="franchise_douaniere" {{ old('type_demande', $demande->type_demande) === 'franchise_douaniere' ? 'selected' : '' }}>Franchise douanière</option>
                        <option value="immatriculation_diplomatique" {{ old('type_demande', $demande->type_demande) === 'immatriculation_diplomatique' ? 'selected' : '' }}>Immatriculation diplomatique</option>
                        <option value="autorisation_entree" {{ old('type_demande', $demande->type_demande) === 'autorisation_entree' ? 'selected' : '' }}>Autorisation d'entrée</option>
                        <option value="autorisation_sortie" {{ old('type_demande', $demande->type_demande) === 'autorisation_sortie' ? 'selected' : '' }}>Autorisation de sortie</option>
                    </select>
                    @error('type_demande')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Priorité -->
                <div class="mb-3">
                    <label for="priorite" class="form-label">Priorité</label>
                    <select name="priorite" id="priorite" class="form-select">
                        <option value="normal" {{ old('priorite', $demande->priorite) === 'normal' ? 'selected' : '' }}>Normale</option>
                        <option value="urgent" {{ old('priorite', $demande->priorite) === 'urgent' ? 'selected' : '' }}>Urgente</option>
                    </select>
                </div>

                <!-- Détails de la demande -->
                <div class="mb-3">
                    <label for="motif" class="form-label">Motif <span class="text-danger">*</span></label>
                    <textarea name="motif" id="motif" rows="3" class="form-control @error('motif') is-invalid @enderror" required>{{ old('motif', $demande->motif) }}</textarea>
                    @error('motif')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="date_depart_prevue" class="form-label">Date de départ prévue <span class="text-danger">*</span></label>
                        <input type="date" name="date_depart_prevue" id="date_depart_prevue" class="form-control @error('date_depart_prevue') is-invalid @enderror" value="{{ old('date_depart_prevue', optional($demande->date_depart_prevue)->format('Y-m-d')) }}" required>
                        @error('date_depart_prevue')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="pays_destination" class="form-label">Pays de destination <span class="text-danger">*</span></label>
                        <input type="text" name="pays_destination" id="pays_destination" class="form-control @error('pays_destination') is-invalid @enderror" value="{{ old('pays_destination', $demande->pays_destination) }}" required>
                        @error('pays_destination')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Bénéficiaires -->
                <div class="mb-3">
                    <label class="form-label">Bénéficiaires <span class="text-danger">*</span></label>
                    <div id="beneficiaires-container">
                        @foreach($beneficiaires as $index => $beneficiaire)
                            <div class="beneficiaire-item mb-2 p-2 border rounded" id="beneficiaire-{{ $index }}">
                                <input type="hidden" name="beneficiaires[{{ $index }}][beneficiaire_type]" value="{{ $beneficiaire['beneficiaire_type'] }}">
                                <input type="hidden" name="beneficiaires[{{ $index }}][beneficiaire_id]" value="{{ $beneficiaire['beneficiaire_id'] }}">
                                <input type="hidden" name="beneficiaires[{{ $index }}][role_dans_demande]" value="{{ $beneficiaire['role_dans_demande'] }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $beneficiaire['label'] }}</strong>
                                        <span class="badge bg-{{ $beneficiaire['beneficiaire_type'] === 'fonctionnaire' ? 'primary' : 'info' }} ms-2">
                                            {{ $beneficiaire['beneficiaire_type'] === 'fonctionnaire' ? 'Fonctionnaire' : 'Ayant droit' }}
                                            @if($beneficiaire['beneficiaire_type'] === 'ayant_droit' && $beneficiaire['lien_familial'])
                                                ({{ $beneficiaire['lien_familial'] }})
                                            @endif
                                        </span>
                                    </div>
                                    @if($beneficiaire['beneficiaire_type'] !== 'fonctionnaire')
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeBeneficiaire({{ $index }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

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

                <!-- Actions -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('demandes.show', $demande) }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Annuler
                    </a>
                    <div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Enregistrer
                        </button>
                    </div>
                </div>
            </form>
        </x-card>

        @push('scripts')
        <script>
            let beneficiaireIndex = {{ $initialBeneficiaireCount }};

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
        </script>
        @endpush
    @endsection
</x-proto-layout>


