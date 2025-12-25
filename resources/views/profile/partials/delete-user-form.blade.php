<div class="alert alert-warning">
    <i class="bi bi-exclamation-triangle"></i>
    <strong>Attention :</strong> Une fois votre compte supprimé, toutes vos ressources et données seront définitivement supprimées. 
    Avant de supprimer votre compte, veuillez télécharger toutes les données ou informations que vous souhaitez conserver.
</div>

<button type="button" 
        class="btn btn-danger" 
        data-bs-toggle="modal" 
        data-bs-target="#confirmDeleteModal">
    <i class="bi bi-trash"></i> Supprimer mon compte
</button>

<!-- Modal de confirmation -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="confirmDeleteModalLabel">
                    <i class="bi bi-exclamation-triangle"></i> Confirmer la suppression
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer votre compte ?</p>
                    <p class="text-muted small">Cette action est irréversible. Toutes vos données seront définitivement supprimées.</p>

                    <div class="mb-3">
                        <label for="password" class="form-label">Confirmez avec votre mot de passe :</label>
                        <input type="password" 
                               class="form-control @error('password', 'userDeletion') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               placeholder="Votre mot de passe"
                               required>
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Annuler
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Supprimer définitivement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
