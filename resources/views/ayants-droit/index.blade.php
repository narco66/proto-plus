<x-proto-layout page-title="Mes ayants droit" :breadcrumbs="[['label' => 'Ayants droit']]">
    @section('content')
        <x-card>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Liste de mes ayants droit</h5>
                @can('ayants_droit.create')
                    <a href="{{ route('ayants-droit.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Ajouter un ayant droit
                    </a>
                @endcan
            </div>

            @if($ayantsDroit->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nom complet</th>
                                <th>Lien familial</th>
                                <th>Date de naissance</th>
                                <th>Nationalité</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ayantsDroit as $ayantDroit)
                                <tr>
                                    <td>{{ $ayantDroit->full_name }}</td>
                                    <td>{{ ucfirst($ayantDroit->lien_familial) }}</td>
                                    <td>{{ $ayantDroit->date_naissance ? $ayantDroit->date_naissance->format('d/m/Y') : '-' }}</td>
                                    <td>{{ $ayantDroit->nationalite ?? '-' }}</td>
                                    <td>
                                        <x-badge-status :status="$ayantDroit->status" />
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('ayants-droit.show', $ayantDroit) }}" class="btn btn-sm btn-outline-primary" title="Voir">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @can('update', $ayantDroit)
                                                <a href="{{ route('ayants-droit.edit', $ayantDroit) }}" class="btn btn-sm btn-outline-secondary" title="Modifier">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endcan
                                            @can('delete', $ayantDroit)
                                                <form action="{{ route('ayants-droit.destroy', $ayantDroit) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet ayant droit ?');">
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

                <div class="mt-3">
                    {{ $ayantsDroit->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Aucun ayant droit déclaré.
                    @can('ayants_droit.create')
                        <a href="{{ route('ayants-droit.create') }}">Déclarer un ayant droit</a>
                    @endcan
                </div>
            @endif
        </x-card>
    @endsection
</x-proto-layout>

