@props(['status'])

@php
    $badgeClasses = [
        'brouillon' => 'bg-secondary',
        'soumis' => 'bg-info',
        'en_cours' => 'bg-primary',
        'valide' => 'bg-success',
        'rejete' => 'bg-danger',
        'expire' => 'bg-warning',
        'annule' => 'bg-dark',
        'cloture' => 'bg-success',
        'actif' => 'bg-success',
        'inactif' => 'bg-secondary',
    ];

    $labels = [
        'brouillon' => 'Brouillon',
        'soumis' => 'Soumis',
        'en_cours' => 'En cours',
        'valide' => 'Validé',
        'rejete' => 'Rejeté',
        'expire' => 'Expiré',
        'annule' => 'Annulé',
        'cloture' => 'Clôturé',
        'actif' => 'Actif',
        'inactif' => 'Inactif',
    ];

    $badgeClass = $badgeClasses[$status] ?? 'bg-secondary';
    $label = $labels[$status] ?? ucfirst($status);
@endphp

<span class="badge {{ $badgeClass }} px-3 py-2" style="font-weight: 500;">
    {{ $label }}
</span>
