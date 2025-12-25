@props(['title' => null, 'class' => ''])

<div class="card shadow-sm border-0 mb-4 {{ $class }}" style="border-radius: 12px;">
    @if($title)
        <div class="card-header bg-white border-bottom" style="border-radius: 12px 12px 0 0;">
            <h5 class="mb-0 fw-semibold" style="color: var(--ceeac-primary);">
                {{ $title }}
            </h5>
        </div>
    @endif
    <div class="card-body">
        {{ $slot }}
    </div>
</div>
