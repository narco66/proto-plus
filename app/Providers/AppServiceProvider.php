<?php

namespace App\Providers;

use App\Models\Demande;
use App\Models\Document;
use App\Models\AyantDroit;
use App\Policies\DemandePolicy;
use App\Policies\DocumentPolicy;
use App\Policies\AyantDroitPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Demande::class => DemandePolicy::class,
        Document::class => DocumentPolicy::class,
        AyantDroit::class => AyantDroitPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Utiliser Bootstrap 5 pour la pagination
        Paginator::useBootstrapFive();
    }
}
