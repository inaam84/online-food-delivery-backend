<?php

namespace App\Providers;

use App\Models\DeliveryVehicle;
use App\Policies\DeliveryVehiclePolicy;
use Illuminate\Support\Facades\Gate;
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
        Gate::policy(DeliveryVehicle::class, DeliveryVehiclePolicy::class);
    }
}
