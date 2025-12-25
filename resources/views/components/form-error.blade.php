@props(['field'])

@error($field)
    <div class="invalid-feedback d-block">
        <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
    </div>
@enderror


