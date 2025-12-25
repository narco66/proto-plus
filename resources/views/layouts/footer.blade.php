<footer class="bg-white border-top py-3 mt-auto">
    <div class="container-fluid px-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <small class="text-muted">
                    <i class="bi bi-shield-check"></i> 
                    &copy; {{ date('Y') }} Commission de la CEEAC - PROTO PLUS
                </small>
            </div>
            <div class="col-md-6 text-end">
                <small class="text-muted">
                    <a href="{{ route('documentation') }}" class="text-decoration-none text-muted me-3">
                        <i class="bi bi-book"></i> Documentation
                    </a>
                    <a href="{{ route('faq') }}" class="text-decoration-none text-muted">
                        <i class="bi bi-question-circle"></i> FAQ
                    </a>
                </small>
            </div>
        </div>
    </div>
</footer>
