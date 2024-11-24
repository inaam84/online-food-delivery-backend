<?php

namespace App\Providers;

use App\Interfaces\CustomerRepositoryInterface;
use App\Interfaces\DeliveryDriverRepositoryInterface;
use App\Interfaces\DeliveryVehicleRepositoryInterface;
use App\Interfaces\FoodRepositoryInterface;
use App\Interfaces\MediaRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\VendorMenuRepositoryInterface;
use App\Interfaces\VendorRepositoryInterface;
use App\Repositories\CustomerRepository;
use App\Repositories\DeliveryDriverRepository;
use App\Repositories\DeliveryVehicleRepository;
use App\Repositories\FoodRepository;
use App\Repositories\MediaRepository;
use App\Repositories\UserRepository;
use App\Repositories\VendorMenuRepository;
use App\Repositories\VendorRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
        $this->app->bind(DeliveryDriverRepositoryInterface::class, DeliveryDriverRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(DeliveryVehicleRepositoryInterface::class, DeliveryVehicleRepository::class);
        $this->app->bind(MediaRepositoryInterface::class, MediaRepository::class);
        $this->app->bind(VendorRepositoryInterface::class, VendorRepository::class);
        $this->app->bind(VendorMenuRepositoryInterface::class, VendorMenuRepository::class);
        $this->app->bind(FoodRepositoryInterface::class, FoodRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
