@php
    $currentRoute = request()->route()->getName();
    
    $menuItems = [
        [
            'title' => 'Tableau de bord',
            'icon' => 'bi-speedometer2',
            'route' => 'dashboard',
            'permission' => null,
        ],
        [
            'title' => 'Mes demandes',
            'icon' => 'bi-file-earmark-text',
            'route' => 'demandes.index',
            'permission' => null,
        ],
        [
            'title' => 'Mes ayants droit',
            'icon' => 'bi-person-badge',
            'route' => 'ayants-droit.index',
            'permission' => 'ayants_droit.view',
        ],
        [
            'title' => 'Workflow',
            'icon' => 'bi-diagram-3',
            'route' => 'workflow.index',
            'permission' => 'workflow.view',
        ],
        [
            'title' => 'Exports',
            'icon' => 'bi-file-earmark-spreadsheet',
            'route' => 'exports.index',
            'permission' => 'rapports.export',
        ],
        [
            'title' => 'Notifications',
            'icon' => 'bi-bell',
            'route' => 'notifications.index',
            'permission' => null,
        ],
    ];
@endphp

<aside class="sidebar" style="width: 250px; position: fixed; left: 0; top: 0; z-index: 1000;">
    <div class="sidebar-header">
        <h5 class="mb-0">
            <i class="bi bi-shield-check"></i> PROTO PLUS
        </h5>
        <small class="text-white-50">Commission de la CEEAC</small>
    </div>

    <nav class="sidebar-nav p-3">
        <ul class="nav flex-column">
            @foreach($menuItems as $item)
                @if(Route::has($item['route']) && (!$item['permission'] || auth()->user()->can($item['permission'])))
                    @php
                        $isActive = str_starts_with($currentRoute, $item['route']) || $currentRoute === $item['route'];
                    @endphp
                    <li class="nav-item">
                        <a href="{{ route($item['route']) }}" 
                           class="nav-link {{ $isActive ? 'active' : '' }}">
                            <i class="{{ $item['icon'] }} me-2"></i>
                            {{ $item['title'] }}
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </nav>

    <div class="sidebar-footer p-3 border-top border-secondary position-absolute bottom-0 w-100">
        <a href="{{ route('home') }}" class="nav-link text-white-50">
            <i class="bi bi-house me-2"></i>
            Retour à l'accueil
        </a>
    </div>
</aside>
