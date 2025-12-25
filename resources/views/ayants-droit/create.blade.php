<x-proto-layout page-title="Déclarer un ayant droit" :breadcrumbs="[['label' => 'Ayants droit', 'url' => route('ayants-droit.index')], ['label' => 'Nouveau']]">
    @section('content')
        <x-card title="Déclarer un ayant droit">
            <form action="{{ route('ayants-droit.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label for="civilite" class="form-label">Civilité <span class="text-danger">*</span></label>
                        <select name="civilite" id="civilite" class="form-select @error('civilite') is-invalid @enderror" required>
                            <option value="M." {{ old('civilite') === 'M.' ? 'selected' : '' }}>M.</option>
                            <option value="Mme" {{ old('civilite') === 'Mme' ? 'selected' : '' }}>Mme</option>
                            <option value="Mlle" {{ old('civilite') === 'Mlle' ? 'selected' : '' }}>Mlle</option>
                        </select>
                        @error('civilite')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-5 mb-3">
                        <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                        <input type="text" name="nom" id="nom" class="form-control @error('nom') is-invalid @enderror" value="{{ old('nom') }}" required>
                        @error('nom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-5 mb-3">
                        <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                        <input type="text" name="prenom" id="prenom" class="form-control @error('prenom') is-invalid @enderror" value="{{ old('prenom') }}" required>
                        @error('prenom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="date_naissance" class="form-label">Date de naissance</label>
                        <input type="date" name="date_naissance" id="date_naissance" class="form-control @error('date_naissance') is-invalid @enderror" value="{{ old('date_naissance') }}">
                        @error('date_naissance')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="lieu_naissance" class="form-label">Lieu de naissance</label>
                        <input type="text" name="lieu_naissance" id="lieu_naissance" class="form-control @error('lieu_naissance') is-invalid @enderror" value="{{ old('lieu_naissance') }}">
                        @error('lieu_naissance')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="lien_familial" class="form-label">Lien familial <span class="text-danger">*</span></label>
                        <select name="lien_familial" id="lien_familial" class="form-select @error('lien_familial') is-invalid @enderror" required>
                            <option value="">Sélectionner</option>
                            <option value="conjoint" {{ old('lien_familial') === 'conjoint' ? 'selected' : '' }}>Conjoint(e)</option>
                            <option value="enfant" {{ old('lien_familial') === 'enfant' ? 'selected' : '' }}>Enfant</option>
                            <option value="autre" {{ old('lien_familial') === 'autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                        @error('lien_familial')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="nationalite" class="form-label">Nationalité</label>
                        <input type="text" name="nationalite" id="nationalite" class="form-control @error('nationalite') is-invalid @enderror" value="{{ old('nationalite') }}">
                        @error('nationalite')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="passeport_num" class="form-label">Numéro de passeport</label>
                        <input type="text" name="passeport_num" id="passeport_num" class="form-control @error('passeport_num') is-invalid @enderror" value="{{ old('passeport_num') }}">
                        @error('passeport_num')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="passeport_expire_at" class="form-label">Date d'expiration du passeport</label>
                        <input type="date" name="passeport_expire_at" id="passeport_expire_at" class="form-control @error('passeport_expire_at') is-invalid @enderror" value="{{ old('passeport_expire_at') }}">
                        @error('passeport_expire_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('ayants-droit.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Enregistrer
                    </button>
                </div>
            </form>
        </x-card>
    @endsection
</x-proto-layout>


