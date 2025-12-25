<x-proto-layout :page-title="$ayantDroit->full_name" :breadcrumbs="[['label' => 'Ayants droit', 'url' => route('ayants-droit.index')], ['label' => $ayantDroit->full_name]]">
    @section('content')
        <x-card>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Informations de l'ayant droit</h5>
                @if($ayantDroit?->exists)
                    <div>
                        <a href="{{ route('ayants-droit.edit', ['ayantDroit' => $ayantDroit->getRouteKey()]) }}" class="btn btn-outline-primary">
                            <i class="bi bi-pencil"></i> Modifier
                        </a>
                    </div>
                @endif
            </div>

            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Nom complet :</th>
                            <td><strong>{{ $ayantDroit->full_name }}</strong></td>
                        </tr>
                        <tr>
                            <th>Civilité :</th>
                            <td>{{ $ayantDroit->civilite }}</td>
                        </tr>
                        <tr>
                            <th>Lien familial :</th>
                            <td>{{ ucfirst($ayantDroit->lien_familial) }}</td>
                        </tr>
                        <tr>
                            <th>Date de naissance :</th>
                            <td>{{ $ayantDroit->date_naissance ? $ayantDroit->date_naissance->format('d/m/Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Lieu de naissance :</th>
                            <td>{{ $ayantDroit->lieu_naissance ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Nationalité :</th>
                            <td>{{ $ayantDroit->nationalite ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Numéro de passeport :</th>
                            <td>{{ $ayantDroit->passeport_num ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Expiration passeport :</th>
                            <td>{{ $ayantDroit->passeport_expire_at ? $ayantDroit->passeport_expire_at->format('d/m/Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Statut :</th>
                            <td><x-badge-status :status="$ayantDroit->status" /></td>
                        </tr>
                        <tr>
                            <th>Fonctionnaire :</th>
                            <td>{{ optional($ayantDroit->fonctionnaire)->full_name ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </x-card>
    @endsection
</x-proto-layout>


