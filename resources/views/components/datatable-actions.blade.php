@props(['model', 'showRoute' => null, 'editRoute' => null, 'deleteRoute' => null, 'deleteFormId' => null])

<div class="btn-group" role="group">
    @if($showRoute)
        <a href="{{ $showRoute }}" class="btn btn-sm btn-outline-primary" title="Voir">
            <i class="bi bi-eye"></i>
        </a>
    @endif
    @if($editRoute)
        <a href="{{ $editRoute }}" class="btn btn-sm btn-outline-secondary" title="Modifier">
            <i class="bi bi-pencil"></i>
        </a>
    @endif
    @if($deleteRoute)
        <form action="{{ $deleteRoute }}" method="POST" class="d-inline" id="{{ $deleteFormId ?? 'delete-form-' . $model->id }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger" 
                    title="Supprimer"
                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet élément ?');">
                <i class="bi bi-trash"></i>
            </button>
        </form>
    @endif
</div>


