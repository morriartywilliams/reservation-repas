<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
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
        \Illuminate\Support\Facades\Gate::policy(\App\Models\User::class, \App\Policies\UserPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Eleve::class, \App\Policies\StudentPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Menu::class, \App\Policies\MenuPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Plat::class, \App\Policies\PlatPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Repas::class, \App\Policies\RepasPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Reservation::class, \App\Policies\ReservationPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Paiement::class, \App\Policies\PaiementPolicy::class);
    }
}
