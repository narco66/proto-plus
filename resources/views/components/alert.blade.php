@if(session('success') || session('error') || session('warning') || session('info'))
    <div class="alert alert-{{ session('success') ? 'success' : (session('error') ? 'danger' : (session('warning') ? 'warning' : 'info')) }} alert-dismissible fade show shadow-sm" 
         role="alert" 
         style="border-radius: 10px; border: none;">
        @if(session('success'))
            <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
        @elseif(session('error'))
            <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
        @elseif(session('warning'))
            <i class="bi bi-exclamation-circle me-2"></i> {{ session('warning') }}
        @elseif(session('info'))
            <i class="bi bi-info-circle me-2"></i> {{ session('info') }}
        @endif
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 10px; border: none;">
        <div class="d-flex align-items-start">
            <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>
            <div class="flex-grow-1">
                <strong>Erreurs de validation :</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
