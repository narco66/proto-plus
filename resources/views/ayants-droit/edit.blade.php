<x-proto-layout :page-title="'Modifier ' . $ayantDroit->full_name" :breadcrumbs="[['label' => 'Ayants droit', 'url' => route('ayants-droit.index')], ['label' => $ayantDroit->full_name, 'url' => route('ayants-droit.show', $ayantDroit)], ['label' => 'Modifier']]">
    @section('content')
        <x-card title="Modifier l'ayant droit">
            <form action="{{ route('ayants-droit.update', $ayantDroit) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label for="civilite" class="form-label">Civilité</label>
                        <select name="civilite" id="civilite" class="form-select">
                            <option value="M." {{ old('civilite', $ayantDroit->civilite) === 'M.' ? 'selected' : '' }}>M.</option>
                            <option value="Mme" {{ old('civilite', $ayantDroit->civilite) === 'Mme' ? 'selected' : '' }}>Mme</option>
                            <option value="Mlle" {{ old('civilite', $ayantDroit->civilite) === 'Mlle' ? 'selected' : '' }}>Mlle</option>
                        </select>
                    </div>

                    <div class="col-md-5 mb-3">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" name="nom" id="nom" class="form-control" value="{{ old('nom', $ayantDroit->nom) }}" required>
                    </div>

                    <div class="col-md-5 mb-3">
                        <label for="prenom" class="form-label">Prénom</label>
                        <input type="text" name="prenom" id="prenom" class="form-control" value="{{ old('prenom', $ayantDroit->prenom) }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="date_naissance" class="form-label">Date de naissance</label>
                        <input type="date" name="date_naissance" id="date_naissance" class="form-control" value="{{ old('date_naissance', $ayantDroit->date_naissance?->format('Y-m-d')) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="lieu_naissance" class="form-label">Lieu de naissance</label>
                        <input type="text" name="lieu_naissance" id="lieu_naissance" class="form-control" value="{{ old('lieu_naissance', $ayantDroit->lieu_naissance) }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="lien_familial" class="form-label">Lien familial</label>
                        <select name="lien_familial" id="lien_familial" class="form-select" required>
                            <option value="conjoint" {{ old('lien_familial', $ayantDroit->lien_familial) === 'conjoint' ? 'selected' : '' }}>Conjoint(e)</option>
                            <option value="enfant" {{ old('lien_familial', $ayantDroit->lien_familial) === 'enfant' ? 'selected' : '' }}>Enfant</option>
                            <option value="autre" {{ old('lien_familial', $ayantDroit->lien_familial) === 'autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="nationalite" class="form-label">Nationalité</label>
                        <input type="text" name="nationalite" id="nationalite" class="form-control" value="{{ old('nationalite', $ayantDroit->nationalite) }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="passeport_num" class="form-label">Numéro de passeport</label>
                        <input type="text" name="passeport_num" id="passeport_num" class="form-control" value="{{ old('passeport_num', $ayantDroit->passeport_num) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="passeport_expire_at" class="form-label">Date d'expiration du passeport</label>
                        <input type="date" name="passeport_expire_at" id="passeport_expire_at" class="form-control" value="{{ old('passeport_expire_at', $ayantDroit->passeport_expire_at?->format('Y-m-d')) }}">
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('ayants-droit.show', $ayantDroit) }}" class="btn btn-secondary">
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


