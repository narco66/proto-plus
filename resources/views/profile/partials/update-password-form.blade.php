<form method="post" action="{{ route('password.update') }}">
    @csrf
    @method('put')

    <!-- Mot de passe actuel -->
    <div class="mb-3">
        <label for="update_password_current_password" class="form-label">Mot de passe actuel <span class="text-danger">*</span></label>
        <input type="password" 
               class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" 
               id="update_password_current_password" 
               name="current_password" 
               autocomplete="current-password">
        @error('current_password', 'updatePassword')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Nouveau mot de passe -->
    <div class="mb-3">
        <label for="update_password_password" class="form-label">Nouveau mot de passe <span class="text-danger">*</span></label>
        <input type="password" 
               class="form-control @error('password', 'updatePassword') is-invalid @enderror" 
               id="update_password_password" 
               name="password" 
               autocomplete="new-password">
        @error('password', 'updatePassword')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="form-text text-muted">Minimum 8 caractères</small>
    </div>

    <!-- Confirmer le mot de passe -->
    <div class="mb-3">
        <label for="update_password_password_confirmation" class="form-label">Confirmer le nouveau mot de passe <span class="text-danger">*</span></label>
        <input type="password" 
               class="form-control" 
               id="update_password_password_confirmation" 
               name="password_confirmation" 
               autocomplete="new-password">
    </div>

    <!-- Actions -->
    <div class="d-flex justify-content-between align-items-center">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Enregistrer
        </button>

        @if (session('status') === 'password-updated')
            <div class="alert alert-success mb-0" role="alert">
                <i class="bi bi-check-circle"></i> Mot de passe mis à jour avec succès.
            </div>
        @endif
    </div>
</form>
