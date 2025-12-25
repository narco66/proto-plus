<x-proto-layout page-title="Notifications" :breadcrumbs="[['label' => 'Notifications']]">
    @section('content')
        <x-card>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Mes notifications</h5>
                <div>
                    @if($notifications->where('read_at', null)->count() > 0)
                        <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-check-all"></i> Tout marquer comme lu
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            @if($notifications->isEmpty())
                <div class="alert alert-info mb-0">
                    <i class="bi bi-bell-slash"></i> Aucune notification pour le moment.
                </div>
            @else
                <div class="list-group">
                    @foreach($notifications as $notification)
                        <div class="list-group-item list-group-item-action flex-column align-items-start {{ $notification->read_at ? '' : 'bg-light' }}">
                            <div class="d-flex w-100 justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-1">
                                        @if(!$notification->read_at)
                                            <span class="badge bg-primary me-2">Nouveau</span>
                                        @endif
                                        <h6 class="mb-0">{{ $notification->data['title'] ?? 'Notification' }}</h6>
                                    </div>
                                    <p class="mb-1">{{ $notification->data['message'] ?? $notification->data['detail'] ?? '' }}</p>
                                    @if(isset($notification->data['link']))
                                        <a href="{{ $notification->data['link'] }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-arrow-right-circle"></i> Voir les détails
                                        </a>
                                    @endif
                                    <small class="text-muted d-block mt-2">
                                        <i class="bi bi-clock"></i> {{ $notification->created_at->format('d/m/Y H:i') }}
                                        @if($notification->read_at)
                                            | <i class="bi bi-check-circle text-success"></i> Lue le {{ $notification->read_at->format('d/m/Y H:i') }}
                                        @endif
                                    </small>
                                </div>
                                <div class="btn-group ms-2" role="group">
                                    @if(!$notification->read_at)
                                        <form action="{{ route('notifications.mark-read', $notification->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Marquer comme lu">
                                                <i class="bi bi-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette notification ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-3">
                    {{ $notifications->links() }}
                </div>
            @endif
        </x-card>
    @endsection
</x-proto-layout>
