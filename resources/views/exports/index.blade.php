<x-proto-layout page-title="Exports" :breadcrumbs="[['label' => 'Exports']]">
    @section('content')
        <x-card>
            @php $filters = $filters ?? []; @endphp
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="mb-0">Exports des demandes</h5>
                    <p class="text-muted mb-0">Telechargez les donnees au format Excel ou PDF. Les filtres ci-dessous restreignent les dossiers exports.</p>
                </div>
                <div>
                    <a href="{{ route('exports.demandes.excel', request()->all()) }}" class="btn btn-outline-success me-2">
                        <i class="bi bi-file-earmark-excel"></i> Export Excel
                    </a>
                    <a href="{{ route('exports.demandes.pdf', request()->all()) }}" class="btn btn-outline-danger">
                        <i class="bi bi-file-earmark-pdf"></i> Export PDF
                    </a>
                </div>
            </div>

            <form method="GET" action="{{ route('exports.index') }}" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Statut</label>
                        <select name="statut" class="form-select">
                            <option value="">Tous</option>
                            @foreach($statusOptions as $value => $label)
                                <option value="{{ $value }}" {{ ($filters['statut'] ?? request('statut')) === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Type de demande</label>
                        <select name="type_demande" class="form-select">
                            <option value="">Tous</option>
                            @foreach($typeOptions as $value => $label)
                                <option value="{{ $value }}" {{ ($filters['type_demande'] ?? request('type_demande')) === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Date debut</label>
                        <input type="date" name="date_start" value="{{ $filters['date_start'] ?? request('date_start') }}" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Date fin</label>
                        <input type="date" name="date_end" value="{{ $filters['date_end'] ?? request('date_end') }}" class="form-control">
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="bi bi-funnel"></i> Appliquer
                        </button>
                        <a href="{{ route('exports.index') }}" class="btn btn-outline-secondary">
                            Reinitialiser
                        </a>
                    </div>
                </div>
            </form>

            @if(!empty($previewDemandes) && $previewDemandes->count())
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Apercu des demandes ({{ $previewDemandes->total() }} dossiers)</h6>
                        <small class="text-muted">Les filtres appliques se reflectent ici.</small>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Reference</th>
                                    <th>Type</th>
                                    <th>Demandeur</th>
                                    <th>Statut</th>
                                    <th>Date soumission</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($previewDemandes as $demande)
                                    <tr>
                                        <td><strong>{{ $demande->reference }}</strong></td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $demande->type_demande)) }}</td>
                                        <td>{{ $demande->demandeur->full_name }}</td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $demande->statut)) }}</td>
                                        <td>{{ optional($demande->date_soumission)->format('d/m/Y H:i') ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($previewDemandes->hasPages())
                        <div class="d-flex justify-content-end mt-2">
                            {{ $previewDemandes->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i> Aucun dossier ne correspond aux filtres selectionnes.
                </div>
            @endif

            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <strong>Excel</strong> -> Telechargez un tableau detaille des demandes avec les informations de demandeur et de statut.
                </li>
                <li class="list-group-item">
                    <strong>PDF</strong> -> Generez un rapport imprimable regroupant les demandes et leurs beneficiaires.
                </li>
            </ul>
        </x-card>
    @endsection
</x-proto-layout>
