<form method="post" action="{{ route('profile.update') }}">
    @csrf
    @method('patch')

    <!-- Nom -->
    <div class="mb-3">
        <label for="name" class="form-label">Nom <span class="text-danger">*</span></label>
        <input type="text" 
               class="form-control @error('name') is-invalid @enderror" 
               id="name" 
               name="name" 
               value="{{ old('name', $user->name) }}" 
               required 
               autofocus>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Prénom -->
    @if(isset($user->firstname))
    <div class="mb-3">
        <label for="firstname" class="form-label">Prénom</label>
        <input type="text" 
               class="form-control @error('firstname') is-invalid @enderror" 
               id="firstname" 
               name="firstname" 
               value="{{ old('firstname', $user->firstname) }}">
        @error('firstname')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    @endif

    <!-- Email -->
    <div class="mb-3">
        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
        <input type="email" 
               class="form-control @error('email') is-invalid @enderror" 
               id="email" 
               name="email" 
               value="{{ old('email', $user->email) }}" 
               required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="alert alert-warning mt-2">
                <i class="bi bi-exclamation-triangle"></i> 
                Votre adresse email n'est pas vérifiée.
                <form method="post" action="{{ route('verification.send') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link p-0 text-decoration-none">
                        Cliquez ici pour renvoyer l'email de vérification.
                    </button>
                </form>
            </div>

            @if (session('status') === 'verification-link-sent')
                <div class="alert alert-success mt-2">
                    <i class="bi bi-check-circle"></i> 
                    Un nouveau lien de vérification a été envoyé à votre adresse email.
                </div>
            @endif
        @endif
    </div>

    <!-- Actions -->
    <div class="d-flex justify-content-between align-items-center">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Enregistrer
        </button>

        @if (session('status') === 'profile-updated')
            <div class="alert alert-success mb-0" role="alert">
                <i class="bi bi-check-circle"></i> Enregistré avec succès.
            </div>
        @endif
    </div>
</form>
